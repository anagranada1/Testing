<script setup lang="ts">
import { computed, ref } from 'vue';
import Button from '@/components/atoms/Button.vue';
import Select from '@/components/atoms/Select.vue';
import Textarea from '@/components/atoms/Textarea.vue';
import TextInput from '@/components/atoms/TextInput.vue';
import FormField from '@/components/molecules/FormField.vue';
import type { HeaderEntry } from '@/components/molecules/HeaderRow.vue';
import HeadersEditor from '@/components/molecules/HeadersEditor.vue';
import StatusBadge from '@/components/molecules/StatusBadge.vue';
import Tabs from '@/components/molecules/Tabs.vue';
import type { TabDef } from '@/components/molecules/Tabs.vue';

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

interface ExecutionResult {
    targetUrl?: string;
    status?: number;
    body?: string;
    headers?: Record<string, string[]>;
    error?: string;
    parsed?: unknown;
}

interface Props {
    step: StepConfig;
    index: number;
    total: number;
    targetUrl: string;
    submitting: boolean;
    activePreset: string;
    response: ExecutionResult | null;
}

const props = defineProps<Props>();

const open = defineModel<boolean>('open', { required: true });
const method = defineModel<string>('method', { required: true });
const path = defineModel<string>('path', { required: true });
const headers = defineModel<HeaderEntry[]>('headers', { required: true });
const body = defineModel<string>('body', { required: true });

type RequestTabId = 'headers' | 'body';
const REQUEST_TABS: TabDef<RequestTabId>[] = [
    { id: 'headers', label: 'Headers' },
    { id: 'body', label: 'Body' },
];
const activeTab = ref<RequestTabId>('headers');

const emit = defineEmits<{
    submit: [];
    applyPreset: [name: string];
    resetPath: [];
}>();

function toggle() {
    open.value = !open.value;
}

const presetNames = computed(() => Object.keys(props.step.presets));

const status = computed<number | null>(() =>
    props.response?.status !== undefined ? props.response.status : null,
);
const errorMessage = computed<string | null>(
    () => props.response?.error ?? null,
);

const isLast = computed(() => props.index === props.total - 1);
</script>

