<script setup lang="ts">
import { computed, ref, watchEffect, onMounted } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem, SharedData, User, Review } from '@/types';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { formatVND } from '@/lib/utils';
import { HardDrive, Package, PhoneCall, Truck } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { format } from 'date-fns';
import { vi } from 'date-fns/locale';
import { toast } from 'vue-sonner';

import StarRating from 'vue-star-rating';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';

const { product } = defineProps<{ product: any }>();
const quantity = ref(1);
const thumbsSwiper = ref(null);
const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const increaseQuantity = () => quantity.value++;
const decreaseQuantity = () => {
    if (quantity.value > 1) quantity.value--;
};

const snakeToTitleCase = (str: string | null | undefined) => {
    console.log(product.specs);
    if (!str) return '';
    return str
        .split('_')
        .map((s) => s.charAt(0).toUpperCase() + s.slice(1).toLowerCase())
        .join(' ');
};

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
        onError: (errors) => console.error('Lỗi khi thêm vào giỏ hàng', errors)
    });
};
const setThumbsSwiper = (swiper: any) => {
    thumbsSwiper.value = swiper;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Trang chủ',
        href: '/'
    },
    {
        title: product.name,
        href: '/cart'
    }
];

const reviews = ref<Review[]>([]);
const averageRating = ref(0);
const totalReviews = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const ratingCounts = ref<Record<number, number>>({});

const fetchReviews = async (page = 1) => {
    try {
        const response = await fetch(route('reviews.product', product.id) + `?page=${page}`);
        const data = await response.json();
        reviews.value = data.reviews.data;
        averageRating.value = data.average_rating;
        totalReviews.value = data.total_reviews;
        currentPage.value = data.reviews.current_page;
        lastPage.value = data.reviews.last_page;
        ratingCounts.value = data.rating_counts;
    } catch (error) {
        console.error('Error fetching reviews:', error);
    }
};

const changePage = (page: number) => {
    fetchReviews(page);
};

const reviewForm = useForm({
    product_id: product.id,
    rating: 5,
    comment: ''
});

const submitReview = () => {
    reviewForm.post(route('reviews.store'), {
        preserveScroll: true,
        onSuccess: () => {
            fetchReviews();
            reviewForm.reset();
        }
    });
};

onMounted(() => {
    fetchReviews();
});

watchEffect(() => {
    if (page.props.flash?.success) {
        toast.success(page.props.flash.success);
    }
    if (page.props.flash?.error) {
        toast.error(page.props.flash.error);
    }
});
</script>

