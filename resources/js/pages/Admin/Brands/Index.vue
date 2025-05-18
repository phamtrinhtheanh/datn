<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { toast } from 'vue-sonner'

import { Checkbox } from '@/components/ui/checkbox';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import BrandDialog from './BrandDialog.vue';
import { ref, computed } from 'vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { MoreVertical } from 'lucide-vue-next';

interface Brand {
    id: number;
    name: string;
    created_at: string;
}

interface Props {
    brands: {
        data: Brand[];
        from: number;
        to: number;
        total: number;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
}

const props = defineProps<Props>();

const showDialog = ref(false);
const selectedBrand = ref<Brand | null>(null);

const openCreateDialog = () => {
    selectedBrand.value = null;
    showDialog.value = true;
};

const openEditDialog = (brand: Brand) => {
    selectedBrand.value = brand;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedBrand.value = null;
};

const deleteBrand = (brand: Brand) => {
    if (!confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')) return;

    router.delete(route('admin.brands.destroy', brand), {
        onSuccess: () => {
            toast.success("Thương hiệu đã được xóa thành công")
        },
        onError: (errors) => {
            toast.error("Có lỗi xảy ra khi xóa thương hiệu")
        }
    });
};

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleDateString('vi-VN');
};

const breadcrumbs = computed(() => [{
    title: 'Thương hiệu',
    href: '/dashboard',
}]);
</script>

<template>
    <Head title="Danh sách thương hiệu" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Danh sách thương hiệu</h1>
                <Button @click="openCreateDialog" class="bg-primary hover:bg-primary/90 text-white">
                    <span class="mr-2">+</span>
                    Thêm mới thương hiệu
                </Button>
            </div>

            <div class="rounded-lg bg-white shadow">
                <div class="overflow-auto max-h-[70vh] w-full">
                <Table>
                    <TableHeader>
                        <TableRow class="sticky top-0 z-10 bg-white">
                            <TableHead class="font-medium">Tên thương hiệu</TableHead>
                            <TableHead class="font-medium">Ngày tạo</TableHead>
                            <TableHead class="text-right font-medium">Thao tác</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="!brands.data.length" class="hover:bg-gray-50">
                            <TableCell colspan="3" class="py-4 text-center text-gray-500">
                                Không tìm thấy thương hiệu nào
                            </TableCell>
                        </TableRow>
                        <TableRow
                            v-for="brand in brands.data"
                            :key="brand.id"
                            class="hover:bg-gray-50"
                        >
                            <TableCell>{{ brand.name }}</TableCell>
                            <TableCell>{{ formatDate(brand.created_at) }}</TableCell>
                            <TableCell class="text-right">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon">
                                            <MoreVertical class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem
                                            @click="openEditDialog(brand)"
                                            class="cursor-pointer"
                                        >
                                            <span class="mr-2">✏️</span>
                                            Chỉnh sửa
                                        </DropdownMenuItem>
                                        <DropdownMenuItem
                                            @click="deleteBrand(brand)"
                                            class="text-red-500 cursor-pointer"
                                        >
                                            <span class="mr-2">🗑️</span>
                                            Xóa
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                </div>
                <div class="flex items-center justify-between border-t p-4">
                    <div class="text-sm text-gray-500">
                        Hiển thị từ {{ brands.from }} đến {{ brands.to }} trong tổng số {{ brands.total }} thương hiệu
                    </div>
                    <div class="flex space-x-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!brands.prev_page_url"
                            @click="router.visit(brands.prev_page_url)"
                        >
                            <span class="mr-2">←</span>
                            Trước
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!brands.next_page_url"
                            @click="router.visit(brands.next_page_url)"
                        >
                            Sau
                            <span class="ml-2">→</span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <BrandDialog :show="showDialog" :brand="selectedBrand" @close="closeDialog" />
    </AppLayout>
</template>
