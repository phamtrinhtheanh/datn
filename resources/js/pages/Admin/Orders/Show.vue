<script setup lang="ts">
import AdminLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
    order: {
        id: number;
        order_number: string;
        customer_name: string;
        phone: string;
        email: string;
        address: string;
        notes: string;
        payment_method: 'vnpay' | 'cod';
        total: number;
        status: 'pending' | 'processing' | 'confirmed' | 'completed' | 'cancelled';
        created_at: string;
        updated_at: string;
        user: {
            id: number;
            name: string;
        };
        items: Array<{
            id: number;
            product_id: number;
            product_name: string;
            quantity: number;
            price: number;
            subtotal: number;
        }>;
    };
}>();

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

const paymentLabels = {
    vnpay: 'Thanh toán qua VNPay',
    cod: 'Thanh toán khi nhận hàng'
};

const formatVND = (price: number) =>
    new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);

const updateStatus = (status: string) => {
    router.put(route('admin.orders.status', props.order.id), { status });
};
</script>

<template>
    <AdminLayout>
        <div class="container px-4 mx-auto py-8">
            <div class="bg-white rounded-lg shadow">
                <!-- Header -->
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold">Chi tiết đơn hàng #{{ order.order_number }}</h2>
                            <p class="text-gray-500 mt-1">
                                Đặt lúc {{ new Date(order.created_at).toLocaleString() }}
                            </p>
                        </div>
                        <Select
                            :value="order.status"
                            @update:value="updateStatus"
                        >
                            <SelectTrigger class="w-[180px]">
                                <SelectValue>
                                    <Badge :class="statusColors[order.status]">
                                        {{ statusLabels[order.status] }}
                                    </Badge>
                                </SelectValue>
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="pending">
                                    <Badge :class="statusColors.pending">
                                        {{ statusLabels.pending }}
                                    </Badge>
                                </SelectItem>
                                <SelectItem value="processing">
                                    <Badge :class="statusColors.processing">
                                        {{ statusLabels.processing }}
                                    </Badge>
                                </SelectItem>
                                <SelectItem value="confirmed">
                                    <Badge :class="statusColors.confirmed">
                                        {{ statusLabels.confirmed }}
                                    </Badge>
                                </SelectItem>
                                <SelectItem value="completed">
                                    <Badge :class="statusColors.completed">
                                        {{ statusLabels.completed }}
                                    </Badge>
                                </SelectItem>
                                <SelectItem value="cancelled">
                                    <Badge :class="statusColors.cancelled">
                                        {{ statusLabels.cancelled }}
                                    </Badge>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600">Họ tên</p>
                            <p class="font-medium">{{ order.customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Số điện thoại</p>
                            <p class="font-medium">{{ order.phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-medium">{{ order.email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Địa chỉ</p>
                            <p class="font-medium">{{ order.address }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Phương thức thanh toán</p>
                            <p class="font-medium">{{ paymentLabels[order.payment_method] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Ghi chú</p>
                            <p class="font-medium">{{ order.notes || 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold mb-4">Sản phẩm</h3>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sản phẩm</TableHead>
                                <TableHead class="text-right">Đơn giá</TableHead>
                                <TableHead class="text-right">Số lượng</TableHead>
                                <TableHead class="text-right">Thành tiền</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="item in order.items" :key="item.id">
                                <TableCell>
                                    <div class="flex items-center gap-4">
                                        <span>{{ item.product_name }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-right">{{ formatVND(item.price) }}</TableCell>
                                <TableCell class="text-right">{{ item.quantity }}</TableCell>
                                <TableCell class="text-right">{{ formatVND(item.subtotal) }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Order Summary -->
                <div class="p-6">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tổng tiền hàng:</span>
                                <span>{{ formatVND(order.total) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển:</span>
                                <span>{{ formatVND(0) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-lg">
                                <span>Tổng cộng:</span>
                                <span class="text-red-600">{{ formatVND(order.total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <Link :href="route('admin.orders.index')">
                    <Button variant="outline" class="flex items-center gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Quay lại danh sách đơn hàng
                    </Button>
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>
