<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandTitle from '@/components/atoms/BrandTitle.vue';
import NavSection from '@/components/molecules/NavSection.vue';
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
</script>

<template>
    <aside
        class="sticky top-0 flex h-screen w-64 shrink-0 flex-col gap-6 overflow-y-auto border-r border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
    >
        <Link href="/" class="px-2 py-2">
            <BrandTitle />
        </Link>
        <div class="flex flex-col gap-4">
            <NavSection
                v-for="group in groups"
                :key="group.title"
                :title="group.title"
                :items="group.items"
                nested
            />
            <p
                v-if="groups.length === 0"
                class="px-3 text-xs text-[#706f6c] dark:text-[#A1A09A]"
            >
                Aún no hay servicios configurados.
            </p>
        </div>
    </aside>
</template>
