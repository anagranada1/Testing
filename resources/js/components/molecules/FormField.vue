<script setup lang="ts">
interface Props {
    label: string;
    hint?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="flex flex-col gap-1.5">
        <label
            class="text-xs font-medium tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
        >
            {{ props.label }}
        </label>
        <slot />
        <p v-if="props.hint" class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
            {{ props.hint }}
        </p>
    </div>
</template>
