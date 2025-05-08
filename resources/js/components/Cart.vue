<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const page = usePage();

const cartCount = computed(() => {
    if (page.props.auth.user) {
        return page.props.cartCount || 0;
    } else {
        const guestCart = JSON.parse(localStorage.getItem('guest_cart') || '[]');
        return guestCart.reduce((total, item) => total + item.quantity, 0);
    }
});

// Lắng nghe sự kiện cập nhật giỏ hàng
window.addEventListener('cart-updated', () => {
    // Force update computed property
    cartCount.value;
});
</script>

<template>
    <Link :href="route('cart')" class="relative">
        <span class="sr-only">Giỏ hàng</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
            {{ cartCount }}
        </span>
    </Link>
</template>
