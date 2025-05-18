<script setup lang="ts">
import CustomerHeader from '@/components/main/CustomerHeader.vue';
import CustomerFooter from '@/components/main/CustomerFooter.vue';
import { Head, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItemType } from '@/types';
import CustomerBreadcrumbs from '@/components/CustomerBreadcrumbs.vue';

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

        <CustomerHeader :query="query"/>

        <main class="container mx-auto px-4 py-0">
            <template v-if="breadcrumbs.length > 0">
                <CustomerBreadcrumbs class="py-2 text-base font-medium" :breadcrumbs="breadcrumbs" />
            </template>
            <slot />
        </main>

        <CustomerFooter class="mt-16" />
    </div>
</template>
