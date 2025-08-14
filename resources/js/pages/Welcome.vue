<script setup>
import BackgroundImage from '@/components/ui/BackgroundImage.vue';
import LargePill from '@/components/ui/buttons/LargePill.vue';
import LargeCategoryCard from '@/components/ui/card/LargeCategoryCard.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    products: Object,
    categories: Array,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="">
        <div class="flex h-[95vh] max-h-[840px] w-full items-center text-center">
            <BackgroundImage class="h-[95vh] max-h-[840px] w-full" />
            <div class="animate__animated animate__slideInDown animate__faster bg-secondary/20 mb-7 w-full space-y-8 py-10 text-white">
                <h1 class="text-5xl font-semibold md:text-6xl">Services. Offers. Products</h1>

                <Link :href="route('shop.index')">
                    <LargePill label="Check our products" />
                </Link>
            </div>
        </div>

        <div v-if="Object.keys(products).length" class="bottom-0-mx-2 relative -mb-7 py-8 pb-20">
            <BackgroundImage path="images/background/blob.jpg" class="h-full" />

            <h3 class="text-light mt-5 mb-20 text-center">Products and Services</h3>

            <div class="grid justify-center gap-6 px-2">
                <LargeCategoryCard v-for="(product, i) in products" :key="i" :product="product" :variant="Number(i)" />
            </div>
        </div>
    </div>
</template>
