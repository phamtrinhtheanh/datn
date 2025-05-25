<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem, SharedData, User } from '@/types';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { formatVND } from '@/lib/utils';
import { HardDrive, Package, PhoneCall, Settings, Truck, ShoppingCartIcon, IdCard } from 'lucide-vue-next';

import StarRating from 'vue3-star-ratings';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';
import axios from 'axios';
import { Button } from '@/components/ui/button';

const { product } = defineProps<{ product: any }>();
const quantity = ref(1);
const thumbsSwiper = ref(null);
const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const increaseQuantity = () => quantity.value++;
const decreaseQuantity = () => {
    if (quantity.value > 1) quantity.value--;
};

const snakeToTitleCase = (str: string) =>
    str
        ? str
              .split('_')
              .map((s) => s[0].toUpperCase() + s.slice(1).toLowerCase())
              .join(' ')
        : '';

const images = computed(() => {
    try {
        if (typeof product.images === 'string') {
            return JSON.parse(product.images) || [];
        }
        return product.images || [];
    } catch (e) {
        console.error('Error parsing images:', e);
        return [];
    }
});

const selectedImage = ref(images.value.length ? `/storage/${images.value[0]}` : '');

const form = useForm({
    product_id: product.id,
    quantity: quantity.value,
});

watchEffect(() => {
    form.quantity = quantity.value;
});


const addToCart = (productId: number) => {
    const form = useForm({
        quantity: 1
    });

    form.post(route('cart.add', productId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            alert('Thêm vào giỏ hàng thành công');
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
        onError: (errors) => console.error('Lỗi khi thêm vào giỏ hàng', errors),
    });
};
const setThumbsSwiper = (swiper: any) => {
    thumbsSwiper.value = swiper;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/',
    },
    {
        title: product.name,
        href: '/cart',
    },
];
</script>

