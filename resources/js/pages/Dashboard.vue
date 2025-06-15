<script setup>

    import ProductsManagement from '../components/sections/ProductsManagement.vue';

    defineProps({
        products: Object
    });
</script>

<template>

    <Head title="Dashboard" />
    <div class="px-4">
        <ProductsManagement :products="products" />
    </div>
</template>