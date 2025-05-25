<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, MoreVertical, Search, ShoppingCart } from 'lucide-vue-next';
import { computed, PropType, ref, watch } from 'vue';
import { Label } from '@/components/ui/label';
import { formatVND } from '@/lib/utils';
import { useSmartPagination } from '@/composables/useSmartPagination';

interface Product {
    id: number;
    name: string;
    images: string;
    price: number;
    sale_price?: number;
    stock: number;
    created_at: string;
    brand: {
        name: string;
    };
    sold: number;
    rating: number;
    category: {
        name: string;
    };
    published: boolean;
    color: string;
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

const searchValue = ref(props.filters.search);
const selectedSortField = ref(props.filters.sort_field || 'created_at');
const selectedSortDirection = ref(props.filters.sort_direction || 'desc');

let searchTimeout: ReturnType<typeof setTimeout> | null = null;

watch(searchValue, (val) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (val !== props.filters.search) {
            router.get(
                route('admin.products.index'),
                {
                    search: val,
                    sort_field: props.filters.sort_field,
                    sort_direction: props.filters.sort_direction,
                },
                { preserveState: true },
            );
        }
    }, 500);
});

const applySort = () => {
    router.get(
        route('admin.products.index'),
        {
            sort_field: selectedSortField.value,
            sort_direction: selectedSortDirection.value,
            search: searchValue.value,
        },
        { preserveState: true },
    );
};

const deleteProduct = (product: Product) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.destroy', { id: product.id }));
    }
};

const navigateToCreate = () => {
    router.visit(route('admin.products.create'));
};

const navigateToEdit = (product: Product) => {
    router.visit(route('admin.products.edit', { id: product.id }));
};

const navigateToDetail = (product: Product) => {
    router.visit(route('admin.products.show', { id: product.id }));
};

const paginationPages = useSmartPagination(props.products.links);

// Map products so that images is always an array
const productsWithImages = computed(() => {
    return props.products.data.map((product) => ({
        ...product,
        images: typeof product.images === 'string' ? JSON.parse(product.images || '[]') : Array.isArray(product.images) ? product.images : [],
    }));
});
</script>

<template>
    <Head title="Products" />

    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-3xl font-extrabold">Sản phẩm</h1>
                <Button @click="navigateToCreate">Thêm sản phẩm</Button>
            </div>
            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <Label class="">Sắp xếp theo:</Label>
                    <Select v-model="selectedSortField" class="rounded shadow">
                        <SelectTrigger class="w-[140px]">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="created_at">Mới nhất</SelectItem>
                            <SelectItem value="price">Giá</SelectItem>
                            <SelectItem value="sold">Bán chạy</SelectItem>
                            <SelectItem value="stock">Tồn kho</SelectItem>
                            <SelectItem value="rating">Đánh giá</SelectItem>
                        </SelectContent>
                    </Select>
                    <Select v-model="selectedSortDirection" class="w-[140px] rounded shadow">
                        <SelectTrigger class="w-[140px]">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="asc">Tăng dần</SelectItem>
                            <SelectItem value="desc">Giảm dần</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button @click="applySort"> Áp dụng </Button>
                </div>
                <div class="flex w-full justify-end gap-4 sm:w-auto">
                    <div class="relative w-full max-w-xs">
                        <Input type="text" v-model="searchValue" placeholder="Search..." class="rounded-lg border border-gray-200 pl-10" />
                        <span class="absolute inset-y-0 left-0 flex items-center px-3">
                            <Search class="text-muted-foreground size-5" />
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-hidden border-t border-b">
                <Table class="">
                    <TableHeader class="text-primary">
                        <TableRow>
                            <TableHead class="text-primary w-[10%] text-center font-bold">
                                <div class="px-3 py-4">Ảnh</div>
                            </TableHead>
                            <TableHead class="text-primary w-[20%] font-bold">
                                <div class="px-3 py-4">Sản phẩm</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] font-bold">
                                <div class="px-3 py-4">Danh mục</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] font-bold">
                                <div class="px-3 py-4">Thương hiệu</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] text-right font-bold">
                                <div class="px-3 py-4">Giá bán</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] text-right font-bold">
                                <div class="px-3 py-4">Đánh giá</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] text-right font-bold">
                                <div class="px-3 py-4">Đã bán</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] text-right font-bold">
                                <div class="px-3 py-4">Kho</div>
                            </TableHead>
                            <TableHead class="text-primary w-[10%] text-right font-bold">
                                <div class="px-3 py-4">Hành động</div>
                            </TableHead>
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
                            <TableRow v-for="product in productsWithImages" :key="product.id" class="hover:bg-muted/30">
                                <TableCell class="text-center align-middle">
                                    <div class="flex items-center justify-center px-3 py-3">
                                        <img
                                            v-if="product.images.length > 0 && product.images[0]"
                                            :src="product.images[0]"
                                            class="h-10 w-10 rounded-sm object-cover"
                                            alt="N/A"
                                        />
                                        <div v-else class="border-sm h-10 w-10 rounded-sm bg-gray-100"></div>
                                    </div>
                                </TableCell>

                                <TableCell class="font-medium">
                                    <div class="px-3 py-3">
                                        <a @click="navigateToDetail(product)" class="hover:text-primary cursor-pointer">
                                            {{ product.name }}
                                        </a>
                                    </div>
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
                                <TableCell class="w-[12%] text-right font-semibold">
                                    <div class="px-3 py-3">{{ formatVND(product.price) }}</div>
                                </TableCell>

                                <TableCell class="w-[12%] text-right font-semibold">
                                    <div class="px-3 py-3">{{ product.rating }}</div>
                                </TableCell>

                                <TableCell class="w-[12%] text-right font-semibold">
                                    <div class="px-3 py-3">{{ product.sold }}</div>
                                </TableCell>
                                <TableCell class="w-[12%] text-right font-semibold">
                                    <div class="px-3 py-3">{{ product.stock }}</div>
                                </TableCell>

                                <TableCell class="w-[12%] text-right">
                                    <div class="px-3 py-3">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" size="icon">
                                                    <MoreVertical class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem @click="navigateToDetail(product)">View</DropdownMenuItem>
                                                <DropdownMenuItem @click="navigateToEdit(product)">Edit</DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteProduct(product)" class="text-red-500">Delete</DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="text-muted-foreground text-sm">
                    Hiển thị từ {{ products.from }} đến {{ products.to }} của {{ products.total }} kết quả
                </div>
                <div class="flex items-center gap-1">
                    <!-- Prev icon -->
                    <Button
                        variant="ghost"
                        size="icon"
                        :disabled="!products.prev_page_url"
                        @click="products.prev_page_url && router.get(products.prev_page_url)"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </Button>

                    <!-- Page numbers and ellipsis -->
                    <template v-for="(page, index) in paginationPages" :key="page.type === 'ellipsis' ? 'ellipsis-' + index : 'page-' + page.page">
                        <Button
                            v-if="page.type === 'page'"
                            variant="ghost"
                            size="sm"
                            :class="{ 'bg-primary text-primary-foreground': page.active }"
                            @click="router.get(page.url)"
                            v-html="page.label"
                        />
                        <span v-else class="text-muted-foreground px-2">...</span>
                    </template>

                    <!-- Next icon -->
                    <Button
                        variant="ghost"
                        size="icon"
                        :disabled="!products.next_page_url"
                        @click="products.next_page_url && router.get(products.next_page_url)"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
