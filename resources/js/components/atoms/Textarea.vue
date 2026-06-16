<script setup lang="ts">
import { STextArea } from '@placetopay/spartan-vue';

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
    <STextArea
        :model-value="modelValue"
        :placeholder="placeholder"
        :rows="rows"
        class="w-full font-mono"
        @update:model-value="$emit('update:modelValue', $event)"
    />
</template>
