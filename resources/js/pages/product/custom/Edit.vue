<script setup>
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import AddImages from '@/components/designer/AddImages.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import { onMounted, ref, watch, computed } from 'vue';
import ExampleDesigns from '@/components/designer/ExampleDesigns.vue';

const page = usePage();
const props = defineProps({
    product: Object,
});

// Form state
const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    size: props.product.size,
    type: props.product.type,
    category: props.product.category.name,
    unit: props.product.unit,
    images: props.product.images,
    design: null,
    _method: 'PATCH',
});

// Reactive state
const images = ref([null, null, null, null]);
const hasUnsavedChanges = ref(false);
const isSubmitting = ref(false);

// Computed properties
const productTypeOptions = computed(() => ['custom', 'unavailable']);

const unitOptions = computed(() => ['inc', 'cm', 'foot', 'miter']);

const canSubmit = computed(() => {
    return hasUnsavedChanges.value && form.name && form.price > 0 && form.stock >= 0 && !isSubmitting.value;
});

// Initialize form
onMounted(() => {
    images.value = [...form.images];

    // Watch for form changes
    const stopWatch = watch(
        () => ({
            name: form.name,
            description: form.description,
            price: form.price,
            stock: form.stock,
            size: form.size,
            type: form.type,
            unit: form.unit,
            design: form.design,
        }),
        (newVal, oldVal) => {
            hasUnsavedChanges.value = JSON.stringify(newVal) !== JSON.stringify(oldVal);
        },
        { deep: true, immediate: false },
    );

    // Watch images separately
    watch(
        images,
        (newImages, oldImages) => {
            const hasImageChanges = newImages.some((img, index) => {
                const oldImg = oldImages[index];
                return img instanceof File || img === 'delete' || img !== oldImg;
            });

            if (hasImageChanges) {
                hasUnsavedChanges.value = true;
            }
        },
        { deep: true },
    );
});

// Event handlers
function handleSubmit() {
    if (!canSubmit.value) return;

    isSubmitting.value = true;

    // Prepare images for upload
    const uploadedFiles = images.value.map((img, index) => {
        if (img instanceof File || img === 'delete') {
            return img;
        }
        // Keep existing images that haven't changed
        return props.product.images[index] || null;
    });

    form.images = uploadedFiles;

    form.post(route('product.update', props.product), {
        onFinish: () => {
            isSubmitting.value = false;
        },
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
        onError: () => {
            // Handle specific errors if needed
            console.error('Update failed:', form.errors);
        },
    });
}

function handleImagesChange(data) {
    images.value = [...data.images];
}

function handleDelete() {
    page.props.confirm = {
        ...page.props.confirm,
        show: true,
        message: `Are you sure you want to delete "${props.product.name}"? This action cannot be undone.`,
        type: 'danger',
        action: {
            delete_product: true,
        },
    };
}

function handleCancel() {
    if (hasUnsavedChanges.value) {
        page.props.confirm = {
            ...page.props.confirm,
            show: true,
            message: 'You have unsaved changes. Are you sure you want to leave?',
            type: 'warning',
            action: {
                navigate_back: true,
            },
        };
    } else {
        goBack();
    }
}

function goBack() {
    const url = page.props.urlPrevious || route('products.index');
    router.get(url);
}

// Confirm dialog handlers
watch(
    () => page.props.confirm?.confirmed,
    (confirmed) => {
        if (!confirmed) return;

        const action = page.props.confirm?.action;

        if (action?.delete_product) {
            router.delete(route('product.destroy', props.product), {
                onSuccess: () => {
                    // Optional: Show success message
                    console.log('Product deleted successfully');
                },
            });
        } else if (action?.navigate_back) {
            goBack();
        }

        // Reset confirmation
        page.props.confirm.confirmed = false;
    },
);

// Price formatting
function formatPrice(value) {
    return parseFloat(value) || 0;
}

// Stock validation
function validateStock(value) {
    const stock = parseInt(value);
    return stock >= 0 ? stock : 0;
}

const styleClasses = {
    // Layout
    container: 'mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8',
    formContainer: 'rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800',

    // Header
    backButton:
        'text-link mb-2 flex items-center gap-3',
    title: 'text-2xl font-bold text-gray-900 md:text-3xl dark:text-white',
    subtitle: 'mt-1 text-sm text-gray-600 dark:text-gray-400',

    // Alerts
    unsavedChangesAlert: 'mt-3 rounded-md border border-amber-200 bg-amber-50 p-3 dark:border-amber-800 dark:bg-amber-900/20',
    unsavedChangesText: 'flex items-center gap-2 text-amber-800 dark:text-amber-200',
    errorAlert: 'rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20',
    errorHeader: 'mb-2 flex items-center gap-2 text-red-800 dark:text-red-200',
    errorList: 'list-inside list-disc space-y-1 text-sm text-red-700 dark:text-red-300',

    // Sections
    sectionGrid: 'grid gap-8 lg:grid-cols-2',
    sectionColumn: 'space-y-6',
    sectionCard: 'rounded-lg bg-gray-50 p-6 dark:bg-gray-700/50',
    sectionTitle: 'mb-4 text-lg font-semibold text-gray-900 dark:text-white',

    // Form Elements
    formContent: 'space-y-8',
    formGrid: 'space-y-4',
    formGridCols: 'grid grid-cols-1 gap-4 sm:grid-cols-2',

    // Buttons
    actionButtonsContainer: 'flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-end',
    deleteButton: 'btn btn-danger flex-1 lg:flex-none dark:bg-red-600 dark:hover:bg-red-700 dark:border-red-600',
    cancelSubmitContainer: 'inline-flex flex-1 items-center gap-3 lg:flex-none',

    // Error States
    errorText: 'mt-2 text-sm text-red-600 dark:text-red-400',
};
</script>

