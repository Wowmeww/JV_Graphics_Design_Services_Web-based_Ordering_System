<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
    images: {
        type: Array,
        default: () => [],
    },
    disabled: { type: Boolean, default: false },
    defaultImages: { type: Array, default: () => [null, null] },
    allowDelete: { type: Boolean, default: false },
});

const emit = defineEmits(['changed']);

// Reactive state
const errors = ref([]);
const tempImage = ref([null, null]);
const defaultImages = ref([]);
const previousUrls = [null, null];
const imagePlaceholder = '/images/img-placeholder.jpg';

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

    if (props.images[index]?.image_path) {
        const path = props.images[index]?.image_path;
        return path.includes('product_images') ? `/storage/${path}` : path;
    }

    return imagePlaceholder;
}

function changed(e, i) {
    if (props.disabled) return;
    const file = e.target.files[0];
    if (!file) return;

    // Reset file input to allow selecting same file again
    e.target.value = '';

    if (!file.type.startsWith('image/')) {
        errors.value[i] = 'Please select a valid image file.';
        emitChange(i);
        return;
    }

    const maxSizeMB = 2;
    if (file.size > maxSizeMB * 1024 * 1024) {
        errors.value[i] = `Image size must be less than ${maxSizeMB}MB.`;
        emitChange(i);
        return;
    }

    tempImage.value[i] = file;
    errors.value[i] = '';
    emitChange(i);
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
        if (url) {
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
    if (index === 0) {
        previousUrls[1] = null;
        tempImage.value[1] = props.defaultImages[1];
    }

    tempImage.value[index] = props.defaultImages[index];
    emitChange(index);
}

function handleDelete(index) {
    if (previousUrls[index]) {
        URL.revokeObjectURL(previousUrls[index]);
        previousUrls[index] = 'delete';
    }
    if (index === 0) {
        tempImage.value[1] = 'delete';
    }

    tempImage.value[index] = 'delete';
    emitChange(index);
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
        error: 'border-red-400',
    },

    emptyState: 'flex h-64 flex-col items-center justify-center',
    emptyIcon: 'h-12 w-12 text-gray-400',
    emptyText: 'mt-2 text-gray-500 dark:text-gray-400',

    uploadButton: 'mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-white transition-colors duration-200 hover:bg-blue-600',

    previewContainer: 'relative h-64 w-full rounded-lg overflow-hidden',
    previewImage: 'h-full w-full object-contain bg-gray-100 dark:bg-gray-700',

    controls: 'absolute top-3 right-3 flex gap-2',
    controlButton: 'grid h-8 w-8 place-content-center rounded-full p-1 text-white transition-colors duration-200',
    editButton: 'bg-amber-400 hover:bg-amber-500',
    deleteButton: 'bg-red-500 hover:bg-red-600',

    errorMessage: 'mt-1 text-sm text-red-500 dark:text-red-400',
};
</script>

<template>
    <input type="file" @change="changed($event, 0)" accept="image/*" hidden id="front-image" />
    <input type="file" @change="changed($event, 1)" accept="image/*" hidden id="back-image" />

    <div>
        <p class="input-label">Product Images</p>
        <div :class="styleClass.container">
            <!-- Front Image Upload -->
            <div>
                <div :class="[styleClass.uploadBox.base, errors[0] ? styleClass.uploadBox.error : styleClass.uploadBox.normal]">
                    <div v-if="!tempImage[0]" :class="styleClass.emptyState">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.emptyIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <p :class="styleClass.emptyText">Front View</p>
                        <label for="front-image" :class="styleClass.uploadButton">
                            <i class="fas fa-upload"></i>
                            Upload Front Image
                        </label>
                    </div>
                    <div v-else :class="styleClass.previewContainer">
                        <img :src="imageUrl(0)" alt="Front Preview" :class="styleClass.previewImage" />
                        <div :class="styleClass.controls">
                            <label for="front-image" :class="[styleClass.controlButton, styleClass.editButton]">
                                <i class="fas fa-pen text-sm"></i>
                            </label>
                            <button
                                v-if="tempImage[0] && !images[0]?.id"
                                type="button"
                                @click="clearImage(0)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                            <button
                                v-else-if="allowDelete && tempImage[0]"
                                @click.prevent="handleDelete(0)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                                type="button"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p v-if="errors[0]" :class="styleClass.errorMessage">{{ errors[0] }}</p>
            </div>

            <!-- Back Image Upload (only shown if front image exists) -->
            <div v-if="tempImage[0] && tempImage[0] !== 'delete'">
                <div :class="[styleClass.uploadBox.base, errors[1] ? styleClass.uploadBox.error : styleClass.uploadBox.normal]">
                    <div v-if="!tempImage[1]" :class="styleClass.emptyState">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.emptyIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <p :class="styleClass.emptyText">Back View</p>
                        <label for="back-image" :class="styleClass.uploadButton">
                            <i class="fas fa-upload"></i>
                            Upload Back Image
                        </label>
                    </div>
                    <div v-else :class="styleClass.previewContainer">
                        <img :src="imageUrl(1)" alt="Back Preview" :class="styleClass.previewImage" />
                        <div :class="styleClass.controls">
                            <label for="back-image" :class="[styleClass.controlButton, styleClass.editButton]">
                                <i class="fas fa-pen text-sm"></i>
                            </label>
                            <!-- <button
                                v-if="allowDelete && tempImage[1]"
                                @click.prevent="handleDelete(1)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                                type="button"
                            >
                                <i class="fas fa-trash"></i>
                            </button> -->
                            <button
                                v-if="tempImage[1] && !images[1]?.id"
                                type="button"
                                @click="clearImage(1)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                            >
                                <i class="fas fa-times"></i>
                            </button>

                            <button
                                v-else-if="allowDelete && tempImage[1]"
                                @click.prevent="handleDelete(1)"
                                :class="[styleClass.controlButton, styleClass.deleteButton]"
                                type="button"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p v-if="errors[1]" :class="styleClass.errorMessage">{{ errors[1] }}</p>
            </div>
        </div>
    </div>
</template>
