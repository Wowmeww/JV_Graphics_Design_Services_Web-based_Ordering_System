<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue';
    import UserProfile from './UserProfile.vue';

    const isDropdownOpen = ref(false);

    function toggleDropdown() {
        isDropdownOpen.value = !isDropdownOpen.value;
    }

    function closeDropdown() {
        isDropdownOpen.value = false;
    }

    const emit = defineEmits(['toggle', 'openCart', 'openWishlist']);

    function handleToggle() {
        emit('toggle')
    }

</script>

<template>
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between ">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button @click="handleToggle" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-secondary-100/60 transition focus:outline-none focus:ring-2 focus:ring-secondary-200 dark:text-gray-400 dark:hover:bg-secondary-400/20 dark:focus:ring-secondary-400"
                        aria-controls="logo-sidebar">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <Link :href="route('home')" class="flex gap-3 items-center ms-2 md:me-24">
                    <img class="w-auto h-6 sm:h-7" src="/favicon.png" alt="logo">
                    <p class="self-center text-lg font-semibold sm:text-2xl whitespace-nowrap dark:text-white">
                        <span class="hidden md:inline">JV Graphics Design Services</span>
                        <span class="md:hidden">JV Graphics</span>
                    </p>
                    </Link>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <UserProfile
                            @openCart="() => emit('openCart')"
                            @openWishlist="() => emit('openWishlist')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </nav>


</template>