<script setup lang="ts">
import AppearanceButton from '@/components/AppearanceButton.vue';
import { type BreadcrumbItemType, type SharedData, type User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { CircleUser, LogIn, LogOut, MonitorSmartphone, Phone, PhoneCall, ShoppingBag, UserPlus } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const categories = computed(() => page.props.categories ?? []);

const cart = ref({
    count: 0,
});

const fetchCart = async () => {
    try {
        const response = await axios.get(route('minicart'));
        cart.value = {
            count: response.data.count || 0,
        };
    } catch (error) {
        console.error('Lỗi khi lấy giỏ hàng:', error);
    }
};

onMounted(() => {
    if (user) fetchCart();
});
const cartCount = computed(() => cart.value.count);

const stickyHeader = ref<HTMLElement | null>(null);
const isSticky = ref(false);

const handleScroll = () => {
    if (!stickyHeader.value) return;
    const offset = stickyHeader.value.getBoundingClientRect().top;
    isSticky.value = offset <= 0;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});
const props = defineProps<{
    query?: string;
}>();
const searchQuery = ref(props.query || '');
const handleSearch = () => {
    if (searchQuery.value.length === 0) {
        alert('Vui lòng nhập ít nhất một từ khóa để tìm kiếm');
        return;
    }
    router.get(route('search'), {
        query: searchQuery.value
    });
};


</script>

<template>
    <div class="header-top bg-red-700 text-sm text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-6">
                    <Link href="#" class="flex items-center gap-1 font-semibold hover:text-gray-200">
                        <Phone class="h-4 w-4" />
                        Hotline: +85 86 6123 158
                    </Link>
                </div>

                <div class="flex items-center space-x-4">
                    <AppearanceButton />
                    <template v-if="!user">
                        <Link href="/login" class="flex items-center gap-1 font-semibold hover:text-gray-200">
                            <LogIn class="h-4 w-4" />
                            Đăng nhập
                        </Link>
                        <Link href="/sign-up" class="flex items-center gap-1 font-semibold hover:text-gray-200">
                            <UserPlus class="h-4 w-4" />
                            Đăng ký
                        </Link>
                    </template>

                    <template v-else>
                        <div class="flex items-center gap-1 font-semibold hover:text-gray-200">
                            <CircleUser class="h-4 w-4" />
                            <span>{{ user.name }}</span>
                        </div>
                        <Link href="/logout" method="post" as="button" class="flex items-center gap-1 font-semibold hover:text-gray-200">
                            <LogOut class="h-4 w-4" />
                            Đăng xuất
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div ref="stickyHeader" class="sticky top-0 z-50 shadow-red-200 bg-white py-4 transition-colors duration-300" :class="{'shadow' :isSticky}">
        <div class="container mx-auto flex items-center justify-between px-4">
            <Link href="/" class="flex items-center space-x-3">
                <div class="flex flex-col text-red-800">
                    <span class="text-2xl font-black uppercase tracking-wide">THEANHPC.VN</span>
                    <span class="text-xs font-bold">HI-END PC & GAMING GEAR</span>
                </div>
            </Link>

            <div class="mx-8 max-w-xl flex-1">
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Nhập từ khóa cần tìm"
                        class="w-full rounded-full border border-gray-300 px-4 py-2 pr-10 focus:border-red-600 focus:outline-none"
                    />
                    <button @click="handleSearch" class="absolute right-0 top-0 h-full rounded-r-full bg-red-700 px-4 text-white hover:bg-red-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex space-x-6">
                <Link href="/don-hang" class="flex items-center">
                    <div class="mr-3 rounded-full bg-red-100 p-2">
                        <PhoneCall class="h-6 w-6 text-red-800" />
                    </div>
                    <div>
                        <div class="text-lg font-bold text-red-700">Đơn mua</div>
                    </div>
                </Link>

                <Link href="/buildpc" class="flex items-center">
                    <div class="mr-3 rounded-full bg-red-100 p-2">
                        <MonitorSmartphone class="h-6 w-6 text-red-800" />
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-600">Xây dựng</div>
                        <div class="text-lg font-bold text-red-700">Cấu hình PC</div>
                    </div>
                </Link>

                <Link href="/cart" class="flex items-center">
                    <div class="relative mr-3 rounded-full bg-red-100 p-2">
                        <ShoppingBag class="h-6 w-6 text-red-800" />
                        <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-700 text-xs text-white">{{
                            cartCount
                        }}</span>
                    </div>

                    <div class="text-lg font-bold text-red-700">Giỏ hàng</div>
                </Link>
            </div>
        </div>
    </div>

    <div class="bg-red-700 text-white transition-opacity duration-200 ease-in-out" :class="{ 'opacity-0': isSticky, 'opacity-100': !isSticky }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="category.slug"
                    class="flex h-full flex-1 flex-col items-center justify-center py-4 hover:bg-red-600"
                >
                    <div v-html="category.icon" class="mb-2 mr-1 h-5 w-5"></div>
                    <span class="text-base font-semibold uppercase">{{ category.name }}</span>
                </Link>
            </div>
        </div>
    </div>
</template>
