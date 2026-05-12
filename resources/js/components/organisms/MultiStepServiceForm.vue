<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import Button from '@/components/atoms/Button.vue';
import Select from '@/components/atoms/Select.vue';
import TextInput from '@/components/atoms/TextInput.vue';
import FormField from '@/components/molecules/FormField.vue';
import type { HeaderEntry } from '@/components/molecules/HeaderRow.vue';
import PipelineStepCard from '@/components/organisms/PipelineStepCard.vue';
import { useMultiStep } from '@/composables/useMultiStep';

interface PresetData {
    headers: Record<string, string>;
    body: string;
}

interface StepConfig {
    id: string;
    label: string;
    method: string;
    path: string;
    note: string;
    presets: Record<string, PresetData>;
}

interface StepLocalState {
    method: string;
    path: string;
    headers: HeaderEntry[];
    body: string;
    preset: string;
    response: ExecutionResult | null;
}

interface ExecutionResult {
    targetUrl?: string;
    status?: number;
    body?: string;
    headers?: Record<string, string[]>;
    error?: string;
    parsed?: unknown;
}

interface Props {
    slug: string;
    label: string;
    description: string;
    defaultBaseUrl: string;
    basePath: string;
    basePathByVersion: Record<string, string>;
    versions: string[];
    steps: Record<string, StepConfig[]>;
}

const props = defineProps<Props>();

const STORAGE_KEY = `cowork:baseUrl:${props.slug}`;

function loadStoredBaseUrl(): string {
    try {
        const stored = window.localStorage.getItem(STORAGE_KEY);

        return stored && stored.length > 0 ? stored : props.defaultBaseUrl;
    } catch {
        return props.defaultBaseUrl;
    }
}

const baseUrl = ref<string>(loadStoredBaseUrl());

watch(baseUrl, (next) => {
    try {
        window.localStorage.setItem(STORAGE_KEY, next);
    } catch {
        // localStorage no disponible (SSR / sandbox); ignoramos.
    }
});

function resetBaseUrl() {
    baseUrl.value = props.defaultBaseUrl;
}

const version = ref<string>(props.versions[0] ?? '');

const stepsForVersion = computed<StepConfig[]>(
    () => props.steps[version.value] ?? [],
);

const flow = useMultiStep<StepConfig>(() => stepsForVersion.value);

const stepStates = reactive<Record<string, StepLocalState>>({});
const submitting = ref<Record<string, boolean>>({});
const openCards = reactive<Record<string, boolean>>({});
const runningAll = ref<boolean>(false);

function headersFromMap(map: Record<string, string>): HeaderEntry[] {
    return Object.entries(map).map(([key, value]) => ({ key, value }));
}

function firstPresetName(step: StepConfig): string {
    return Object.keys(step.presets)[0] ?? '';
}

function buildInitialState(step: StepConfig): StepLocalState {
    const presetName = firstPresetName(step);
    const preset = step.presets[presetName];

    return {
        method: step.method,
        path: step.path,
        headers: preset ? headersFromMap(preset.headers) : [],
        body: preset?.body ?? '',
        preset: presetName,
        response: null,
    };
}

function rebuildStates() {
    for (const key of Object.keys(stepStates)) {
        delete stepStates[key];
    }

    for (const key of Object.keys(openCards)) {
        delete openCards[key];
    }

    stepsForVersion.value.forEach((step, idx) => {
        stepStates[step.id] = buildInitialState(step);
        // Sólo el primer paso abierto al inicio.
        openCards[step.id] = idx === 0;
    });

    flow.reset();
}

rebuildStates();

watch(
    () => version.value,
    () => rebuildStates(),
);

function applyPreset(stepId: string, name: string) {
    const step = stepsForVersion.value.find((s) => s.id === stepId);

    if (!step) {
        return;
    }

    const preset = step.presets[name];

    if (!preset) {
        return;
    }

    const state = stepStates[stepId];
    state.preset = name;
    state.headers = headersFromMap(preset.headers);
    state.body = preset.body;
}

