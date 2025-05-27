<script setup lang="ts">
import ProductListSection from '@/components/ProductListSection.vue';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ChevronRight, ChevronLeft } from 'lucide-vue-next';
import 'swiper/css';
import 'swiper/css/autoplay';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { ref } from 'vue';

interface HomeCategory {
    id: number;
    name: string;
    slug: string;
    tags: string; // Based on the provided structure, tags is a string
    icon?: string; // Assuming there's an icon field for the image
}

interface HomeCategoriesProps {
    data: HomeCategory[];
    // Add other properties if needed, e.g., total: number;
}

// Define a simple interface for the product data expected by ProductListSection
interface HomeProduct {
    id: number;
    name: string;
    slug: string;
    images: string | Array<string>; // Can be JSON string or array
    line_price: number;
    price: number;
    reviews_count?: number;
    // Add other necessary product properties if any
}

// Extend the HomePageProps interface to include the new prop for new arrivals
interface HomePageProps extends Record<string, any> {
    // Use Record<string, any> for base shared props
    categories: HomeCategoriesProps;
    newArrivals: HomeProduct[]; // Add the new prop for new arrivals
    // Keep other specific shared props here if needed for type safety in other parts
    auth: { user: any } | null;
    brands: { data: any[] };
    errors: any;
    name: string;
    ziggy: any;
    cart: any;
    canLogin: boolean;
    canRegister: boolean;
    sidebarOpen: boolean;
}

// Get categories from shared props with type assertion
const categories = usePage<HomePageProps>().props.categories.data;
const newArrivals = usePage<HomePageProps>().props.newArrivals;

const bannerImages = ref([
    { id: 1, src: '/banner/main.png', alt: 'Banner 1' },
    { id: 2, src: '/banner/main.png', alt: 'Banner 2' },
    { id: 3, src: '/banner/main.png', alt: 'Banner 3' },
]);

const modules = [Autoplay, Pagination, Navigation];
</script>

<style>
.swiper-container {
    width: 100%;
    height: 100%;
}

.banner-image {
    width: auto;
    height: 100%;
    object-fit: cover;
}

.swiper-pagination-bullet {
    width: 24px;
    height: 4px;
    background-color: #a0aec0;
    border-radius: 9999px;
    opacity: 1;
    margin: 0 6px !important;
    transition: all 0.3s ease;
}

.swiper-pagination-bullet-active {
    background-color: #1a202c;
    transform: scale(1.2);
}
</style>

<template>
    <Head title="The Anh Computer" />
    <CustomerLayout>
        <section class="container mx-auto mt-4 px-4">
            <div class="flex flex-col gap-4 md:gap-6 lg:flex-row lg:gap-4">
                <div class="relative aspect-[5/2] overflow-hidden rounded-xl lg:w-2/3">
                    <Swiper
                        :modules="modules"
                        :autoplay="{
                            delay: 3000,
                            disableOnInteraction: false,
                        }"
                        :loop="true"
                        :pagination="{
                            clickable: true,
                            type: 'bullets',
                        }"
                        class="swiper-container"
                    >
                        <SwiperSlide v-for="image in bannerImages" :key="image.id">
                            <img :src="image.src" :alt="image.alt" class="banner-image" />
                        </SwiperSlide>
                    </Swiper>
                </div>

                <div class="flex h-auto flex-col gap-4 lg:h-full lg:w-1/3">
                    <div class="flex-1 overflow-hidden rounded-xl bg-white dark:bg-gray-900">
                        <img class="h-full w-full object-cover" src="/banner/main.png" alt="small banner 1" />
                    </div>
                    <div class="flex-1 overflow-hidden rounded-xl bg-white dark:bg-gray-900">
                        <img class="h-full w-full object-cover" src="/banner/main.png" alt="small banner 2" />
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-background my-4 pt-8">
            <div class="bg-background container mx-auto border-b px-4 pb-4">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Danh Mục Hàng</h2>
                    <div class="flex gap-2">
                        <div
                            class="swiper-button-prev-category bg-primary text-primary-foreground flex h-8 w-8 cursor-pointer items-center justify-center rounded-md"
                        >
                            <ChevronLeft />
                        </div>
                        <div
                            class="swiper-button-next-category bg-primary text-primary-foreground flex h-8 w-8 cursor-pointer items-center justify-center rounded-md"
                        >
                            <ChevronRight />
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <Swiper
                        :modules="[Navigation]"
                        :slides-per-view="3"
                        :space-between="10"
                        :loop="true"
                        :slides-offset-before="0"
                        :slides-offset-after="0"
                        v-bind:navigation="
                            {
                                nextEl: '.swiper-button-next-category',
                                prevEl: '.swiper-button-prev-category',
                            } as any
                        "
                        :breakpoints="{
                            640: { slidesPerView: 4, spaceBetween: 0 },
                            768: { slidesPerView: 6, spaceBetween: 0 },
                            1024: { slidesPerView: 8, spaceBetween: 0 },
                        }"
                        class="mySwiper swiper-fix-padding"
                    >
                        <SwiperSlide v-for="category in categories" :key="category.id">
                            <a :href="category.slug" class="group flex flex-col items-center text-center">
                                <div class="bg-secondary mb-2 flex h-30 w-30 items-center justify-center overflow-hidden rounded-full">
                                    <img :src="category.icon" :alt="category.name" class="h-full w-full object-contain p-3" />
                                </div>
                                <span class="text-primary group-hover:text-primary-500 dark:group-hover:text-primary-400 text-lg font-semibold">{{
                                    category.name
                                }}</span>
                            </a>
                        </SwiperSlide>
                    </Swiper>
                </div>
            </div>
            <div class="bg-background container mx-auto border-b px-4 pt-8 pb-12">
                <ProductListSection title="Hàng mới về" :seeAllLink="'#'" :products="newArrivals" />
            </div>
        </section>
    </CustomerLayout>
</template>
