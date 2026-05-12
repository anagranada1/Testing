<script setup lang="ts">
import Button from '@/components/atoms/Button.vue';
import TextInput from '@/components/atoms/TextInput.vue';

export interface HeaderEntry {
    key: string;
    value: string;
}

interface Props {
    modelValue: HeaderEntry;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:modelValue': [value: HeaderEntry];
    remove: [];
}>();

function update(field: keyof HeaderEntry, value: string, current: HeaderEntry) {
    emit('update:modelValue', { ...current, [field]: value });
}
</script>

<template>
    <div class="flex items-center gap-2">
        <TextInput
            :model-value="modelValue.key"
            placeholder="Header name"
            @update:model-value="update('key', $event, modelValue)"
        />
        <TextInput
            :model-value="modelValue.value"
            placeholder="Header value"
            @update:model-value="update('value', $event, modelValue)"
        />
        <Button variant="secondary" class="shrink-0" @click="emit('remove')">
            Quitar
        </Button>
    </div>
</template>
