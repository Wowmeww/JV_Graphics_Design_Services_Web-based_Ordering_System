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

const styleClass = {
    // Main container
    mainContainer: 'mx-auto max-w-7xl space-y-6 pt-8 pb-7 md:space-y-8 px-4',

    // Product information container
    productInfo: {
        grid: 'grid gap-6 pt-4 md:grid-cols-2 md:gap-8',
        column: 'space-y-4',
        imagesSection: 'space-y-4',
    },

    // Size grid
    sizeGrid: 'grid grid-cols-12 gap-3 pt-4',
    sizeFull: 'col-span-12',

    // Form groups
    formGroup: 'form-group flex flex-col space-y-2',
    inputLabel: 'input-label text-sm font-semibold text-gray-700 dark:text-gray-300',
    formControl:
        'form-control form-control-secondary bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3 text-gray-800 dark:text-gray-200 min-h-12',

    // Right column grid
    rightColumnGrid: 'grid gap-3 sm:grid-cols-2',

    // Variants section
    variants: {
        container: 'mt-4',
        label: 'input-label mt-2 mb-3 inline-block font-semibold text-gray-700 dark:text-gray-300',
        variantsList: 'h-fit max-h-90 space-y-3 overflow-y-auto rounded-xl pr-2',
        addVariantLink:
            'container-primary text-secondary border-secondary dark:text-secondary-100 hover:bg-secondary-50 dark:hover:bg-secondary-900/20 inline-block w-full rounded-xl border-2 bg-white p-4 text-center transition-colors duration-300 dark:border-gray-600 dark:bg-gray-800 text-2xl',
    },

    // No variants message
    noVariants: 'flex h-30 flex-wrap items-center justify-center px-4 text-center',
    noVariantsLabel: 'input-label mt-2 mb-2 inline-block font-medium text-gray-600 dark:text-gray-400 text-sm',

    // Action buttons
    actionButtons: 'mx-auto grid max-w-3xl grid-cols-2 gap-4 py-6 pt-8',
};
</script>

<template>
    <Head title="View Product" />

    <div :class="styleClass.mainContainer">
        <PageTitleHeader title="Product View" />

        <ContainerPrimary title="Product Information View">
            <!-- Two Column Grid -->
            <div :class="styleClass.productInfo.grid">
                <!-- Left Column -->
                <div :class="styleClass.productInfo.column">
                    <!-- Product Images -->
                    <div :class="styleClass.productInfo.imagesSection">
                        <ProductImagesInput disabled :images="product.images" />
                    </div>

                    <!-- Size -->
                    <div :class="styleClass.sizeGrid">
                        <div :class="styleClass.sizeFull">
                            <div :class="styleClass.formGroup">
                                <p :class="styleClass.inputLabel">Size</p>
                                <p :class="styleClass.formControl">{{ product.show_size }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div :class="styleClass.formGroup">
                        <p :class="styleClass.inputLabel">Product Description</p>
                        <p :class="styleClass.formControl + ' h-fit min-h-24'">{{ product.description }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div :class="styleClass.productInfo.column">
                    <!-- Product Name -->
                    <div :class="styleClass.formGroup">
                        <p :class="styleClass.inputLabel">Product Name</p>
                        <p :class="styleClass.formControl">{{ product.name }}</p>
                    </div>

                    <!-- Product Details Grid -->
                    <div :class="styleClass.rightColumnGrid">
                        <div :class="styleClass.formGroup">
                            <p :class="styleClass.inputLabel">Category</p>
                            <p :class="styleClass.formControl">{{ product.category.name }}</p>
                        </div>

                        <div :class="styleClass.formGroup">
                            <p :class="styleClass.inputLabel">Price</p>
                            <p :class="styleClass.formControl">{{ product.price }}</p>
                        </div>

                        <div :class="styleClass.formGroup">
                            <p :class="styleClass.inputLabel">Product Type</p>
                            <p :class="styleClass.formControl">{{ product.type }}</p>
                        </div>

                        <div :class="styleClass.formGroup">
                            <p :class="styleClass.inputLabel">Stock</p>
                            <p :class="styleClass.formControl">{{ product.stock }}</p>
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div v-if="product.type === 'main product with variant'" :class="styleClass.variants.container">
                        <label :class="styleClass.variants.label">Variant/s</label>
                        <div :class="styleClass.variants.variantsList">
                            <Product v-for="opt of product.options" :key="opt.id" :product="opt" />

                            <!-- Add Variant Link -->
                            <Link
                                :href="route('product.option.create', { product: product.id })"
                                type="button"
                                :class="styleClass.variants.addVariantLink"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </Link>
                        </div>
                    </div>

                    <div v-else :class="styleClass.noVariants">
                        <label :class="styleClass.noVariantsLabel">
                            To add a variant update the "type" of this product to (Main product with variant)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div :class="styleClass.actionButtons">
                <PillPrimary is="Link" :href="route('product.edit', product)" label="Edit" variant="secondary" type="button" />
                <PillPrimary is="Link" :href="route('product.index')" label="Back" variant="outlineSecondary" />
            </div>
        </ContainerPrimary>
    </div>
</template>

<style scoped>
/* Enhanced focus states for accessibility */
a:focus-visible,
button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 6px;
}

/* Smooth transitions */
.container-primary,
.form-control,
.pill-primary {
    transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for variants list */
.variants-list::-webkit-scrollbar {
    width: 4px;
}

.variants-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.variants-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.variants-list::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
@media (prefers-color-scheme: dark) {
    .variants-list::-webkit-scrollbar-track {
        background: #374151;
    }

    .variants-list::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    .variants-list::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .container-primary,
    .form-control,
    .pill-primary {
        transition: none;
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .action-buttons {
        grid-template-columns: 1fr;
        gap: 3;
    }

    .right-column-grid {
        grid-template-columns: 1fr;
    }
}
</style>
