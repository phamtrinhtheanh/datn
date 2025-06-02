<script setup lang="ts">
import { computed, reactive } from 'vue';
import CustomerLayout from '@/layouts/MainLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Minus, Plus, Trash2 } from 'lucide-vue-next';
import { formatVND } from '@/lib/utils';
import { useDebounceFn } from '@vueuse/core';

interface CartItem {
    product_id: number;
    quantity: number;
    [key: string]: any;
}

interface Product {
    id: number;
    title: string;
    price: number;
    images: string[];
    [key: string]: any;
}

interface CartData {
    items: CartItem[];
    products: Product[];
    total: number;
}

interface PageProps extends InertiaPageProps {
    cart: {
        data: CartData;
    };
}

const page = usePage<PageProps>();
const carts = computed(() => page.props.cart.data.items);
const products = computed(() => page.props.cart.data.products);
const total = computed(() => page.props.cart.data.total);
const itemId = (id: number) => carts.value.findIndex((item: CartItem) => item.product_id === id);

// Selection state
const selectedProducts = reactive(new Set<number>());
const allSelected = computed({
    get: () => products.value.length > 0 && selectedProducts.size === products.value.length,
    set: (value: boolean) => {
        if (value) {
            products.value.forEach((product) => selectedProducts.add(product.id));
        } else {
            selectedProducts.clear();
        }
    },
});

const selectedTotal = computed(() => {
    return products.value
        .filter((product) => selectedProducts.has(product.id))
        .reduce((sum, product) => {
            const cartItem = carts.value[itemId(product.id)];
            return sum + product.price * cartItem.quantity;
        }, 0);
});

const form = reactive({
    address1: null as string | null,
    state: null as string | null,
    city: null as string | null,
    zipcode: null as string | null,
    country_code: null as string | null,
    type: null as string | null,
});

const formFilled = computed(() => {
    return (
        form.address1 !== null &&
        form.state !== null &&
        form.city !== null &&
        form.zipcode !== null &&
        form.country_code !== null &&
        form.type !== null
    );
});

const update = (product: Product, quantity: number) =>
    router.patch(route('cart.update', { product: product.id }), {
        quantity,
    });

const debouncedUpdate = useDebounceFn((product: Product, quantity: number) => {
    update(product, quantity);
}, 500);

const handleQuantityChange = (product: Product, newQuantity: number) => {
    if (newQuantity < 1) {
        newQuantity = 1;
    }
    const cartItem = carts.value[itemId(product.id)];
    cartItem.quantity = newQuantity;
    debouncedUpdate(product, newQuantity);
};

const remove = (product: Product) => router.delete(route('cart.delete', { product: product.id }));

function submit() {
    if (selectedProducts.size === 0) {
        alert('Please select at least one product');
        return;
    }

    const selectedItems = products.value
        .filter((product) => selectedProducts.has(product.id))
        .map((product) => ({
            product_id: product.id,
            quantity: carts.value[itemId(product.id)].quantity,
        }));

    router.post(route('checkout.store'), {
        items: selectedItems,
    });
}
</script>

<template>
    <CustomerLayout>
        <div class="container mx-auto px-4 py-8">
            <div v-if="products.length > 0" class="bg-background rounded-lg px-8 shadow">
                <div class="p-6">
                    <h2 class="text-xl font-semibold">Giỏ hàng của bạn</h2>
                </div>
                <div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[5%]">
                                    <div class="flex items-center justify-center px-3 py-2">
                                        <Checkbox v-model="allSelected" />
                                    </div>
                                </TableHead>
                                <TableHead class="text-primary text-base font-bold">Sản phẩm</TableHead>
                                <TableHead class="text-primary text-right text-base font-bold">Giá bán</TableHead>
                                <TableHead class="text-primary text-center text-base font-bold">Số lượng</TableHead>
                                <TableHead class="text-primary text-right text-base font-bold">Thành tiền</TableHead>
                                <TableHead class="w-[100px]"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="product in products" :key="product.id">
                                <TableCell>
                                    <div class="flex items-center justify-center px-3 py-2">
                                        <Checkbox
                                            :model-value="selectedProducts.has(product.id)"
                                            @update:model-value="
                                                (checked) => {
                                                    if (checked) selectedProducts.add(product.id);
                                                    else selectedProducts.delete(product.id);
                                                }
                                            "
                                        />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-4">
                                        <img
                                            v-if="product.images.length > 0"
                                            :src="product.images[0]"
                                            :alt="product.title"
                                            class="h-16 w-16 rounded-md object-cover"
                                        />
                                        <img
                                            v-else
                                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                            :alt="product.name"
                                            class="h-16 w-16 rounded-md object-cover"
                                        />
                                        <span class="font-medium">{{ product.name }}</span>
                                    </div>
                                </TableCell>

                                <TableCell class="text-right">{{ formatVND(product.price) }}</TableCell>
                                <TableCell class="w-1/5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <Button
                                            variant="outline"
                                            size="icon"
                                            @click="handleQuantityChange(product, carts[itemId(product.id)].quantity - 1)"
                                            :disabled="carts[itemId(product.id)].quantity <= 1"
                                        >
                                            <Minus class="h-4 w-4" />
                                        </Button>
                                        <Input
                                            type="number"
                                            :model-value="carts[itemId(product.id)].quantity"
                                            @update:model-value="(value) => handleQuantityChange(product, Number(value))"
                                            class="w-20 text-center"
                                            min="1"
                                        />
                                        <Button
                                            variant="outline"
                                            size="icon"
                                            @click="handleQuantityChange(product, carts[itemId(product.id)].quantity + 1)"
                                        >
                                            <Plus class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                                <TableCell class="w-1/10 text-right">{{ formatVND(product.price * carts[itemId(product.id)].quantity) }}</TableCell>
                                <TableCell class="w-1/10 text-center">
                                    <Button variant="ghost" size="icon" @click="remove(product)">
                                        <Trash2 class="text-destructive h-4 w-4" />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
                <div class="flex items-center justify-between border-t p-6">
                    <div class="text-muted-foreground text-sm">Đã chọn {{ selectedProducts.size }} items</div>
                    <div class="flex items-center gap-4">
                        <div class="text-md font-medium">Tổng cộng: {{ formatVND(selectedTotal) }}</div>
                        <Button @click="submit" :disabled="selectedProducts.size === 0"> Checkout </Button>
                    </div>
                </div>
            </div>
            <div v-else class="rounded-lg border bg-white py-12 text-center shadow-sm">
                <div class="flex flex-col items-center gap-4">
                    <div class="text-4xl">🛒</div>
                    <h3 class="text-lg font-medium">Giỏ hàng của bạn đang trống</h3>
                    <p class="text-muted-foreground">Có vẻ chưa có sản phẩm nào trong giỏ hàng của bạn.</p>
                    <Button as-child class="mt-4">
                        <Link :href="route('home')"> Tiếp tục mua hàng </Link>
                    </Button>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