<template>
    <Head :title="product.name" />
    <CustomerLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 rounded-t-lg bg-white p-4">
                <div class="col-span-2">
                    <div class="h-[600px]">
                        <div class="aspect-square overflow-hidden rounded-lg border col-span-3">
                            <Swiper :spaceBetween="10" :thumbs="{ swiper: thumbsSwiper }"
                                    :modules="[FreeMode, Navigation, Thumbs]" class="main-swiper">
                                <SwiperSlide v-for="(img, index) in images" :key="index"
                                             class="swiper-slide-active:border-2 swiper-slide-active:border-red-500">
                                    <img :src="img" :alt="`${product.name} ${index}`"
                                         class="h-full w-full object-contain p-4" />
                                </SwiperSlide>
                            </Swiper>
                        </div>
                        <div class="mt-2">
                            <Swiper
                                @swiper="setThumbsSwiper"
                                :spaceBetween="8"
                                :slidesPerView="5"
                                :freeMode="true"
                                :watchSlidesProgress="true"
                                :modules="[FreeMode, Navigation, Thumbs]"
                                class="thumbnail-swiper h-full"
                            >
                                <SwiperSlide v-for="(img, index) in images" :key="index" class="rounded-md border">
                                    <div class="aspect-square w-full">
                                        <img
                                            :src="img"
                                            :alt="`${product.name} thumbnail ${index}`"
                                            class="h-full w-full  object-contain p-1 hover:border-red-500"
                                            :class="{ 'border-red-700': selectedImage === img }"
                                        />
                                    </div>
                                </SwiperSlide>
                            </Swiper>
                        </div>
                    </div>
                </div>

                <!-- Product Info Section -->
                <div class="col-span-3">
                    <div class="rounded-xl px-6">
                        <h1 class="mb-2 text-2xl font-bold text-gray-900">{{ product.name }}</h1>

                        <div class="items-center mb-4 flex">
                            <span class="text-lg font-medium">{{ averageRating }}</span>
                            <StarRating :rating="1" :max-rating="1" :star-size="14" :read-only="true"
                                        :show-rating="false" class="ml-1 pb-1" />
                            <span class="ml-2 text-sm text-gray-500">({{ totalReviews }} đánh giá)</span>
                            <span class="mx-2 text-gray-300">|</span>
                            <span class="text-base text-green-600">Còn hàng</span>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <span class="text-3xl font-bold text-red-600"> {{ formatVND(product.price) }}</span>
                                <span class="ml-3 text-lg text-gray-500 line-through">{{ formatVND(product.line_price)
                                    }}</span>
                                <span class="ml-3 rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                    -{{ Math.round((1 - product.price / product.line_price) * 100) }}%
                                </span>
                            </div>
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
                                <button @click="increaseQuantity" class="px-3 py-1 text-gray-600 hover:bg-gray-100">+
                                </button>
                            </div>
                        </div>

                        <div class="mb-4 flex flex-wrap gap-3">
                            <Button class="h-16 flex-1 flex-col rounded-md py-6 max-w-xl font-medium"
                                    @click="addToCart(product.id)">
                                <span class="flex items-center justify-center text-xl font-extrabold">Thêm vào giỏ hàng</span>
                                <span>Giao nhanh, miễn phí toàn quốc</span>
                            </Button>
                        </div>
                        <ul class="grid grid-cols-2 gap-4 p-6">
                            <li class="flex items-center gap-2">
                                <span class="bg-primary flex h-10 w-10 items-center justify-center rounded-full">
                                    <Truck class="text-white" />
                                </span>
                                <span>Chính sách bảo hành</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-primary flex h-10 w-10 items-center justify-center rounded-full">
                                    <Package class="text-white" />
                                </span>
                                <span>Bảo hành 36 tháng</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-primary flex h-10 w-10 items-center justify-center rounded-full">
                                    <HardDrive class="text-white" />
                                </span>
                                <span>Giá tốt nhất</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="bg-primary flex h-10 w-10 items-center justify-center rounded-full">
                                    <PhoneCall class="text-white" />
                                </span>
                                <span>Mua hàng trực tuyến</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-span-1 max-h-[600px] overflow-hidden">
                    <img class="h-[600px]" src="https://nguyencongpc.vn/media/banner/27_Mayfa89a7eb5cdc9b7551f1b3a79831db91.webp" alt="">
                </div>
            </div>
            <hr />
            <div class="bg-background p-4">
                <h2 class="mb-2 text-2xl font-bold text-gray-900">Mô tả sản phẩm</h2>

                <p class="text-primary">{{ product.description || 'Không có mô tả chi tiết' }}</p>
                <div class="flex items-center justify-center py-6">
                    <div class="mb-6 max-w-xl">
                        <Table variant="bordered text-lg">
                            <TableHeader>
                                <TableRow class="bg-muted border">
                                    <TableHead class="w-1/3">Thông số</TableHead>
                                    <TableHead class="border">Giá trị</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(value, key) in product.specs" :key="key"
                                          class="hover:bg-muted/50 border ">
                                    <TableCell class="font-medium border">{{ snakeToTitleCase(key) }}</TableCell>
                                    <TableCell class="border">{{ value }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>

            </div>
            <hr>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-background px-4 pt-8">
                <!-- Review Form -->
                <div class="lg:col-span-2">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Viết đánh giá</h3>
                        <form @submit.prevent="submitReview" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Đánh giá của bạn</label>
                                <div class="flex items-center">
                                    <StarRating
                                        v-model:rating="reviewForm.rating"
                                        :max-rating="5"
                                        :star-size="24"
                                        :show-rating="false"
                                        :read-only="false"
                                    />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Nhận xét của bạn</label>
                                <Textarea
                                    v-model="reviewForm.comment"
                                    placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
                                    class="min-h-[100px]"
                                />
                            </div>
                            <Button type="submit" :disabled="reviewForm.processing">
                                {{ reviewForm.processing ? 'Đang gửi...' : 'Gửi đánh giá' }}
                            </Button>
                        </form>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div>
                        <h3 class="text-lg font-semibold">Đánh giá trung bình</h3>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-xl font-bold">{{ averageRating }}</span>
                                <StarRating :rating="averageRating" :max-rating="5" :star-size="16" :show-rating="false"
                                            :read-only="true" class="pb-1" />
                            </div>
                            <span class="text-base">({{ totalReviews }} đánh giá)</span>
                        </div>
                        <div class="">
                            <div v-for="rating in 5" :key="rating" class="flex items-center mb-3 gap-6">
                                <span class="text-base w-16">{{ rating }} sao <span
                                    class="text-sm text-muted-foreground w-12 text-right">({{ ratingCounts[rating] || 0
                                    }})</span></span>
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-yellow-400"
                                        :style="{ width: `${(ratingCounts[rating] || 0) / totalReviews * 100}%` }"
                                    ></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="lg:col-span-3 pb-6">
                    <h2 class="text-2xl font-bold mb-6">Đánh giá từ khách hàng</h2>
                    <div class="space-y-6">
                        <div v-for="review in reviews" :key="review.id" class="rounded-lg border bg-card p-6">
                            <div class="flex items-start gap-4">
                                <Avatar>
                                    <AvatarImage :src="review.user.avatar" />
                                    <AvatarFallback>{{ review.user.name.charAt(0) }}</AvatarFallback>
                                </Avatar>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-medium">{{ review.user.name }}</h3>
                                            <p class="text-sm text-gray-500">
                                                {{ format(new Date(review.created_at), 'dd MMMM yyyy', { locale: vi })
                                                }}
                                            </p>
                                        </div>
                                        <StarRating
                                            :rating="review.rating"
                                            :max-rating="5"
                                            :star-size="16"
                                            :read-only="true"
                                            :show-rating="false"
                                        />
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ review.comment }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-muted-foreground">
                            Hiển thị {{ reviews.length }} trong tổng số {{ totalReviews }} đánh giá
                        </div>
                        <div class="flex items-center gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="currentPage === 1"
                                @click="changePage(currentPage - 1)"
                            >
                                Trước
                            </Button>
                            <div class="flex items-center gap-1">
                                <Button
                                    v-for="page in lastPage"
                                    :key="page"
                                    variant="outline"
                                    size="sm"
                                    :class="{ 'bg-primary text-primary-foreground': currentPage === page }"
                                    @click="changePage(page)"
                                >
                                    {{ page }}
                                </Button>
                            </div>
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="currentPage === lastPage"
                                @click="changePage(currentPage + 1)"
                            >
                                Sau
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-4 py-2"></div>
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

:deep(.main-swiper .swiper-slide) {
    border: none !important;
}

:deep(.thumbnail-swiper .swiper-slide-thumb-active) {
    border-color: rgb(239, 68, 68);
    border-radius: 0.375rem;
}

:deep(.thumbnail-swiper .swiper-slide:hover) {
    border-color: rgb(239, 68, 68);
}
</style>