<template>
    <Head :title="product.name" />
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto flex gap-2 px-4">
            <div class="flex flex-col gap-4 rounded-lg bg-white p-4 lg:w-full lg:flex-row">
                <div class="lg:w-1/2">
                    <div class="flex-col gap-4">
                        <div class="aspect-square overflow-hidden rounded-lg border">
                            <Swiper :spaceBetween="10" :thumbs="{ swiper: thumbsSwiper }" :modules="[FreeMode, Navigation, Thumbs]" class="h-full">
                                <SwiperSlide v-for="(img, index) in images" :key="index">
                                    <img :src="img" :alt="`${product.name} ${index}`" class="h-full w-full object-contain p-4" />
                                </SwiperSlide>
                            </Swiper>
                        </div>
                        <!-- Thumbnail Swiper -->
                        <div class="mt-2 h-full">
                            <Swiper
                                @swiper="setThumbsSwiper"
                                :spaceBetween="10"
                                :slidesPerView="6"
                                :freeMode="true"
                                :watchSlidesProgress="true"
                                :modules="[FreeMode, Navigation, Thumbs]"
                                class="thumbnail-swiper"
                            >
                                <SwiperSlide v-for="(img, index) in images" :key="index">
                                    <button class="aspect-square w-full">
                                        <img
                                            :src="img"
                                            :alt="`${product.name} thumbnail ${index}`"
                                            class="h-full w-full rounded-md border object-contain p-1 hover:border-red-500"
                                            :class="{ 'border-red-700': selectedImage === img }"
                                        />
                                    </button>
                                </SwiperSlide>
                            </Swiper>
                        </div>
                    </div>

                </div>

                <!-- Product Info Section -->
                <div class="lg:w-1/2">
                    <div class="sticky top-24 rounded-xl px-6">
                        <h1 class="mb-2 text-2xl font-bold text-gray-900">{{ product.name }}</h1>

                        <div class="items-top mb-4 flex">
                            <StarRating :star-size="14" />
                            <span class="ml-2 text-sm text-gray-500">(0 đánh giá)</span>
                            <span class="mx-2 text-gray-300">|</span>
                            <span class="text-sm text-green-600">Còn hàng</span>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-red-600"> {{ formatVND(product.price) }}</span>
                                <span class="ml-3 text-lg text-gray-500 line-through">{{ formatVND(product.line_price) }}</span>
                                <span class="ml-3 rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                    -{{ Math.round((1 - product.price / product.line_price) * 100) }}%
                                </span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="mb-2 text-base font-bold text-gray-900">Thông số sản phẩm</h3>
                            <div class="text-sm text-gray-600">
                                <div class="specs">
                                    <div class="space-y-2">
                                        <div v-for="(value, key) in product.specs" :key="key" class="flex">
                                            <span class="font-base text-base text-gray-800">{{ snakeToTitleCase(key) }} : {{ value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="mb-4 flex flex-wrap gap-3 lg:w-2/3">-->
<!--                            <button class="rounded-md border-2 border-red-700 bg-white px-4 py-3 font-medium text-red-600 hover:shadow-lg">-->
<!--                                <span class="flex items-center justify-center font-extrabold">-->
<!--                                    <Settings class="mr-2 h-6 w-6" />Build PC với sản phẩm này</span-->
<!--                                >-->
<!--                            </button>-->
<!--                        </div>-->
                        <div class="mb-6 flex items-center">
                            <span class="mr-4 text-sm font-medium text-gray-900">Số lượng:</span>
                            <div class="flex items-center rounded-md border border-gray-300">
                                <button
                                    @click="decreaseQuantity"
                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100"
                                    :class="{ 'cursor-not-allowed opacity-50': quantity <= 1 }"
                                >
                                    -
                                </button>
                                <span class="border-x border-gray-300 px-4 py-1">{{ quantity }}</span>
                                <button @click="increaseQuantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>

                        <div class="mb-4 flex flex-wrap gap-3">
                            <Button class="flex-1 rounded-md  h-16 py-6 font-medium flex-col" @click="addToCart(product.id)">
                                <span class="flex items-center justify-center font-extrabold">Thêm vào giỏ hàng</span>
                                <span>Giao nhanh, miễn phí toàn quốc</span>
                            </Button>
                        </div>
                        <ul class="grid grid-cols-2 gap-4 p-6">
                            <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary">
                                <Truck class="text-white" />
                            </span>
                                <span>Chính sách bảo hành</span>
                            </li>
                            <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary">
                                <Package class="text-white" />
                            </span>
                                <span>Bảo hành 36 tháng</span>
                            </li>
                            <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary">
                                <HardDrive class="text-white" />
                            </span>
                                <span>Giá tốt nhất</span>
                            </li>
                            <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary">
                                <PhoneCall class="text-white" />
                            </span>
                                <span>Mua hàng trực tuyến</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
        <div class="mt-8 rounded-xl px-4 container mx-auto">
            <div class="bg-background px-4 py-2">
                <div class="">
                    <nav class="-mb-px flex space-x-8">
                        <a href="#" class="whitespace-nowrap border-b-2 border-blue-500 px-1 py-4 text-sm font-semibold text-xl"> Mô tả sản phẩm </a>
<!--                        <a-->
<!--                            href="#"-->
<!--                            class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"-->
<!--                        >-->
<!--                            Thông số kỹ thuật-->
<!--                        </a>-->
<!--                        <a-->
<!--                            href="#"-->
<!--                            class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"-->
<!--                        >-->
<!--                            Đánh giá (0)-->
<!--                        </a>-->
                    </nav>
                </div>
                <div class="py-4">
                    <p class="text-gray-600">{{ product.description || 'Không có mô tả chi tiết' }}</p>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<style scoped>
.gallery {
    max-width: 100%;
    overflow: hidden;
}

.gallery img {
    width: 100%;
    height: auto;
    object-fit: contain;
}

@media (max-width: 1023px) {
    .flex {
        flex-direction: column;
    }
}
</style>
