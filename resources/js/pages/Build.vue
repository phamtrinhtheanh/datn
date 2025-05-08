<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { useCurrency } from '@/composables/useCurrency';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import { type BreadcrumbItem } from '@/types';
import axios from 'axios';
import { ChevronLeft, ChevronRight, RotateCcw, Trash } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Trang chủ', href: '/' },
    { title: 'Xây dựng cấu hình', href: '/buildpc' },
];

const { formatVND } = useCurrency();

// Danh sách linh kiện mặc định
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

// State lưu các linh kiện đã chọn
const selectedParts = ref<(null | {
    id: number;
    name: string;
    price: number;
    slug: string;
    quantity: number;
    image: string;
    status: string;
})[]>(Array(defaultParts.length).fill(null));

// Dialog chọn linh kiện
const isDialogOpen = ref(false);
const currentPartIndex = ref<number | null>(null);
const currentCategory = ref<string>('');
const searchQuery = ref('');
const searchResults = ref<any[]>([]);
const isLoading = ref(false);

// Pagination
const currentPage = ref(1);
const totalPages = ref(1);
const perPage = ref(10);

// Tính tổng giá
const totalPrice = computed(() => {
    return selectedParts.value.reduce((sum, part) => {
        return sum + (part ? part.price * part.quantity : 0);
    }, 0);
});

// 🔄 Tự động lưu vào LocalStorage khi có thay đổi
watch(selectedParts, (newValue) => {
    localStorage.setItem('pcBuild', JSON.stringify(newValue));
}, { deep: true });

// 🔄 Load cấu hình từ LocalStorage khi trang được tải
onMounted(() => {
    const savedBuild = localStorage.getItem('pcBuild');
    if (savedBuild) {
        selectedParts.value = JSON.parse(savedBuild);
    }
});

// 🗑 Xóa linh kiện
const handleDelete = (index: number) => {
    selectedParts.value[index] = null;
};

// 📂 Mở dialog chọn linh kiện
const openSelectionDialog = async (index: number) => {
    currentPartIndex.value = index;
    currentCategory.value = defaultParts[index].split('-')[0].trim();
    isDialogOpen.value = true;
    searchQuery.value = '';
    searchResults.value = [];
    currentPage.value = 1;
    await searchProducts();
};

// 🔍 Tìm kiếm sản phẩm
const searchProducts = async () => {
    isLoading.value = true;
    try {
        const params = {
            q: searchQuery.value.trim(),
            category_id: currentPartIndex.value + 2,
            page: currentPage.value,
            per_page: perPage.value,
        };
        const response = await axios.get(route('build.part'), { params });
        searchResults.value = Array.isArray(response.data) ? response.data : [];
        currentPage.value = 1;
        totalPages.value = 1;
    } catch (error) {
        console.error('Error loading products:', error);
        searchResults.value = [];
    } finally {
        isLoading.value = false;
    }
};

// ➕ Chọn sản phẩm
const selectProduct = (product: any) => {
    if (currentPartIndex.value !== null) {
        selectedParts.value[currentPartIndex.value] = {
            id: product.id,
            name: product.name,
            price: product.price,
            slug: product.slug,
            quantity: 1,
            image: product.images[0] ?? '',
            status: product.stock > 0 ? 'Còn hàng' : 'Hết hàng',
        };
        isDialogOpen.value = false;
    }
};

// 🔄 Reset cấu hình
const resetBuild = () => {
    selectedParts.value = Array(defaultParts.length).fill(null);
    localStorage.removeItem('pcBuild'); // Xóa dữ liệu đã lưu
};

// Pagination
const goToPage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        searchProducts();
    }
};

// Tự động tìm kiếm khi searchQuery thay đổi
let searchTimeout: number;
watch(searchQuery, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage.value = 1;
        searchProducts();
    }, 500);
});
</script>

