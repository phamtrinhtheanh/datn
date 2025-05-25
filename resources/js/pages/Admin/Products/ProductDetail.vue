<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { formatVND } from '@/lib/utils';
import { computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const productImages = computed(() => {
    if (!props.product.images) return [];
    return typeof props.product.images === 'string' ? JSON.parse(props.product.images) : props.product.images;
});

const navigateToEdit = () => {
    router.visit(route('admin.products.edit', { id: props.product.id }));
};

const deleteProduct = () => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('admin.products.destroy', { id: props.product.id }));
    }
};
</script>

<template>
    <Head :title="product.name" />

    <AppLayout>
        <div class="container mx-auto py-6">
            <div class="mb-6 flex items-start justify-between">
                <h1 class="mb-4 text-3xl font-extrabold">Chi tiết sản phẩm</h1>
                <div class="flex gap-4">
                    <Button variant="outline" @click="router.visit(route('admin.products.index'))">Quay lại</Button>
                    <Button @click="navigateToEdit">Chỉnh sửa</Button>
                    <Button variant="destructive" @click="deleteProduct">Xóa</Button>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Product Image -->
                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="aspect-square overflow-hidden rounded-lg">
                        <img
                            v-if="productImages.length > 0"
                            :src="productImages[0]"
                            class="h-full w-full object-cover"
                            :alt="product.name"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center bg-gray-100">
                            <span class="text-gray-400">No image</span>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <div class="rounded-lg border bg-card p-6 shadow-sm">
                        <h2 class="mb-4 text-2xl font-bold">{{ product.name }}</h2>
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <Badge variant="outline" class="border-yellow-200 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-600">
                                    {{ product.category.name }}
                                </Badge>
                                <Badge variant="outline" class="border-green-200 bg-green-50 px-3 py-1 text-xs font-bold text-green-600">
                                    {{ product.brand.name }}
                                </Badge>
                                <Badge v-if="product.is_featured" variant="outline" class="border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">
                                    Nổi bật
                                </Badge>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Giá nhập</h3>
                                    <p class="text-lg font-semibold">{{ formatVND(product.import_price) }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Giá niêm yết</h3>
                                    <p class="text-lg font-semibold">{{ formatVND(product.line_price) }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Giá bán</h3>
                                    <p class="text-lg font-semibold text-primary">{{ formatVND(product.price) }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Số lượng</h3>
                                    <p class="text-lg font-semibold">{{ product.stock }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Specs -->
                    <div v-if="Object.keys(product.specs || {}).length > 0" class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold">Thông số kỹ thuật</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div v-for="(value, key) in product.specs" :key="key" class="space-y-1">
                                <h4 class="text-sm font-medium text-gray-500">{{ key }}</h4>
                                <p class="text-base">{{ value }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div v-if="product.description" class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold">Mô tả</h3>
                        <div class="prose max-w-none" v-html="product.description"></div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
