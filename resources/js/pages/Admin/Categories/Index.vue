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


// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    categories: Object,
});

const showDialog = ref(false);
const selectedCategory = ref(null);

const openCreateDialog = () => {
    selectedCategory.value = null;
    showDialog.value = true;
};

const openEditDialog = (category) => {
    selectedCategory.value = category;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedCategory.value = null;
};
const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString();
};
const deleteCategory = (category) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('admin.categories.destroy', category));
    }
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
        <div class="flex h-full flex-col gap-4 overflow-hidden rounded-xl p-4">
            <div class="mb-2 flex flex-none items-center justify-between">
                <h1 class="text-2xl font-bold">Categories</h1>
                <Button @click="openCreateDialog">Add New Category</Button>
            </div>

            <div class="flex grow flex-col overflow-hidden border">
                <Table class="grow">
                    <TableHeader class="sticky top-0 z-10 bg-gray-50 shadow">
                        <TableRow>
                            <TableHead class="font-medium"></TableHead>
                            <TableHead class="font-medium">Name</TableHead>
                            <TableHead class="font-medium">Tag</TableHead>
                            <TableHead class="text-right font-medium">Actions</TableHead>
                        </TableRow>
                    </TableHeader>

                    <!-- Áp dụng cuộn cho thân bảng -->
                    <TableBody class="max-h-64 overflow-y-auto">
                        <!-- max-h-64: Chiều cao tối đa cho phần thân bảng -->
                        <template v-if="categories.data.length === 0">
                            <TableRow>
                                <TableCell colspan="7" class="py-4 text-center text-gray-500"> No categories found </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="category in categories.data" :key="category.id" class="p-0 hover:bg-gray-50">
                                <TableCell>
                                    <Checkbox class="h-4 w-4"></Checkbox>
                                </TableCell>
                                <TableCell class="font-medium">{{ category.name }}</TableCell>
                                <TableCell class="flex flex-wrap gap-1">
                                    <template v-for="(tag, index) in category.tags" :key="index">
                                        <Badge variant="secondary">
                                            {{ tag }}
                                        </Badge>
                                    </template>
                                </TableCell>

                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MoreVertical class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem @click="openEditDialog(category)">Edit</DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteCategory(category)" class="text-red-500"> Delete </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>

                <div class="flex flex-none items-center justify-between border-t p-4">
                    <div class="text-sm text-gray-500">Showing {{ categories.from }} to {{ categories.to }} of {{ categories.total }} entries</div>
                    <div class="flex space-x-2">
                        <Button variant="outline" size="sm" :disabled="!categories.prev_page_url" @click="router.visit(categories.prev_page_url)">
                            Previous
                        </Button>
                        <Button variant="outline" size="sm" :disabled="!categories.next_page_url" @click="router.visit(categories.next_page_url)">
                            Next
                        </Button>

                    </div>
                </div>
            </div>
        </div>

        <CategoryDialog :show="showDialog" :category="selectedCategory" @close="closeDialog" />
    </AppLayout>
</template>
