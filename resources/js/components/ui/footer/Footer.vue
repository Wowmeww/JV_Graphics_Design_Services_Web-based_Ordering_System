<script setup>

    import { usePage } from '@inertiajs/vue3';
    import FooterLink from './FooterLink.vue';
    const page = usePage();
</script>

<template>
    <footer class="bg-white dark:bg-gray-900 animate__animated animate__slideInUp z-40">
        <div class="container px-6 py-8 mx-auto">

            <div class="flex flex-wrap justify-center mt-3 -mx-4">
                <FooterLink label="Home" routeName="home" />
                <FooterLink label="About" routeName="about" />
                <FooterLink label="Privacy" routeName="register" />
                <FooterLink label="Cookies" routeName="register" />
            </div>

            <hr class="my-6 border-gray-200 md:my-10 dark:border-gray-700" />

            <div class="flex flex-col gap-3 items-center sm:flex-row sm:justify-between px-6">
                <p class="text-sm text-gray-500 dark:text-gray-300">© 2025 JV Graphics Design Services. All rights
                    reserved.</p>

                <div class="flex items-center gap-3 -mx-2 text-xl">
                    <i class="fa-brands fa-facebook" />
                    <a :href="page.props.github" target="_blank" >
                        <i class="fa-brands fa-github" />
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>