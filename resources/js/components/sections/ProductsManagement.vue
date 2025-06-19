<script setup>
    import ProductsTable from '../tables/ProductsTable.vue';
    import Paginator from '../tables/Paginator.vue';
    import ProductFilters from '../tables/ProductFilters.vue';
    import PageTitleHeader from '../ui/PageTitleHeader.vue';

    defineProps({
        products: Object,
        categories: Array
    });
   
</script>

<template>
    <PageTitleHeader title="Product Management" />

    <!---------------------------------- FILTERS ----------------------------------------------->
    <ProductFilters :categories="categories" />

    <!---------------------------------- TABLE ----------------------------------------------->
    <ProductsTable :products="products" />

    <!-- Paginator -->
    <!---------------------------------- Paginator ----------------------------------------------->
    <Paginator :products="products" />
</template>
