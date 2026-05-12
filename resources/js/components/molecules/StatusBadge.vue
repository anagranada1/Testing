<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    status: number | null;
    error?: string | null;
    pending?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    error: null,
    pending: false,
});

type Tone = 'idle' | 'pending' | 'success' | 'warning' | 'danger';

const tone = computed<Tone>(() => {
    if (props.pending) {
        return 'pending';
    }

    if (props.error) {
        return 'danger';
    }

    if (props.status === null) {
        return 'idle';
    }

    if (props.status >= 200 && props.status < 300) {
        return 'success';
    }

    if (props.status >= 300 && props.status < 500) {
        return 'warning';
    }

    return 'danger';
});

const label = computed<string>(() => {
    if (props.pending) {
        return 'enviando…';
    }

    if (props.error) {
        return 'error';
    }

    if (props.status === null) {
        return 'sin ejecutar';
    }

    return String(props.status);
});

const toneClass = computed<string>(() => {
    switch (tone.value) {
        case 'pending':
            return 'border-blue-300 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-950/40 dark:text-blue-200';
        case 'success':
            return 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200';
        case 'warning':
            return 'border-amber-300 bg-amber-50 text-amber-900 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-200';
        case 'danger':
            return 'border-red-300 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200';
        case 'idle':
        default:
            return 'border-[#e3e3e0] bg-[#f3f3f0] text-[#706f6c] dark:border-[#3E3E3A] dark:bg-[#0a0a0a] dark:text-[#A1A09A]';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 font-mono text-[11px] font-medium"
        :class="toneClass"
    >
        {{ label }}
    </span>
</template>
