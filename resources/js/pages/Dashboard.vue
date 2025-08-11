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
    fragmentsContainer: 'px-8 py-4 bg-white rounded-xl shadow-lg dark:bg-gray-800 grid md:grid-cols-2 gap-4 gap-y-7',
    fragment: 'h-fit overflow-y-hidden rounded-2xl bg-slate-100 pb-3 dark:bg-slate-700 px-1 pt-2',
    fragmentTitle: 'font-medium text-lg pb-3 text-center',
    scrollContainer: 'h-[70vh] overflow-y-scroll px-1 space-y-6',
    pageContainer: 'mx-auto max-w-7xl pb-7 px-1',
}));

const unseenMessages = computed(() => {
    console.dir(props.user);
    return props.user.received_messages?.filter((message) => !message.seen)?.length;
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="pt-8" v-if="user.role === 'suspended'">
        <div class="w-full bg-red-500 text-white">
            <div class="container mx-auto flex items-center justify-between px-6 py-4">
                <div class="flex items-center">
                    <svg viewBox="0 0 40 40" class="h-6 w-6 fill-current">
                        <path
                            d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z"
                        ></path>
                    </svg>

                    <p class="mx-3">
                        Your account is currently <strong>Suspended</strong>. You cannot place orders or access certain features until it is
                        reactivated.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <AdminDashboard v-if="user.is_admin" :admin="user" :shop="shop" :contacts="contacts" />
    <!-- CUSTOMER -->
    <div v-else :class="styleClass.pageContainer">
        <div class="space-y-4 py-6">
            <ContainerPrimary v-if="true" title="Dashboard">
                <div class="flex flex-wrap justify-center gap-4 pt-2">
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
                        :count="unseenMessages"
                        icon="fa-solid fa-comments"
                    />
                    <a href="#announcements">
                        <DashboardMenuItem type="button" label="announcements" :count="announcements.length" icon="fa-solid fa-bullhorn" />
                    </a>
                    <DashboardMenuItem type="button" label="profile" is="Link" :href="route('profile.edit')" icon="fa-solid fa-user" />
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
                    <div class="grid justify-center gap-2 sm:grid-cols-2">
                        <DashboardCartWishlistItem v-for="item of user.cart.items" :key="item.id" from="cart" :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.wishlist?.items.length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Wishlist items</p>
                <div :class="styleClass.scrollContainer">
                    <div class="grid justify-center gap-2 sm:grid-cols-2">
                        <DashboardCartWishlistItem v-for="item of user.wishlist.items" :key="item.id" from="wishlist" :item="item" />
                    </div>
                </div>
            </div>
            <div v-if="user.orders.filter((order) => order.status == 'pending' || order.status == 'processing').length" :class="styleClass.fragment">
                <p :class="styleClass.fragmentTitle">Orders</p>
                <div :class="styleClass.scrollContainer">
                    <div class="grid justify-center gap-2 sm:grid-cols-2">
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
