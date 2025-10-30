<script setup>
import Status from '@/components/alert/Status.vue';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    product: Object,
    status: Object,
    categories: Array,
});

const page = usePage();

const form = useForm({
    name: props.product.name,
    category: props.product.category.name,
    price: props.product.price,
    type: props.product.type,
    stock: props.product.stock,
    size: props.product.size,
    unit: props.product.unit,
    description: props.product.description,
    images: props.product.images,
    _method: 'PATCH',
});
const is_unchanged = ref(true);

const images = ref([null, null, null]);

onMounted(() => {
    images.value = [...form.images];
    is_unchanged.value = true;
    watch(form, () => (is_unchanged.value = false));
});

function submit() {
    const uploadedFiles = images.value.map((img, i) => {
        return img instanceof File || img == 'delete' ? img : null;
    });
    form.images = uploadedFiles;

    form.post(route('product.update', props.product));
}

function handleImagesChange(data) {
    images.value = [...data.images];
    is_unchanged.value = false;
}

function resetForm() {
    form.reset();
    images.value = [...props.product.images];
    is_unchanged.value = true;
}

function handleDelete() {
    page.props.confirm = {
        ...page.props.confirm,
        show: true,
        message: `Do you to delete ${props.product.name}?`,
        action: {
            delete_product: true,
        },
    };
}

watch(
    () => page.props.confirm.confirmed,
    () => {
        if (page.props.confirm.confirmed && page.props.confirm.action?.delete_product) {
            router.delete(route('product.destroy', props.product));
            page.props.confirm.confirmed = false;
        }
    },
);
function goBack() {
    window.history.back();
}

const styleClass = {
    // Main form container
    formContainer: 'mx-auto max-w-7xl space-y-6 py-8 md:space-y-8 px-4',

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
        container: 'mx-auto grid max-w-3xl gap-4 py-6 pt-8 sm:grid-cols-2 md:grid-cols-4',
        updateButton: 'w-full',
        deleteButton: 'w-full dark:bg-red-600/70 bg-red-600/90 hover:opacity-80 text-white transition-opacity duration-300',
        resetButton: 'w-full',
        cancelButton: 'w-full',
    },
};
</script>

<template>
    <Head title="Edit Product" />

    <form :class="styleClass.formContainer" @submit.prevent="submit">
        <PageTitleHeader title="Product Editor" />

        <ContainerPrimary title="Product Setting">
            <!-- Two Column Grid -->
            <div :class="styleClass.productSettings.grid">
                <!-- Left Column -->
                <div :class="styleClass.productSettings.column">
                    <!-- Product Images -->
                    <div :class="styleClass.productSettings.imagesSection">
                        <ProductImagesInput
                            :images="images"
                            :errors="[form.errors['images.0'], form.errors['images.1'], form.errors['images.2']]"
                            @changed="handleImagesChange"
                            :default-images="form.images"
                            allow-delete
                        />
                    </div>

                    <!-- Size and Unit -->
                    <div :class="styleClass.sizeUnitGrid">
                        <div :class="styleClass.sizeInput">
                            <TextInputPrimary v-model="form.size" :required="false" label="Size L*W*H, W*H" placeholder="L*W*H" variant="secondary" />
                        </div>
                        <div :class="styleClass.unitInput">
                            <Dropdown
                                allowNull
                                label="Unit"
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
                        label="Product Name"
                        placeholder="Enter product name"
                        variant="secondary"
                    />

                    <!-- Product Details Grid -->
                    <div :class="styleClass.rightColumnGrid">
                        <Dropdown
                            :value="form.category"
                            :error="form.errors.category"
                            label="Category"
                            placeholder="Select category"
                            variant="secondary"
                            @select="(option) => (form.category = option)"
                            :options="categories"
                            allow-custom-value
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
                            :options="['single product', 'main product with variant', 'unavailable', 'custom']"
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
                <PillPrimary
                    :disabled="is_unchanged || form.processing"
                    label="Update product"
                    variant="secondary"
                    type="submit"
                    :class="styleClass.formActions.updateButton"
                />

                <PillPrimary @click="handleDelete" label="Delete" variant="outlineSecondary" :class="styleClass.formActions.cancelButton" />

                <PillPrimary
                    @click="resetForm"
                    label="Reset"
                    :disabled="is_unchanged || form.processing"
                    variant="outlineSecondary"
                    :class="styleClass.formActions.resetButton"
                />

                <PillPrimary @click="goBack" label="Cancel" variant="outlineSecondary" :class="styleClass.formActions.cancelButton" />
            </div>
        </ContainerPrimary>
    </form>
</template>

<style scoped>
/* Enhanced focus states for accessibility */
button:focus-visible,
input:focus-visible,
select:focus-visible,
textarea:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 6px;
}

/* Smooth transitions */
.pill-primary {
    transition: all 0.2s ease-in-out;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
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
    .delete-button {
        background: rgba(220, 38, 38, 0.8) !important;
    }

    .delete-button:hover {
        background: rgba(220, 38, 38, 0.9) !important;
    }
}
</style>
