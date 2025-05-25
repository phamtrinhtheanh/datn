<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ChevronLeft } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const props = defineProps<{
    products: {
        id: number;
        quantity: number;
        price: number;
        product: {
            id: number;
            name: string;
            images: string[];
            price: number;
        };
    }[];
    total: number;
    activePromotions: {
        id: number;
        code: string;
        name: string;
        type: 'percentage' | 'fixed_amount';
        value: number;
        min_order_amount: number;
        max_uses: number;
        start_date: string;
        end_date: string;
    }[];
}>();

const mappedProducts = computed(() =>
    props.products.map((item) => ({
        ...item,
        subtotal: item.price * item.quantity,
        product: {
            ...item.product,
            thumbnail: item.product.images[0] ?? null,
        },
    }))
);

const totalAmount = computed(() => props.total);

// Form fields
const customerName = ref('Nguyễn Văn A');
const phone = ref('0987654321');
const email = ref('demo@gmail.com');
const address = ref('123 Đường Demo, Quận 1, TP.HCM');
const notes = ref('Giao hàng giờ hành chính');
const payment = ref('vnpay');
const selectedPromotion = ref<string>('');
const selectedPromotionId = computed(() => {
    const promo = props.activePromotions.find(p => p.code === selectedPromotion.value);
    return promo ? promo.id : 0;
});

const discount = computed(() => {
    if (!selectedPromotion.value) return 0;

    const promotion = props.activePromotions.find(p => p.code === selectedPromotion.value);
    if (!promotion) return 0;

    if (promotion.type === 'percentage') {
        return (totalAmount.value * promotion.value) / 100;
    }
    return promotion.value;
});

const finalTotal = computed(() => totalAmount.value - discount.value);