<template>
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <h2 class="mb-4 text-center text-xl font-extrabold">Build PC - Tự chọn linh kiện xây dựng cấu hình</h2>
        <div class="flex justify-between p-4">
            <Button class="bg-gray-800 text-base font-bold" @click="resetBuild">
                Làm mới
                <RotateCcw class="ml-2" />
            </Button>
            <div class="text-base font-bold text-red-700">
                Chi phí dự tính: <span>{{ formatVND(totalPrice) }}</span>
            </div>
        </div>
        <div class="p-4">
            <Table class="border">
                <TableBody>
                    <TableRow v-for="(title, i) in defaultParts" :key="i" class="border">
                        <TableCell class="w-96 border text-base font-bold"> {{ i + 1 }}. {{ title }}</TableCell>
                        <TableCell>
                            <div v-if="selectedParts[i]" class="flex items-center gap-4">
                                <div class="w-32 p-2">
                                    <a :href="selectedParts[i]!.slug">
                                        <img
                                            :src="`storage` + selectedParts[i]!.image"
                                            alt="Product image"
                                            class="aspect-square w-24 object-contain"
                                        />
                                    </a>
                                </div>
                                <div class="flex-1">
                                    <div class="text-lg font-bold">{{ selectedParts[i]!.name }}</div>
                                    <div class="text-base font-semibold text-gray-600">{{ selectedParts[i]!.status }}</div>
                                    <div class="text-base font-bold text-red-700">
                                        Giá build: {{ formatVND(selectedParts[i]!.price) }}
                                    </div>
                                </div>
                                <div class="text-sm">{{ selectedParts[i]!.quantity }}</div>
                                <div class="text-base font-bold text-red-600">
                                    Tổng: {{ formatVND(selectedParts[i]!.price * selectedParts[i]!.quantity) }}
                                </div>
                                <div class="gap flex">
                                    <Button variant="link" size="sm" @click="openSelectionDialog(i)">
                                        <RotateCcw class="h-4 w-4" />
                                    </Button>
                                    <Button variant="link" size="sm" @click="handleDelete(i)">
                                        <Trash class="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                            <Button v-else class="bg-gray-800 text-base font-semibold" @click="openSelectionDialog(i)">
                                Chọn <span class="text-md">{{ title }}</span>
                            </Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Dialog chọn linh kiện -->
        <Dialog v-model:open="isDialogOpen" @open="searchQuery = ''">
            <DialogContent class="max-h-screen sm:max-w-[1000px]">
                <DialogHeader>
                    <DialogTitle>Chọn {{ currentCategory }}</DialogTitle>
                </DialogHeader>

                <div class="mt-4 flex gap-2">
                    <Input v-model="searchQuery" placeholder="Tìm kiếm sản phẩm..." />
                </div>

                <div v-if="isLoading" class="flex justify-center py-8">
                    <div class="h-12 w-12 animate-spin rounded-full border-b-2 border-t-2 border-gray-900"></div>
                </div>

                <div v-else class="mt-4 max-h-[500px] overflow-y-auto">
                    <Table>
                        <TableBody>
                            <TableRow v-for="product in searchResults" :key="product.id" class="cursor-pointer hover:bg-gray-100">
                                <TableCell class="w-32">
                                    <a :href="product.slug">
                                        <img :src="`storage` + product.images[0]" alt="Product image" class="aspect-square w-full object-contain" />
                                    </a>
                                </TableCell>
                                <TableCell class="pl-6">
                                    <div class="text-base font-semibold">{{ product.name }}</div>
                                    <div class="text-md font-medium text-gray-800">
                                        {{ product.stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                    </div>
                                    <div class="text-base font-bold text-red-700">Giá: {{ formatVND(product.price) }}</div>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button
                                        variant="outline"
                                        class="border-red-700 text-base font-semibold text-red-700"
                                        @click="selectProduct(product)"
                                    >
                                        Thêm vào cấu hình
                                    </Button>
                                </TableCell>
                            </TableRow>

                            <TableRow v-if="searchResults.length === 0">
                                <TableCell colspan="3" class="py-8 text-center text-gray-500">
                                    {{ searchQuery ? 'Không tìm thấy sản phẩm phù hợp' : 'Nhập từ khóa để tìm kiếm' }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="mt-4 flex items-center justify-between">
                    <div class="text-sm text-gray-600">Trang {{ currentPage }} / {{ totalPages }}</div>
                    <div class="flex gap-2">
                        <Button variant="outline" size="sm" :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
                            <ChevronLeft class="h-4 w-4" />
                        </Button>
                        <Button
                            v-for="page in Math.min(5, totalPages)"
                            :key="page"
                            variant="outline"
                            size="sm"
                            :class="{ 'bg-gray-200': currentPage === page }"
                            @click="goToPage(page)"
                        >
                            {{ page }}
                        </Button>
                        <Button variant="outline" size="sm" :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
                            <ChevronRight class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </CustomerLayout>
</template>
