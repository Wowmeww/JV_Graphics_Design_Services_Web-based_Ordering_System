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
    shop: Object,
    contacts: Array,
});

function openCart() {
    page.props.shopAside = {
        isOpen: true,
        isCartOpen: true,
        isWishlistOpen: false,
    };
}
function openWishlist() {
    page.props.shopAside = {
        isOpen: true,
        isWishlistOpen: true,
        isCartOpen: false,
    };
}

const orderCount = computed(() => {
    return props.user.orders.filter((order) => {
        return order.status === 'pending' || order.status === 'processing';
    }).length;
});

const styleClass = computed(() => ({
    // Suspended user banner
    suspendedBanner: {
        container: 'pt-8 animate__animated animate__fadeInDown',
        banner: 'w-full bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg border-b-2 border-red-300',
        content: 'container mx-auto flex items-center justify-between px-6 py-5',
        iconContainer: 'flex items-center gap-4',
        icon: 'h-7 w-7 fill-current flex-shrink-0 drop-shadow-lg',
        text: 'text-base font-medium leading-relaxed',
        strong: 'font-bold text-red-100',
    },

    // Page layout
    pageContainer: 'mx-auto max-w-8xl pb-8 px-4 sm:px-6 lg:px-8',

    // Dashboard sections
    dashboard: {
        container: 'space-y-8 py-8',
        menu: {
            container: 'flex flex-wrap justify-center gap-6 pt-4',
        },
    },

    // Fragments grid
    fragmentsContainer:
        'px-6 py-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-3xl shadow-2xl dark:shadow-2xl border border-gray-200 dark:border-gray-700 grid lg:grid-cols-2 gap-8 gap-y-10 backdrop-blur-sm',

    // Individual fragment cards
    fragment:
        'group h-fit overflow-hidden rounded-3xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 pb-4 px-3 pt-4 shadow-lg hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] border border-slate-200 dark:border-slate-600',

    // Fragment titles
    fragmentTitle:
        'font-bold text-xl pb-4 text-center text-gray-800 dark:text-white bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent border-b border-slate-300 dark:border-slate-600 mx-4 pb-4',

    // Scrollable content areas
    scrollContainer:
        'h-[70vh] overflow-y-auto px-3 space-y-6 pt-4 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-600 scrollbar-track-transparent hover:scrollbar-thumb-slate-400 dark:hover:scrollbar-thumb-slate-500',

    // Grid layouts for items
    grid: {
        container: 'grid justify-center gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2',
    },

    // Additional effects
    shimmer:
        'absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000',
}));
</script>

<template>
    <Head title="Dashboard" />

    <div :class="styleClass.suspendedBanner.container" v-if="user.role === 'suspended'">
        <div :class="styleClass.suspendedBanner.banner">
            <div :class="styleClass.suspendedBanner.content">
                <div :class="styleClass.suspendedBanner.iconContainer">
                    <svg viewBox="0 0 40 40" :class="styleClass.suspendedBanner.icon">
                        <path
                            d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"
                        ></path>
                    </svg>

                    <p :class="styleClass.suspendedBanner.text">
                        Your account is currently <strong>Suspended</strong>. You cannot place orders or access certain features until it is
                        reactivated.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!---- ADMIN DASHBOARD ------------------------------------------------------------------------------------------------->
    <AdminDashboard v-if="user.is_admin" :admin="user" :shop="shop" :contacts="contacts" />

    <!-- CUSTOMER -->
    <div v-else :class="styleClass.pageContainer">
        <div :class="styleClass.dashboard.container">
            <ContainerPrimary v-if="true" title="Dashboard">
                <div :class="styleClass.dashboard.menu.container">
                    <DashboardMenuItem
                        is="Link"
                        :href="route('order.index')"
                        type="button"
                        label="orders"
                        :count="orderCount"
                        icon="fa-solid fa-bag-shopping"
                    />
                    <DashboardMenuItem
                        type="button"
                        @click="openWishlist"
                        label="wishlist"
                        :count="user.wishlist?.items.length"
                        icon="fa-solid fa-heart"
                    />
                    <DashboardMenuItem
                        type="button"
                        @click="openCart"
                        label="cart"
                        :count="user.cart?.items.length"
                        icon="fa-solid fa-cart-shopping"
                    />
                    <DashboardMenuItem
                        type="button"
                        label="message"
                        is="Link"
                        :href="route('message.index')"
                        :count="user.not_seen_messages"
                        icon="fa-solid fa-comments"
                    />
                    <a href="#announcements">
                        <DashboardMenuItem type="button" label="announcements" :count="announcements.length" icon="fa-solid fa-bullhorn" />
                    </a>
                    <!-- <DashboardMenuItem type="button" label="profile" is="Link" :href="route('profile.edit')" icon="fa-solid fa-user" /> -->
                </div>
            </ContainerPrimary>
        </div>

        <div
            :class="styleClass.fragmentsContainer"
            v-if="
                announcements?.length ||
                user.cart?.items?.length ||
                user.orders.filter((order) => order.status == 'pending' || order.status == 'processing')?.length ||
                user.wishlist?.items?.length
            "
        >
            <div id="announcements" v-if="announcements.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Announcements</p>
                <div :class="styleClass.scrollContainer">
                    <Announcement :announcement="announcement" v-for="announcement in announcements" :key="announcement.id" />
                </div>
            </div>
            <div v-if="user.cart?.items.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Cart items</p>
                <div :class="styleClass.scrollContainer">
                    <div :class="styleClass.grid.container">
                        <DashboardCartWishlistItem v-for="item of user.cart.items" :key="item.id" from="cart" :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.wishlist?.items.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Wishlist items</p>
                <div :class="styleClass.scrollContainer">
                    <div :class="styleClass.grid.container">
                        <DashboardCartWishlistItem v-for="item of user.wishlist.items" :key="item.id" from="wishlist" :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.orders.filter((order) => order.status == 'pending' || order.status == 'processing').length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Orders</p>
                <div :class="styleClass.scrollContainer">
                    <div :class="styleClass.grid.container">
                        <DashboardOrder
                            v-for="order in user.orders.filter((order) => order.status == 'pending' || order.status == 'processing')"
                            :key="'order' + order.id"
                            :order="order"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
