<script setup>
import { usePage } from '@inertiajs/vue3';
import FooterLink from './FooterLink.vue';
import axios from 'axios';
import { reactive } from 'vue';
const page = usePage();

const app = reactive({
    name: '',
});

axios.get(route('page.settings', ['app_name'])).then((res) => {
    if (res.status === 200) {
        app.name = res.data;
    }
});
</script>

<template>
    <footer class="animate__animated animate__slideInUp z-40 bg-white shadow-sm dark:bg-gray-900">
        <div class="container mx-auto px-6 py-8">
            <div class="-mx-4 mt-3 flex flex-wrap justify-center">
                <FooterLink label="Home" routeName="home" />
                <FooterLink label="About" routeName="about" />
                <FooterLink label="Privacy" routeName="register" />
                <!-- <FooterLink label="Cookies" routeName="register" /> -->
            </div>

            <hr class="my-6 border-gray-200 md:my-10 dark:border-gray-700" />

            <div class="flex flex-col items-center gap-3 px-6 sm:flex-row sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-300">© 2025 {{ app.name }}. All rights reserved.</p>

                <div class="-mx-2 flex items-center gap-3 text-xl">
                    <!-- <i class="fa-brands fa-facebook" /> -->
                    <a :href="page.props.github" target="_blank">
                        <i class="fa-brands fa-github" />
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
