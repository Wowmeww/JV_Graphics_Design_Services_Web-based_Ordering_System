<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import Status from '../../../components/alert/Status.vue';
    import Confirm from '@/components/modal/Confirm.vue';

    const page = usePage();

    const status = computed(() => page.props.status);

    const component = computed(() => page.component);

    const sidebarOpen = ref(false);

    function logout() {
        page.props.confirm = {
            show: true,
            message: 'Do you want to logout?',
            confirmed: false,
            action: { logout: true },
        }
    }

    watch(page, () => {

        if (page.props.confirm.confirmed && page.props.confirm.action.logout) {
            router.delete(route('logout'));
        }
    });

    const styleClass = computed(() => ({
        aside: 'fixed top-0 pt-12 sm:pt-1 left-0 z-40 w-64 h-screen transition-transform bg-gray-50 dark:bg-gray-800 sm:translate-x-0',
        asideLink: 'flex ps-4 items-center p-2 text-gray-900 flex gap-3 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group w-full',
        asideLinkActive: 'bg-gray-100 dark:bg-gray-700'
    }));
</script>

<template>
    <!-- STATUS -->
    <Status :status="status" v-if="status" />
    <!-- ALERT -->
    <Confirm v-if="$page.props.confirm.show" />
    <div class="fixed z-50 w-full sm:hidden p-2 dark:bg-slate-900 bg-white">
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="inline-flex items-center p-2  text-sm text-gray-500 rounded-lg  hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z" />
            </svg>
        </button>
    </div>
    <div>
        <!-- Toggle Button for Mobile -->
        <!-- Sidebar -->
        <aside :class="[
            styleClass.aside,
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        ]" aria-label="Sidebar">
            <div class="h-full px-3 py-4 overflow-y-auto">

                <div class="space-y-2 font-medium">

                    <Link :href="route('dashboard')" :class="styleClass.asideLink" as="button">
                    <i class="fa-solid fa-chart-pie"></i>
                    Dashboard
                    </Link>


                    <Link :href="route('profile.edit')" as="button"
                        :class="component == 'profile/UserSettings' ? [styleClass.asideLink, styleClass.asideLinkActive] : styleClass.asideLink">
                    <i class="fa-solid fa-user"></i>
                    Profile
                    </Link>

                    <Link :href="route('password.edit')" as="button"
                        :class="component == 'profile/Password' ? [styleClass.asideLink, styleClass.asideLinkActive] : styleClass.asideLink">
                    <i class="fa-solid fa-key"></i>
                    Password
                    </Link>



                    <Link :href="route('appearance.edit')" as="button"
                        :class="component == 'profile/Appearance' ? [styleClass.asideLink, styleClass.asideLinkActive] : styleClass.asideLink">
                    <i class="fa-solid fa-desktop"></i>
                    Appearance
                    </Link>


                    <button @click="logout" :class="styleClass.asideLink">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Sign out
                    </button>

                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="p-4 pt-20 sm:pt-4 sm:ml-64">
            <slot />
        </div>
    </div>
</template>
