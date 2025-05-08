<script setup lang="ts">
import { computed, ref, watchEffect, onMounted } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import {
    Breadcrumb,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
    BreadcrumbItem
} from '@/components/ui/breadcrumb';
import CustomerLayout from '@/layouts/CustomerLayout.vue';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

import {
    Truck,
    Package,
    HardDrive,
    PhoneCall,
    Settings
} from 'lucide-vue-next';

import StarRating from 'vue3-star-ratings';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';

const breadcrumbs: BreadcrumbItemType[] = [
    { title: 'Trang chủ', href: '/' },
    { title: 'Chi tiết sản phẩm', href: '/san-pham/chi-tiet' }
];

const { product } = defineProps<{ product: any }>();
const quantity = ref(1);
const thumbsSwiper = ref(null);
const page = usePage();

const formatVND = (price: number) =>
    new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);

const increaseQuantity = () => quantity.value++;
const decreaseQuantity = () => {
    if (quantity.value > 1) quantity.value--;
};

const snakeToTitleCase = (str: string) =>
    str
        ? str.split('_').map(s => s[0].toUpperCase() + s.slice(1).toLowerCase()).join(' ')
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
    quantity: quantity.value
});

watchEffect(() => {
    form.quantity = quantity.value;
});

// Hàm lưu vào giỏ hàng tạm thời (localStorage)
const saveToLocalCart = () => {
    const cartItems = JSON.parse(localStorage.getItem('guest_cart') || '[]');

    // Kiểm tra xem sản phẩm đã có trong giỏ chưa
    const existingItemIndex = cartItems.findIndex(item => item.product_id === product.id);

    if (existingItemIndex >= 0) {
        // Nếu đã có thì cập nhật số lượng
        cartItems[existingItemIndex].quantity += quantity.value;
    } else {
        // Nếu chưa có thì thêm mới
        cartItems.push({
            product_id: product.id,
            quantity: quantity.value,
            product: { // Lưu thêm thông tin sản phẩm để hiển thị
                id: product.id,
                name: product.name,
                price: product.price,
                images: images.value,
                slug: product.slug
            }
        });
    }

    localStorage.setItem('guest_cart', JSON.stringify(cartItems));
    alert('Thêm vào giỏ hàng thành công');

    // Gửi event để các component khác biết giỏ hàng đã thay đổi
    window.dispatchEvent(new CustomEvent('cart-updated'));
};

// Hàm đồng bộ giỏ hàng từ localStorage lên server khi đăng nhập
const syncCartWithServer = async () => {
    const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');

    if (guestCart.length > 0) {
        try {
            const response = await axios.post(route('cart.sync'), {
                items: guestCart
            });

            if (response.data.success) {
                localStorage.removeItem('guest_cart');
                window.dispatchEvent(new CustomEvent('cart-updated'));
            }
        } catch (error) {
            console.error('Lỗi khi đồng bộ giỏ hàng:', error);
        }
    }
};

// Kiểm tra khi component mounted
onMounted(() => {
    // Nếu người dùng đăng nhập thì đồng bộ giỏ hàng
    if (page.props.auth.user) {
        syncCartWithServer();
    }
});

const addToCart = () => {
    if (page.props.auth.user) {
        // Nếu đã đăng nhập thì gửi lên server
        form.post(route('cart.add'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => alert('Thêm vào giỏ hàng thành công'),
            onError: (errors) => console.error('Lỗi khi thêm vào giỏ hàng', errors)
        });
    } else {
        // Nếu chưa đăng nhập thì lưu vào localStorage
        saveToLocalCart();
    }
};

const setThumbsSwiper = (swiper: any) => {
    thumbsSwiper.value = swiper;
};
</script>

