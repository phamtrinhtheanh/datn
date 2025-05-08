import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const cart = reactive({
    items: [],
    isOpen: false,
    isLoading: false,

    get count() {
        return cart.items.reduce((total, item) => total + item.quantity, 0)
    },

    get total() {
        return cart.items.reduce((total, item) => total + item.product.price * item.quantity, 0)
    },

    get formattedTotal() {
        return cart.total.toFixed(2)
    },

    async fetch() {
        cart.isLoading = true
        try {
            const response = await router.get(route('cart.index'))
            cart.items = response.props.cart?.items || []
        } finally {
            cart.isLoading = false
        }
    },

    async add(productId: number, quantity: number = 1) {
        try {
            await router.post(route('cart.store'), { product_id: productId, quantity }, {
                preserveScroll: true,
                preserveState: true
            })
            await cart.fetch()
        } catch (error) {
            console.error('Error adding to cart:', error)
        }
    },

    async remove(itemId: number) {
        try {
            await router.delete(route('cart.destroy', itemId), {
                preserveScroll: true,
                preserveState: true
            })
            await cart.fetch()
        } catch (error) {
            console.error('Error removing from cart:', error)
        }
    },

    async sync() {
        try {
            await router.post(route('cart.sync'))
            await cart.fetch()
        } catch (error) {
            console.error('Error syncing cart:', error)
        }
    },

    toggle() {
        cart.isOpen = !cart.isOpen
    }
})

export function useCart() {
    return cart
}
