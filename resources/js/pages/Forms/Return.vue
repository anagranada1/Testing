<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import AppLayout from '@/components/templates/AppLayout.vue';
import { RETURN_MESSAGE_SOURCE } from '@/types/pipeline';
import type { ReturnMessage } from '@/types/pipeline';

interface Props {
    slug: string;
    label: string;
    method: string;
    params: Record<string, string>;
}

const props = defineProps<Props>();

const delivered = ref<boolean>(false);

onMounted(() => {
    if (!window.opener) {
        return;
    }

    // postMessage clona por structured clone, que no sabe clonar el proxy
    // reactivo con el que Vue envuelve las props: hay que aplanarlo a objeto
    // plano o lanza DataCloneError.
    const message: ReturnMessage = {
        source: RETURN_MESSAGE_SOURCE,
        slug: props.slug,
        stepId: new URLSearchParams(window.location.search).get('step') ?? '',
        params: { ...props.params },
    };

    try {
        window.opener.postMessage(message, window.location.origin);
        delivered.value = true;
    } catch {
    }
});
</script>

<template>
    <Head :title="`Retorno · ${props.label}`" />
    <AppLayout>
        <div class="mx-auto flex w-full max-w-3xl flex-col gap-6">
            <header class="flex flex-col gap-1">
                <h1
                    class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]"
                >
                    Retorno del proceso de pago
                </h1>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    {{ props.label }} — recibido por {{ props.method }}. No se
                    confirmó ninguna transacción.
                </p>
            </header>

            <div
                class="flex flex-col gap-4 rounded-md border border-[#e3e3e0] bg-white p-4 dark:border-[#3E3E3A] dark:bg-[#161615]"
            >
                <p
                    v-if="Object.keys(props.params).length === 0"
                    class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                >
                    No llegó ningún parámetro.
                </p>
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="text-xs text-[#706f6c] dark:text-[#A1A09A]"
                        >
                            <tr>
                                <th class="py-1 pr-4 font-medium">Parámetro</th>
                                <th class="py-1 font-medium">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(value, key) in props.params"
                                :key="key"
                                class="border-t border-[#e3e3e0] dark:border-[#3E3E3A]"
                            >
                                <td
                                    class="py-2 pr-4 align-top font-mono text-xs text-[#1b1b18] dark:text-[#EDEDEC]"
                                >
                                    {{ key }}
                                </td>
                                <td
                                    class="py-2 align-top font-mono text-xs break-all text-[#1b1b18] dark:text-[#EDEDEC]"
                                >
                                    {{ value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                    <template v-if="delivered">
                        Enviado a la pestaña del pipeline. Puedes cerrar esta
                        pestaña y pulsar «Confirmar» allí cuando quieras.
                    </template>
                    <template v-else>
                        No se pudo contactar la pestaña del pipeline (se cerró o
                        se abrió esta URL directamente). Copia los valores a
                        mano.
                    </template>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
