<script setup lang="ts">
type Status = 'pending' | 'active' | 'completed';

interface Props {
    index: number;
    label: string;
    status: Status;
}

defineProps<Props>();
</script>

<template>
    <div class="flex items-center gap-2">
        <span
            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-xs font-semibold"
            :class="{
                'border-[#1b1b18] bg-[#1b1b18] text-white dark:border-[#EDEDEC] dark:bg-[#EDEDEC] dark:text-[#161615]':
                    status === 'active',
                'border-emerald-500 bg-emerald-500 text-white':
                    status === 'completed',
                'border-[#e3e3e0] text-[#706f6c] dark:border-[#3E3E3A] dark:text-[#A1A09A]':
                    status === 'pending',
            }"
        >
            <template v-if="status === 'completed'">✓</template>
            <template v-else>{{ index }}</template>
        </span>
        <span
            class="text-sm capitalize"
            :class="
                status === 'pending'
                    ? 'text-[#706f6c] dark:text-[#A1A09A]'
                    : 'text-[#1b1b18] dark:text-[#EDEDEC]'
            "
        >
            {{ label }}
        </span>
    </div>
</template>
