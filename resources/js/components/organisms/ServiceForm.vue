<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Button from '@/components/atoms/Button.vue';
import Select from '@/components/atoms/Select.vue';
import Textarea from '@/components/atoms/Textarea.vue';
import TextInput from '@/components/atoms/TextInput.vue';
import FormField from '@/components/molecules/FormField.vue';
import type { HeaderEntry } from '@/components/molecules/HeaderRow.vue';
import HeadersEditor from '@/components/molecules/HeadersEditor.vue';
import Tabs from '@/components/molecules/Tabs.vue';
import type { TabDef } from '@/components/molecules/Tabs.vue';

interface PresetData {
    headers: Record<string, string>;
    body: string;
}

interface Props {
    slug: string;
    label: string;
    defaultBaseUrl: string;
    basePath: string;
    basePathByVersion: Record<string, string>;
    versions: string[];
    defaults: Record<string, Record<string, PresetData>>;
    initialMethod?: string | null;
    initialPath?: string | null;
}

const props = withDefaults(defineProps<Props>(), {
    initialMethod: null,
    initialPath: null,
});

const METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as const;
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
    }
});

function presetsFor(v: string): Record<string, PresetData> {
    return props.defaults[v] ?? {};
}

function firstPresetName(v: string): string {
    return Object.keys(presetsFor(v))[0] ?? '';
}

function presetHeaders(v: string, name: string): HeaderEntry[] {
    const preset = presetsFor(v)[name];

    if (!preset) {
        return [];
    }

    return Object.entries(preset.headers).map(([key, value]) => ({
        key,
        value,
    }));
}

function presetBody(v: string, name: string): string {
    return presetsFor(v)[name]?.body ?? '';
}

function headersEqual(a: HeaderEntry[], b: HeaderEntry[]): boolean {
    if (a.length !== b.length) {
        return false;
    }

    return a.every(
        (row, i) => row.key === b[i].key && row.value === b[i].value,
    );
}

const method = ref<string>(props.initialMethod ?? 'POST');
const version = ref<string>(props.versions[0] ?? '');
const preset = ref<string>(firstPresetName(version.value));
const headers = ref<HeaderEntry[]>(presetHeaders(version.value, preset.value));
const body = ref<string>(presetBody(version.value, preset.value));
const path = ref<string>(props.initialPath ?? '');

const presetNames = computed(() => Object.keys(presetsFor(version.value)));

function applyPreset(name: string) {
    if (presetsFor(version.value)[name] === undefined) {
        return;
    }

    preset.value = name;
    headers.value = presetHeaders(version.value, name);
    body.value = presetBody(version.value, name);
}

watch(
    () => version.value,
    (next, prev) => {
        const prevName = preset.value;
        const bodyEdited = body.value !== presetBody(prev, prevName);
        const headersEdited = !headersEqual(
            headers.value,
            presetHeaders(prev, prevName),
        );
        const nextName =
            presetsFor(next)[prevName] !== undefined
                ? prevName
                : firstPresetName(next);
        preset.value = nextName;

        if (!bodyEdited) {
            body.value = presetBody(next, nextName);
        }

        if (!headersEdited) {
            headers.value = presetHeaders(next, nextName);
        }
    },
);

const submitting = ref(false);
const result = ref<{
    status?: number;
    body?: string;
    headers?: Record<string, string[]>;
    error?: string;
} | null>(null);

const targetUrl = computed(
    () =>
        `${baseUrl.value.replace(/\/+$/, '')}${props.basePathByVersion[version.value] ?? props.basePath}/${version.value}${path.value}`,
);

type RequestTabId = 'headers' | 'body';
const REQUEST_TABS: TabDef<RequestTabId>[] = [
    { id: 'headers', label: 'Headers' },
    { id: 'body', label: 'Body' },
];
const activeTab = ref<RequestTabId>('headers');

async function submit() {
    submitting.value = true;
    result.value = null;

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
                method: method.value,
                version: version.value,
                baseUrl: baseUrl.value,
                pathSuffix: path.value,
                headers: headers.value,
                body: body.value,
            }),
        });
        result.value = await response.json();
    } catch (e) {
        result.value = { error: (e as Error).message };
    } finally {
        submitting.value = false;
    }
}

