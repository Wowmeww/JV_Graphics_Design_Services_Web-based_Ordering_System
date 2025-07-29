<script setup>
    import Status from '@/components/alert/Status.vue';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
    import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
    import Product from '@/components/ui/card/Product.vue';
    import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';

    const props = defineProps({
        option: Object,
    });

    function goBack() {
        window.history.back();
    }
</script>
<template>

    <Head title="View Product Variant" />
    <div class="space-y-3 md:space-y-8 py-10 max-w-7xl mx-auto">
        <PageTitleHeader title="Variant View" />
        <!---------- STATUS ALERT ------------------------------------------------------->
        <!-- <Status :status="$page.props.status" @close="() => ($page.props.status = null)" /> -->

        <ContainerPrimary title="Product Variant Information View">
            <div class="grid gap-6 pt-2 md:grid-cols-2">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput disabled :images="option.images" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Size</p>
                                <p class="form-control form-control-secondary">{{ option.size }}</p>
                            </div>
                        </div>
                        <div class="col-span-4">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Unit</p>
                                <p class="form-control form-control-secondary">{{ option.unit }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Description</p>
                        <p class="form-control form-control-secondary h-fit min-h-26">{{ option.description }}</p>
                    </div>
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Variant Name</p>
                        <p class="form-control form-control-secondary">{{ option.name }}</p>
                    </div>
                    <div class="grid gap-2 gap-y-3 sm:grid-cols-2">
                        <div class="form-group flex flex-col">
                            <p class="input-label">Category</p>
                            <p class="form-control form-control-secondary">{{ option.product.category.name }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Price</p>
                            <p class="form-control form-control-secondary">{{ option.price }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Product Type</p>
                            <p class="form-control form-control-secondary">{{ option.type }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Stock</p>
                            <p class="form-control form-control-secondary">{{ option.stock }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="input-label mt-2 mb-2 inline-block"> Parent product </label>
                        <div class="h-fit max-h-90 space-y-2 rounded-xl">
                            <Product :product="option.product" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-auto grid max-w-3xl grid-cols-2 gap-3 py-6 pt-8">
                <PillPrimary is="Link" :href="route('product.option.edit', { product: option.product, option })"
                    label="Edit" variant="secondary" type="button" />
                <PillPrimary @click="goBack()" label="Back" variant="outlineSecondary" />
            </div>
        </ContainerPrimary>
    </div>
</template>
