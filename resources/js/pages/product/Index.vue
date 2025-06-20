<script setup>

    import ProductsManagement from '@/components/sections/ProductsManagement.vue'

    defineProps({
        products: Object,
        categories: Array
    });
</script>

<template>

    <Head title="Products Management" />
    <ProductsManagement :products="products" :categories="categories" />
</template>