function resetStepPath(stepId: string) {
    const step = stepsForVersion.value.find((s) => s.id === stepId);

    if (!step) {
        return;
    }

    stepStates[stepId].path = step.path;
}

function getByPath(value: unknown, path: string[]): unknown {
    return path.reduce<unknown>((acc, key) => {
        if (acc === null || acc === undefined) {
            return undefined;
        }

        if (typeof acc !== 'object') {
            return undefined;
        }

        return (acc as Record<string, unknown>)[key];
    }, value);
}

// Captura comillas opcionales que rodeen el placeholder para saber si
// estamos dentro de un string JSON. Eso decide qué fallback usar cuando
// el placeholder no resuelve (null vs "" para no romper JSON).
const PLACEHOLDER_RE = /(")?\{\{\s*steps\.([\w-]+)\.([\w.]+)\s*\}\}(")?/g;

function interpolate(text: string): string {
    return text.replace(
        PLACEHOLDER_RE,
        (
            _match,
            leadQuote: string | undefined,
            stepId: string,
            path: string,
            trailQuote: string | undefined,
        ) => {
            const wrapped = leadQuote === '"' && trailQuote === '"';
            const source = flow.responses.value[stepId];
            const value =
                source !== undefined && source !== null
                    ? getByPath(source, path.split('.'))
                    : undefined;

            if (value === undefined || value === null) {
                // JSON-safe: "" dentro de comillas, null fuera.
                return wrapped ? '""' : 'null';
            }

            const stringified =
                typeof value === 'string' ? value : JSON.stringify(value);

            return wrapped ? `"${stringified}"` : stringified;
        },
    );
}

function targetUrlFor(step: StepConfig): string {
    const state = stepStates[step.id];

    if (!state) {
        return '';
    }

    const versionBasePath =
        props.basePathByVersion[version.value] ?? props.basePath;
    const resolvedPath = interpolate(state.path);

    return `${baseUrl.value.replace(/\/+$/, '')}${versionBasePath}/${version.value}${resolvedPath}`;
}

function tryParseJson(text: string | undefined): unknown {
    if (!text) {
        return undefined;
    }

    try {
        return JSON.parse(text);
    } catch {
        return undefined;
    }
}

/**
 * Ejecuta un paso. Retorna true si la API respondió con un 2xx
 * (lo necesitamos para encadenar en "Ejecutar todo": si falla, paramos).
 */
async function submit(stepId: string): Promise<boolean> {
    const step = stepsForVersion.value.find((s) => s.id === stepId);

    if (!step) {
        return false;
    }

    const state = stepStates[stepId];
    submitting.value[stepId] = true;
    state.response = null;

    try {
        const response = await fetch(`/forms/${props.slug}/execute`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': decodeURIComponent(
                    document.cookie
                        .split('; ')
                        .find((c) => c.startsWith('XSRF-TOKEN='))
                        ?.split('=')[1] ?? '',
                ),
            },
            body: JSON.stringify({
                method: state.method,
                version: version.value,
                baseUrl: baseUrl.value,
                pathSuffix: interpolate(state.path),
                headers: state.headers,
                body: interpolate(state.body),
            }),
        });
        const data = (await response.json()) as ExecutionResult;
        const parsed = tryParseJson(data.body);
        state.response = { ...data, parsed };
        flow.complete(step.id, parsed ?? data.body ?? data);

        return (
            data.error === undefined &&
            data.status !== undefined &&
            data.status >= 200 &&
            data.status < 300
        );
    } catch (e) {
        state.response = { error: (e as Error).message };

        return false;
    } finally {
        submitting.value[step.id] = false;
    }
}

