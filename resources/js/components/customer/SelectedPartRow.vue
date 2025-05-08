<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useCurrency } from '@/composables/useCurrency';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { RotateCcw, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

// Breadcrumbs configuration
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: 'Xây dựng cấu hình',
        href: '/buildpc',
    },
];

const { formatVND } = useCurrency();

// Default parts categories
const defaultParts = [
    'CPU - BỘ XỬ LÝ',
    'MAINBOARD - BO MẠCH CHỦ',
    'RAM - BỘ NHỚ TRONG',
    'VGA - CARD MÀN HÌNH',
    'Ổ CỨNG',
    'PSU - NGUỒN MÁY TÍNH',
    'CASE - VỎ MÁY TÍNH',
    'MONITOR - MÀN HÌNH',
    'PHỤ KIỆN',
];

// Selected parts state
const selectedParts = ref<(null | {
    id: number;
    name: string;
    price: number;
    quantity: number;
    image: string;
    status: string;
})[]>(Array(defaultParts.length).fill(null));

// Computed total price
const totalPrice = computed(() => {
    return selectedParts.value.reduce((sum, part) => {
        return sum + (part ? part.price * part.quantity : 0);
    }, 0);
});

// Handle part deletion
const handleDelete = (index: number) => {
    selectedParts.value[index] = null;
};

// Handle part reselection
const handleReselect = (index: number) => {
    openSelectionDialog(defaultParts[index], index);
};

// Handle initial part selection
const handleSelect = (index: number) => {
    openSelectionDialog(defaultParts[index], index);
};

// Open selection dialog by navigating to search page
const openSelectionDialog = (category: string, index: number) => {
    const mainCategory = category.split('-')[0].trim();
    router.get(route('products.search'), {
        category: mainCategory,
        buildpc_index: index
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

// Set selected part from search results
const setSelectedPart = (index: number, part: any) => {
    selectedParts.value[index] = {
        id: part.id,
        name: part.name,
        price: part.price,
        quantity: 1,
        image: part.images[0]?.url || '/images/default-product.png',
        status: part.in_stock > 0 ? 'Còn hàng' : 'Hết hàng'
    };
};

// Check URL for selected part (when returning from search)
const urlParams = new URLSearchParams(window.location.search);
const buildpcIndex = urlParams.get('buildpc_index');
const selectedPartId = urlParams.get('selected_part');

if (buildpcIndex && selectedPartId) {
    axios.get(route('api.products.show', selectedPartId))
        .then(response => {
            setSelectedPart(parseInt(buildpcIndex), response.data.data);
            window.history.replaceState({}, document.title, window.location.pathname);
        })
        .catch(error => {
            console.error('Error fetching part details:', error);
        });
}
</script>

<template>
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <h2 class="mb-4 text-center text-xl font-extrabold">
            Build PC - Tự chọn linh kiện xây dựng cấu hình
        </h2>
        <div class="flex justify-between p-4">
            <Button class="bg-gray-800 text-base font-bold" @click="selectedParts = Array(defaultParts.length).fill(null)">
                Làm mới
                <RotateCcw />
            </Button>
            <div class="text-base font-bold text-red-700">
                Chi phí dự tính: <span>{{ formatVND(totalPrice) }}</span>
            </div>
        </div>
        <div class="p-4">
            <Table class="border">
                <TableBody>
                    <TableRow v-for="(title, i) in defaultParts" :key="i" class="border">
                        <TableCell class="w-12 text-base font-bold border">
                            {{ i + 1 }}. {{ title }}
                        </TableCell>
                        <TableCell class="w-36">
                            <div v-if="selectedParts[i]" class="flex items-start gap-4">
                                <img :src="selectedParts[i]!.image" alt="Ảnh linh kiện" class="w-16 h-16 object-cover rounded" />
                                <div class="flex-1">
                                    <div class="font-semibold">{{ selectedParts[i]!.name }}</div>
                                    <div class="text-sm text-gray-600">Trạng thái: {{ selectedParts[i]!.status }}</div>
                                    <div class="text-sm text-gray-600">Số lượng: {{ selectedParts[i]!.quantity }}</div>
                                    <div class="text-sm text-gray-600">Đơn giá: {{ formatVND(selectedParts[i]!.price) }}</div>
                                    <div class="text-base font-bold text-red-600">
                                        Tổng: {{ formatVND(selectedParts[i]!.price * selectedParts[i]!.quantity) }}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <Button variant="destructive" size="sm" @click="handleDelete(i)">
                                        <Trash class="w-4 h-4 mr-1" /> Xóa
                                    </Button>
                                    <Button variant="outline" size="sm" @click="handleReselect(i)">
                                        <RotateCcw class="w-4 h-4 mr-1" /> Chọn lại
                                    </Button>
                                </div>
                            </div>
                            <Button v-else class="bg-gray-800 text-base font-semibold" @click="handleSelect(i)">
                                Chọn <span class="text-md">{{ title }}</span>
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </CustomerLayout>
</template>
