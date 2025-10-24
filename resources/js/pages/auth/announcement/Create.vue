<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: null,
    content: null,
    image: null,
});

const imagePreview = ref(null);

function changeImage(e) {
    const file = e.target.files[0];

    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            form.errors.image = 'Please select a valid image file (JPEG, PNG, GIF, WebP).';
            return;
        }

        // Validate file size (5MB max)
        const maxSize = 2 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
            form.errors.image = 'Image size must be less than 2MB.';
            return;
        }

        form.image = file;
        form.errors.image = null;

        // Create image preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    form.image = null;
    imagePreview.value = null;
    // Reset file input
    const fileInput = document.getElementById('image');
    if (fileInput) fileInput.value = '';
}

function submit() {
    // Convert to FormData to handle file upload
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('content', form.content);
    if (form.image) {
        formData.append('image', form.image);
    }

    form.post(route('announcement.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            imagePreview.value = null;
        },
    });
}

function clearError(field) {
    if (form.errors[field]) {
        form.errors[field] = null;
    }
}
</script>

<template>
    <Head title="Create Announcement" />

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Gradient Header -->
        <div class="rounded-t-xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg">
            <div class="mx-auto max-w-3xl px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Create Announcement</h2>
                        <p class="mt-1 text-blue-100">Share important updates with your community</p>
                    </div>
                    <div class="rounded-full bg-white/10 p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625 1.234 9.366 3.87a1 1 0 01-.127 1.454A3.995 3.995 0 0117 13h-1.832c-2.127 0-4.113.676-5.73 1.832a4.002 4.002 0 00-2.002.626z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="overflow-hidden rounded-b-xl bg-white shadow-xl dark:bg-gray-800">
            <form @submit.prevent="submit" class="space-y-8 p-8">
                <!-- Title Field -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <label for="title" class="block text-lg font-semibold text-gray-800 dark:text-white"> Announcement Title </label>
                        <span class="text-xl text-red-500">*</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Make it clear and attention-grabbing</p>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="form-control form-control-secondary block w-full text-xl"
                        placeholder="e.g., System Maintenance This Weekend"
                        :class="{ 'border-red-400': form.errors.title }"
                        @input="clearError('title')"
                    />
                    <p v-if="form.errors.title" class="mt-1 flex items-center gap-1 text-sm font-medium text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Content Field -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <label for="content" class="block text-lg font-semibold text-gray-800 dark:text-white"> Announcement Details </label>
                        <span class="text-xl text-red-500">*</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Include all relevant information for your audience</p>
                    <textarea
                        id="content"
                        v-model="form.content"
                        rows="5"
                        class="form-control form-control-secondary block w-full text-xl"
                        placeholder="Write your detailed announcement here..."
                        :class="{ 'border-red-400': form.errors.content }"
                        @input="clearError('content')"
                    ></textarea>
                    <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>Be as detailed as possible</span>
                        <span>{{ form.content?.length || 0 }}/5000 characters</span>
                    </div>
                    <p v-if="form.errors.content" class="mt-1 flex items-center gap-1 text-sm font-medium text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ form.errors.content }}
                    </p>
                </div>

                <!-- Image Field -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <label for="image" class="block text-lg font-semibold text-gray-800 dark:text-white"> Announcement Image </label>
                        <span class="text-sm text-gray-500">(Optional)</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Supported formats: JPEG, PNG, GIF, WebP. Max size: 2MB</p>

                    <!-- Image Preview -->
                    <div v-if="imagePreview" class="relative inline-block">
                        <img
                            :src="imagePreview"
                            alt="Image preview"
                            class="h-48 w-auto rounded-lg border-2 border-dashed border-gray-300 object-cover"
                        />
                        <button
                            type="button"
                            @click="removeImage"
                            class="absolute -top-2 -right-2 rounded-full bg-red-500 p-1 text-white shadow-lg hover:bg-red-600"
                            aria-label="Remove image"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- File Input -->
                    <div class="flex items-center gap-4">
                        <input
                            id="image"
                            type="file"
                            accept="image/*"
                            @change="changeImage"
                            class="form-control form-control-secondary block flex-1 text-xl"
                            :class="{ 'border-red-400': form.errors.image }"
                        />
                    </div>

                    <p v-if="form.errors.image" class="mt-1 flex items-center gap-1 text-sm font-medium text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ form.errors.image }}
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col items-center justify-end gap-4 border-t border-gray-100 pt-8 sm:flex-row dark:border-gray-700">
                    <Link
                        :href="route('announcement.index')"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-gray-200 px-6 py-3 text-base font-medium text-gray-700 transition-all duration-300 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700/50"
                        :disabled="form.processing"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        class="from-secondary-500 to-primary hover:from-secondary hover:to-primary-700 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r px-6 py-3 text-base font-medium text-white shadow-lg transition-all duration-300 hover:shadow-xl sm:w-auto"
                        :disabled="form.processing"
                        :class="{
                            'cursor-not-allowed opacity-80': form.processing,
                            'opacity-50': !form.title || !form.content,
                        }"
                    >
                        <template v-if="!form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Publish Announcement
                        </template>
                        <template v-else>
                            <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Publishing...
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
