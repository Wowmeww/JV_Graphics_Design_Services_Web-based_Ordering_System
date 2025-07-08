<script setup>
    import ThemeToggler from '@/components/ui/navbar/ThemeToggler.vue';
    import DashboardMenuItem from '@/components/ui/card/DashboardMenuItem.vue';
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import Announcement from '@/components/ui/card/Announcement.vue';
    import DashboardCartWishlistItem from '../components/ui/card/DashboardCartWishlistItem.vue';
    import DashboardOrder from '@/components/ui/card/DashboardOrder.vue';
    import AdminDashboard from '@/components/sections/AdminDashboard.vue';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';

    //   import ProductsManagement from '../components/sections/ProductsManagement.vue'

    const page = usePage();

    const props = defineProps({
        user: Object,
        announcements: Object,
        shop: Object
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

    const styleClass = computed(() => ({
        fragmentsContainer: 'px-8 py-4 bg-white rounded-xl shadow-lg dark:bg-gray-800 grid md:grid-cols-2 gap-4 gap-y-7',
        fragment: 'h-fit overflow-y-hidden rounded-2xl bg-slate-100 pb-3 dark:bg-slate-700 px-1 pt-2',
        fragmentTitle: 'font-medium text-lg pb-3 text-center',
        scrollContainer: 'h-[70vh] overflow-y-scroll px-1 space-y-6',
        pageContainer: 'mx-auto max-w-7xl pb-7 px-1'
    }));

</script>

<template>
    <!-- {{ user }} -->

    <Head title="Dashboard" />


    <AdminDashboard v-if="user.is_admin" :shop="shop" />

    <!-- CUSTOMER -->
    <div v-else :class="styleClass.pageContainer">
        <div class="py-6 space-y-4 ">
            <ContainerPrimary v-if="true" title="Dashboard">
                <div class="flex flex-wrap justify-center gap-4 pt-2">
                    <DashboardMenuItem is="Link" :href="route('order.index')" type="button" label="orders"
                        :count="orderCount" icon="fa-solid fa-bag-shopping" />
                    <DashboardMenuItem type="button" @click="openWishlist" label="wishlist"
                        :count="user.wishlist?.items.length" icon="fa-solid fa-heart" />
                    <DashboardMenuItem type="button" @click="openCart" label="cart" :count="user.cart?.items.length"
                        icon="fa-solid fa-cart-shopping" />
                    <DashboardMenuItem type="button" label="message" :count="user.messages.length"
                        icon="fa-solid fa-comments" />
                    <a href="#announcements">
                        <DashboardMenuItem type="button" label="announcements" :count="announcements.length"
                            icon="fa-solid fa-bullhorn" />
                    </a>
                    <DashboardMenuItem type="button" label="profile" is="Link" :href="route('profile.edit')"
                        icon="fa-solid fa-user" />
                </div>
            </ContainerPrimary>
        </div>
        <div :class="styleClass.fragmentsContainer"
            v-if="announcements.length || user.cart.items.length || user.orders.filter(order => order.status == 'pending' || order.status == 'processing').length || user.wishlist.items.length">
            <div id="announcements" v-if="announcements.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Announcements</p>
                <div :class="styleClass.scrollContainer">
                    <Announcement :announcement="announcement" v-for="announcement in announcements"
                        :key="announcement.id" />
                </div>
            </div>
            <div v-if="user.cart?.items.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Cart items</p>
                <div :class="styleClass.scrollContainer">
                    <div class="grid justify-center sm:grid-cols-2 gap-2">
                        <DashboardCartWishlistItem v-for="item of user.cart.items" :key="item.id" from="cart"
                            :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.wishlist?.items.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Wishlist items</p>
                <div :class="styleClass.scrollContainer">
                    <div class="grid justify-center sm:grid-cols-2 gap-2">
                        <DashboardCartWishlistItem v-for="item of user.wishlist.items" :key="item.id" from="wishlist"
                            :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.orders.filter(order => order.status == 'pending' || order.status == 'processing').length"
                :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Orders</p>
                <div :class="styleClass.scrollContainer">
                    <div class="grid justify-center sm:grid-cols-2 gap-2">
                        <DashboardOrder v-for="order in user.orders.filter(order => order.status == 'pending' || order.status ==
                            'processing')" :key="'order' + order.id" :order="order" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
