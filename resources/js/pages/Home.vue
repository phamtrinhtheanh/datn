<script setup lang="ts">
import CustomerLayout from '@/layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Autoplay, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/autoplay';
import 'swiper/css/pagination';

const { categories } = defineProps<{
    categories: Array<any>;
}>();

const bannerImages = ref([
    { id: 1, src: '/banner/main.png', alt: 'Banner 1' },
    { id: 2, src: '/banner/main.png', alt: 'Banner 2' },
    { id: 3, src: '/banner/main.png', alt: 'Banner 3' },
]);

const modules = [Autoplay, Pagination];
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
        <section class="container mx-auto mt-12 px-4">
            <div class="flex flex-col gap-4 md:gap-6 lg:flex-row lg:gap-8">
                <!-- Banner lớn với Swiper -->
                <div class="relative aspect-[3/2] overflow-hidden rounded-xl lg:w-2/3">
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

                <!-- Container cho 2 banner nhỏ -->
                <div class="flex h-auto flex-col gap-8 lg:h-full lg:w-1/3">
                    <div class="flex-1 overflow-hidden rounded-xl bg-white dark:bg-gray-900">
                        <img class="h-full w-full object-cover" src="/banner/main.png" alt="small banner 1" />
                    </div>
                    <div class="flex-1 overflow-hidden rounded-xl bg-white dark:bg-gray-900">
                        <img class="h-full w-full object-cover" src="/banner/main.png" alt="small banner 2" />
                    </div>
                </div>
            </div>
        </section>
    </CustomerLayout>
</template>
