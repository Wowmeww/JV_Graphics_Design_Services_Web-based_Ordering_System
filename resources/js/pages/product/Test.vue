<script setup>
    // import ProductsManagement from '@/components/sections/ProductsManagement.vue'

    defineProps({
        products: Object,
        categories: Array
    });
</script>

<template>
   {{ products }}
</template>