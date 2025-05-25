<template>
    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-3xl font-extrabold">Khuyến mãi</h1>
                <Link :href="route('admin.promotions.create')">
                    <Button>Thêm khuyến mãi</Button>
                </Link>
            </div>

            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <Label class="">Sắp xếp theo:</Label>
                    <Select
                        v-model="selectedSortField"
                        class="rounded shadow"
                    >
                        <SelectTrigger class="w-[180px]">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="name">Tên khuyến mãi</SelectItem>
                            <SelectItem value="code">Mã khuyến mãi</SelectItem>
                            <SelectItem value="start_date">Ngày bắt đầu</SelectItem>
                            <SelectItem value="end_date">Ngày kết thúc</SelectItem>
                        </SelectContent>
                    </Select>
                    <Select
                        v-model="selectedSortDirection"
                        class="rounded shadow"
                    >
                        <SelectTrigger class="w-[140px]">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="asc">Tăng dần</SelectItem>
                            <SelectItem value="desc">Giảm dần</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button
                        @click="applySort"
                    >
                        Áp dụng
                    </Button>
                </div>
            </div>

            <div class="overflow-hidden border-t border-b">
                <Table>
                    <TableHeader class="text-primary">
                        <TableRow>
                            <TableHead class="text-primary w-[15%] font-bold">
                                <div class="px-3 py-4">Mã</div>
                            </TableHead>
                            <TableHead class="text-primary w-[25%] font-bold">
                                <div class="px-3 py-4">Tên</div>
                            </TableHead>
                            <TableHead class="text-primary w-[15%] font-bold">
                                <div class="px-3 py-4">Loại</div>
                            </TableHead>
                            <TableHead class="text-primary w-[15%] font-bold">
                                <div class="px-3 py-4">Giá trị</div>
                            </TableHead>
                            <TableHead class="text-primary w-[15%] font-bold">
                                <div class="px-3 py-4">Thời gian</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] font-bold">
                                <div class="px-3 py-4">Trạng thái</div>
                            </TableHead>
                            <TableHead class="text-primary w-[5%] text-right font-bold">
                                <div class="px-3 py-4">Hành động</div>
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="promotions.data.length === 0">
                            <TableRow>
                                <TableCell colspan="7">
                                    <div class="px-3 py-4 text-center text-gray-500">Không tìm thấy khuyến mãi nào.</div>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="promotion in promotions.data" :key="promotion.id" class="hover:bg-muted/30">
                                <TableCell class="font-medium">
                                    <div class="px-3 py-3">{{ promotion.code }}</div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">{{ promotion.name }}</div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">
                                        <Badge variant="outline" class="border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">
                                            {{ promotion.type === 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">
                                        <Badge variant="outline" class="border-green-200 bg-green-50 px-3 py-1 text-xs font-bold text-green-600">
                                            {{ promotion.type === 'percentage' 
                                                ? `${promotion.value}%` 
                                                : `${formatCurrency(promotion.value)}đ` }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">
                                        <div class="text-sm">{{ formatDate(promotion.start_date) }}</div>
                                        <div class="text-sm text-gray-500">đến</div>
                                        <div class="text-sm">{{ formatDate(promotion.end_date) }}</div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">
                                        <Badge 
                                            :variant="promotion.is_active ? 'default' : 'secondary'"
                                            :class="[
                                                promotion.is_active 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            {{ promotion.is_active ? 'Đang hoạt động' : 'Đã tắt' }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell class="text-right">
                                    <div class="px-3 py-3">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" size="icon">
                                                    <MoreVertical class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem>
                                                    <Link :href="route('admin.promotions.edit', promotion.id)">
                                                        Chỉnh sửa
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem @click="deletePromotion(promotion)" class="text-red-500">
                                                    Xóa
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { MoreVertical } from 'lucide-vue-next';
import { ref } from 'vue';
import { PropType } from 'vue';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { format } from 'date-fns';

interface Promotion {
    id: number;
    code: string;
    name: string;
    type: 'percentage' | 'fixed_amount';
    value: number;
    start_date: string;
    end_date: string;
    is_active: boolean;
    [key: string]: any;
}

interface PaginatedData {
    data: Promotion[];
    from: number;
    to: number;
    total: number;
}

const props = defineProps({
    promotions: {
        type: Object as PropType<{
            data: Promotion[];
            total: number;
        }>,
        required: true,
    },
    filters: {
        type: Object as PropType<{
            sort_field?: string;
            sort_direction?: string;
        }>,
        default: () => ({
            sort_field: 'name',
            sort_direction: 'asc'
        })
    },
});

const selectedSortField = ref(props.filters?.sort_field || 'name');
const selectedSortDirection = ref(props.filters?.sort_direction || 'asc');

const formatDate = (date: string) => {
    return format(new Date(date), 'dd/MM/yyyy');
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('vi-VN').format(value);
};

const deletePromotion = (promotion: Promotion) => {
    if (confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')) {
        router.delete(route('admin.promotions.destroy', promotion.id));
    }
};

const applySort = () => {
    router.get(route('admin.promotions.index'), {
        sort_field: selectedSortField.value,
        sort_direction: selectedSortDirection.value
    }, { preserveState: true });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script> 