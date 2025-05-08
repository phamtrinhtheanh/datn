<script setup lang="ts">
import ProductSearchSection from '@/components/ProductSearchSection.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import 'swiper/css';
import 'swiper/css/autoplay';
import { ref, watch } from 'vue';

const props = defineProps<{
    products: {
        data: Array<any>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
        next_page_url: string | null;
        prev_page_url: string | null;
    };
    category?: {
        id: number;
        name: string;
        slug: string;
    };
    query?: string;
    sort_by?: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: props.category ? props.category.name : 'Tìm kiếm',
        href: props.category ? props.category.slug : '',
    },
];

function goToPage(url: string | null) {
    if (url) router.visit(url);
}

const searchQuery = ref(props.query || '');

const sortBy = ref(props.sort_by || '');

const sortOptions = [
    { label: 'Mới nhất', value: 'latest' },
    { label: 'Tăng dần', value: 'price_asc' },
    { label: 'Giảm dần', value: 'price_desc' },
];

// Watch để gửi request khi sortBy thay đổi
watch(sortBy, (newSort) => {
    router.get(route('search'), {
        query: searchQuery.value,
        sort_by: newSort,
    });
});
</script>

<template>
    <Head title="The Anh Computer" />
    <CustomerLayout :breadcrumbs="breadcrumbs" :query="query">
        <div class="space-y-4">
            <div class="flex rounded-lg bg-white p-4">
                <div class="flex items-center gap-3">
                    <Label class="text-base font-bold">Sắp xếp theo:</Label>

                    <div class="flex gap-2">
                        <Label
                            v-for="option in sortOptions"
                            :key="option.value"
                            :class="[
                                'cursor-pointer rounded-full px-4 py-1 text-base font-semibold',
                                sortBy === option.value ? ' text-green-600 font-bold' : 'bg-transparent text-red-700 hover:text-green-700',
                            ]"
                        >
                            <input type="radio" name="sort" :value="option.value" v-model="sortBy" class="hidden" />
                            {{ option.label }}
                        </Label>
                    </div>
                </div>
            </div>

            <ProductSearchSection :products="props.products.data" />

            <!-- Pagination -->
            <div class="flex justify-center gap-2">
                <button
                    v-for="(link, index) in props.products.links"
                    :key="index"
                    :disabled="!link.url"
                    :class="['rounded border px-3 py-1', { 'bg-gray-200': link.active, 'cursor-not-allowed opacity-50': !link.url }]"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                />
            </div>
        </div>
    </CustomerLayout>
</template>
