<script setup>
import ProductsManagement from '@/components/sections/ProductsManagement.vue';

defineProps({
    products: Object,
    categories: Array,
    status: Object,
});
</script>

<template>
    <Head title="Products Management" />
    <ProductsManagement :status="status" :products="products" :categories="categories" />
</template>
