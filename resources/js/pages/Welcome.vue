<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import BackgroundImage from '../components/ui/BackgroundImage.vue';
    import LargePill from '../components/ui/buttons/LargePill.vue';
</script>

<template>

    <Head title="Welcome" />
    <BackgroundImage class="max-h-fit" />

    <div class="h-[95vh] w-full text-center flex items-center">
        <div
            class="animate__animated animate__slideInDown animate__faster bg-secondary/20 w-full py-10 mb-7 text-white space-y-8">
            <h1 class="text-5xl font-semibold  md:text-6xl">Services. Offers. Products</h1>
            <LargePill label="Check our products" />
        </div>
    </div>
    <div class="py-10 -mx-1 px-3 relative overflow-hidden -mb-7">
        <BackgroundImage path="storage/images/background/blob.jpg" class="h-full"/>
        <h1 class="text-2xl text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis animi,
            perspiciatis adipisci enim dolore illum in ipsa repellendus recusandae vero porro ea eos non labore
            corporis, alias voluptatum tenetur nulla?</h1>
       
    </div>

</template>