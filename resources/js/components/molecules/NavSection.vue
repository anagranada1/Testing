<script setup lang="ts">
import { faAngleDown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';
import NavLink from '@/components/atoms/NavLink.vue';

export interface NavLeafItem {
    type: 'item';
    label: string;
    href: string;
}

export interface NavGroupItem {
    type: 'group';
    title: string;
    items: NavSectionItem[];
}

export type NavSectionItem = NavLeafItem | NavGroupItem;

interface Props {
    title: string;
    items: NavSectionItem[];
    defaultOpen?: boolean;
    nested?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    defaultOpen: true,
    nested: false,
});

const open = ref<boolean>(props.defaultOpen);

function toggle() {
    open.value = !open.value;
}
</script>

<template>
    <section v-if="items" class="flex flex-col gap-1">
        <button
            type="button"
            class="flex items-center justify-between gap-2 rounded px-2 py-1 text-left transition hover:bg-[#f3f3f0] dark:hover:bg-[#1f1f1e]"
            :aria-expanded="open"
            @click="toggle"
        >
            <h3
                class="text-xs font-semibold tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
                :class="nested ? 'normal-case' : ''"
            >
                {{ title }}
            </h3>
            <span
                class="inline-block text-[10px] text-[#706f6c] transition-transform dark:text-[#A1A09A]"
                :class="open ? '' : '-rotate-90'"
                aria-hidden="true"
            >
                <FontAwesomeIcon :icon="faAngleDown" />
            </span>
        </button>
        <ul
            v-if="open"
            class="flex flex-col gap-1"
            :class="nested ? 'pl-3' : ''"
        >
            <li v-for="(item, idx) in items" :key="idx">
                <NavLink
                    v-if="item.type === 'item'"
                    :href="item.href"
                    :label="item.label"
                />
                <NavSection
                    v-else
                    :title="item.title"
                    :items="item.items"
                    nested
                />
            </li>
            <li
                v-if="items.length === 0"
                class="px-3 text-xs text-[#706f6c] dark:text-[#A1A09A]"
            >
                Sin elementos.
            </li>
        </ul>
    </section>
</template>
