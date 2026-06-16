<script setup lang="ts">
import { SButton } from '@placetopay/spartan-vue';
import { computed } from 'vue';

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

// Mapea las variantes locales a las del design system Spartan.
const spartanVariant = computed(() => {
    switch (props.variant) {
        case 'secondary':
            return 'secondary' as const;
        case 'ghost':
            return 'link' as const;
        default:
            return 'primary' as const;
    }
});

// SButton solo admite 'button' | 'submit'.
const spartanType = computed(() =>
    props.type === 'submit' ? ('submit' as const) : ('button' as const),
);
</script>

<template>
    <SButton :type="spartanType" :variant="spartanVariant" :disabled="disabled">
        <slot />
    </SButton>
</template>
