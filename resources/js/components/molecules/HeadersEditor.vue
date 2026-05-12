<script setup lang="ts">
import Button from '@/components/atoms/Button.vue';
import HeaderRow from '@/components/molecules/HeaderRow.vue';
import type { HeaderEntry } from '@/components/molecules/HeaderRow.vue';

interface Props {
    modelValue: HeaderEntry[];
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update:modelValue': [value: HeaderEntry[]];
}>();

function updateAt(index: number, entry: HeaderEntry) {
    const next = [...props.modelValue];
    next[index] = entry;
    emit('update:modelValue', next);
}

function removeAt(index: number) {
    emit(
        'update:modelValue',
        props.modelValue.filter((_, i) => i !== index),
    );
}

function add() {
    emit('update:modelValue', [...props.modelValue, { key: '', value: '' }]);
}
</script>

<template>
    <div class="flex flex-col gap-2">
        <p
            v-if="modelValue.length === 0"
            class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
        >
            Sin headers configurados.
        </p>
        <HeaderRow
            v-for="(entry, index) in modelValue"
            :key="index"
            :model-value="entry"
            @update:model-value="updateAt(index, $event)"
            @remove="removeAt(index)"
        />
        <Button variant="secondary" class="self-start" @click="add">
            + Agregar
        </Button>
    </div>
</template>
