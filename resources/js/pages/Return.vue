<script setup lang="ts">
import CustomerLayout from '@/layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
const props = defineProps<{
    paymentStatus: string;
    orderId: string;
    transactionNo: string;
    amount: number;
    message?: string;
}>();
</script>

<template>
    <Head :title="paymentStatus === 'success' ? 'Payment Success' : 'Payment Failed'" />
    <CustomerLayout>
        <div class="flex items-center justify-center mt-6">
            <div class="w-full max-w-md rounded-lg bg-white border p-8 shadow-md">
                <div v-if="paymentStatus === 'success'" class="text-center">
                    <div class="mb-4 text-5xl text-green-500">✓</div>
                    <h1 class="mb-2 text-2xl font-bold">Thanh toán thành công</h1>
                    <p class="mb-4">Cảm ơn bạn đã mua hàng!</p>

                    <div class="space-y-2 text-left">
                        <p><strong>Order ID:</strong> {{ orderId }}</p>
                        <p><strong>Transaction No:</strong> {{ transactionNo }}</p>
                        <p><strong>Số tiền:</strong> {{ amount.toLocaleString() }} VND</p>
                    </div>

                   <div class="flex gap-6 justify-center">
                       <Link href="/" class="mt-6 inline-block rounded bg-red-700 px-4 py-2 text-white hover:bg-red-600"> Về trang chủ </Link>
                       <Link href="/don-hang" class="mt-6 inline-block border border-red-700 rounded bg-transparent text-red-700 px-4 py-2 hover:shadow-md"> Đơn mua </Link>
                   </div>
                </div>

                <div v-else class="text-center">
                    <div class="mb-4 text-5xl text-red-500">✗</div>
                    <h1 class="mb-2 text-2xl font-bold">Có lỗi xảy ra</h1>
                    <p class="mb-4">{{message}}</p>

                    <Link href="/" class="mt-6 inline-block rounded bg-red-700 px-4 py-2 text-white hover:bg-red-600"> Về trang chủ </Link>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
