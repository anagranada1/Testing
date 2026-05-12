<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

interface Props {
    href: string;
    label: string;
    active?: boolean | null;
}

const props = withDefaults(defineProps<Props>(), {
    active: null,
});

const page = usePage();

const isActive = computed<boolean>(() =>
    props.active !== null ? props.active : page.url === props.href,
);
</script>

<template>
    <Link
        :href="href"
        :class="
            cn(
                'block rounded-md px-3 py-2 text-sm font-medium capitalize transition-colors',
                isActive
                    ? 'bg-amber-700/10 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400'
                    : 'text-[#1b1b18] hover:bg-[#f3f3f0] dark:text-[#EDEDEC] dark:hover:bg-[#1f1f1e]',
            )
        "
    >
        {{ props.label }}
    </Link>
</template>