function goBack() {
    router.visit('/');
}
</script>

<template>
    <div class="flex flex-col gap-6">
        <header class="flex flex-col gap-1">
            <h1
                class="text-xl font-semibold text-[#1b1b18] capitalize dark:text-[#EDEDEC]"
            >
                {{ label }}
            </h1>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                Configura headers y body para consumir el servicio.
            </p>
        </header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_18rem]">
            <div class="flex flex-col gap-6">
                <div
                    class="flex flex-col gap-4 rounded-md border border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
                >
                    <div class="grid gap-4 lg:grid-cols-2">
                        <FormField label="Versión de la API">
                            <Select v-model="version" :options="versions" />
                        </FormField>
                        <FormField label="Método">
                            <Select v-model="method" :options="METHODS" />
                        </FormField>
                    </div>
                    <div>
                        <FormField label="URL destino (resuelta)">
                            <TextInput :model-value="targetUrl" readonly />
                        </FormField>
                    </div>
                </div>

                <div
                    class="flex flex-col rounded-md border border-[#e3e3e0] bg-white dark:border-[#3E3E3A] dark:bg-[#161615]"
                >
                    <Tabs v-model="activeTab" :tabs="REQUEST_TABS" />
                    <div class="p-4">
                        <div v-show="activeTab === 'headers'">
                            <HeadersEditor v-model="headers" />
                        </div>
                        <div v-show="activeTab === 'body'">
                            <FormField
                                label="Body"
                                hint="Texto crudo. Para JSON, asegúrate de que el header Content-Type sea application/json."
                            >
                                <Textarea
                                    v-model="body"
                                    :rows="10"
                                    placeholder='{"key": "value"}'
                                />
                            </FormField>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button :disabled="submitting" @click="submit">
                        {{ submitting ? 'Enviando...' : 'Ejecutar' }}
                    </Button>
                    <Button variant="secondary" @click="goBack">Volver</Button>
                </div>
            </div>

            <aside
                class="h-fit rounded-md border border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
            >
                <h2
                    class="mb-1 text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Casos predeterminados
                </h2>
                <p class="mb-3 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    Carga un body de ejemplo para {{ version || '...' }}.
                </p>
                <div class="flex flex-col gap-2">
                    <button
                        v-for="name in presetNames"
                        :key="name"
                        type="button"
                        class="flex w-full items-center justify-between rounded border px-3 py-2 text-left text-sm capitalize transition"
                        :class="
                            preset === name
                                ? 'border-[#1b1b18] bg-[#f3f3f0] text-[#1b1b18] dark:border-[#EDEDEC] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]'
                                : 'border-[#e3e3e0] text-[#1b1b18] hover:bg-[#f3f3f0] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:bg-[#0a0a0a]'
                        "
                        @click="applyPreset(name)"
                    >
                        <span>{{ name.replace(/_/g, ' ') }}</span>
                        <span
                            v-if="preset === name"
                            class="text-xs text-[#706f6c] dark:text-[#A1A09A]"
                        >
                            activo
                        </span>
                    </button>
                    <p
                        v-if="presetNames.length === 0"
                        class="text-xs text-[#706f6c] dark:text-[#A1A09A]"
                    >
                        Sin casos definidos para esta versión.
                    </p>
                </div>
            </aside>
        </div>

        <section
            v-if="result"
            class="rounded-md border border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
        >
            <h2
                class="mb-2 text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]"
            >
                Respuesta
            </h2>
            <p
                v-if="result.error"
                class="text-sm text-red-600 dark:text-red-400"
            >
                {{ result.error }}
            </p>
            <template v-else>
                <p class="mb-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Status: <span class="font-mono">{{ result.status }}</span>
                </p>
                <pre
                    class="max-h-80 overflow-auto rounded bg-[#f3f3f0] p-3 font-mono text-xs text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                    >{{ result.body }}</pre
                >
            </template>
        </section>
    </div>
</template>
