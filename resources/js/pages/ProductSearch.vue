<script setup lang="ts">
import ProductSearchSection from '@/components/ProductSearchSection.vue';
import { Label } from '@/components/ui/label';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import 'swiper/css';
import 'swiper/css/autoplay';
import { computed, ref, watch } from 'vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';

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

// Lọc bỏ các nút Previous/Next
const pageLinks = computed(() => {
    return props.products.links.filter((link) => {
        const label = link.label.toLowerCase();
        return !label.includes('previous') && !label.includes('next');
    });
});
</script>

<template>
    <Head title="The Anh Computer" />
    <CustomerLayout :breadcrumbs="breadcrumbs" :query="query">
        <div class="container mx-auto space-y-4 px-4">
            <div class="flex rounded-lg bg-white p-4">
                <div class="flex items-center gap-3">
                    <Label class="text-base font-bold">Sắp xếp theo:</Label>
                    <Select v-model="sortBy">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue :placeholder="sortOptions.find((opt) => opt.value === sortBy)?.label || 'Chọn sắp xếp'" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in sortOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <ProductSearchSection :products="props.products.data" />

            <!-- Pagination -->
            <div class="flex justify-center gap-2">
                <Button
                    v-for="(link, index) in props.products.links.filter((link) => !/«|»|Previous|Next/.test(link.label))"
                    :key="index"
                    variant="ghost"
                    size="sm"
                    :class="{ 'bg-primary text-primary-foreground': link.active }"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                />
            </div>
        </div>
    </CustomerLayout>
</template>
