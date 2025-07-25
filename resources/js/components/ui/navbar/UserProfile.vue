<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { ref, onMounted, onBeforeUnmount, watch, computed } from 'vue';
    import ThemToggler from './ThemeToggler.vue';
    import { router } from '@inertiajs/vue3'

    const page = usePage();
    const user = page.props.auth?.user;
    const user_avatar_path = computed(() => {
        if (user.avatar_url) {
            return user.avatar_url.includes('https') ? user.avatar_url : `/storage/${user.avatar_url}`
        }
        return '/images/avatar-placeholder.webp';
    });


    const isDropdownOpen = ref(false);

    function toggleDropdown() {
        isDropdownOpen.value = !isDropdownOpen.value;
    }

    function closeDropdown(event) {
        const dropdown = document.getElementById('dropdown-user');
        if (!dropdown || dropdown.contains(event.target)) return;

        isDropdownOpen.value = false;
    }


    onMounted(() => {
        document.addEventListener('click', closeDropdown);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', closeDropdown);
    });

    function logout() {
        page.props.confirm = {
            show: true,
            message: 'Do you want to logout?',
            confirmed: false,
            action: { logout: true }
        }
    }
    watch(page, () => {
        let confirm = page.props.confirm;
        if (confirm.confirmed && confirm.action.logout) {
            router.delete(route('logout'));
        }
    });

    const emit = defineEmits(['openCart', 'openWishlist']);

    function openCart() {
        toggleDropdown();
        emit('openCart');
    }
    function openWishlist() {
        toggleDropdown();
        emit('openWishlist');
    }

    const styleClass = {
        name: 'text-sm text-gray-900 dark:text-white flex items-center justify-between min-w-54',
        email: 'text-sm font-medium text-gray-900 truncate dark:text-gray-300',
        profile: 'flex w-10 h-10 text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600',
        image: 'h-full w-full rounded-full ring-1 ring-secondary-400 dark:ring-secondary-200',
        dropdown: 'absolute right-0 z-50 mt-4 min-w-56 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm dark:bg-gray-700 dark:divide-gray-600',
        dropdownButton: 'flex w-full cursor-pointer justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white'
    }

</script>

<template>
    <div v-if="user" class="relative">
        <!-- Toggle Button -->
        <div class="flex gap-3 items-center">
            <button @click="openCart" v-if="!user.is_admin">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4H6L7 8M7 8L9 16H17L19 8H7Z" stroke="#1E293B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <circle cx="9" cy="19" r="1" fill="#1E293B" />
                    <circle cx="17" cy="19" r="1" fill="#1E293B" />
                    <path d="M3 4H5.5L7.5 12H18" stroke="#1E293B" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
            <button>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 2C10.8954 2 10 2.89543 10 4V4.28988C7.10851 4.88449 5 7.54258 5 10.618V15.764L4 16.764V17H20V16.764L19 15.764V10.618C19 7.54258 16.8915 4.88449 14 4.28988V4C14 2.89543 13.1046 2 12 2Z"
                        fill="#334155" />
                    <path d="M9 17V18C9 19.6569 10.3431 21 12 21C13.6569 21 15 19.6569 15 18V17H9Z" fill="#334155" />
                    <circle cx="17" cy="6" r="3" fill="#EF4444" stroke="white" stroke-width="1.5" />
                </svg>
            </button>
            <ThemToggler />
            <button type="button" @click.stop="toggleDropdown" :class="styleClass.profile"
                :aria-expanded="isDropdownOpen.toString()">
                <span class="sr-only">Open user menu</span>
                <img :class="styleClass.image" :src="user_avatar_path" alt="user photo" />
            </button>
        </div>

        <!-- Dropdown Menu -->
        <div v-show="isDropdownOpen" id="dropdown-user" :class="styleClass.dropdown">
            <Link as="button" type="button" :href="route('profile.edit')"
                class="px-4 py-3 block w-full text-left hover:bg-slate-100 dark:hover:bg-gray-600">

            <p :class="styleClass.name">
                <span>
                    {{ user.name }}
                </span>
                <i class="fa-solid fa-pen-to-square"></i>
            </p>
            <p :class="styleClass.email">
                {{ user.email }}
            </p>


            </Link>
            <div class="py-1" role="none">

                <Link :href="route('dashboard')" :class="styleClass.dropdownButton" role="menuitem">
                Dashboard
                <i class="fa-solid fa-chart-pie"></i>
                </Link>
                <button v-if="!user.is_admin" type="button" @click="openCart" :class="styleClass.dropdownButton"
                    role="menuitem">
                    Cart
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
                <button v-if="!user.is_admin" type="button" @click="openWishlist" :class="styleClass.dropdownButton"
                    role="menuitem">
                    Wishlist
                    <i class="fa-solid fa-heart"></i>
                </button>

                <Link as="button" type="button" :href="route('appearance.edit')" :class="styleClass.dropdownButton"
                    role="menuitem">
                Settings <i class="fa-solid fa-gear"></i>
                </Link>

                <button @click="logout" :class="styleClass.dropdownButton" role="menuitem">
                    Sign out <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>

            </div>
        </div>
    </div>
</template>
