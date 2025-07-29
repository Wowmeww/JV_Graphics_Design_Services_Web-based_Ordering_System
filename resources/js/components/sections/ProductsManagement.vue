<script setup>
    import Status from '../alert/Status.vue';
    import Paginator from '../tables/Paginator.vue';
    import ProductFilters from '../tables/ProductFilters.vue';
    import ProductsTable from '../tables/ProductsTable.vue';
    import PageTitleHeader from '../ui/PageTitleHeader.vue';

    defineProps({
        products: Object,
        categories: Array,
        status: Object,
        productTypes: Array
    });
</script>

<template>
    <div class="pt-8 px-2">
        <PageTitleHeader title="Product Management" />

        <!---------------------------------- FILTERS ----------------------------------------------->
        <ProductFilters :categories="categories" :productTypes="productTypes" />

        <!---------------------------------- Status ----------------------------------------------->
        <!-- <Status :status="$page.props.status" @close="$page.props.status = null" /> -->
        <!---------------------------------- TABLE ----------------------------------------------->
        <ProductsTable :products="products" />

        <!-- Paginator -->
        <!---------------------------------- Paginator ----------------------------------------------->
        <Paginator :paginator="products" />
    </div>
</template>
