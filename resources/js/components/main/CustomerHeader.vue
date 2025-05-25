<script setup lang="ts">
import AppearanceButton from '@/components/AppearanceButton.vue';
import { Button } from '@/components/ui/button';
import { Drawer, DrawerContent, DrawerTrigger } from '@/components/ui/drawer';
import { type SharedData, type User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { CircleUser, Menu, MonitorSmartphone, PhoneCall, SearchIcon, ShoppingBag } from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';

// Define interfaces for the relevant shared props
interface SharedCartData {
    count: number;
    [key: string]: any; // Allow other properties like total, items, products
}

interface SharedHeaderProps {
    auth: { user: User | null }; // Assuming user can be null if not logged in
    cart: { data: SharedCartData };
    [key: string]: any; // Allow other shared props
}

const page = usePage<SharedHeaderProps>();
const user = computed(() => page.props.auth.user);
const cartCount = computed(() => page.props.cart?.data?.count || 0);

const categories = computed(() => page.props.categories ?? []);

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
        query: searchQuery.value,
    });
};
</script>

<template>
    <div class="header-top bg-secondary text-primary hidden text-sm md:block">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-2">
                    <Link href="#" class="flex items-center gap-1 font-semibold"> Hotline: 086 6123 158</Link>
                    <span>|</span>
                    <AppearanceButton />
                </div>

                <div class="flex items-center space-x-2">
                    <template v-if="!user">
                        <Link href="/sign-up" class="flex items-center gap-1 font-semibold"> Tạo tài khoản</Link>
                        <span>|</span>
                        <Link href="/login" class="flex items-center gap-1 font-semibold"> Đăng nhập</Link>
                    </template>
                    <template v-else>
                        <div class="flex items-center gap-1 font-semibold">
                            <CircleUser class="h-4 w-4" />
                            <span>{{ user.name }}</span>
                        </div>
                        <span>|</span>
                        <Link href="/logout" method="post" as="button" class="flex items-center gap-1 font-semibold"> Đăng xuất </Link>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div ref="stickyHeader" class="dark:bg-primary-foreground sticky top-0 z-50 bg-white py-4 shadow-sm transition-colors duration-300">
        <div class="container mx-auto flex flex-col items-center justify-between gap-4 px-4 md:flex-row">
            <div class="flex w-full justify-between md:w-auto">
                <Drawer direction="left">
                    <DrawerTrigger as-child>
                        <Button variant="ghost" class="text-secondary bg-muted block md:hidden">
                            <Menu class="text-primary h-10 w-10" />
                        </Button>
                    </DrawerTrigger>
                    <DrawerContent class="dark:bg-background h-screen w-full max-w-xs rounded-none rounded-r-lg bg-white shadow-lg">
                        <div class="space-y-4 p-4">
                            <Link href="/" class="text-primary block font-semibold">Trang chủ</Link>
                            <Link href="/don-hang" class="text-primary block font-semibold">Đơn mua</Link>
                            <Link href="/buildpc" class="text-primary block font-semibold">Xây dựng PC</Link>
                            <Link href="/cart" class="text-primary block font-semibold">Giỏ hàng</Link>
                        </div>
                    </DrawerContent>
                </Drawer>
                <Link href="/" class="flex items-center">
                    <div class="text-primary flex flex-col">
                        <span class="text-2xl font-black tracking-wide uppercase">THEANHPC.VN</span>
                        <span class="text-xs font-bold">HI-END PC & GAMING GEAR</span>
                    </div>
                </Link>
                <Link href="/cart" class="flex items-center md:hidden">
                    <div class="bg-muted relative rounded-full p-2 xl:mr-3">
                        <ShoppingBag class="text-primary h-6 w-6" />
                        <span
                            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                            >{{ cartCount }}</span
                        >
                    </div>
                    <div class="text-primary hidden text-lg font-bold xl:block">Giỏ hàng</div>
                </Link>
            </div>

            <div class="w-full max-w-2xl flex-1 md:w-auto">
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        @keyup.enter="handleSearch"
                        type="text"
                        placeholder="Nhập từ khóa cần tìm"
                        class="focus:border-primary w-full rounded-full border border-gray-300 px-4 py-2 pr-10 focus:outline-none"
                    />
                    <button
                        @click="handleSearch"
                        class="bg-primary text-secondary hover:bg-primary/95 absolute top-0 right-0 h-full rounded-r-full px-4 focus:outline-none"
                    >
                        <SearchIcon class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <div class="hidden space-x-4 md:flex xl:space-x-6">
                <Link href="/don-hang" class="flex items-center">
                    <div class="bg-muted rounded-full p-2 xl:mr-3">
                        <PhoneCall class="text-primary h-6 w-6" />
                    </div>
                    <div class="text-primary hidden text-lg font-bold xl:block">Đơn mua</div>
                </Link>

                <Link href="/buildpc" class="flex items-center">
                    <div class="bg-muted rounded-full p-2 xl:mr-3">
                        <MonitorSmartphone class="text-primary h-6 w-6" />
                    </div>
                    <div class="hidden xl:block">
                        <div class="text-primary text-sm font-bold">Xây dựng</div>
                        <div class="text-primary text-lg font-bold">Cấu hình PC</div>
                    </div>
                </Link>

                <Link href="/cart" class="flex items-center">
                    <div class="bg-muted relative rounded-full p-2 xl:mr-3">
                        <ShoppingBag class="text-primary h-6 w-6" />
                        <span
                            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-xs font-bold text-white"
                            >{{ cartCount }}</span
                        >
                    </div>
                    <div class="text-primary hidden text-lg font-bold xl:block">Giỏ hàng</div>
                </Link>
            </div>
        </div>
    </div>
</template>
