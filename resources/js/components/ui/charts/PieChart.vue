<script setup lang="ts">
import { Pie } from 'vue-chartjs';
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend
} from 'chart.js';

ChartJS.register(
    ArcElement,
    Tooltip,
    Legend
);

interface Props {
    data: {
        labels: string[];
        datasets: Array<{
            data: number[];
        }>;
    };
}

const props = defineProps<Props>();

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'right' as const,
        },
    },
};
</script>

<template>
    <div class="h-[300px]">
        <Pie :data="data" :options="options" />
    </div>
</template> 