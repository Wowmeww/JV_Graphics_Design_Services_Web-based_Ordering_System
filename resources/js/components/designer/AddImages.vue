<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
    images: {
        type: Array,
        default: () => [],
    },
    disabled: { type: Boolean, default: false },
    defaultImages: { type: Array, default: () => [null, null, null, null] },
    allowDelete: { type: Boolean, default: false },
});

const emit = defineEmits(['changed']);

// Reactive state
const errors = ref([]);
const tempImage = ref([null, null, null, null]);
const defaultImages = ref([]);
const previousUrls = [null, null, null, null];
const imagePlaceholder = '/images/img-placeholder.jpg';

// Image configuration
const imageConfig = [
    { id: 'front-image', label: 'Front View', required: true },
    { id: 'back-image', label: 'Back View', required: false },
    { id: 'right-image', label: 'Right-side View', required: false },
    { id: 'left-image', label: 'Left-side View', required: false },
];

// Watchers
watch(
    () => props.images,
    (newImages) => {
        if (defaultImages.value.length < 1) {
            defaultImages.value = [...props.images];
        }
        tempImage.value = [...newImages];
        clearObjectURLs();
    },
    { immediate: true },
);

// Methods
function imageUrl(index) {
    const file = tempImage.value[index];

    if (file instanceof File) {
        if (previousUrls[index]) {
            URL.revokeObjectURL(previousUrls[index]);
        }
        const url = URL.createObjectURL(file);
        previousUrls[index] = url;
        return url;
    }

    if (file === 'delete') {
        return imagePlaceholder;
    }

    if (props.images[index]?.image_path) {
        const path = props.images[index]?.image_path;
        return path.includes('product_images') ? `/storage/${path}` : path;
    }

    return imagePlaceholder;
}

function changed(e, index) {
    if (props.disabled) return;

    const file = e.target.files[0];
    if (!file) return;

    // Reset file input to allow selecting same file again
    e.target.value = '';

    // Validate file type
    if (!file.type.startsWith('image/')) {
        errors.value[index] = 'Please select a valid image file.';
        emitChange(index);
        return;
    }

    // Validate file size (2MB)
    const maxSizeMB = 2;
    if (file.size > maxSizeMB * 1024 * 1024) {
        errors.value[index] = `Image size must be less than ${maxSizeMB}MB.`;
        emitChange(index);
        return;
    }

    tempImage.value[index] = file;
    errors.value[index] = '';
    emitChange(index);
}

function emitChange(index) {
    emit('changed', {
        images: [...tempImage.value],
        error: errors.value[index],
        index,
    });
}

function clearObjectURLs() {
    previousUrls.forEach((url, i) => {
        if (url && url !== 'delete') {
            URL.revokeObjectURL(url);
            previousUrls[i] = null;
        }
    });
}

function clearImage(index) {
    if (previousUrls[index]) {
        URL.revokeObjectURL(previousUrls[index]);
        previousUrls[index] = null;
    }

    tempImage.value[index] = props.defaultImages[index] || null;

    // Clear subsequent images if clearing front or back
    if (index === 0) {
        // Clear back and side images when front is cleared
        for (let i = 1; i < 4; i++) {
            if (previousUrls[i]) {
                URL.revokeObjectURL(previousUrls[i]);
                previousUrls[i] = null;
            }
            tempImage.value[i] = props.defaultImages[i] || null;
        }
    } else if (index === 1) {
        // Clear side images when back is cleared
        for (let i = 2; i < 4; i++) {
            if (previousUrls[i]) {
                URL.revokeObjectURL(previousUrls[i]);
                previousUrls[i] = null;
            }
            tempImage.value[i] = props.defaultImages[i] || null;
        }
    }

    emitChange(index);
}

function handleDelete(index) {
    if (previousUrls[index]) {
        URL.revokeObjectURL(previousUrls[index]);
        previousUrls[index] = 'delete';
    }

    tempImage.value[index] = 'delete';

    // Mark subsequent images for deletion if deleting front or back
    if (index === 0) {
        // Mark back and side images for deletion when front is deleted
        for (let i = 1; i < 4; i++) {
            if (previousUrls[i]) {
                URL.revokeObjectURL(previousUrls[i]);
                previousUrls[i] = 'delete';
            }
            tempImage.value[i] = 'delete';
        }
    } else if (index === 1) {
        // Mark side images for deletion when back is deleted
        for (let i = 2; i < 4; i++) {
            if (previousUrls[i]) {
                URL.revokeObjectURL(previousUrls[i]);
                previousUrls[i] = 'delete';
            }
            tempImage.value[i] = 'delete';
        }
    }

    emitChange(index);
}

