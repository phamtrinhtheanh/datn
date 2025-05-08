<script setup lang="ts">
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import ProductListSection from '@/components/ProductListSection.vue';
import { Head } from '@inertiajs/vue3';
import 'swiper/css';
import 'swiper/css/autoplay';
import Cart from '@/components/Cart.vue';

const { categories } = defineProps<{
    categories: Array<any>;
}>();
</script>

<style scoped>
.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

<template>
    <Head title="The Anh Computer" />
    <CustomerLayout>
        <Cart/>
        <div class="space-y-8">
            <div class="relative mt-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <img src="/storage/banners/main.webp" alt="Banner Chính" class="h-full w-full rounded-lg object-cover" />
                    </div>
                    <div class="flex h-full flex-col gap-4">
                        <img src="/storage/banners/sub-1.webp" alt="Banner 1" class="h-full w-full rounded-lg object-cover" />
                        <img src="/storage/banners/sub-2.webp" alt="Banner 2" class="h-full w-full rounded-lg object-cover" />
                    </div>
                </div>
            </div>

            <div v-for="category in categories" :key="category.id">
                <ProductListSection :title="category.name" :seeAllLink="`/danh-muc/${category.slug}`" :products="category.products" />
            </div>
        </div>
    </CustomerLayout>
</template>
