<script setup lang="ts">
import { ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { BarChart, LineChart, PieChart } from '@/components/ui/charts';
import { formatCurrency } from '@/lib/utils';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

interface Props {
    revenueStats: {
        total: number;
        by_day: Array<{ date: string; total: number }>;
        period: string;
    };
    orderStats: {
        total: number;
        completed: number;
        pending: number;
        cancelled: number;
        by_day: Array<{ date: string; total: number }>;
        period: string;
    };
    topProducts: Array<{
        id: number;
        name: string;
        total_sold: number;
        total_revenue: number;
    }>;
    inventoryStats: {
        total_products: number;
        low_stock: number;
        out_of_stock: number;
        by_category: Array<{
            id: number;
            name: string;
            products_count: number;
        }>;
    };
    promotionStats: {
        active_promotions: number;
        total_discount: number;
        by_type: Array<{
            type: string;
            count: number;
        }>;
    };
}

const props = defineProps<Props>();
const period = ref<string>(props.revenueStats.period);

const handlePeriodChange = (value: string) => {
    router.get(route('dashboard'), { period: value }, {
        preserveState: true,
        preserveScroll: true,
        only: ['revenueStats', 'orderStats', 'topProducts'],
    });
};

const revenueChartData = {
    labels: props.revenueStats.by_day.map(item => item.date),
    datasets: [{
        label: 'Doanh thu',
        data: props.revenueStats.by_day.map(item => item.total),
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
    }],
};

const orderChartData = {
    labels: props.orderStats.by_day.map(item => item.date),
    datasets: [{
        label: 'Số đơn hàng',
        data: props.orderStats.by_day.map(item => item.total),
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
    }],
};

const categoryChartData = {
    labels: props.inventoryStats.by_category.map(item => item.name),
    datasets: [{
        data: props.inventoryStats.by_category.map(item => item.products_count),
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
        ],
    }],
};
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Thống kê
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Select v-model="period" @update:model-value="handlePeriodChange">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Chọn khoảng thời gian" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="week">Tuần này</SelectItem>
                            <SelectItem value="month">Tháng này</SelectItem>
                            <SelectItem value="year">Năm nay</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                Tổng doanh thu
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">
                                {{ formatCurrency(revenueStats.total) }}
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                Tổng đơn hàng
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">
                                {{ orderStats.total }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ orderStats.completed }} hoàn thành
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                Sản phẩm tồn kho
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">
                                {{ inventoryStats.total_products }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ inventoryStats.low_stock }} sắp hết hàng
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                Khuyến mãi đang áp dụng
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">
                                {{ promotionStats.active_promotions }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                Tổng giảm giá: {{ formatCurrency(promotionStats.total_discount) }}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7 mt-4">
                    <Card class="col-span-4">
                        <CardHeader>
                            <CardTitle>Doanh thu theo ngày</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <LineChart :data="revenueChartData" />
                        </CardContent>
                    </Card>

                    <Card class="col-span-3">
                        <CardHeader>
                            <CardTitle>Sản phẩm bán chạy</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Sản phẩm</TableHead>
                                        <TableHead>Đã bán</TableHead>
                                        <TableHead>Doanh thu</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="product in topProducts" :key="product.id">
                                        <TableCell>{{ product.name }}</TableCell>
                                        <TableCell>{{ product.total_sold }}</TableCell>
                                        <TableCell>{{ formatCurrency(product.total_revenue) }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7 mt-4">
                    <Card class="col-span-3">
                        <CardHeader>
                            <CardTitle>Phân bố sản phẩm theo danh mục</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <PieChart :data="categoryChartData" />
                        </CardContent>
                    </Card>

                    <Card class="col-span-4">
                        <CardHeader>
                            <CardTitle>Đơn hàng theo ngày</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <BarChart :data="orderChartData" />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
