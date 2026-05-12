<script setup lang="ts">
interface Props {
    text?: string;
    as?: 'h1' | 'h2' | 'span';
}

const props = withDefaults(defineProps<Props>(), {
    as: 'h1',
});

</script>

<template>
    <component
        :is="props.as"
        class="text-lg font-semibold tracking-tight text-[#1b1b18] capitalize dark:text-[#EDEDEC]"
    >
        {{ props.text }}
    </component>
</template>
