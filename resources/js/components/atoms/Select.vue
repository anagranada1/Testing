<script setup lang="ts">
import { SSelect } from '@placetopay/spartan-vue';

interface Props {
    modelValue: string;
    options: readonly string[];
}

defineProps<Props>();

defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>

<template>
    <SSelect
        :model-value="modelValue"
        @update:model-value="
            $emit('update:modelValue', String($event ?? ''))
        "
    >
        <option v-for="option in options" :key="option" :value="option">
            {{ option }}
        </option>
    </SSelect>
</template>
