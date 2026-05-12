<script setup lang="ts">
interface Props {
    modelValue: string;
    options: readonly string[];
}

const props = defineProps<Props>();

defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>

<template>
    <select
        :value="props.modelValue"
        class="rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm text-[#1b1b18] focus:border-amber-700 focus:ring-1 focus:ring-amber-700 focus:outline-none dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]"
        @change="
            $emit(
                'update:modelValue',
                ($event.target as HTMLSelectElement).value,
            )
        "
    >
        <option v-for="option in options" :key="option" :value="option">
            {{ option }}
        </option>
    </select>
</template>
