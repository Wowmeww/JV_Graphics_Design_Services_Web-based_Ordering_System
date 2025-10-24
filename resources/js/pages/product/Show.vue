<script setup>
import Status from '@/components/alert/Status.vue';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
import Product from '@/components/ui/card/Product.vue';
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';

const props = defineProps({
    product: Object,
});

function goBack() {
    window.history.back();
}
</script>
<template>
    <Head title="View Product" />
    <div class="mx-auto max-w-7xl space-y-3 pt-10 pb-7 md:space-y-8">
        <PageTitleHeader title="Product View" />
        <!---------- STATUS ALERT ------------------------------------------------------->
        <!-- <Status :status="$page.props.status" @close="() => ($page.props.status = null)" /> -->

        <ContainerPrimary title="Product Information View">
            <div class="grid gap-6 pt-2 md:grid-cols-2">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput disabled :images="product.images" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-12">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Size</p>
                                <p class="form-control form-control-secondary">{{ product.show_size }}</p>
                            </div>
                        </div>
                        <!-- <div class="col-span-4">
                            <div class="form-group flex flex-col">
                                <p class="input-label">Unit</p>
                                <p class="form-control form-control-secondary">{{ product.unit }}</p>
                            </div>
                        </div> -->
                    </div>
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Description</p>
                        <p class="form-control form-control-secondary h-fit min-h-26">{{ product.description }}</p>
                    </div>
                </div>
                <!-- Another Column -->
                <div class="space-y-3">
                    <div class="form-group flex flex-col">
                        <p class="input-label">Product Name</p>
                        <p class="form-control form-control-secondary">{{ product.name }}</p>
                    </div>
                    <div class="grid gap-2 gap-y-3 sm:grid-cols-2">
                        <div class="form-group flex flex-col">
                            <p class="input-label">Category</p>
                            <p class="form-control form-control-secondary">{{ product.category.name }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Price</p>
                            <p class="form-control form-control-secondary">{{ product.price }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Product Type</p>
                            <p class="form-control form-control-secondary">{{ product.type }}</p>
                        </div>

                        <div class="form-group flex flex-col">
                            <p class="input-label">Stock</p>
                            <p class="form-control form-control-secondary">{{ product.stock }}</p>
                        </div>
                    </div>
                    <div v-if="product.type === 'main product with variant'">
                        <label class="input-label mt-2 mb-2 inline-block"> Variant/s </label>
                        <div class="h-fit max-h-90 space-y-2 overflow-y-auto rounded-xl pr-3">
                            <Product v-for="opt of product.options" :key="opt.id" :product="opt" />
                            <!---------- OPTION CREATE LINK ------------------------------------------------------->
                            <Link
                                :href="route('product.option.create', { product: product.id })"
                                type="button"
                                class="container-primary text-secondary border-secondary dark:text-secondary-100 :text-6xl hover:bg-secondary-200/60 dark:hover:bg-secondary-200/10 inline-block w-full min-w-fit rounded-xl border-2 bg-white p-4 text-center text-4xl text-wrap transition duration-1000 dark:border-white/10 dark:bg-[#1e293b]"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </Link>
                        </div>
                    </div>
                    <div v-else class="flex h-30 flex-wrap items-center justify-center px-5 text-wrap">
                        <label class="input-label mt-2 mb-2 inline-block font-semibold">
                            To add a variant update the "type" of this product to (Main product with variant)
                        </label>
                    </div>
                </div>
            </div>
            <div class="mx-auto grid max-w-3xl grid-cols-2 gap-3 py-6 pt-8">
                <PillPrimary is="Link" :href="route('product.edit', product)" label="Edit" variant="secondary" type="button" />
                <PillPrimary is="Link" :href="route('product.index')" label="Back" variant="outlineSecondary" />
            </div>
        </ContainerPrimary>
    </div>
</template>
