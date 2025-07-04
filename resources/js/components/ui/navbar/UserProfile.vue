<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { ref, onMounted, onBeforeUnmount, watch, computed } from 'vue';
    import ThemToggler from './ThemeToggler.vue';
    import { router } from '@inertiajs/vue3'

    const page = usePage();
    const user = page.props.auth?.user;
    const user_avatar_path = computed(() => {
        if (user.avatar_url) {
            return user.avatar_url.includes('product_images')
                ? `/storage/${user.avatar_url}`
                : user.avatar_url;
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
            action: {
                logout: true
            },
        }
    }
    watch(page, () => {
        if (page.props.confirm.confirmed && page.props.confirm.action.logout) {
            router.post(route('logout'));
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
        name: 'text-sm text-gray-900 dark:text-white',
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
            <ThemToggler />
            <button type="button" @click.stop="toggleDropdown" :class="styleClass.profile"
                :aria-expanded="isDropdownOpen.toString()">
                <span class="sr-only">Open user menu</span>
                <img :class="styleClass.image" :src="user_avatar_path" alt="user photo" />
            </button>
        </div>

        <!-- Dropdown Menu -->
        <div v-show="isDropdownOpen" id="dropdown-user" :class="styleClass.dropdown">
            <div class="px-4 py-3" role="none">

                <p :class="styleClass.name">
                    {{ user.name }}
                </p>
                <p :class="styleClass.email">
                    {{ user.email }}
                </p>
            </div>
            <div class="py-1" role="none">

                <Link :href="route('dashboard')" :class="styleClass.dropdownButton" role="menuitem">
                Dashboard
                <i class="fa-solid fa-chart-pie"></i>
                </Link>
                <button type="button" @click="openCart" :class="styleClass.dropdownButton" role="menuitem">
                    Cart
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
                <button type="button" @click="openWishlist" :class="styleClass.dropdownButton" role="menuitem">
                    Wishlist
                    <i class="fa-solid fa-heart"></i>
                </button>

                <a href="#" :class="styleClass.dropdownButton" role="menuitem">
                    Settings <i class="fa-solid fa-gear"></i>
                </a>

                <button @click="logout" :class="styleClass.dropdownButton" role="menuitem">
                    Sign out <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>

            </div>
        </div>
    </div>
</template>
