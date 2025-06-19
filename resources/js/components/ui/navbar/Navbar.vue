<script setup>
    import { computed, ref } from 'vue';
    import MobileMenuButton from './MobileMenuButton.vue';
    import { usePage } from '@inertiajs/vue3';
    import ButtonLink from './ButtonLink.vue';
    import NavLink from './NavLink.vue';
    import ThemeToggler from './ThemeToggler.vue';

    const isNavigationOpen = ref(false);
    const toggleNavigation = () => {
        isNavigationOpen.value = !isNavigationOpen.value;
    };

    const component = computed(() => usePage().component);

    const user = computed(() => usePage().props.auth.user);
</script>

<template>
    <nav class="relative bg-white shadow dark:bg-gray-800">
        <div class="container px-6 py-3 mx-auto md:flex">
            <div class="flex items-center justify-between pr-6">
                <Link :href="route('home')">
                <img class="w-auto h-6 sm:h-7" src="favicon.png" alt="logo">

                </Link>
                <MobileMenuButton :isNavigationOpen="isNavigationOpen" @toggleNavigation="toggleNavigation" />
            </div>

            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div v-cloak :class="[isNavigationOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']"
                class="absolute  inset-x-0 z-20 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-white dark:bg-gray-800 md:mt-0 md:p-0 md:top-0 md:relative md:opacity-100 md:translate-x-0 md:flex md:items-center md:justify-between border-b border-b-gray-300 dark:border-b-gray-500 md:border-0">
                <div class="flex flex-col px-2 -mx-4 md:flex-row md:mx-00 md:py-0 gap-y-0.5 ">
                    <NavLink routeName="home" label="Home" :active="component === 'Welcome'" />
                    <NavLink routeName="register" v-if="user" label="Dashboard" :active="component === 'Dashboard'" />
                    <NavLink routeName="product.index" v-if="user ? user.is_admin : false" label="Products"
                        :active="component === 'product/Index'" />
                    <NavLink routeName="register" label="Shop" :active="component === 'Shop'" />
                    <NavLink routeName="register" label="About" :active="component === 'About'" />

                    <!------------ THEME TOGGLER BUTTON DROPDOWN --------------------------------------------------------------------->

                    <!-- <div class="pl-4 flex items-center md:hidden">
                        <ThemeToggler />
                    </div> -->

                </div>

                <div class="flex items-center flex-col md:flex-row gap-2 mt-2">
                    <!------------ GUEST --------------------------------------------------------------------->
                    <ButtonLink v-if="!user" as="Link" :href="route('login')" :active="component === 'auth/Login'">
                        Login</ButtonLink>
                    <ButtonLink v-if="!user" as="Link" :href="route('register')"
                        :active="component === 'auth/Register'">
                        Register</ButtonLink>

                    <!------------ AUTH USER non ADMIN --------------------------------------------------------------------->

                    <ButtonLink v-else-if="!user.is_admin" :active="component === 'auth/Login'">
                        <i class="fa-solid fa-cart-shopping" />
                        Cart
                    </ButtonLink>
                    <ButtonLink v-else-if="!user.is_admin" :active="component === 'auth/Register'">
                        <i class="fa-solid fa-heart" />
                        Wishlist
                    </ButtonLink>

                    <!------------ THEME TOGGLER BUTTON DROPDOWN --------------------------------------------------------------------->
                    <!-- <div class=" items-center hidden md:flex">
                        <ThemeToggler />
                    </div> -->
                </div>
            </div>
        </div>
    </nav>
</template>
