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

const spartanType = computed(() =>
    props.type === 'submit' ? ('submit' as const) : ('button' as const),
);
</script>

<template>
    <SButton :type="spartanType" :variant="spartanVariant" :disabled="disabled">
        <slot />
    </SButton>
</template>
