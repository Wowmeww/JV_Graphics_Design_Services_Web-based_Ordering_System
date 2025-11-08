<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import CartItem from '../card/CartItem.vue';
import ButtonPrimary from '../buttons/ButtonPrimary.vue';

defineProps({
    expanded: Boolean,
});
const page = usePage();
const cartItems = computed(() => page.props.auth.user?.cart?.items || []);
const wishlistItems = computed(() => page.props.auth.user?.wishlist?.items || []);
const emit = defineEmits(['close']);

const open = computed(() => ({
    cart: page.props.shopAside.isCartOpen,
    wishlist: page.props.shopAside.isWishlistOpen,
}));

function close() {
    emit('close');
}

const selectedCartItems = ref([]);
const selectedWishlistItems = ref([]);

function toggleAllCartItem(event) {
    if (event.target.checked) {
        selectedCartItems.value = [...cartItems.value];
    } else {
        selectedCartItems.value = [];
    }
}
function toggleAllWishlistItems(event) {
    if (event.target.checked) {
        selectedWishlistItems.value = [...wishlistItems.value];
    } else {
        selectedWishlistItems.value = [];
    }
}

function handleCheckout() {
    if (open.value.cart && selectedCartItems.value) {
        router.get(
            route('order.create', {
                items: selectedCartItems.value.map((item) => item.id),
                from: 'cart',
            }),
            {
                onSuccess: () => {
                    selectedCartItems.value = [];
                    close();
                },
            },
        );
    }
    if (open.value.wishlist && selectedWishlistItems.value) {
        router.post(
            route('wishlist.add.cart', {
                items: selectedWishlistItems.value.map((item) => item.id),
                from: 'wishlist',
            }),
            {
                onSuccess: () => {
                    selectedWishlistItems.value = [];
                    close();
                },
            },
        );
    }
}
function deleteBulkItems() {
    if (open.value.cart && selectedCartItems.value) {
        router.delete(route('cart.destroy', { ids: selectedCartItems.value.map((item) => item.id) }), {
            onSuccess: () => (selectedCartItems.value = []),
        });
    }
    if (open.value.wishlist && selectedWishlistItems.value) {
        router.delete(route('wishlist.destroy', { ids: selectedWishlistItems.value.map((item) => item.id) }), {
            onSuccess: () => (selectedWishlistItems.value = []),
        });
    }
}

const totalAmount = reactive({
    cart: 0,
    wishlist: 0,
});

watch(
    () => [...selectedCartItems.value, ...selectedWishlistItems.value],
    (items) => {
        const temps = {
            cart: 0,
            wishlist: 0,
        };
        items.forEach((item) => {
            if (item.cart_id) {
                temps.cart += item.total_amount;
            } else {
                temps.wishlist += item.total_amount;
            }
        });

        totalAmount.cart = temps.cart;
        totalAmount.wishlist = temps.wishlist;
    },
);

function formatCurrency(num) {
    return num.toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP',
    });
}
</script>

<template>
    <div
        v-if="expanded"
        class="bg-secondary/20 fixed inset-0 z-30 backdrop-blur-xs transition dark:bg-black/30 dark:backdrop-blur-xs"
        @click="close"
    ></div>
    <aside
        :class="[
            'fixed top-0 right-0 z-50 h-screen w-full border-r border-gray-200 bg-white pt-20 transition-transform sm:w-90 dark:border-gray-700 dark:bg-gray-800',
            expanded ? 'translate-x-0' : 'translate-x-full',
        ]"
        aria-label="Sidebar"
        id="sidebar"
    >
        <div class="flex h-full w-full flex-col justify-between space-y-2 bg-white px-3 font-medium dark:bg-gray-800">
            <div class="top-20 z-10 bg-white px-3 pb-2 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b-2 border-slate-300 pb-2">
                    <button @click="close" type="button" class="hover:bg-secondary-100/20 h-8 w-8 rounded-full text-2xl">
                        <i class="bi bi-x"></i>
                    </button>
                    <h4 class="text-lg font-medium md:text-xl">
                        <span v-if="open.cart">Shopping Cart</span>
                        <span v-if="open.wishlist">Shopping Wishlist</span>

                        <span v-if="cartItems.length && open.cart">{{ `(${cartItems.length})` }}</span>
                        <span v-if="wishlistItems.length && open.wishlist">{{ `(${wishlistItems.length})` }}</span>
                    </h4>
                </div>
                <div v-if="cartItems.length > 1 && open.cart" class="flex items-center justify-between px-2 pt-2">
                    <input type="checkbox" class="accent-secondary" @change="toggleAllCartItem" />

                    <div class="space-x-3">
                        <span>{{ selectedCartItems.length }}</span>
                        <button v-if="selectedCartItems.length" @click="deleteBulkItems" type="button">
                            <i class="bi bi-trash text-red-600"></i>
                        </button>
                    </div>
                </div>
                <div v-if="wishlistItems.length > 1 && open.wishlist" class="flex items-center justify-between px-2 pt-2">
                    <input type="checkbox" class="accent-secondary" @change="toggleAllWishlistItems" />

                    <div class="space-x-3">
                        <span>{{ selectedWishlistItems.length }}</span>
                        <button v-if="selectedWishlistItems.length" @click="deleteBulkItems" type="button">
                            <i class="bi bi-trash text-red-600"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-auto">
                <div v-if="open.cart" class="space-y-2 scroll-auto py-4">
                    <CartItem v-model="selectedCartItems" @close="close" v-for="(item, i) in cartItems" :item="item" :key="`c-${i}`" />
                </div>
                <div v-if="open.wishlist" class="space-y-2 scroll-auto py-4">
                    <CartItem
                        v-model="selectedWishlistItems"
                        @close="close"
                        v-for="(item, i) in wishlistItems"
                        :item="item"
                        :key="`w-${i}`"
                        from="wishlist"
                    />
                </div>
            </div>

            <div class="w-full space-y-3 border-t-2 border-slate-300 bg-white p-3 pb-6 dark:bg-gray-800">
                <div class="flex items-center justify-between" v-if="selectedCartItems.length && open.cart">
                    <span>Total amount:</span>
                    <span class="font-bold"> {{ formatCurrency(totalAmount.cart) }}</span>
                </div>
                <div class="flex items-center justify-between" v-if="selectedWishlistItems.length && open.wishlist">
                    <span>Total amount:</span>
                    <span class="font-bold" v-if="selectedWishlistItems.length && open.wishlist"> {{ formatCurrency(totalAmount.wishlist) }}</span>
                </div>
                <div>
                    <ButtonPrimary
                        v-if="open.cart"
                        :withSpinner="false"
                        :disable="!selectedCartItems.length"
                        label="Checkout"
                        variant="secondary"
                        @click="handleCheckout"
                    />
                    <ButtonPrimary
                        v-if="open.wishlist"
                        :withSpinner="false"
                        :disable="!selectedWishlistItems.length"
                        label="Add to cart"
                        variant="secondary"
                        @click="handleCheckout"
                    />
                </div>
            </div>
        </div>
    </aside>
</template>
