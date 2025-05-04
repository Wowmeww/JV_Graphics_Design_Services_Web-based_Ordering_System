<script
    setup
    lang="ts"
>
    import FooterLink from './FooterLink.vue';

</script>

<template>
    <footer class="bg-white dark:bg-gray-900 animate__animated animate__slideInUp">
        <div class="container px-6 py-8 mx-auto">

            <div class="flex flex-wrap justify-center mt-3 -mx-4">
                <FooterLink label="Home" routeName="register" />
                <FooterLink label="About" routeName="register" />
                <FooterLink label="Privacy" routeName="register" />
                <FooterLink label="Cookies" routeName="register" />
            </div>

            <hr class="my-6 border-gray-200 md:my-10 dark:border-gray-700" />

            <div class="flex flex-col gap-3 items-center sm:flex-row sm:justify-between px-6">
                <p class="text-sm text-gray-500 dark:text-gray-300">© 2025 JV Graphics Design Services. All rights
                    reserved.</p>

                <div class="flex gap-3 -mx-2 text-xl">
                    <i class="fa-brands fa-facebook" />
                    <i class="fa-brands fa-github" />
                </div>
            </div>
        </div>
    </footer>
</template>