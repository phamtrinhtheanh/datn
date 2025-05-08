<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import ProductDialog from './ProductDialog.vue';
import { ref } from 'vue';
import { Select } from '@/components/ui';
import { Checkbox } from '@/components/ui/checkbox';
import { MoreVertical } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const showDialog = ref(false);
const selectedProduct = ref(null);

const openDialog = (product = null) => {
    selectedProduct.value = product;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedProduct.value = null;
};

const deleteProduct = (product) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.destroy', product));
    }
};
</script>

<template>
    <Head title="Products" />

    <AdminLayout>
        <div class="container mx-auto py-6">
            <div class="mb-2 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Products</h1>
                <Button @click="openDialog()" class="h-10"> Add New Product </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead></TableHead>
                            <TableHead>Image</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Brand</TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Stock</TableHead>
                            <TableHead>Created At</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="products.data.length === 0">
                            <TableRow>
                                <TableCell colspan="9" class="py-4 text-center"> No products found. </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="product in products.data" :key="product.id">
                                <TableCell> <Checkbox class="w-4 h-4"></Checkbox></TableCell>
                                <TableCell>
                                    <img v-if="product.image" :src="'/storage/' + product.image" class="h-10 w-10 rounded object-cover" />
                                    <div v-else class="h-10 w-10 rounded bg-gray-200"></div>
                                </TableCell>
                                <TableCell>{{ product.name }}</TableCell>
                                <TableCell>{{ product.brand.name }}</TableCell>
                                <TableCell>{{ product.category.name }}</TableCell>
                                <TableCell>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ product.price.toLocaleString('vi-VN') }}đ</span>
                                        <span v-if="product.sale_price" class="text-sm text-green-600">
                                            {{ product.sale_price.toLocaleString('vi-VN') }}đ
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell>{{ product.stock }}</TableCell>
                                <TableCell>{{ new Date(product.created_at).toLocaleDateString() }}</TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MoreVertical class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem @click="openDialog(product)">Edit</DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteProduct(product)" class="text-red-500"> Delete </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">Showing {{ products.from }} to {{ products.to }} of {{ products.total }} entries</div>
                    <div class="flex items-center gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!products.prev_page_url"
                            @click="router.get(products.prev_page_url)"
                            class="h-8"
                        >
                            Previous
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="!products.next_page_url"
                            @click="router.get(products.next_page_url)"
                            class="h-8"
                        >
                            Next
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <ProductDialog :show="showDialog" :product="selectedProduct" @close="closeDialog" />
    </AdminLayout>
</template>
