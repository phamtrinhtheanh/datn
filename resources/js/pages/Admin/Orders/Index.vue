<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { router } from '@inertiajs/vue3';
import { Skeleton } from '@/components/ui/skeleton';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';

const props = defineProps({
    orders: {
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

const updateStatus = (order, status) => {
    router.put(route('admin.orders.update-status', order.id), { status });
};
</script>

<template>
    <Head title="Orders" />

    <AdminLayout>
        <div class="container mx-auto py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Orders</h1>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <Input
                                type="text"
                                placeholder="Search orders..."
                                class="w-64"
                            />
                        </div>
                    </div>

                    <div class="relative overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Order Number</TableHead>
                                    <TableHead>Customer</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Total</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="orders.data.length">
                                    <TableRow v-for="order in orders.data" :key="order.id">
                                        <TableCell>{{ order.order_number }}</TableCell>
                                        <TableCell>
                                            <div>{{ order.customer_name }}</div>
                                            <div class="text-sm text-gray-500">{{ order.customer_email }}</div>
                                        </TableCell>
                                        <TableCell>
                                            <Select
                                                :model-value="order.status"
                                                @update:model-value="(value) => updateStatus(order, value)"
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
                                        </TableCell>
                                        <TableCell>${{ order.total }}</TableCell>
                                        <TableCell>{{ new Date(order.created_at).toLocaleDateString() }}</TableCell>
                                        <TableCell>
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                @click="router.visit(route('admin.orders.show', order.id))"
                                            >
                                                View
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                </template>
                                <template v-else>
                                    <TableRow>
                                        <TableCell colspan="6" class="text-center py-4">
                                            No orders found
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="mt-4">
                        <nav class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ orders.from }} to {{ orders.to }} of {{ orders.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <Button
                                    variant="outline"
                                    :disabled="!orders.prev_page_url"
                                    @click="router.visit(orders.prev_page_url)"
                                >
                                    Previous
                                </Button>
                                <Button
                                    variant="outline"
                                    :disabled="!orders.next_page_url"
                                    @click="router.visit(orders.next_page_url)"
                                >
                                    Next
                                </Button>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
