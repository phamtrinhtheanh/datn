<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const toggleAppearance = () => {
    const newMode = appearance.value === 'light' ? 'dark' : 'light';
    updateAppearance(newMode);
};

const icon = computed(() => appearance.value === 'light' ? Sun : Moon);
</script>

<template>
    <div class="rounded-full overflow-hidden" :class="['dark:bg-red-900', containerClass]">
        <Button
            @click="toggleAppearance"
            class="border-0 py-0 px-1"
            :class="[
                'flex items-center justify-end dark:justify-start rounded-full h-5 w-9',
                'bg-white text-red-900 hover:bg-gray-200',
                'dark:bg-red-900 dark:text-white dark:hover:bg-red-600',
                'transition-colors duration-300 shadow-sm',
            ]"
        >
            <component :is="icon" class="h-4 w-4" />
        </Button>
    </div>
</template>