function canDelete(index) {
    return props.allowDelete && tempImage.value[index] && tempImage.value[index] !== 'delete';
}

function canClear(index) {
    return tempImage.value[index] && !props.images[index]?.id && tempImage.value[index] !== 'delete';
}

// Cleanup
onBeforeUnmount(() => {
    clearObjectURLs();
});

// Styles
const styleClass = {
    container: 'grid gap-4 md:grid-cols-2',

    uploadBox: {
        base: 'relative rounded-xl border-2 border-dashed p-4 text-center transition-colors duration-200',
        normal: 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
        error: 'border-red-400 bg-red-50 dark:bg-red-900/20',
    },

    emptyState: 'flex h-64 flex-col items-center justify-center space-y-3',
    emptyIcon: 'h-12 w-12 text-gray-400',
    emptyText: 'text-gray-500 dark:text-gray-400 font-medium',

    uploadButton:
        'inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-white transition-colors duration-200 hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed',

    previewContainer: 'relative h-64 w-full rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700',
    previewImage: 'h-full w-full object-contain',

    controls: 'absolute top-3 right-3 flex gap-2',
    controlButton: 'grid h-8 w-8 place-content-center rounded-full p-1 text-white transition-colors duration-200 shadow-lg',
    editButton: 'bg-blue-500 hover:bg-blue-600',
    clearButton: 'bg-amber-500 hover:bg-amber-600',
    deleteButton: 'bg-red-500 hover:bg-red-600',

    errorMessage: 'mt-1 text-sm text-red-500 dark:text-red-400',

    requiredBadge: 'ml-1 text-red-500',
    optionalBadge: 'ml-1 text-xs text-gray-500',
};
</script>

<template>
    <!-- Hidden file inputs -->
    <input
        v-for="(config, index) in imageConfig"
        :key="config.id"
        type="file"
        @change="changed($event, index)"
        accept="image/*"
        hidden
        :id="config.id"
        :disabled="disabled"
    />

    <div>
        <p class="input-label">Product Images</p>
        <div :class="styleClass.container">
            <!-- Dynamic image upload boxes -->
            <div v-for="(config, index) in imageConfig" :key="config.id">
                <div
                    v-show="index === 0 || tempImage[index - 1]"
                    :class="[styleClass.uploadBox.base, errors[index] ? styleClass.uploadBox.error : styleClass.uploadBox.normal]"
                >
                    <!-- Empty State -->
                    <div v-if="!tempImage[index] || tempImage[index] === 'delete'" :class="styleClass.emptyState">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.emptyIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <p :class="styleClass.emptyText">
                            {{ config.label }}
                            <span v-if="config.required" :class="styleClass.requiredBadge">*</span>
                            <span v-else :class="styleClass.optionalBadge">(Optional)</span>
                        </p>
                        <label :for="config.id" :class="styleClass.uploadButton" :disabled="disabled">
                            <i class="fas fa-upload"></i>
                            Upload {{ config.label }}
                        </label>
                    </div>

                    <!-- Preview State -->
                    <div v-else :class="styleClass.previewContainer">
                        <img :src="imageUrl(index)" :alt="`${config.label} Preview`" :class="styleClass.previewImage" />
                        <div :class="styleClass.controls">
                            <!-- Edit Button -->
                            <label :for="config.id" :class="[styleClass.controlButton, styleClass.editButton]" :disabled="disabled">
                                <i class="fas fa-pen text-sm"></i>
                            </label>

                            <!-- Clear Button (for new uploads) -->
                            <button
                                v-if="canClear(index)"
                                type="button"
                                @click="clearImage(index)"
                                :class="[styleClass.controlButton, styleClass.clearButton]"
                                :disabled="disabled"
                            >
                                <i class="fas fa-times"></i>
                            </button>

                            <!-- Delete Button (for existing images) -->
                            <button
                                v-else-if="canDelete(index)"
                                @click.prevent="handleDelete(index)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                                type="button"
                                :disabled="disabled"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p v-if="errors[index]" :class="styleClass.errorMessage">
                    {{ errors[index] }}
                </p>
            </div>
        </div>

        <!-- Help Text -->
        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
            <p>• Front image is required. Back and side images are optional.</p>
            <p>• Supported formats: JPG, PNG, WebP. Max size: 2MB</p>
            <p v-if="disabled" class="text-amber-600">• Image upload is currently disabled</p>
        </div>
    </div>
</template>