/**
 * Ejecuta todos los pasos en serie con sus presets/cases actuales. Para
 * al primer error (status >= 400 o error de red) — los siguientes pasos
 * dependen de las respuestas previas via {{steps.<id>.<campo>}}, así que
 * encadenarlos sobre un fallo da basura.
 */
async function submitAll() {
    if (runningAll.value) {
        return;
    }

    runningAll.value = true;

    // Reset visual: todos los badges vuelven a "sin ejecutar" antes de
    // arrancar. También limpiamos `flow` para que los placeholders de la
    // corrida nueva sólo se resuelvan con respuestas de esta misma corrida.
    flow.reset();
    stepsForVersion.value.forEach((step) => {
        stepStates[step.id].response = null;
    });

    try {
        for (const step of stepsForVersion.value) {
            // Abrimos la card del paso en curso para que se vea su progreso.
            openCards[step.id] = true;
            const ok = await submit(step.id);

            if (!ok) {
                break;
            }
        }
    } finally {
        runningAll.value = false;
    }
}

function expandAll() {
    stepsForVersion.value.forEach((s) => {
        openCards[s.id] = true;
    });
}

function collapseAll() {
    stepsForVersion.value.forEach((s) => {
        openCards[s.id] = false;
    });
}

function goBack() {
    router.visit('/');
}

const baseUrlIsCustom = computed(() => baseUrl.value !== props.defaultBaseUrl);
</script>

<template>
    <div class="flex flex-col gap-6">
        <header class="flex flex-col gap-1">
            <h1
                class="text-xl font-semibold text-[#1b1b18] capitalize dark:text-[#EDEDEC]"
            >
                {{ label }}
            </h1>
            <p
                v-if="description"
                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
            >
                {{ description }}
            </p>
        </header>

        <div
            class="flex flex-col gap-4 rounded-md border border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
        >
            <div class="grid gap-4 lg:grid-cols-2">
                <FormField label="Versión de la API">
                    <Select v-model="version" :options="versions" />
                </FormField>
                <FormField
                    label="Base URL (host)"
                >
                    <div class="flex items-center gap-2">
                        <TextInput
                            v-model="baseUrl"
                            placeholder="https://..."
                        />
                        <Button
                            v-if="baseUrlIsCustom"
                            variant="secondary"
                            class="shrink-0"
                            @click="resetBaseUrl"
                        >
                            ↺ Default
                        </Button>
                    </div>
                </FormField>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <Button :disabled="runningAll" @click="submitAll">
                    {{ runningAll ? 'Ejecutando…' : '▶ Ejecutar todo' }}
                </Button>
                <Button variant="secondary" @click="expandAll">
                    Expandir todo
                </Button>
                <Button variant="secondary" @click="collapseAll">
                    Colapsar todo
                </Button>
                <span class="ml-auto" />
                <Button variant="secondary" @click="goBack"> Volver </Button>
            </div>
            <p
                v-if="runningAll"
                class="text-xs text-[#706f6c] dark:text-[#A1A09A]"
            >
                Encadenando los pasos con los casos actuales. Si alguno falla
                (status ≥ 400 o error de red) la ejecución se detiene ahí.
            </p>
        </div>

        <!-- Timeline -->
        <div class="flex flex-col">
            <PipelineStepCard
                v-for="(step, idx) in stepsForVersion"
                :key="step.id"
                :step="step"
                :index="idx"
                :total="stepsForVersion.length"
                :target-url="targetUrlFor(step)"
                :submitting="!!submitting[step.id]"
                :active-preset="stepStates[step.id].preset"
                :response="stepStates[step.id].response"
                v-model:open="openCards[step.id]"
                v-model:method="stepStates[step.id].method"
                v-model:path="stepStates[step.id].path"
                v-model:headers="stepStates[step.id].headers"
                v-model:body="stepStates[step.id].body"
                @submit="submit(step.id)"
                @apply-preset="(name) => applyPreset(step.id, name)"
                @reset-path="resetStepPath(step.id)"
            />
        </div>
    </div>
</template>
