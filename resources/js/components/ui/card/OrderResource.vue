<script setup>
import { ref } from 'vue';

defineProps({
    resource: { type: Object, required: true },
});
function image_src(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }

    return '/images/img-placeholder.jpg';
}
const showResource = ref(false);
</script>
<template>
    <!-- Overlay with perfect image styling -->
    <div v-if="showResource" class="fixed inset-0 z-80 flex items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="relative max-w-4xl p-4">
            <!-- Close button -->
            <button
                @click="showResource = false"
                class="absolute -top-2 right-2 z-10 rounded-full bg-white p-2 text-gray-800 shadow-lg transition hover:scale-110 hover:text-amber-500 dark:bg-gray-800 dark:text-white"
            >
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Image container with perfect styling -->
            <div class="relative overflow-hidden rounded-xl shadow-2xl">
                <img :src="image_src(resource.image)" :alt="resource.label" class="max-h-[90vh] w-full object-contain" loading="lazy" />
                <!-- Image info footer -->
                <div class="absolute right-0 bottom-0 left-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-white">
                    <h3 class="text-lg font-semibold capitalize text-white">{{ resource.label }}</h3>
                    <p class="text-sm text-white/80" v-if="resource.description">{{ resource.description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card component -->
    <div
        class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800"
    >
        <!-- Image container -->
        <div class="aspect-square w-full overflow-hidden bg-gray-100 dark:bg-gray-700" @click="showResource = true">
            <img
                :src="image_src(resource.image)"
                :alt="resource.label"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                loading="lazy"
            />
            <!-- Hover overlay -->
            <div
                class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >
                <span class="rounded-full bg-white/80 p-2 text-amber-500 backdrop-blur-sm">
                    <i class="fa-solid fa-expand text-lg"></i>
                </span>
            </div>
        </div>

        <!-- Card footer -->
        <div class="p-3">
            <h3 class="truncate text-sm font-medium text-gray-800 capitalize dark:text-gray-200">
                {{ resource.label }}
            </h3>
            <p class="truncate text-xs text-gray-500 dark:text-gray-400" v-if="resource.fileType">
                {{ resource.fileType }}
            </p>
        </div>

        <!-- View button -->
        <button
            @click="showResource = true"
            class="absolute top-2 right-2 rounded-full bg-white/80 p-1.5 backdrop-blur-sm transition-all hover:scale-110 hover:bg-white dark:bg-gray-800/80 dark:hover:bg-gray-700"
            aria-label="View image"
        >
            <i class="fa-regular fa-eye text-xs text-amber-500"></i>
        </button>

        <!-- Download button -->
        <button
            v-if="resource.downloadable"
            class="absolute right-2 bottom-2 rounded-full bg-white/80 p-1.5 backdrop-blur-sm transition-all hover:scale-110 hover:bg-white dark:bg-gray-800/80 dark:hover:bg-gray-700"
            aria-label="Download"
        >
            <i class="fa-solid fa-download text-xs text-amber-500"></i>
        </button>
    </div>
</template>
