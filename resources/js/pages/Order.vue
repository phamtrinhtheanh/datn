<script setup lang="ts">
import CustomerLayout from '@/layouts/MainLayout.vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ChevronLeft, ChevronRight, Eye, Trash2, MoreVertical } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const props = defineProps<{
    orders: Array<{
        id: number;
        order_number: string;
        customer_name: string;
        phone: string;
        address: string;
        status: 'pending' | 'processing' | 'confirmed' | 'completed' | 'cancelled';
        total: number;
        notes: string;
        created_at: string;
        updated_at: string;
    }>;
    pagination?: {
        total: number;
        per_page: number;
        current_page: number;
        last_page: number;
        from: number;
        to: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: 'Đơn hàng của tôi',
        href: '/orders',
    },
];

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    confirmed: 'bg-green-100 text-green-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
};

const statusLabels = {
    pending: 'Chờ xử lý',
    processing: 'Đang xử lý',
    confirmed: 'Đã xác nhận',
    completed: 'Hoàn thành',
    cancelled: 'Đã hủy'
};

const cancelOrder = (orderId: number) => {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        router.put(route('orders.cancel', orderId));
    }
};

const deleteOrder = (orderId: number) => {
    if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
        router.delete(route('orders.destroy', orderId));
    }
};
</script>

<template>
    <CustomerLayout>
        <div class="container px-4 mx-auto py-8">
            <div class="bg-white rounded-lg shadow px-8">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-6">Đơn hàng của tôi</h2>

                    <div class="relative overflow-x-auto border-y">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="text-primary text-base font-bold">
                                        <div class="px-4 py-3">
                                            Mã đơn hàng
                                        </div>
                                    </TableHead>
                                    <TableHead class="text-primary text-base font-bold">Trạng thái</TableHead>
                                    <TableHead class="text-primary text-base font-bold">Tổng tiền</TableHead>
                                    <TableHead class="text-primary text-base font-bold">Ngày tạo</TableHead>
                                    <TableHead class="text-primary text-base font-bold text-right">Thao tác</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="order in orders" :key="order.id">
                                    <TableCell>
                                        <div class="px-4 py-3 text-base">
                                            {{ order.order_number }}
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :class="statusColors[order.status]" class="flex items-center gap-1.5 px-1.5 py-0.5 text-xs">
                                            {{ statusLabels[order.status] }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>{{ order.total.toLocaleString() }}₫</TableCell>
                                    <TableCell>{{ new Date(order.created_at).toLocaleString() }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <Button
                                                v-if="order.status === 'pending' || order.status === 'processing'"
                                                variant="destructive"
                                                size="sm"
                                                @click="cancelOrder(order.id)"
                                            >
                                                Hủy đơn
                                            </Button>
                                            <DropdownMenu>
                                                <DropdownMenuTrigger asChild>
                                                    <Button variant="ghost" size="icon">
                                                        <MoreVertical class="h-4 w-4" />
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end">
                                                    <DropdownMenuItem asChild>
                                                        <Link :href="route('orders.show', order.id)" class="flex items-center">
                                                            <Eye class="mr-2 h-4 w-4" />
                                                            <span>Xem chi tiết</span>
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem 
                                                        v-if="order.status === 'cancelled' || order.status === 'completed'"
                                                        @click="deleteOrder(order.id)"
                                                        class="text-red-500"
                                                    >
                                                        <Trash2 class="mr-2 h-4 w-4" />
                                                        <span>Xóa đơn hàng</span>
                                                    </DropdownMenuItem>
                                                </DropdownMenuContent>
                                            </DropdownMenu>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="pagination" class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                        <div class="text-muted-foreground text-sm">
                            Hiển thị từ {{ pagination.from }} đến {{ pagination.to }} của {{ pagination.total }} kết quả
                        </div>
                        <div class="flex items-center gap-1">
                            <Button
                                variant="ghost"
                                size="icon"
                                :disabled="pagination.current_page === 1"
                                @click="router.get(route('orders.index', { page: pagination.current_page - 1 }))"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </Button>
                            <Button
                                variant="ghost"
                                size="icon"
                                :disabled="pagination.current_page === pagination.last_page"
                                @click="router.get(route('orders.index', { page: pagination.current_page + 1 }))"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
