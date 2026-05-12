<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import NavLink from '@/components/atoms/NavLink.vue';
import type { NavNode } from '@/types/nav';

interface Props {
    nodes: NavNode[];
    nested?: boolean;
}

withDefaults(defineProps<Props>(), {
    nested: false,
});

const page = usePage();
const currentUrl = computed(() => page.url);
</script>

<template>
    {{ nodes }}
    <ul class="flex flex-col gap-1" :class="nested ? 'pl-3' : ''">
        <li
            v-for="node in nodes"
            :key="
                node.type === 'group'
                    ? `group:${node.group}`
                    : `item:${node.slug}`
            "
        >
            <template v-if="node.type === 'group'">
                <p
                    class="px-2 py-1 text-xs font-semibold tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
                >
                    {{ node.group }}
                </p>
                <NavTree :nodes="node.items" nested />
            </template>
            <NavLink
                v-else
                :href="node.href"
                :label="node.label"
                :active="currentUrl === node.href"
            />
        </li>
    </ul>
</template>
