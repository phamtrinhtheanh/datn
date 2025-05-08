<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { useCurrency } from '@/composables/useCurrency';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { LockKeyhole, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Constants
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: 'Thông tin giỏ hàng',
        href: '/cart',
    },
];

// Props
const props = defineProps<{
    products: {
        id: number;
        quantity: number;
        price: number;
        product: {
            id: number;
            name: string;
            images?: string[];
            price: number;
        };
    }[];
}>();

// Refs
const selectedProducts = ref<number[]>([]);

// Computed
const mappedProducts = computed(() =>
    props.products.map((item) => ({
        ...item,
        subtotal: item.product.price * item.quantity,
        price: item.product.price,
        product: {
            ...item.product,
            thumbnail: item.product.images?.[0] ?? null,
        },
    })),
);

const totalAmount = computed(() =>
    mappedProducts.value.filter((item) => selectedProducts.value.includes(item.id)).reduce((acc, item) => acc + item.subtotal, 0),
);

// Composable
const { formatVND } = useCurrency();

// Methods
const toggleSelection = (productId: number) => {
    const index = selectedProducts.value.indexOf(productId);
    if (index !== -1) {
        selectedProducts.value.splice(index, 1);
    } else {
        selectedProducts.value.push(productId);
    }
};

const updateQuantity = (id: number, quantity: number) => {
    if (quantity < 0) return;
    router.put(route('cart.item.update', id), { quantity });
};

const removeItem = (id: number) => {
    router.delete(route('cart.item.remove', id));
};

const handleCheckout = () => {
    if (selectedProducts.value.length === 0) {
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán');
        return;
    }
    router.post(route('checkout'), {
        product_ids: selectedProducts.value,
    });
};
</script>

<template>
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <template v-if="mappedProducts.length">
            <div class="flex flex-col gap-4 lg:flex-row">
                <!-- Products Table -->
                <div class="w-full rounded-lg border bg-white px-4 lg:w-2/3">
                    <Table class="w-full table-fixed">
                        <TableBody>
                            <TableRow v-for="item in mappedProducts" :key="item.id">
                                <TableCell class="w-12">
                                    <div class="flex items-center justify-center">
                                        <Checkbox class="h-4 w-4" :checked="selectedProducts.includes(item.id)" @click="toggleSelection(item.id)" />
                                    </div>
                                </TableCell>

                                <TableCell class="w-24">
                                    <div class="w-full rounded-md border p-1">
                                        <img
                                            v-if="item.product.thumbnail"
                                            :src="/storage/${item.product.thumbnail}"
                                            :alt="item.product.name"
                                            class="aspect-square w-full object-contain"
                                            loading="lazy"
                                        />
                                    </div>
                                </TableCell>

                                <TableCell class="truncate text-base font-semibold">
                                    {{ item.product.name }}
                                </TableCell>

                                <TableCell class="text-right">
                                    {{ formatVND(item.price) }}
                                </TableCell>

                                <TableCell class="w-32 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            @click="updateQuantity(item.id, item.quantity - 1)"
                                            class="rounded border px-2 py-1 hover:bg-gray-100"
                                            :disabled="item.quantity <= 1"
                                        >
                                            −
                                        </button>
                                        <span class="min-w-[24px] text-center">
                                            {{ item.quantity }}
                                        </span>
                                        <button
                                            @click="updateQuantity(item.id, item.quantity + 1)"
                                            class="rounded border px-2 py-1 hover:bg-gray-100"
                                        >
                                            +
                                        </button>
                                    </div>
                                </TableCell>

                                <TableCell class="w-32 text-right font-bold">
                                    {{ formatVND(item.subtotal) }}
                                </TableCell>

                                <TableCell class="w-16 text-center">
                                    <Button variant="ghost" size="icon" @click="removeItem(item.id)" aria-label="Xóa sản phẩm">
                                        <Trash2 class="h-5 w-5 text-red-600" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Order Summary -->
                <div class="w-full rounded-lg border bg-white px-6 py-6 lg:w-1/3">
                    <p class="rounded-lg bg-gray-100 p-4 font-extrabold">TỔNG TIỀN</p>

                    <div class="flex-col space-y-4 p-4">
                        <div class="flex justify-between">
                            <span class="font-medium">Tổng cộng</span>
                            <span class="font-medium">{{ formatVND(totalAmount) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Phí vận chuyển</span>
                            <span class="text-base">{{ formatVND(0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Thành tiền</span>
                            <span class="text-lg font-extrabold text-red-700">
                                {{ formatVND(totalAmount) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button
                            @click="handleCheckout"
                            class="flex-1 rounded-lg bg-red-700 px-4 py-3 font-medium text-white transition-colors hover:bg-red-600"
                            :disabled="selectedProducts.length === 0"
                        >
                            <span class="flex items-center justify-center font-extrabold"> ĐẶT HÀNG </span>
                            <span>Lựa chọn phương thức thanh toán, nhận hàng</span>
                        </button>
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <span class="flex text-gray-500">
                            <LockKeyhole class="mr-2 h-5 w-5" />
                            Thông tin của bạn được mã hóa
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="my-4 rounded-lg border bg-white p-8 text-center text-lg font-semibold text-gray-500">Giỏ hàng của bạn đang trống.</div>
        </template>
    </CustomerLayout>
</template>

