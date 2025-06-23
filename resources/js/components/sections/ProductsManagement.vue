<script setup>
    import ProductsTable from '../tables/ProductsTable.vue';
    import Paginator from '../tables/Paginator.vue';
    import ProductFilters from '../tables/ProductFilters.vue';
    import PageTitleHeader from '../ui/PageTitleHeader.vue';
    import Status from '../alert/Status.vue';

    defineProps({
        products: Object,
        categories: Array,
        status: Object
    });

</script>

<template>
    <PageTitleHeader title="Product Management" />

    <!---------------------------------- FILTERS ----------------------------------------------->
    <ProductFilters :categories="categories" />

    <!---------------------------------- Status ----------------------------------------------->
    <Status :status="$page.props.status" @close="$page.props.status = null" />
    <!---------------------------------- TABLE ----------------------------------------------->
    <ProductsTable :products="products" />

    <!-- Paginator -->
    <!---------------------------------- Paginator ----------------------------------------------->
    <Paginator :products="products" />
</template>
