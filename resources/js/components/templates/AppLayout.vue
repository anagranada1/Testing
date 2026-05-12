<script setup lang="ts">
import Header from '@/components/organisms/Header.vue';
import Sidebar from '@/components/organisms/Sidebar.vue';
</script>

<template>
    <div
        class="flex min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
    >
        <Sidebar />
        <div class="flex min-w-0 flex-1 flex-col">
            <Header />
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
