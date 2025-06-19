<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { ref, onMounted, onBeforeUnmount } from 'vue';
    import ThemToggler from './ThemeToggler.vue';

    const page = usePage();
    const user = page.props.auth?.user;

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

    const styleClass = {
        name: 'text-sm text-gray-900 dark:text-white',
        email: 'text-sm font-medium text-gray-900 truncate dark:text-gray-300',
        profile: 'flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600',
        image: 'w-10 h-10 rounded-full ring-1 ring-secondary-400 dark:ring-secondary-200',
        dropdown: 'absolute right-0 z-50 mt-4 min-w-56 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-sm dark:bg-gray-700 dark:divide-gray-600',
        dropdownButton: 'flex justify-between px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white'
    }
</script>

<template>
    <div class="relative">
        <!-- Toggle Button -->
        <div class="flex gap-3 items-center">
            <ThemToggler />
            <button type="button" @click.stop="toggleDropdown" :class="styleClass.profile"
                :aria-expanded="isDropdownOpen.toString()">
                <span class="sr-only">Open user menu</span>
                <img :class="styleClass.image" :src="user.avatar_url" alt="user photo" />
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
            <ul class="py-1" role="none">
                <li>
                    <a href="#" :class="styleClass.dropdownButton" role="menuitem">
                        Dashboard
                        <i class="fa-solid fa-chart-pie"></i>
                    </a>
                </li>
                <li>
                    <a href="#" :class="styleClass.dropdownButton" role="menuitem">
                        Settings <i class="fa-solid fa-gear"></i>
                    </a>
                </li>

                <li>
                    <a href="#" :class="styleClass.dropdownButton" role="menuitem">
                        Sign out <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
