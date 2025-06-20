<script setup>
    import { Head, Link, usePage } from '@inertiajs/vue3';
    import BackgroundImage from '@/components/ui/BackgroundImage.vue';
    import LargePill from '@/components/ui/buttons/LargePill.vue';
    import LargeCategoryCard from '@/components/ui/card/LargeCategoryCard.vue';
    import Layout from '@/composables/layout.js';

    defineOptions({
        layout: Layout
    });


    const props = defineProps({
        products: Array,
        categories: Array
    });

</script>

<template>

    <Head title="Welcome" />


    <div class="h-[95vh] max-h-[840px] w-full text-center flex items-center ">
        <BackgroundImage class="h-[95vh] max-h-[840px] w-full" />
        <div
            class="animate__animated animate__slideInDown animate__faster bg-secondary/20 w-full py-10 mb-7 text-white space-y-8">
            <h1 class="text-5xl font-semibold  md:text-6xl">Services. Offers. Products</h1>
            <LargePill label="Check our products" />
        </div>
    </div>
    <div v-if="true" class="py-10 -mx-1 px-3 relative overflow-hidden -mb-7 bottom-0">
        <BackgroundImage path="images/background/blob.jpg" class="h-full" />

        <h3 class="text-light text-center mt-5 mb-20">Products and Services</h3>

        <div class="grid justify-center gap-6">
            <LargeCategoryCard v-for="(product, i) in products" :key="product.id" :product="product"
                :variant="i % 2 === 1 ? 'one' : 'two'" />
        </div>

    </div>

</template>