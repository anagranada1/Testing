<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import NavLink from '@/components/atoms/NavLink.vue';

export interface NavItem {
    slug: string;
    label: string;
    href: string;
}

interface Props {
    items: NavItem[];
}

defineProps<Props>();

const page = usePage();
const currentUrl = computed(() => page.url);
</script>

<template>
    <nav aria-label="Main navigation">
        <ul class="flex flex-col gap-1">
            <li v-for="item in items" :key="item.slug">
                <NavLink
                    :href="item.href"
                    :label="item.label"
                    :active="currentUrl === item.href"
                />
            </li>
        </ul>
    </nav>
</template>