<template>
    <CustomerLayout>
        <template v-if="breadcrumbs.length > 0">
            <Breadcrumb class="py-4">
                <BreadcrumbList>
                    <template v-for="(item, index) in breadcrumbs" :key="index">
                        <BreadcrumbItem class="text-base">
                            <template v-if="index === breadcrumbs.length - 1">
                                <BreadcrumbPage>{{ item.title }}</BreadcrumbPage>
                            </template>
                            <template v-else>
                                <BreadcrumbLink as-child>
                                    <Link :href="item.href ?? '#'">{{ item.title }}</Link>
                                </BreadcrumbLink>
                            </template>
                        </BreadcrumbItem>
                        <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
                    </template>
                </BreadcrumbList>
            </Breadcrumb>
        </template>

        <div class="container mx-auto flex gap-2">
            <div class="flex flex-col gap-4 rounded-lg bg-white p-4 lg:w-full lg:flex-row">
                <!-- Gallery Section -->
                <div class="lg:w-1/2">
                    <div class="flex-col gap-4">

                        <div class="aspect-square overflow-hidden rounded-lg border">
                            <Swiper
                                :spaceBetween="10"
                                :thumbs="{ swiper: thumbsSwiper }"
                                :modules="[FreeMode, Navigation, Thumbs]"
                                class="h-full"
                            >
                                <SwiperSlide v-for="(img, index) in images" :key="index">
                                    <img
                                        :src="'/storage/' + img"
                                        :alt="`${product.name} ${index}`"
                                        class="h-full w-full object-contain p-4"
                                    />
                                </SwiperSlide>
                            </Swiper>
                        </div>
                        <!-- Thumbnail Swiper -->
                        <div class="h-full mt-2">
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
                                    <button class="w-full aspect-square">
                                        <img
                                            :src="'/storage/' + img"
                                            :alt="`${product.name} thumbnail ${index}`"
                                            class="h-full w-full rounded-md border object-contain p-1 hover:border-red-500"
                                            :class="{ 'border-red-700': selectedImage === '/storage/' + img }"
                                        />
                                    </button>
                                </SwiperSlide>
                            </Swiper>
                        </div>

                    </div>

                    <ul class="grid grid-cols-2 gap-4 p-6">
                        <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-red-700">
                                <Truck class="text-white" />
                            </span>
                            <span>Chính sách bảo hành</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-red-700">
                                <Package class="text-white" />
                            </span>
                            <span>Bảo hành 36 tháng</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-red-700">
                                <HardDrive class="text-white" />
                            </span>
                            <span>Giá tốt nhất</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-red-700">
                                <PhoneCall class="text-white" />
                            </span>
                            <span>Mua hàng trực tuyến</span>
                        </li>
                    </ul>
                </div>

                <!-- Product Info Section -->
                <div class="lg:w-1/2">
                    <div class="sticky top-24 rounded-xl px-6 shadow-sm">
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
                        <div class="mb-4 flex flex-wrap gap-3 lg:w-2/3">
                            <button class="rounded-md border-2 border-red-700 bg-white px-4 py-3 font-medium text-red-600 hover:shadow-lg">
                                <span class="flex items-center justify-center font-extrabold">
                                    <Settings class="mr-2 h-6 w-6" />Build PC với sản phẩm này</span
                                >
                            </button>
                        </div>
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
                            <button class="flex-1 rounded-md bg-red-700 px-4 py-3 font-medium text-white hover:bg-red-600">
                                <span class="flex items-center justify-center font-extrabold">MUA NGAY</span>
                                <span>Giao nhanh, miễn phí toàn quốc</span>
                            </button>
                        </div>
                        <div class="mb-4 flex flex-wrap gap-3">
                            <button
                                @click="addToCart"
                                class="flex-1 rounded-md border-2 border-red-700 px-4 py-3 font-medium text-red-700 hover:border-red-600 hover:text-red-600 hover:shadow-lg"
                            >
                                <span class="flex items-center justify-center font-extrabold"
                                    ><ShoppingCartIcon class="mr-2 h-5 w-5" />THÊM VÀO GIỎ</span
                                >
                                <span>Sản phẩm trong giỏ hàng</span>
                            </button>
                            <button
                                class="flex-1 rounded-md border-2 border-green-700 px-4 py-3 font-medium text-green-700 hover:border-green-600 hover:text-green-600 hover:shadow-lg"
                            >
                                <span class="flex items-center justify-center font-extrabold"><IdCard class="mr-2 h-5 w-5" />TRẢ GÓP</span>
                                <span>Hotline: 0987712454</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="#" class="whitespace-nowrap border-b-2 border-blue-500 px-1 py-4 text-sm font-medium text-blue-600"> Mô tả sản phẩm </a>
                    <a
                        href="#"
                        class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                    >
                        Thông số kỹ thuật
                    </a>
                    <a
                        href="#"
                        class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                    >
                        Đánh giá (0)
                    </a>
                </nav>
            </div>
            <div class="py-4">
                <p class="text-gray-600">{{ product.description || 'Không có mô tả chi tiết' }}</p>
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
