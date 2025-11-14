<script setup>    ;
    import Navbar from '../components/ui/navbar/Navbar.vue';
    import Footer from '../components/ui/footer/Footer.vue';

</script>

<template>
    <Navbar />
    <main class="min-h-screen relative px-1.5">
        <slot />
    </main>
    <Footer />
</template>
