<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppFull.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';
import AppSidebar from '@/components/AppSidebar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell>
        <div class="flex flex-col h-screen max-h-screen w-full">
            <AppHeader :breadcrumbs="breadcrumbs" class="flex-none"/>
            <div class="flex flex-1 overflow-hidden">
                <AppSidebar />
                <AppContent class="flex-1 h-full border-l border-dashed overflow-auto">
                    <slot />
                </AppContent>
            </div>

        </div>
    </AppShell>
</template>
