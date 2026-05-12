<script setup lang="ts">
import { cn } from '@/lib/utils';

interface Props {
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'ghost';
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'button',
    variant: 'primary',
    disabled: false,
});

const variants: Record<NonNullable<Props['variant']>, string> = {
    primary:
        'bg-amber-700 text-white hover:bg-amber-800 disabled:bg-amber-700/50',
    secondary:
        'border border-[#e3e3e0] bg-white text-[#1b1b18] hover:bg-[#f3f3f0] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC] dark:hover:bg-[#1f1f1e]',
    ghost: 'text-[#1b1b18] hover:bg-[#f3f3f0] dark:text-[#EDEDEC] dark:hover:bg-[#1f1f1e]',
};
</script>

<template>
    <button
        :type="props.type"
        :disabled="props.disabled"
        :class="
            cn(
                'inline-flex items-center justify-center rounded-md px-3 py-2 text-sm font-medium transition-colors disabled:cursor-not-allowed',
                variants[props.variant],
            )
        "
    >
        <slot />
    </button>
</template>
