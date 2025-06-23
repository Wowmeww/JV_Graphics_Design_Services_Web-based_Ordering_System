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

    const props = defineProps({
        product: Object
    });

    const images = ref([null, null, null]);

    function submit() {
        form.images = [...images.value];

        console.log(form.images);
    }

    function handleImagesChange(data) {
        images.value = [...data.images]
    }


</script>
<template>

    <Head title="View Product" />

    <form class="space-y-3 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product View" />

        <ContainerPrimary title="Product Information VIew">
            <div class="pt-2 grid md:grid-cols-2 gap-6">
                <div class="space-y-3">

                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput disabled :images="product.images" @changed="handleImagesChange" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Size</p>
                                <p class="form-control-secondary">{{ product.size }}</p>
                            </div>
                        </div>
                        <div class="col-span-4">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Unit</p>
                                <p class="form-control-secondary ">{{ product.unit }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Description</p>
                        <p class="form-control-secondary min-h-26 h-fit">{{ product.description }}</p>
                    </div>
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Name</p>
                        <p class="form-control-secondary">{{ product.name }}</p>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-2 gap-y-3">
                        <div class="form-group flex flex-col">
                            <p class="input-label">Category</p>
                            <p class="form-control-secondary">{{ product.category.name }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Price</p>
                            <p class="form-control-secondary">{{ product.price }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Product Type</p>
                            <p class="form-control-secondary">{{ product.type }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Stock</p>
                            <p class="form-control-secondary">{{ product.stock }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="input-label mb-2 mt-2 inline-block">
                            Variant/s
                        </label>
                        <div class="max-h-90 h-fit overflow-y-scroll space-y-2 rounded-xl pr-3">
                            <Product v-for="opt of product.options" :key="opt.id" :product="opt" />

                            <button type="button"
                                class="container-primary border-2 text-secondary border-secondary dark:border-white/10 rounded-xl bg-white p-4 text-4xl dark:text-secondary-100 :text-6xl dark:bg-[#1e293b] w-full min-w-fit text-wrap transition duration-1000 hover:bg-secondary-200/60 dark:hover:bg-secondary-200/10">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
            <div class="py-6 pt-8 grid grid-cols-2 gap-3 max-w-3xl mx-auto ">
                <PillPrimary is="Link" :href="route('product.edit', product)" label="Edit" variant="secondary"
                    type="button" />
                <PillPrimary is="Link" :href="route('product.index')" label="Back" variant="outlineSecondary" />

            </div>
        </ContainerPrimary>
    </form>
</template>
