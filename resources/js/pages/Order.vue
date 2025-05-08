<script setup lang="ts">
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const props = defineProps<{
    orders: Array<{
        id: number;
        order_number: string;
        customer_name: string;
        customer_phone: string;
        shipping_address: string;
        status: string;
        total: number;
        notes: string;
        created_at: string;
        updated_at: string;
    }>;
}>();
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

</script>


<template>
    <Head title="Danh sách đơn hàng" />
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Table class="table-auto w-full">
                <TableHeader>
                <TableRow>
                    <TableHead class="border px-4 py-2">Mã đơn</TableHead>
                    <TableHead class="border px-4 py-2">Khách hàng</TableHead>
                    <TableHead class="border px-4 py-2">Số điện thoại</TableHead>
                    <TableHead class="border px-4 py-2">Địa chỉ</TableHead>
                    <TableHead class="border px-4 py-2">Trạng thái</TableHead>
                    <TableHead class="border px-4 py-2">Tổng tiền</TableHead>
                    <TableHead class="border px-4 py-2">Ghi chú</TableHead>
                    <TableHead class="border px-4 py-2">Ngày tạo</TableHead>
                </TableRow>
                </TableHeader>
                <TableBody>
                <TableRow v-for="order in props.orders" :key="order.id">
                    <TableCell class="border px-4 py-2">{{ order.order_number }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.customer_name }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.customer_phone }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.shipping_address }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.status }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.total.toLocaleString() }}₫</TableCell>
                    <TableCell class="border px-4 py-2">{{ order.notes }}</TableCell>
                    <TableCell class="border px-4 py-2">{{ new Date(order.created_at).toLocaleString() }}</TableCell>
                </TableRow>
                </TableBody>
            </Table>
        </div>
    </CustomerLayout>
</template>
