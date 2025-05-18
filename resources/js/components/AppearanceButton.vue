<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const { appearance, updateAppearance } = useAppearance();

const toggleAppearance = () => {
    const newMode = appearance.value === 'light' ? 'dark' : 'light';
    updateAppearance(newMode);
};

const icon = computed(() => (appearance.value === 'light' ? Sun : Moon));
</script>

<template>
    <Button
        @click="toggleAppearance"
        class="inline-flex h-6 w-6
        items-center justify-center
        text-primary
        bg-secondary
        rounded-md p-0 transition-colors
        hover:bg-background hover:text-accent-foreground
        focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring
        focus-visible:ring-offset-2 disabled:pointer-events-none"
    >
        <component :is="icon" class="h-4 w-4" />
    </Button>
</template>
