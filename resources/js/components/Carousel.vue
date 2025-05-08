<template>
    <div class="w-full space-y-6">
        <!-- Top Banners -->
        <div class="relative rounded-lg overflow-hidden">
            <div class="overflow-hidden aspect-[21/9]">
                <div class="flex transition-transform duration-500 ease-in-out" :style="{ transform: `translateX(-${currentSlideTop * 100}%)` }">
                    <div v-for="banner in topBanners" :key="banner.id" class="w-full flex-shrink-0">
                        <a :href="banner.url" class="block">
                            <img :src="'/storage/' + banner.image" :alt="banner.name" class="w-full h-full object-cover">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button
                v-if="topBanners.length > 1"
                @click="prevSlideTop"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/75 transition-colors"
            >
                <ChevronLeft class="w-6 h-6" />
            </button>
            <button
                v-if="topBanners.length > 1"
                @click="nextSlideTop"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/75 transition-colors"
            >
                <ChevronRight class="w-6 h-6" />
            </button>

            <!-- Indicators -->
            <div v-if="topBanners.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                <button
                    v-for="(_, index) in topBanners"
                    :key="index"
                    @click="currentSlideTop = index"
                    class="w-2.5 h-2.5 rounded-full transition-colors duration-200"
                    :class="currentSlideTop === index ? 'bg-white' : 'bg-white/50 hover:bg-white/75'"
                />
            </div>
        </div>

        <!-- Bottom Banners -->
        <div v-if="bottomBanners.length > 0" class="grid grid-cols-3 gap-6">
            <div v-for="banner in bottomBanners" :key="banner.id" class="relative group rounded-lg overflow-hidden">
                <a :href="banner.url" class="block aspect-[16/9]">
                    <img
                        :src="'/storage/' + banner.image"
                        :alt="banner.name"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    >
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps({
    banners: {
        type: Array,
        required: true
    }
})

const currentSlideTop = ref(0)
const autoSlideInterval = ref(null)

const topBanners = computed(() =>
    props.banners.filter(banner => banner.position === 'top' && banner.is_active)
        .sort((a, b) => a.order - b.order)
)

const bottomBanners = computed(() =>
    props.banners.filter(banner => banner.position === 'bottom' && banner.is_active)
        .sort((a, b) => a.order - b.order)
)

const nextSlideTop = () => {
    currentSlideTop.value = (currentSlideTop.value + 1) % topBanners.value.length
}

const prevSlideTop = () => {
    currentSlideTop.value = (currentSlideTop.value - 1 + topBanners.value.length) % topBanners.value.length
}

const startAutoSlide = () => {
    if (topBanners.value.length > 1) {
        autoSlideInterval.value = setInterval(() => {
            nextSlideTop()
        }, 5000) // Change slide every 5 seconds
    }
}

const stopAutoSlide = () => {
    if (autoSlideInterval.value) {
        clearInterval(autoSlideInterval.value)
    }
}

onMounted(() => {
    startAutoSlide()
})

onUnmounted(() => {
    stopAutoSlide()
})
</script>
