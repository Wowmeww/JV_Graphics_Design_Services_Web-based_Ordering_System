<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthAsideLink from './AuthAsideLink.vue';
import { computed } from 'vue';

defineProps({
    expanded: Boolean,
});
const page = usePage();
const is_admin = computed(() => page.props.auth.user?.is_admin);
const emit = defineEmits(['close']);
const user = computed(() => page.props.auth?.user);
function close() {
    emit('close');
}

const styleClass = {
    aside: 'fixed top-0 left-0 z-70 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700',
    icon: 'w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white',
};
</script>

<template>
    <div
        v-if="expanded"
        class="bg-secondary/20 fixed inset-0 z-60 backdrop-blur-xs transition dark:bg-black/30 dark:backdrop-blur-xs"
        @click="close"
    ></div>
    <aside :class="[styleClass.aside, expanded ? 'translate-x-0' : '-translate-x-full']" aria-label="Sidebar" id="sidebar">
        <div class="h-full space-y-2 overflow-y-auto bg-white px-3 pb-4 font-medium dark:bg-gray-800">
            <AuthAsideLink
                :active="page.component === 'auth/dashboard/Customer' || page.component === 'auth/dashboard/Admin'"
                :routeName="route('dashboard')"
            >
                <svg :class="styleClass.icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                    <path
                        d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"
                    />
                </svg>
                <span class="ms-3">Dashboard</span>
            </AuthAsideLink>

            <AuthAsideLink
                :routeName="route('message.index')"
                :active="page.component === 'auth/message/Messages'"
                :badge="user ? user.not_seen_messages : 0"
            >
                <svg :class="styleClass.icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"
                    />
                </svg>
                <span class="ms-3 flex-1 whitespace-nowrap">Inbox</span>
            </AuthAsideLink>

            <AuthAsideLink v-if="is_admin" :routeName="route('manage.users.index')" :active="page.component === 'manage/user/Users'">
                <svg :class="styleClass.icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path
                        d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"
                    />
                </svg>
                <span class="ms-3 flex-1 whitespace-nowrap">Users</span>
            </AuthAsideLink>

            <AuthAsideLink v-if="is_admin" :active="page.component === 'product/Index'" :routeName="route('product.index')">
                <i class="fa-solid fa-shop text-lg" :class="styleClass.icon"></i>
                <span class="ms-3 flex-1 whitespace-nowrap">Products</span>
            </AuthAsideLink>
            <!-- Change the svg icon proper for orders -->
            <AuthAsideLink v-if="is_admin" :active="page.component === 'manage/order/Index'" :routeName="route('manage.orders.index')">
                <svg :class="styleClass.icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M1 3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5H1V3Zm0 7h18v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-9Z" />
                    <path
                        d="M4 7a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7Zm6 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V7Zm6 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V7Z"
                    />
                </svg>
                <span class="ms-3 flex-1 whitespace-nowrap">Orders</span>
            </AuthAsideLink>
            <AuthAsideLink v-else :active="page.component === 'shop/order/Index'" :routeName="route('order.index')">
                <svg :class="styleClass.icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M1 3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5H1V3Zm0 7h18v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-9Z" />
                    <path
                        d="M4 7a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7Zm6 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V7Zm6 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V7Z"
                    />
                </svg>
                <span class="ms-3 flex-1 whitespace-nowrap">Orders</span>
            </AuthAsideLink>

            <AuthAsideLink :active="page.component === 'shop/Index'" :routeName="route('shop.index')">
                <i class="fa-solid fa-bag-shopping text-xl" :class="styleClass.icon"></i>
                <span class="ms-3 flex-1 whitespace-nowrap">Shop</span>
            </AuthAsideLink>

            <AuthAsideLink :active="page.component === 'auth/announcement/Announcements'" :routeName="route('announcement.index')">
                <i class="fa-solid fa-bullhorn text-lg" :class="styleClass.icon"></i>
                <span class="ms-3 flex-1 whitespace-nowrap">Announcements</span>
            </AuthAsideLink>

            <AuthAsideLink :active="page.component === 'auth/transaction/Transactions'" :routeName="route('transactions')">
                <i class="fa-solid fa-receipt text-lg" :class="styleClass.icon"></i>
                <span class="ms-3 flex-1 whitespace-nowrap">Transaction</span>
            </AuthAsideLink>
        </div>
    </aside>
</template>
