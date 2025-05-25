<script setup lang="ts">
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
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
        <Line :data="data" :options="options" />
    </div>
</template> 