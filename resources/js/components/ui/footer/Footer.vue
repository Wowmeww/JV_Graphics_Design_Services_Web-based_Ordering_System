<script setup>
import { usePage } from '@inertiajs/vue3';
import FooterLink from './FooterLink.vue';
import axios from 'axios';
import { computed, reactive } from 'vue';
const page = usePage();
const component = computed(() => page.component);

const settings = computed(() => page.props.settings);
</script>

<template>
    <footer class="z-60 bg-white shadow-sm dark:bg-gray-900">
        <div class="container mx-auto px-6 py-8">
            <div class="-mx-4 mt-3 flex flex-wrap justify-center">
                <FooterLink label="Home" :active="component === 'Welcome'" routeName="home" />
                <FooterLink label="About" :active="component === 'About'" routeName="about" />
                <FooterLink label="Privacy & Cookie policy" :active="component === 'Privacy'" routeName="privacy" />
                <!-- <FooterLink label="Cookies" routeName="register" /> -->
            </div>

            <hr class="my-6 border-gray-200 md:my-10 dark:border-gray-700" />

            <div class="flex flex-col items-center gap-3 px-6 sm:flex-row sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-300 text-center lg:text-left">
                    © {{ new Date().getFullYear() }} {{ settings.app_name }}. All rights reserved.
                </p>

                <div class="-mx-2 flex items-center gap-3 text-xl">
                    <a :href="settings.facebook_page" target="_blank">
                        <i class="fa-brands fa-facebook" />
                    </a>
                    <!-- <a :href="page.props.github" target="_blank">
                        <i class="fa-brands fa-github" />
                    </a> -->
                </div>
            </div>
        </div>
    </footer>
</template>
