<script setup lang="ts">
import AppLayout from '@/layouts/app/AppFullLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toaster } from "@/components/ui/sonner"

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

</script>

<template>
    <Toaster position="top-right" :expand="false" richColors/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="container mx-auto px-4 py-0">
            <slot/>
        </main>
    </AppLayout>
</template>
