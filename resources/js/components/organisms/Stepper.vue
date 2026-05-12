<script setup lang="ts">
import StepIndicator from '@/components/molecules/StepIndicator.vue';

export interface StepperItem {
    id: string;
    label: string;
}

interface Props {
    steps: StepperItem[];
    currentId: string;
    completed?: string[];
    allowJumpAhead?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    completed: () => [],
    allowJumpAhead: false,
});

const emit = defineEmits<{
    'update:currentId': [id: string];
}>();

function statusOf(step: StepperItem) {
    if (step.id === props.currentId) {
        return 'active' as const;
    }

    if (props.completed.includes(step.id)) {
        return 'completed' as const;
    }

    return 'pending' as const;
}

function selectable(step: StepperItem, idx: number): boolean {
    if (step.id === props.currentId) {
        return false;
    }

    if (props.completed.includes(step.id)) {
        return true;
    }

    if (props.allowJumpAhead) {
        return true;
    }

    const prev = props.steps[idx - 1];

    return prev ? props.completed.includes(prev.id) : true;
}
</script>

<template>
    <ol class="flex flex-wrap items-center gap-x-3 gap-y-2">
        <li
            v-for="(step, idx) in steps"
            :key="step.id"
            class="flex items-center gap-3"
        >
            <button
                type="button"
                :disabled="!selectable(step, idx)"
                class="rounded transition disabled:cursor-not-allowed disabled:opacity-100"
                :class="selectable(step, idx) ? 'hover:opacity-80' : ''"
                @click="emit('update:currentId', step.id)"
            >
                <StepIndicator
                    :index="idx + 1"
                    :label="step.label"
                    :status="statusOf(step)"
                />
            </button>
            <span
                v-if="idx < steps.length - 1"
                class="h-px w-6 bg-[#e3e3e0] dark:bg-[#3E3E3A]"
            />
        </li>
    </ol>
</template>
