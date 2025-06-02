<script setup lang="ts">
import CustomerHeader from '@/components/main/CustomerHeader.vue';
import CustomerFooter from '@/components/main/CustomerFooter.vue';
import { Head, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItemType } from '@/types';
import CustomerBreadcrumbs from '@/components/CustomerBreadcrumbs.vue';
import { Toaster } from '@/components/ui/sonner'

// Force light mode
usePage().props.theme = 'light';
interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    query?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    query: () => '',
});
</script>

<template>
    <div class="bg-secondary dark:bg-secondary/20">
        <Head :title="'Trang chủ'" />

        <Toaster richColors position="top-right" />

        <CustomerHeader :query="query"/>

        <main class="mx-auto py-0">
            <template v-if="breadcrumbs.length > 0">
                <CustomerBreadcrumbs class="py-2 text-base font-medium mx-auto px-2 container" :breadcrumbs="breadcrumbs" />
            </template>
            <slot />
        </main>

        <CustomerFooter />
    </div>
</template>