<template>
    <div :class="styleClasses.container">
        <div :class="styleClasses.formContainer">
            <!-- Header -->
            <div class="mb-6">
                <button @click="handleCancel" :class="styleClasses.backButton">
                    <i class="fas fa-arrow-left-long text-sm"></i>
                    Back to Products
                </button>

                <div class="mt-4">
                    <h1 :class="styleClasses.title">Edit Product</h1>
                    <p :class="styleClasses.subtitle">Update your product details and images</p>
                </div>

                <!-- Unsaved Changes Alert -->
                <div v-if="hasUnsavedChanges" :class="styleClasses.unsavedChangesAlert">
                    <div :class="styleClasses.unsavedChangesText">
                        <i class="fas fa-exclamation-circle text-sm"></i>
                        <span class="text-sm font-medium">You have unsaved changes</span>
                    </div>
                </div>
            </div>
            <form @submit.prevent="handleSubmit" :class="styleClasses.formContent">
                <!-- Main Form Grid -->
                <div :class="styleClasses.sectionGrid">
                    <!-- Left Column - Basic Information -->
                    <div :class="styleClasses.sectionColumn">
                        <div :class="styleClasses.sectionCard">
                            <h3 :class="styleClasses.sectionTitle">Basic Information</h3>

                            <div :class="styleClasses.formGrid">
                                <TextInputPrimary
                                    v-model="form.name"
                                    :error="form.errors.name"
                                    label="Product Name"
                                    placeholder="Enter product name"
                                    variant="secondary"
                                    required
                                    :maxlength="100"
                                />

                                <TextInputPrimary
                                    :required="false"
                                    v-model="form.description"
                                    :error="form.errors.description"
                                    label="Description"
                                    placeholder="Enter product description"
                                    type="textarea"
                                    variant="secondary"
                                    :rows="4"
                                    :maxlength="500"
                                />

                                <Dropdown
                                    :value="form.type"
                                    :error="form.errors.type"
                                    @select="(option) => (form.type = option)"
                                    label="Product Type"
                                    placeholder="Select type"
                                    variant="secondary"
                                    :options="productTypeOptions"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Pricing & Inventory -->
                        <div :class="styleClasses.sectionCard">
                            <h3 :class="styleClasses.sectionTitle">Pricing & Inventory</h3>

                            <div :class="styleClasses.formGridCols">
                                <TextInputPrimary
                                    v-model="form.price"
                                    :error="form.errors.price"
                                    label="Price"
                                    placeholder="0.00"
                                    type="number"
                                    variant="secondary"
                                    required
                                    :min="0"
                                    :step="0.01"
                                    @update:modelValue="form.price = formatPrice($event)"
                                >
                                    <template #prefix>
                                        <span class="text-gray-500 dark:text-gray-400">$</span>
                                    </template>
                                </TextInputPrimary>

                                <TextInputPrimary
                                    v-model="form.stock"
                                    :error="form.errors.stock"
                                    label="Stock Quantity"
                                    placeholder="0"
                                    type="number"
                                    variant="secondary"
                                    required
                                    :min="0"
                                    @update:modelValue="form.stock = validateStock($event)"
                                />
                            </div>
                        </div>

                        <!-- Dimensions -->
                        <div :class="styleClasses.sectionCard">
                            <h3 :class="styleClasses.sectionTitle">Dimensions</h3>

                            <div :class="styleClasses.formGrid">
                                <TextInputPrimary
                                    :required="false"
                                    v-model="form.size"
                                    :error="form.errors.size"
                                    label="Size (L×W×H or W×H)"
                                    placeholder="e.g., 10×8×2 or 15×10"
                                    variant="secondary"
                                    :maxlength="50"
                                />

                                <Dropdown
                                    :required="false"
                                    :value="form.unit"
                                    :error="form.errors.unit"
                                    @select="(option) => (form.unit = option)"
                                    label="Unit of Measurement"
                                    placeholder="Select unit"
                                    variant="secondary"
                                    :options="unitOptions"
                                    allow-null
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Media & Design -->
                    <div :class="styleClasses.sectionColumn">
                        <!-- Product Images -->
                        <div :class="styleClasses.sectionCard">
                            <h3 :class="styleClasses.sectionTitle">Product Images</h3>
                            <AddImages :images="images" @changed="handleImagesChange" :default-images="form.images" allow-delete />
                            <p v-if="form.errors.images" :class="styleClasses.errorText">
                                {{ form.errors.images }}
                            </p>
                        </div>

                        <!-- Design Templates -->
                        <div :class="styleClasses.sectionCard">
                            <h3 :class="styleClasses.sectionTitle">Design Templates</h3>
                            <ExampleDesigns :designs="product.designs" @change:new="({ file }) => (form.design = file)" />
                            <p v-if="form.errors.design" :class="styleClasses.errorText">
                                {{ form.errors.design }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Errors -->
                <div v-if="form.hasErrors" :class="styleClasses.errorAlert">
                    <div :class="styleClasses.errorHeader">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul :class="styleClasses.errorList">
                        <li v-for="error in Object.values(form.errors).filter((e) => e)" :key="error">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div :class="styleClasses.actionButtonsContainer">
                    <button type="button" :class="styleClasses.deleteButton" @click="handleDelete" :disabled="isSubmitting">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Product
                    </button>

                    <div :class="styleClasses.cancelSubmitContainer">
                        <ButtonPrimary @click="handleCancel" label="Cancel" type="button" variant="outline-secondary" :disabled="isSubmitting" />

                        <ButtonPrimary label="Update" type="submit" :disabled="!canSubmit" :with-spinner="isSubmitting" variant="secondary" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