<template>
    <div class="relative flex gap-4">
        <div class="flex w-8 shrink-0 flex-col items-center">
            <span
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border bg-white font-mono text-sm font-semibold text-[#1b1b18] dark:bg-[#161615] dark:text-[#EDEDEC]"
                :class="
                    status !== null && status >= 200 && status < 300
                        ? 'border-emerald-500 bg-emerald-500 text-white dark:border-emerald-500'
                        : 'border-[#e3e3e0] dark:border-[#3E3E3A]'
                "
            >
                <template
                    v-if="status !== null && status >= 200 && status < 300"
                >
                    ✓
                </template>
                <template v-else>
                    {{ index + 1 }}
                </template>
            </span>
            <span
                v-if="!isLast"
                class="mt-2 w-px flex-1 bg-[#e3e3e0] dark:bg-[#3E3E3A]"
                aria-hidden="true"
            />
        </div>

        <article
            class="mb-6 flex-1 rounded-md border border-[#e3e3e0] bg-white dark:border-[#3E3E3A] dark:bg-[#161615]"
        >
            <header class="flex items-center gap-3 px-4 py-3">
                <button
                    type="button"
                    class="flex flex-1 items-center gap-3 text-left"
                    :aria-expanded="open"
                    @click="toggle"
                >
                    <span
                        class="rounded border border-[#e3e3e0] bg-[#f3f3f0] px-2 py-0.5 font-mono text-xs font-semibold text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
                    >
                        {{ method }}
                    </span>
                    <span
                        class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]"
                    >
                        {{ step.label }}
                    </span>
                    <span
                        class="bg hidden truncate font-mono text-xs text-[#706f6c] sm:inline dark:text-[#A1A09A]"
                    >
                    </span>
                </button>
                <StatusBadge
                    :status="status"
                    :error="errorMessage"
                    :pending="submitting"
                />
                <Button :disabled="submitting" @click.stop="emit('submit')">
                    {{ submitting ? 'Enviando…' : 'Ejecutar' }}
                </Button>
                <button
                    type="button"
                    class="rounded p-1 text-[#706f6c] transition hover:bg-[#f3f3f0] dark:text-[#A1A09A] dark:hover:bg-[#0a0a0a]"
                    :aria-expanded="open"
                    :aria-label="open ? 'Colapsar paso' : 'Expandir paso'"
                    @click.stop="toggle"
                >
                    <span
                        class="inline-block transition-transform"
                        :class="open ? 'rotate-180' : ''"
                        aria-hidden="true"
                        >▾</span
                    >
                </button>
            </header>

            <div
                v-if="open"
                class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]"
            >
                <div class="grid gap-6 p-4 lg:grid-cols-[minmax(0,1fr)_16rem]">
                    <div class="flex flex-col gap-4">
                        <!-- Banner contextual -->
                        <div
                            v-if="step.note"
                            class="rounded-md border border-amber-300 bg-amber-50 p-3 text-xs leading-relaxed text-amber-900 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200"
                        >
                            {{ step.note }}
                        </div>

                        <FormField label="URL destino">
                            <TextInput :model-value="targetUrl" readonly copy />
                        </FormField>

                        <Tabs v-model="activeTab" :tabs="REQUEST_TABS" />
                        <div class="p-4">
                            <div v-show="activeTab === 'headers'">
                                <HeadersEditor v-model="headers" />
                            </div>
                            <div v-show="activeTab === 'body'">

                                <FormField
                                    label="Body"
                                    hint="Texto crudo. Soporta placeholders de pasos previos."
                                >
                                    <Textarea
                                        v-model="body"
                                        :rows="8"
                                        placeholder='{"key": "value"}'
                                    />
                                </FormField>
                            </div>
                        </div>

                        <!-- Respuesta -->
                        <section
                            v-if="response"
                            class="rounded-md border border-[#e3e3e0] bg-[#fafaf9] p-3 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]"
                        >
                            <h3
                                class="mb-2 text-xs font-semibold tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
                            >
                                Respuesta
                            </h3>
                            <p
                                v-if="response.error"
                                class="text-sm text-red-600 dark:text-red-400"
                            >
                                {{ response.error }}
                            </p>
                            <template v-else>
                                <p
                                    class="mb-2 text-xs text-[#706f6c] dark:text-[#A1A09A]"
                                >
                                    Status:
                                    <span class="font-mono">{{
                                        response.status
                                    }}</span>
                                </p>
                                <pre
                                    class="max-h-72 overflow-auto rounded bg-white p-2 font-mono text-xs text-[#1b1b18] dark:bg-[#161615] dark:text-[#EDEDEC]"
                                    >{{ response.body }}</pre
                                >
                            </template>
                        </section>
                    </div>

                    <!-- Casos -->
                    <aside class="flex flex-col gap-4">
                        <div>
                            <h3
                                class="mb-1 text-xs font-semibold tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
                            >
                                Casos
                            </h3>
                            <div class="flex flex-col gap-1.5">
                                <button
                                    v-for="name in presetNames"
                                    :key="name"
                                    type="button"
                                    class="flex w-full items-center justify-between rounded border px-2.5 py-1.5 text-left text-xs capitalize transition"
                                    :class="
                                        activePreset === name
                                            ? 'border-[#1b1b18] bg-[#f3f3f0] text-[#1b1b18] dark:border-[#EDEDEC] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]'
                                            : 'border-[#e3e3e0] text-[#1b1b18] hover:bg-[#f3f3f0] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:bg-[#0a0a0a]'
                                    "
                                    @click="emit('applyPreset', name)"
                                >
                                    <span>{{ name.replace(/_/g, ' ') }}</span>
                                    <span
                                        v-if="activePreset === name"
                                        class="text-[10px] text-[#706f6c] dark:text-[#A1A09A]"
                                    >
                                        ✓
                                    </span>
                                </button>
                                <p
                                    v-if="presetNames.length === 0"
                                    class="text-xs text-[#706f6c] dark:text-[#A1A09A]"
                                >
                                    Sin casos definidos.
                                </p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </article>
    </div>
</template>
