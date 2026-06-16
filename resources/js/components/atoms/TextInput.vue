<script setup lang="ts">
import { SInput } from '@placetopay/spartan-vue';
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faClipboard,
    faClipboardCheck,
} from '@fortawesome/free-solid-svg-icons';

interface Props {
    modelValue: string;
    placeholder?: string;
    type?: string;
    readonly?: boolean;
    copy?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: '',
    type: 'text',
    readonly: false,
    copy: false,
});

defineEmits<{
    'update:modelValue': [value: string];
}>();

const copied = ref(false);

async function copyValue() {
    const text = props.modelValue ?? '';
    try {
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(text);
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }

        copied.value = true;
        setTimeout(() => (copied.value = false), 3000);
    } catch {
        copied.value = false;
    }
}
</script>

<template>
    <div :class="copy ? 'flex items-center gap-2' : ''">
        <SInput
            :model-value="props.modelValue"
            :placeholder="props.placeholder"
            :type="props.type"
            :readonly="props.readonly || undefined"
            class="w-full"
            @update:model-value="
                $emit('update:modelValue', String($event ?? ''))
            "
        />

        <button
            v-if="props.copy"
            type="button"
            @click="copyValue"
            class="group relative inline-flex cursor-pointer items-center"
        >
            <font-awesome-icon
                :icon="copied ? faClipboardCheck : faClipboard"
                class="text-gray-600 transition-transform duration-300"
                :class="copied ? 'scale-110' : 'scale-100'"
            />

            <span
                class="absolute -top-8 left-1/2 -translate-x-1/2 scale-95 rounded bg-gray-800 px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 transition-all duration-200 group-hover:scale-100 group-hover:opacity-100"
            >
                {{ copied ? 'Copiado' : 'Copiar' }}
            </span>
        </button>
    </div>
</template>
