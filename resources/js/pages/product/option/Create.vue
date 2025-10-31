<script setup>
import Status from '@/components/alert/Status.vue';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    product: Object,
});

const form = useForm({
    name: null,
    category: props.product.category.name,
    price: props.product.price,
    type: 'Variant',
    stock: null,
    size: null,
    unit: null,
    description: null,
    images: [],
});

const images = ref([null, null, null]);

function submit() {
    form.images = images.value.map((img) => (img instanceof File ? img : null));

    form.post(route('product.option.store', { product: props.product }), {
        forceFormData: true,
        preserveScroll: true,
    });
}

function handleImagesChange(data) {
    images.value = [...data.images];
}
function reset() {
    images.value = [null, null, null];
    form.reset();
}
// Fixed Tailwind classes with proper structure
const styleClass = {
    // Header section
    header: {
        container: 'mx-auto flex max-w-7xl justify-center px-4 pt-8 md:justify-end',
        createButton:
            'btn from-secondary-500 to-primary-500 hover:from-secondary-600 hover:to-primary-600 w-full gap-3 bg-gradient-to-r text-white transition-all duration-300 shadow-md hover:shadow-lg md:w-auto px-6 py-3 rounded-xl font-semibold',
    },

    // Main form
    form: 'mx-auto max-w-7xl space-y-6 px-4 py-8 md:space-y-8',

    // Product settings container
    productSettings: {
        grid: 'grid gap-6 pt-4 md:grid-cols-2 md:gap-8',
        column: 'space-y-4',
        imagesSection: 'space-y-4',
    },

    // Size and unit grid
    sizeUnitGrid: 'grid grid-cols-12 gap-3 pt-4',
    sizeInput: 'col-span-8',
    unitInput: 'col-span-4',

    // Right column grid
    rightColumnGrid: 'grid gap-3 sm:grid-cols-2',

    // Form actions
    formActions: {
        container: 'mx-auto grid max-w-3xl gap-4 py-6 pt-8 sm:grid-cols-2 md:grid-cols-3',
        publishButton: 'w-full',
        cancelButton: 'w-full',
        resetButton: 'w-full dark:bg-red-600/70 bg-red-600/90 hover:opacity-80 text-white transition-opacity duration-300',
    },
};
</script>

<template>
    <Head title="Edit Product" />

    <form @submit.prevent="submit" :class="styleClass.form">
        <PageTitleHeader title="Product Editor" />

        <ContainerPrimary title="Product Setting">
            <!-- Two Column Grid -->
            <div :class="styleClass.productSettings.grid">
                <!-- Left Column -->
                <div :class="styleClass.productSettings.column">
                    <!-- Product Images -->
                    <div :class="styleClass.productSettings.imagesSection">
                        <ProductImagesInput :images="form.images" :error="form.errors" @changed="handleImagesChange" />
                    </div>

                    <!-- Size and Unit -->
                    <div :class="styleClass.sizeUnitGrid">
                        <div :class="styleClass.sizeInput">
                            <TextInputPrimary v-model="form.size" :required="false" label="Size L*W*H, W*H" placeholder="L*W*H" variant="secondary" />
                        </div>
                        <div :class="styleClass.unitInput">
                            <Dropdown
                                label="Unit"
                                allowNull
                                :value="form.unit"
                                @select="(option) => (form.unit = option)"
                                placeholder="Unit"
                                variant="secondary"
                                :options="['inc', 'cm', 'foot', 'miter']"
                            />
                        </div>
                    </div>

                    <!-- Description -->
                    <TextInputPrimary
                        v-model="form.description"
                        :error="form.errors.description"
                        :required="false"
                        type="textarea"
                        label="Product Description"
                        placeholder="Enter product description"
                        variant="secondary"
                    />
                </div>

                <!-- Right Column -->
                <div :class="styleClass.productSettings.column">
                    <!-- Product Name -->
                    <TextInputPrimary
                        v-model="form.name"
                        :error="form.errors.name"
                        label="Product Variant Name"
                        placeholder="Enter product name"
                        variant="secondary"
                    />

                    <!-- Product Details Grid -->
                    <div :class="styleClass.rightColumnGrid">
                        <Dropdown
                            :value="form.category"
                            :error="form.errors.category"
                            @select="(opt) => (form.category = opt)"
                            label="Category"
                            placeholder="Select category"
                            variant="secondary"
                            allow-custom-value
                            disabled
                        />

                        <TextInputPrimary
                            v-model="form.price"
                            :error="form.errors.price"
                            type="number"
                            label="Price"
                            placeholder="Enter product price"
                            variant="secondary"
                        />

                        <Dropdown
                            :value="form.type"
                            :error="form.errors.type"
                            @select="(option) => (form.type = option)"
                            label="Product Type"
                            placeholder="Select type"
                            variant="secondary"
                            disabled
                        />

                        <TextInputPrimary
                            v-model="form.stock"
                            :error="form.errors.stock"
                            type="number"
                            label="Stock"
                            placeholder="Enter product stocks"
                            variant="secondary"
                        />
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div :class="styleClass.formActions.container">
                <PillPrimary label="" variant="secondary" type="submit" :disabled="form.processing" :class="styleClass.formActions.publishButton">
                    <span v-if="form.processing">Publishing...</span>
                    <span v-else>Publish Variant</span>
                </PillPrimary>

                <PillPrimary
                    is="Link"
                    :href="route('product.show', [product.id])"
                    label="Cancel"
                    variant="outlineSecondary"
                    :class="styleClass.formActions.cancelButton"
                />

                <PillPrimary
                    label="Clear/ reset"
                    variant="outlineSecondary"
                    @click="reset"
                    type="reset"
                    :class="styleClass.formActions.cancelButton"
                />
            </div>
        </ContainerPrimary>
    </form>
</template>

<style scoped>
/* Enhanced focus states for accessibility */
button:focus-visible,
a:focus-visible,
input:focus-visible,
select:focus-visible,
textarea:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 6px;
}

/* Smooth transitions */
.btn,
.pill-primary {
    transition: all 0.2s ease-in-out;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .btn,
    .pill-primary {
        transition: none;
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .form-actions {
        grid-template-columns: 1fr;
        gap: 3;
    }

    .size-unit-grid {
        grid-template-columns: 1fr;
    }

    .size-input,
    .unit-input {
        grid-column: span 12;
    }

    .right-column-grid {
        grid-template-columns: 1fr;
    }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
    .reset-button {
        background: rgba(220, 38, 38, 0.8) !important;
    }

    .reset-button:hover {
        background: rgba(220, 38, 38, 0.9) !important;
    }
}
</style>
