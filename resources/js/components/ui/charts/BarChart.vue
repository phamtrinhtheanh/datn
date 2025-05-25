<script setup lang="ts">
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

interface Props {
    data: {
        labels: string[];
        datasets: Array<{
            label: string;
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
            position: 'top' as const,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
        },
    },
};
</script>

<template>
    <div class="h-[300px]">
        <Bar :data="data" :options="options" />
    </div>
</template> 