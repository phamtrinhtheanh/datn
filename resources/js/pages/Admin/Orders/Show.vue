<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { router } from '@inertiajs/vue3';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
});

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
};

const updateStatus = (status) => {
    router.put(route('admin.orders.update-status', props.order.id), { status });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="`Order ${order.order_number}`" />

    <AdminLayout>
        <div class="container mx-auto py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Order #{{ order.order_number }}</h1>
                <Button variant="outline" @click="router.visit(route('admin.orders.index'))">
                    Back to Orders
                </Button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Order Details</h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Order Status</p>
                                    <Select
                                        :model-value="order.status"
                                        @update:model-value="updateStatus"
                                    >
                                        <SelectTrigger class="w-[180px]">
                                            <SelectValue :placeholder="order.status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pending">Pending</SelectItem>
                                            <SelectItem value="processing">Processing</SelectItem>
                                            <SelectItem value="completed">Completed</SelectItem>
                                            <SelectItem value="cancelled">Cancelled</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Order Date</p>
                                    <p>{{ formatDate(order.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow p-6 mt-6">
                        <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                        <div class="relative overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Product</TableHead>
                                        <TableHead>Price</TableHead>
                                        <TableHead>Quantity</TableHead>
                                        <TableHead>Subtotal</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="item in order.items" :key="item.id">
                                        <TableCell>
                                            <div class="font-medium">{{ item.product_name }}</div>
                                        </TableCell>
                                        <TableCell>{{ formatCurrency(item.price) }}</TableCell>
                                        <TableCell>{{ item.quantity }}</TableCell>
                                        <TableCell>{{ formatCurrency(item.subtotal) }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                </div>

                <!-- Customer & Summary -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p>{{ order.customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p>{{ order.customer_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <p>{{ order.customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Shipping Address</p>
                                <p>{{ order.shipping_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>{{ formatCurrency(order.subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping Fee</span>
                                <span>{{ formatCurrency(order.shipping_fee) }}</span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Total</span>
                                    <span>{{ formatCurrency(order.total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div v-if="order.notes" class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Notes</h2>
                        <p class="text-gray-600">{{ order.notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
