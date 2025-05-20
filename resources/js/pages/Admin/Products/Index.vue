<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';

import { ChevronLeft, ChevronRight, MoreVertical, Search } from 'lucide-vue-next';
import { PropType, ref, watch } from 'vue';
import ProductDialog from './ProductDialog.vue';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import {Label} from "@/components/ui/label";

interface Product {
    id: number;
    name: string;
    image: string;
    price: number;
    sale_price?: number;
    stock: number;
    created_at: string;
    brand: {
        name: string;
    };
    category: {
        name: string;
    };
    published: boolean;
    color: string;
}

interface PageProps {
    filters: {
        search: string;
        per_page: number;
        sort_field?: string;
        sort_direction?: string;
    };

    [key: string]: any;
}

const props = defineProps({
    products: {
        type: Object as PropType<{
            data: Product[];
            from: number;
            to: number;
            total: number;
            links: Array<{
                url: string | null;
                label: string;
                active: boolean;
            }>;
            first_page_url?: string;
            prev_page_url?: string;
            next_page_url?: string;
            last_page_url?: string;
        }>,
        required: true,
    },
    filters: {
        type: Object as PropType<{
            search: string;
            per_page: number;
            sort_field?: string;
            sort_direction?: string;
        }>,
        required: true,
    },
});

const page = usePage();
const showDialog = ref(false);
const selectedProduct = ref<Product | null>(null);
const selectedProducts = ref<number[]>([]);
const searchValue = ref(props.filters.search);
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

watch(searchValue, (val) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (val !== props.filters.search) {
            router.get(route('admin.products.index'), { search: val }, { preserveState: true });
        }
    }, 500);
});

const toggleProduct = (productId: number) => {
    const index = selectedProducts.value.indexOf(productId);
    if (index === -1) {
        selectedProducts.value.push(productId);
    } else {
        selectedProducts.value.splice(index, 1);
    }
};

const toggleAllProducts = () => {
    if (selectedProducts.value.length === props.products.data.length) {
        selectedProducts.value = [];
    } else {
        selectedProducts.value = props.products.data.map((p: Product) => p.id);
    }
};

const openDialog = (product: Product | null = null) => {
    selectedProduct.value = product;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedProduct.value = null;
};

const deleteProduct = (product: Product) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.destroy', { id: product.id }));
    }
};
</script>

