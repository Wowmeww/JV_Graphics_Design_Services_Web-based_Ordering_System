<script setup>
    import Navbar from '../components/ui/navbar/Navbar.vue';
    import Footer from '../components/ui/footer/Footer.vue';

</script>

<template>
    <Navbar />

    <main class="px-[4px] py-8 h-full min-h-screen">
        <slot />
    </main>
    <Footer />
</template>
