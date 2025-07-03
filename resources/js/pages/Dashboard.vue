<script setup>
    import ThemeToggler from '@/components/ui/navbar/ThemeToggler.vue';
    import ContainerPrimary from '../components/ContainerPrimary.vue';
    import DashboardMenuItem from '../components/ui/card/DashboardMenuItem.vue';
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';
    //   import ProductsManagement from '../components/sections/ProductsManagement.vue'

    const page = usePage();

    const props = defineProps({
        user: Object,
        announcements: Object
    });

    function openCart() {
        page.props.shopAside = {
            isOpen: true,
            isCartOpen: true,
            isWishlistOpen: false
        }
    }
    function openWishlist() {
        page.props.shopAside = {
            isOpen: true,
            isWishlistOpen: true,
            isCartOpen: false
        }
    }

    const orderCount = computed(() => {
        return props.user.orders.filter(order => {
            return (order.status === 'pending' || order.status === 'processing');
        }).length;
    });

</script>

<template>
    <!-- {{ user }} -->

    <Head title="Dashboard" />

    <div class=" mx-auto px-2 max-w-6xl py-6 h-screen space-y-4">
        <ContainerPrimary v-if="true" title="Dashboard">
            <div class="flex flex-wrap justify-center gap-4 pt-2">
                <DashboardMenuItem is="Link" :href="route('order.index')" type="button" label="orders"
                    :count="orderCount" icon="fa-solid fa-bag-shopping" />
                <DashboardMenuItem type="button" @click="openWishlist" label="wishlist"
                    :count="user.wishlist.items.length" icon="fa-solid fa-heart" />
                <DashboardMenuItem type="button" @click="openCart" label="cart" :count="user.cart.items.length"
                    icon="fa-solid fa-cart-shopping" />
                <DashboardMenuItem type="button" label="message" :count="user.messages.length"
                    icon="fa-solid fa-comments" />
                <DashboardMenuItem type="button" label="announcements" :count="announcements.length"
                    icon="fa-solid fa-bullhorn" />
                <DashboardMenuItem type="button" label="profile" icon="fa-solid fa-user" />
            </div>
        </ContainerPrimary>

        <ContainerPrimary class="!p-3 grid md:grid-cols-2 gap-2 md:h-96">
            <div>
                announcements
            </div>
            <div>
                section
            </div>
        </ContainerPrimary>
    </div>

</template>