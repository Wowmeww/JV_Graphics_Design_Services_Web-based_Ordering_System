<script setup>
    import ProductsManagement from '@/components/sections/ProductsManagement.vue';

    defineProps({
        products: Object,
        categories: Array,
        status: Object,
        productTypes: Array
    });
</script>

<template>

    <Head title="Products Management" />
    <ProductsManagement :status="status" :productTypes="productTypes" :products="products" :categories="categories" />
</template>
