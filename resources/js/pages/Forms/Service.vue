<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import MultiStepServiceForm from '@/components/organisms/MultiStepServiceForm.vue';
import ServiceForm from '@/components/organisms/ServiceForm.vue';
import AppLayout from '@/components/templates/AppLayout.vue';

interface PresetData {
    headers: Record<string, string>;
    body: string;
}

interface StepConfig {
    id: string;
    label: string;
    method: string;
    path: string;
    note: string;
    presets: Record<string, PresetData>;
}

interface Props {
    slug: string;
    label: string;
    description: string;
    defaultBaseUrl: string;
    basePath: string;
    basePathByVersion: Record<string, string>;
    versions: string[];
    multiStep: boolean;
    defaults: Record<string, Record<string, PresetData>> | null;
    steps: Record<string, StepConfig[]> | null;
    initialMethod?: string | null;
    initialPath?: string | null;
}

const props = withDefaults(defineProps<Props>(), {
    initialMethod: null,
    initialPath: null,
});
</script>

<template>
    <Head :title="props.label" />
    <AppLayout>
        <div class="mx-auto w-full max-w-6xl">
            <MultiStepServiceForm
                v-if="props.multiStep && props.steps"
                :slug="props.slug"
                :label="props.label"
                :description="props.description"
                :default-base-url="props.defaultBaseUrl"
                :base-path="props.basePath"
                :base-path-by-version="props.basePathByVersion"
                :versions="props.versions"
                :steps="props.steps"
            />
            <ServiceForm
                v-else-if="props.defaults"
                :slug="props.slug"
                :label="props.label"
                :default-base-url="props.defaultBaseUrl"
                :base-path="props.basePath"
                :base-path-by-version="props.basePathByVersion"
                :versions="props.versions"
                :defaults="props.defaults"
                :initial-method="props.initialMethod"
                :initial-path="props.initialPath"
            />
        </div>
    </AppLayout>
</template>
