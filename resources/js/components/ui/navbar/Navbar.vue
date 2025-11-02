<script setup>
import { computed, ref } from 'vue';
import MobileMenuButton from './MobileMenuButton.vue';
import { usePage } from '@inertiajs/vue3';
import ButtonLink from './ButtonLink.vue';
import NavLink from './NavLink.vue';
import ThemeToggler from './ThemeToggler.vue';
import axios from 'axios';

const isNavigationOpen = ref(false);
const toggleNavigation = () => {
    isNavigationOpen.value = !isNavigationOpen.value;
};
const page = usePage();
const component = computed(() => page.component);
const settings = computed(() => page.props.settings);

const user = computed(() => page.props.auth.user);
</script>

<template>
    <nav class="relative z-50 bg-white shadow dark:bg-gray-800">
        <div class="container mx-auto px-6 py-3 md:flex">
            <div class="flex items-center justify-between pr-6">
                <Link :href="route('home')">
                    <img class="h-6 w-auto rounded-full sm:h-7" :src="settings.app_logo" alt="logo" />
                </Link>
                <MobileMenuButton :isNavigationOpen="isNavigationOpen" @toggleNavigation="toggleNavigation" />
            </div>

            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div
                v-cloak
                :class="[isNavigationOpen ? 'translate-x-0 opacity-100' : '-translate-x-full opacity-0']"
                class="absolute inset-x-0 z-20 w-full border-b border-b-gray-300 bg-white px-6 py-4 transition-all duration-300 ease-in-out md:relative md:top-0 md:mt-0 md:flex md:translate-x-0 md:items-center md:justify-between md:border-0 md:p-0 md:opacity-100 dark:border-b-gray-500 dark:bg-gray-800"
            >
                <div class="md:mx-00 -mx-4 flex flex-col gap-y-0.5 px-2 md:flex-row md:py-0">
                    <NavLink routeName="home" label="Home" :active="component === 'Welcome'" />
                    <NavLink routeName="register" v-if="user" label="Dashboard" :active="component === 'Dashboard'" />
                    <NavLink routeName="product.index" v-if="user ? user.is_admin : false" label="Products" :active="component === 'product/Index'" />
                    <NavLink routeName="shop.index" label="Shop" :active="component === 'shop/Index'" />
                    <NavLink routeName="about" label="About" :active="component === 'About'" />

                    <!------------ THEME TOGGLER BUTTON DROPDOWN --------------------------------------------------------------------->

                    <!-- <div class="pl-4 flex items-center md:hidden">
                        <ThemeToggler />
                    </div> -->
                </div>

                <!------------ GUEST --------------------------------------------------------------------->
                <div v-if="!user" class="mt-2 flex flex-col items-center gap-2 md:flex-row">
                    <ButtonLink as="Link" :href="route('login')" :active="component === 'auth/Login'"> Login</ButtonLink>
                    <ButtonLink as="Link" :href="route('register')" :active="component === 'auth/Register'"> Register</ButtonLink>
                </div>
                <!------------ AUTH USER non ADMIN --------------------------------------------------------------------->
                <div v-else class="mt-2 flex flex-col items-center gap-2 md:flex-row">
                    <ButtonLink v-if="!user.is_admin" :active="component === 'auth/Login'">
                        <i class="fa-solid fa-cart-shopping" />
                        Cart
                    </ButtonLink>
                    <ButtonLink v-if="!user.is_admin" :active="component === 'auth/Register'">
                        <i class="fa-solid fa-heart" />
                        Wishlist
                    </ButtonLink>
                </div>
            </div>
        </div>
    </nav>
</template>
