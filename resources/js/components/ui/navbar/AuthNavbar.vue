<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import UserProfile from './UserProfile.vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const isDropdownOpen = ref(false);
const page = usePage();
const settings = computed(() => page.props.settings);
function toggleDropdown() {
    isDropdownOpen.value = !isDropdownOpen.value;
}

function closeDropdown() {
    isDropdownOpen.value = false;
}

const emit = defineEmits(['toggle', 'openCart', 'openWishlist']);

function handleToggle() {
    emit('toggle');
}
</script>

<template>
    <nav class="fixed top-0 z-50 w-full border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button
                        @click="handleToggle"
                        type="button"
                        class="hover:bg-secondary-100/60 focus:ring-secondary-200 dark:hover:bg-secondary-400/20 dark:focus:ring-secondary-400 inline-flex items-center rounded-lg p-2 text-sm text-gray-500 transition focus:ring-2 focus:outline-none dark:text-gray-400"
                        aria-controls="logo-sidebar"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>
                    <div class="ms-2 flex items-center gap-3 md:me-24">
                        <Link :href="route('dashboard')">
                            <img class="h-6 w-auto rounded-full sm:h-7" :src="settings.app_logo" alt="logo" />
                        </Link>
                        <Link :href="route('home')" class="self-center text-lg font-semibold whitespace-nowrap sm:text-2xl dark:text-white">
                            <span class="hidden md:inline">
                                {{ settings.app_name }}
                            </span>
                            <span class="md:hidden">
                                {{ settings.app_name_short }}
                            </span>
                        </Link>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ms-3 flex items-center">
                        <UserProfile @openCart="() => emit('openCart')" @openWishlist="() => emit('openWishlist')" />
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
