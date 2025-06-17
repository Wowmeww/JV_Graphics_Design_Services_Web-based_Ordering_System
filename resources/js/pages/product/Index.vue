<script setup>

    import ProductsManagement from '../../components/sections/ProductsManagement.vue'

    defineProps({
        products: Object,
        categories: Array
    });
</script>

<template>

    <Head title="Products Management" />
    <div class="px-4">
        <ProductsManagement :products="products" :categories="categories" />
    </div>
</template>