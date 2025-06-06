<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { formatVND } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/vue3';
import { ChevronRight, ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    title: string;
    seeAllLink: string;
    products: Array<{
        id: number;
        name: string;
        slug: string;
        images: string | Array<string>;
        line_price: number;
        price: number;
        reviews_count?: number;
    }>;
}>();

const parseImages = (images: string | Array<string>): string[] => {
    if (typeof images === 'string') {
        try {
            return JSON.parse(images);
        } catch (e) {
            console.error('Error parsing images:', e);
            return [];
        }
    }
    return images || [];
};

const productsWithDiscount = computed(() =>
    props.products.map((product) => {
        const discount = Math.floor(((product.line_price - product.price) / product.line_price) * 100);
        const images = parseImages(product.images);

        return {
            ...product,
            images,
            discount,
        };
    }),
);

const addToCart = (productId: number) => {
    const form = useForm({
        quantity: 1,
    });

    form.post(route('cart.add', productId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
        onError: (errors) => console.error('Lỗi khi thêm vào giỏ hàng', errors),
    });
};

</script>

<template>
    <div class="space-y-4">
        <!-- Section Header -->
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ title }}</h2>
            <Button variant="ghost" class="text-md">
                Xem thêm
                <ChevronRight class="inline-block h-4 w-4" />
            </Button>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-5">
            <Card v-for="product in productsWithDiscount" :key="product.id" class="group gap-0 py-2 transition-shadow hover:shadow-md">
                <Link :href="`/${product.slug}`" class="block">
                    <div class="relative mx-2 mb-2 overflow-hidden rounded-lg border">
                        <img
                            :src="product.images[0]"
                            :alt="product.name"
                            class="aspect-square w-full object-contain transition-transform duration-300 group-hover:scale-[1.02]"
                        />
                    </div>

                    <CardContent class="p-4 pt-0 pb-2">
                        <h3 class="line-clamp-2 min-h-[3em] text-base font-medium">
                            {{ product.name }}
                        </h3>

                        <p v-if="product.discount > 0" class="text-sm text-muted-foreground">
                            <span class="line-through">
                                {{ formatVND(product.line_price) }}
                            </span>
                            &nbsp;
                            <span>
                                <Badge variant="outline"> -{{ product.discount }}% </Badge>
                            </span>
                        </p>

                        <p class="text-xl font-bold text-red-700">
                            {{ formatVND(product.price) }}
                        </p>
                    </CardContent>
                </Link>

                <CardFooter class="flex justify-between px-4 pb-1 pt-0">
                    <p class="text-sm font-semibold">Còn hàng</p>
                    <Button @click="addToCart(product.id)" class="h-10 w-10 rounded-full">
                        <ShoppingCart class="h-4 w-4 text-white" />
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </div>
</template>
