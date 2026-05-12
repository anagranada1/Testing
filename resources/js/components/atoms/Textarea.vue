<script setup lang="ts">
interface Props {
    modelValue: string;
    placeholder?: string;
    rows?: number;
}

withDefaults(defineProps<Props>(), {
    placeholder: '',
    rows: 6,
});

defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>

<template>
    <textarea
        :value="modelValue"
        :placeholder="placeholder"
        :rows="rows"
        class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 font-mono text-sm text-[#1b1b18] placeholder-[#a1a09a] focus:border-amber-700 focus:ring-1 focus:ring-amber-700 focus:outline-none dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]"
        @input="
            $emit(
                'update:modelValue',
                ($event.target as HTMLTextAreaElement).value,
            )
        "
    />
</template>