<template>
    <Head title="Products" />

    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-4xl font-extrabold">Sản phẩm</h1>
                <Button class="px-6 h-11 font-bold md:rounded-xl text-base shadow" @click="openDialog()"> Thêm sản phẩm</Button>
            </div>
            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <Label class="text-base">
                        Hiển thị:
                    </Label>
                    <Select
                        :value="filters.per_page"
                        class="w-24 rounded shadow"
                        @update:value="
                            (value: number) =>
                                router.get(route('admin.products.index'), { per_page: value, search: filters.search }, { preserveState: true })
                        "
                    >
                        <SelectTrigger>
                            <SelectValue>5</SelectValue>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="5">5</SelectItem>
                            <SelectItem value="10">10</SelectItem>
                            <SelectItem value="25">25</SelectItem>
                            <SelectItem value="50">50</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="flex w-full justify-end gap-4 sm:w-auto">
                    <div class="relative w-full max-w-xs">
                        <Input
                            type="text"
                            v-model="searchValue"
                            placeholder="Search..."
                            class="rounded-lg border border-gray-200 py-5 pl-10 md:text-base"
                        />
                        <span class="absolute inset-y-0 left-0 flex items-center px-3">
                            <Search class="text-muted-foreground size-5" />
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-hidden border-t border-b">
                <Table class="text-base">
                    <TableHeader class="text-primary">
                        <TableRow>
                            <TableHead class="w-12"></TableHead>
                            <TableHead class="text-primary text-center font-bold">
                                <div class="px-3 py-4">Ảnh</div>
                            </TableHead>
                            <TableHead class="text-primary font-bold">
                                <div class="px-3 py-4">Sản phẩm</div>
                            </TableHead>
                            <TableHead class="text-primary font-bold">
                                <div class="px-3 py-4">Danh mục</div>
                            </TableHead>
                            <TableHead class="text-primary font-bold">
                                <div class="px-3 py-4">Thương hiệu</div>
                            </TableHead>
                            <TableHead class="text-primary text-right font-bold">
                                <div class="px-3 py-4">Giá bán</div>
                            </TableHead>
                            <TableHead class="w-12"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="products.data.length === 0">
                            <TableRow>
                                <TableCell colspan="8">
                                    <div class="px-3 py-4 text-center text-gray-500">No products found.</div>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow v-for="product in products.data" :key="product.id" class="hover:bg-muted/30">
                                <TableCell>
                                    <div class="flex items-center justify-center px-3 py-3">
                                        <Checkbox :checked="selectedProducts.includes(product.id)" @update:checked="toggleProduct(product.id)" />
                                    </div>
                                </TableCell>
                                <TableCell class="text-center align-middle">
                                    <div class="flex items-center justify-center px-3 py-3">
                                        <img
                                            v-if="product.image"
                                            :src="'/storage/' + product.image"
                                            class="h-10 w-10 rounded-sm object-cover"
                                            :alt="product.name"
                                        />
                                        <div v-else class="border-sm h-10 w-10 rounded-sm bg-gray-100"></div>
                                    </div>
                                </TableCell>

                                <TableCell class="font-medium">
                                    <div class="px-3 py-3">{{ product.name }}</div>
                                </TableCell>
                                <TableCell>
                                    <div class="px-3 py-3">
                                        <Badge variant="outline" class="border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-600">
                                            {{ product.category.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell class="text-left">
                                    <div class="px-3 py-3">
                                        <Badge variant="outline" class="border-green-200 bg-green-50 px-3 py-1 text-xs font-bold text-green-600">
                                            {{ product.brand.name }}
                                        </Badge>
                                    </div>
                                </TableCell>
                                <TableCell class="text-right font-semibold">
                                    <div class="px-3 py-3">{{ product.price }}</div>
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
                                                <DropdownMenuItem @click="openDialog(product)">Edit</DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteProduct(product)" class="text-red-500"> Delete </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination & Per page -->
            <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-muted-foreground text-sm">Showing {{ products.from }} to {{ products.to }} of {{ products.total }} results</div>
                <div class="flex items-center gap-4">

                    <Pagination
                        v-slot="{ page }"
                        :items-per-page="(products as any).per_page"
                        :total="products.total"
                        :default-page="(products as any).current_page"
                    >
                        <PaginationContent v-slot="{ items }" class="flex">
                            <PaginationPrevious
                                v-if="page > 1"
                                class="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-100 border"
                                @click="router.get(route('admin.products.index'), { ...filters, page: page - 1 }, { preserveState: true })"
                            >
                                <ChevronLeft class="h-4 w-4" />
                            </PaginationPrevious>
                            <template v-for="(item, index) in items" :key="index">
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                    @click="
                                        item.value !== page &&
                                        router.get(route('admin.products.index'), { ...filters, page: item.value }, { preserveState: true })
                                    "
                                    :class="[
                                        'flex h-10 w-10 items-center justify-center text-base font-medium transition border',
                                        item.value === page ? 'bg-gray-100 font-bold' : 'text-gray-800 hover:bg-gray-100',
                                    ]"
                                >
                                    {{ item.value }}
                                </PaginationItem>
                                <PaginationEllipsis v-else :index="item.value" class="flex h-10 w-10 items-center justify-center text-gray-500" />
                            </template>
                            <PaginationNext
                                v-if="page < Math.ceil(products.total / (products as any).per_page)"
                                class="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-100 border"
                                @click="router.get(route('admin.products.index'), { ...filters, page: page + 1 }, { preserveState: true })"
                            >
                                <ChevronRight class="h-4 w-4" />
                            </PaginationNext>
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>
        <ProductDialog :show="showDialog" :product="selectedProduct as any" @close="closeDialog" />
    </AppLayout>
</template>
