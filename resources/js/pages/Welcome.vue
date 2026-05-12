<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { NavSectionItem } from '@/components/molecules/NavSection.vue';
import AppLayout from '@/components/templates/AppLayout.vue';

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

/**
 * Aplana la jerarquía a sólo "leaves" linkeables por sección top-level
 * (Servicios, Pipelines). Los subgrupos colapsables del sidebar acá
 * aparecen como cards individuales.
 */
function flatten(items: NavSectionItem[]): Array<{ label: string; href: string }> {
    const out: Array<{ label: string; href: string }> = [];

    for (const item of items) {
        if (item.type === 'item') {
            out.push({ label: item.label, href: item.href });
        } else {
            out.push(...flatten(item.items));
        }
    }

    return out;
}

const sections = computed(() =>
    (page.props.nav ?? []).map((g) => ({
        title: g.title,
        items: flatten(g.items),
    })),
);
</script>

<template>
    <Head title="Inicio" />
    <AppLayout>
        <div class="flex flex-col gap-8">
            <header class="flex flex-col gap-2">
                <h1
                    class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Laboratorio de endpoints Copper
                </h1>
                <p
                    class="max-w-3xl text-sm leading-relaxed text-[#706f6c] dark:text-[#A1A09A]"
                >
                    App de prueba para experimentar con los endpoints de
                    <a
                        href="https://www.transbankdevelopers.cl/documentacion"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline underline-offset-2"
                        >transbankdevelopers.cl</a
                    >. En "Servicios" cada petición se prueba de forma
                    independiente; en "Pipelines" se ejecuta el flujo completo
                    paso a paso.
                </p>
            </header>

            <section
                v-for="section in sections.filter((s) => s.items.length > 0)"
                :key="section.title"
                class="flex flex-col gap-3"
            >
                <h2
                    class="text-sm font-semibold tracking-wide text-[#706f6c] uppercase dark:text-[#A1A09A]"
                >
                    {{ section.title }}
                </h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="item in section.items"
                        :key="item.href"
                        :href="item.href"
                        class="rounded-md border border-[#e3e3e0] bg-white p-4 transition hover:border-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-[#EDEDEC]"
                    >
                        <p
                            class="text-sm font-medium text-[#1b1b18] capitalize dark:text-[#EDEDEC]"
                        >
                            {{ item.label }}
                        </p>
                        <p
                            class="mt-1 font-mono text-xs text-[#706f6c] dark:text-[#A1A09A]"
                        >
                            {{ item.href }}
                        </p>
                    </Link>
                </div>
            </section>

            <section
                v-if="sections.every((s) => s.items.length === 0)"
                class="rounded-md border border-dashed border-[#e3e3e0] bg-white p-6 text-sm text-[#706f6c] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#A1A09A]"
            >
                No hay servicios configurados todavía. Agrégalos en
                <code class="font-mono">config/services.php</code>.
            </section>
        </div>
    </AppLayout>
</template>
