<script setup lang="ts" generic="T extends string">
import { cn } from '@/lib/utils';

export interface TabDef<T extends string> {
    id: T;
    label: string;
}

interface Props {
    tabs: TabDef<T>[];
    modelValue: T;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:modelValue': [id: T];
}>();
</script>

<template>
    <div
        role="tablist"
        class="flex border-b border-[#e3e3e0] dark:border-[#3E3E3A]"
    >
        <button
            v-for="tab in tabs"
            :key="tab.id"
            type="button"
            role="tab"
            :aria-selected="modelValue === tab.id"
            :class="
                cn(
                    '-mb-px border-b-2 px-4 py-2 text-sm font-medium transition',
                    modelValue === tab.id
                        ? 'border-amber-700 text-amber-700 dark:border-amber-400 dark:text-amber-400'
                        : 'border-transparent text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]',
                )
            "
            @click="emit('update:modelValue', tab.id)"
        >
            {{ tab.label }}
        </button>
    </div>
</template>
