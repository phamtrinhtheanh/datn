<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { MoreVertical } from 'lucide-vue-next';
import { ref } from 'vue';
import CategoryDialog from './CategoryDialog.vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { PropType } from 'vue';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Category {
    id: number;
    name: string;
    tags: string[];
    products_count: number;
    [key: string]: any; // Add index signature for route parameters
}

interface PaginatedData {
    data: Category[];
    from: number;
    to: number;
    total: number;
}

const props = defineProps({
    categories: {
        type: Object as PropType<{
            data: Category[];
            total: number;
        }>,
        required: true,
    },
    filters: {
        type: Object as PropType<{
            sort_field?: string;
            sort_direction?: string;
        }>,
        required: true,
    },
});

const showDialog = ref(false);
const selectedCategory = ref<Category | null>(null);
const selectedSortField = ref(props.filters.sort_field || 'name');
const selectedSortDirection = ref(props.filters.sort_direction || 'asc');

const openCreateDialog = () => {
    selectedCategory.value = null;
    showDialog.value = true;
};

const openEditDialog = (category: Category) => {
    selectedCategory.value = category;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedCategory.value = null;
};

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleDateString();
};

const deleteCategory = (category: Category) => {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        router.delete(route('admin.categories.destroy', { id: category.id }));
    }
};

const applySort = () => {
    router.get(route('admin.categories.index'), {
        sort_field: selectedSortField.value,
        sort_direction: selectedSortDirection.value
    }, { preserveState: true });
};

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-3xl font-extrabold">Danh mục</h1>
                <Button @click="openCreateDialog">Thêm danh mục</Button>
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
                            <SelectItem value="name">Tên danh mục</SelectItem>
                            <SelectItem value="products_count">Số sản phẩm</SelectItem>
                            <SelectItem value="created_at">Ngày tạo</SelectItem>
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
                <Table class="">
                    <TableHeader class="text-primary">
                        <TableRow>
                            <TableHead class="text-primary w-[40%] font-bold">
                                <div class="px-3 py-4">Tên danh mục</div>
                            </TableHead>
                            <TableHead class="text-primary w-[30%] font-bold">
                                <div class="px-3 py-4">Tags</div>
                            </TableHead>
                            <TableHead class="text-primary w-[15%] font-bold">
                                <div class="px-3 py-4 text-center">Số sản phẩm</div>
                            </TableHead>
                            <TableHead class="text-primary w-[15%] text-right font-bold">
                                <div class="px-3 py-4">Hành động</div>
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="categories.data.length === 0">
                            <TableRow>
                                <TableCell colspan="4">
                                    <div class="px-3 py-4 text-center text-gray-500">Không tìm thấy danh mục nào.</div>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="category in categories.data" :key="category.id" class="hover:bg-muted/30">
                                <TableCell class="font-medium">
                                    <div class="px-3 py-3">{{ category.name }}</div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3 flex flex-wrap gap-1">
                                        <template v-for="(tag, index) in category.tags" :key="index">
                                            <Badge variant="outline" class="border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-600">
                                                {{ tag }}
                                            </Badge>
                                        </template>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3 text-center">
                                        <Badge variant="outline" class="border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">
                                            {{ category.products_count }}
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
                                                <DropdownMenuItem @click="openEditDialog(category)">Chỉnh sửa</DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteCategory(category)" class="text-red-500">Xóa</DropdownMenuItem>
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

        <CategoryDialog :show="showDialog" :category="selectedCategory" @close="closeDialog" />
    </AppLayout>
</template>
