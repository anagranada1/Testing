<script setup lang="ts">
import {
    SSidebar,
    SSidebarItem,
    SSidebarSeparator,
} from '@placetopay/spartan-vue';
import { ReceiptTextIcon } from '@placetopay/iconsax-vue/linear';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import BrandTitle from '@/components/atoms/BrandTitle.vue';
import type { NavSectionItem } from '@/components/molecules/NavSection.vue';

interface NavGroup {
    type: 'group';
    title: string;
    items: NavSectionItem[];
}

interface SharedProps {
    nav?: NavGroup[];
    [key: string]: unknown;
}

const page = usePage<SharedProps>();

const groups = computed<NavGroup[]>(() => page.props.nav ?? []);

// SSidebar resalta el item cuyo `path` coincide con el modelValue; lo
// mantenemos sincronizado con la URL actual de Inertia.
const activePath = ref<string>(page.url);
watch(
    () => page.url,
    (url) => {
        activePath.value = url;
    },
);

type NavLeaf = Extract<NavSectionItem, { type: 'item' }>;

function leaves(items: NavSectionItem[]): NavLeaf[] {
    return items.filter((item): item is NavLeaf => item.type === 'item');
}
</script>

<template>
    <SSidebar v-model="activePath" class="w-64 shrink-0 pb-8">
        <template v-for="group in groups" :key="group.title">
            <SSidebarItem :as="Link">{{ group.title }} </SSidebarItem>
            <SSidebarItem
                v-for="item in leaves(group.items)"
                :key="item.href"
                :as="Link"
                :href="item.href"
                :path="item.href"
                :icon="ReceiptTextIcon"
            >
                {{ item.label }}
            </SSidebarItem>
        </template>

        <p
            v-if="groups.length === 0"
            class="px-4 text-xs text-gray-500 dark:text-gray-400"
        >
            Aún no hay servicios configurados.
        </p>
    </SSidebar>
</template>