const submitForm = () => {
    const orderData = {
        customer_name: customerName.value,
        phone: phone.value,
        email: email.value,
        address: address.value,
        payment: payment.value,
        notes: notes.value,
        products: props.products,
        total: totalAmount.value,
        discount: discount.value,
        final_total: finalTotal.value,
        promotion: selectedPromotionId.value,
    };

    router.post(route('order.create'), orderData, {
        onSuccess: () => {
            // Handle success
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: 'Thông tin đơn hàng',
        href: '/cart',
    },
];

const formatVND = (price: number) =>
    new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);
</script>

<template>
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <div class="flex w-full flex-col gap-4 lg:flex-row container mx-auto">
            <!-- Phần thanh toán -->
            <div class="flex-col rounded-lg bg-white p-6 lg:w-1/2">
                <div class="flex h-full flex-col justify-between">
                    <div>
                        <p class="py-3 text-xl font-extrabold text-gray-800">Thông tin đơn hàng</p>
                        <Table class="w-full table-fixed">
                            <TableBody>
                                <TableRow v-for="item in mappedProducts" :key="item.id">
                                    <TableCell class="w-24">
                                        <div class="w-full rounded-md p-1">
                                            <img
                                                v-if="item.product.thumbnail"
                                                :src="item.product.thumbnail"
                                                alt="product image"
                                                class="aspect-square w-full object-contain"
                                            />
                                        </div>
                                    </TableCell>
                                    <TableCell class="truncate text-base font-semibold">
                                        {{ item.product.name }}
                                    </TableCell>
                                    <TableCell class="w-32 text-center">
                                        <div class="flex items-center">
                                            x
                                            <span class="min-w-[24px] text-center">{{ item.quantity }}</span>
                                        </div>
                                    </TableCell>
                                    <TableCell class="w-32 text-right font-bold">
                                        {{ formatVND(item.subtotal) }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-end justify-between">
                        <a class="flex items-center hover:text-red-600" href="/">
                            <ChevronLeft class="mr-2 h-4 w-4" />
                            Quay lại mua sắm</a
                        >
                        <div class="flex-col space-y-2 pr-4">
                            <div class="flex items-center gap-2">
                                <Label class="text-base font-semibold">Mã khuyến mãi:</Label>
                                <Select v-model="selectedPromotion">
                                    <SelectTrigger class="w-[200px]">
                                        <SelectValue placeholder="Chọn mã khuyến mãi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="0">Không áp dụng</SelectItem>
                                        <SelectItem
                                            v-for="promo in activePromotions"
                                            :key="promo.id"
                                            :value="promo.code"
                                        >
                                            {{ promo.name }} - {{ promo.type === 'percentage' ? promo.value + '%' : formatVND(promo.value) }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <p class="flex w-full justify-between">
                                <span class="mr-4 text-base font-semibold">Tổng cộng:</span>
                                <span>{{ formatVND(totalAmount) }}</span>
                            </p>
                            <p class="flex w-full justify-between">
                                <span class="mr-24 text-base font-semibold">Khuyến mãi:</span>
                                <span>{{ formatVND(discount) }}</span>
                            </p>
                            <p class="flex w-full items-center justify-between">
                                <span class="mr-4 text-base font-extrabold">Tổng cộng:</span>
                                <span class="text-lg font-extrabold text-red-700">{{ formatVND(finalTotal) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Phần thông tin khách hàng -->
            <div class="w-full lg:w-1/2">
                <div class="rounded-lg border bg-white p-6">
                    <h2 class="mb-4 rounded-lg bg-gray-100 p-3 text-xl font-extrabold">Thông tin khách hàng</h2>

                    <form v-on:submit.prevent="submitForm" class="space-y-4">
                        <!-- Các trường thông tin - FLEX NGANG -->
                        <div class="flex flex-col gap-4">
                            <!-- Mỗi dòng là 1 flex container -->
                            <div class="flex items-center gap-4">
                                <Label class="w-1/3 min-w-[120px] text-base font-semibold">Họ tên</Label>
                                <Input class="flex-1" text-base required v-model="customerName" />
                            </div>

                            <div class="flex items-center gap-4">
                                <Label class="w-1/3 min-w-[120px] text-base font-semibold">Số điện thoại</Label>
                                <Input class="flex-1" text-base required v-model="phone" />
                            </div>

                            <div class="flex items-center gap-4">
                                <Label class="w-1/3 min-w-[120px] text-base font-semibold">Email</Label>
                                <Input class="flex-1" text-base required type="email" v-model="email" />
                            </div>

                            <div class="flex items-center gap-4">
                                <Label class="w-1/3 min-w-[120px] text-base font-semibold">Địa chỉ</Label>
                                <Input class="flex-1" text-base required v-model="address" />
                            </div>

                            <div class="flex items-center gap-4">
                                <Label class="w-1/3 min-w-[120px] text-base font-semibold">Ghi chú</Label>
                                <Textarea class="flex-1" text-base required v-model="notes" />
                            </div>
                        </div>

                        <div class="items-top flex flex-col gap-4">
                            <Label class="w-1/3 min-w-[120px] text-base font-semibold">Thanh toán</Label>
                            <div class="flex-1 flex-col items-center space-y-4">
                                <div class="flex items-center">
                                    <input type="radio" value="vnpay" id="vnpay" v-model="payment" name="payment" class="h-3 w-3" />
                                    <Label for="vnpay" class="ml-2 flex items-center gap-2 text-base"
                                    ><span class="h-12 w-12 overflow-hidden rounded-md border bg-gray-100"
                                    ><img
                                        src="https://vnpay.vn/s1/statics.vnpay.vn/2023/6/0oxhzjmxbksr1686814746087.png"
                                        class="h-full w-full object-contain"
                                        alt="vnpay" /></span
                                    >Thanh toán bằng QR Code, thẻ ATM nội địa, VISA</Label
                                    >
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="cod" value="cod" v-model="payment" name="payment" class="h-3 w-3" />
                                    <Label for="cod" class="ml-2 flex items-center gap-2 text-base"
                                    ><span class="h-12 w-12 overflow-hidden rounded-md border bg-gray-100"
                                    ><img
                                        src="https://cdn-icons-png.flaticon.com/512/10053/10053703.png"
                                        class="h-full w-full object-contain"
                                        alt="vnpay" /></span
                                    >Thanh toán khi nhận hàng(COD)</Label
                                    >
                                </div>
                            </div>
                        </div>

                        <Button class="w-full bg-red-700 py-2 text-base font-bold hover:bg-red-800" type="submit"> Đặt hàng</Button>
                    </form>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
