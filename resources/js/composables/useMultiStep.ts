import { computed, ref } from 'vue';

export interface StepLike {
    id: string;
    label: string;
}

export function useMultiStep<T extends StepLike>(initialSteps: () => T[]) {
    const steps = computed(() => initialSteps());
    const currentId = ref<string>(steps.value[0]?.id ?? '');
    const responses = ref<Record<string, unknown>>({});
    const completed = ref<string[]>([]);

    const currentIndex = computed(() =>
        steps.value.findIndex((s) => s.id === currentId.value),
    );
    const currentStep = computed<T | undefined>(
        () => steps.value[currentIndex.value],
    );

    function goTo(id: string) {
        if (steps.value.some((s) => s.id === id)) {
            currentId.value = id;
        }
    }

    function next() {
        const idx = currentIndex.value;

        if (idx >= 0 && idx < steps.value.length - 1) {
            currentId.value = steps.value[idx + 1].id;
        }
    }

    function complete(id: string, response: unknown) {
        responses.value[id] = response;

        if (!completed.value.includes(id)) {
            completed.value = [...completed.value, id];
        }
    }

    function reset() {
        currentId.value = steps.value[0]?.id ?? '';
        responses.value = {};
        completed.value = [];
    }

    return {
        steps,
        currentId,
        currentIndex,
        currentStep,
        completed,
        responses,
        goTo,
        next,
        complete,
        reset,
    };
}
