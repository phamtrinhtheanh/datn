<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { formatVND } from '@/lib/utils';

const page = usePage();
const category = ref<string | null>(null);
const buildpcIndex = ref<string | null>(null);
const products = ref<any[]>([]);

onMounted(() => {
    const query = new URLSearchParams(window.location.search);
    category.value = query.get('category');
    buildpcIndex.value = query.get('buildpc_index');

    if (category.value) {
        axios.get(route('api.products.byCategory'), {
            params: { category: category.value }
        }).then((response) => {
            products.value = response.data.data;
        });
    }
});

const handleSelect = (productId: number) => {
    router.get(route('buildpc.index'), {
        buildpc_index: buildpcIndex.value,
        selected_part: productId,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Chọn linh kiện: {{ category }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div v-for="product in products" :key="product.id" class="border rounded-lg p-4 shadow">
                <img :src="product.images[0]?.url || '/images/default-product.png'" alt="" class="w-full h-40 object-cover mb-2">
                <div class="font-bold">{{ product.name }}</div>
                <div class="text-gray-600 text-sm mb-2">Giá: {{ formatVND(product.price) }}</div>
                <Button class="bg-blue-600 text-white w-full" @click="handleSelect(product.id)">
                    Chọn linh kiện này
                </Button>
            </div>
        </div>
    </div>
</template>
