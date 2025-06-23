<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
    import Dropdown from '@/components/ui/input/Dropdown.vue';
    import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
    import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
    import Product from '@/components/ui/card/Product.vue';
    import { ref } from 'vue';
    import { route } from 'ziggy-js';

    const props = defineProps({
        option: Object
    });

    const images = ref([null, null, null]);


    function handleImagesChange(data) {
        images.value = [...data.images]
    }


</script>
<template>
    <!-- {{ console.dir(option) }} -->

    <Head title="View Product Option" />

    <form class="space-y-3 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product Option View" />

        <ContainerPrimary title="Product Option Information View">
            <div class="pt-2 grid md:grid-cols-2 gap-6">
                <div class="space-y-3">

                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput disabled :images="option.images" @changed="handleImagesChange" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Size</p>
                                <p class="form-control-secondary">{{ option.size }}</p>
                            </div>
                        </div>
                        <div class="col-span-4">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Unit</p>
                                <p class="form-control-secondary ">{{ option.unit }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Description</p>
                        <p class="form-control-secondary min-h-26 h-fit">{{ option.description }}</p>
                    </div>
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Name</p>
                        <p class="form-control-secondary">{{ option.name }}</p>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-2 gap-y-3">
                        <div class="form-group flex flex-col">
                            <p class="input-label">Category</p>
                            <p class="form-control-secondary">{{ option.product.category.name }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Price</p>
                            <p class="form-control-secondary">{{ option.price }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Product Type</p>
                            <p class="form-control-secondary">{{ option.type }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Stock</p>
                            <p class="form-control-secondary">{{ option.stock }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="input-label mb-2 mt-2 inline-block">
                            Parent/owner Product
                        </label>
                        <div class="max-h-90 h-fit overflow-y-scroll space-y-2 rounded-xl pr-3">
                            <Product :product="option.product" />
                        </div>
                    </div>


                </div>
            </div>
            <div class="py-6 pt-8 grid md:grid-cols-3 gap-3 max-w-3xl mx-auto ">
                <PillPrimary is="Link" :href="route('option.edit', option)" label="Edit" variant="secondary" type="button" />
                <PillPrimary label="Cancel" variant="outlineSecondary" />
                <PillPrimary label="Delete" variant="outlineSecondary"
                    :style="'dark:!bg-red-600/70 !bg-red-600/90  hover:!opacity-80 text-white'" />
            </div>
        </ContainerPrimary>
    </form>
</template>
