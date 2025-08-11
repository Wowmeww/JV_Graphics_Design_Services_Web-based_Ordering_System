<script setup>
import { ref } from 'vue';

const props = defineProps({
    idCard: {
        type: Object,
        required: true,
        // expected shape:
        // { type: 'Passport', selfie: 'selfie.jpg', front: 'front.jpg', back: 'back.jpg' }
    },
});

function imageSrc(path) {
    return `/storage/${path}`;
}

const focused = ref('');
</script>
<template>
    <!-- image focus -->
    <div class="absolute inset-0 z-40 grid" v-if="focused">
        <!-- Enhanced Image Focus Overlay -->
        <transition name="fade">
            <div v-if="focused" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm" @click.self="focused = ''">
                <div class="relative max-h-[90vh] max-w-[90vw]">
                    <img :src="focused" alt="Focused document" class="max-h-[70vh] max-w-[70vw] object-contain" />

                    <!-- Close Button -->
                    <button
                        @click="focused = ''"
                        class="absolute -top-3 -right-3 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow-lg transition-all hover:scale-110 hover:bg-white focus:ring-2 focus:ring-white focus:outline-none"
                        aria-label="Close image viewer"
                    >
                        <i class="fas fa-times text-lg"></i>
                    </button>

                    <!-- Download Button -->
                    <a
                        :href="focused"
                        download
                        class="absolute -bottom-3 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-medium text-gray-800 shadow-lg transition-all hover:scale-105 hover:bg-white focus:ring-2 focus:ring-white focus:outline-none"
                    >
                        <i class="fas fa-download"></i>
                        Download
                    </a>
                </div>
            </div>
        </transition>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <!-- Header -->
        <div class="mb-6 border-b border-gray-100 pb-4 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-id-card mr-2 text-indigo-500"></i>
                User Verification Documents
            </h2>
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                <span class="font-medium text-gray-800 dark:text-gray-200">ID Type:</span>
                <span class="ml-1 rounded bg-indigo-100 px-2 py-0.5 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                    {{ idCard?.type }}
                </span>
            </div>
        </div>

        <!-- Images Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <!-- Selfie -->
            <div
                @click="focused = imageSrc(idCard.selfie)"
                class="group relative overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-3 transition-all hover:border-indigo-300 dark:border-gray-600 dark:bg-gray-700/30 dark:hover:border-indigo-500"
            >
                <div class="mb-3 flex items-center">
                    <div class="mr-2 h-2 w-2 rounded-full bg-indigo-500"></div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selfie</p>
                </div>
                <img
                    :src="imageSrc(idCard.selfie)"
                    alt="Selfie"
                    class="h-52 w-full cursor-zoom-in object-contain transition-transform duration-300 hover:scale-105"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                    <span class="rounded-lg bg-white/90 px-3 py-1.5 text-xs font-medium text-gray-800 shadow-sm">
                        <i class="fas fa-expand mr-1"></i> View full size
                    </span>
                </div>
            </div>

            <!-- Front of ID -->
            <div
                @click="focused = imageSrc(idCard.front)"
                class="group relative overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-3 transition-all hover:border-indigo-300 dark:border-gray-600 dark:bg-gray-700/30 dark:hover:border-indigo-500"
            >
                <div class="mb-3 flex items-center">
                    <div class="mr-2 h-2 w-2 rounded-full bg-indigo-500"></div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Front</p>
                </div>
                <img
                    :src="imageSrc(idCard.front)"
                    alt="Front of ID"
                    class="h-52 w-full cursor-zoom-in object-contain transition-transform duration-300 hover:scale-105"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                    <span class="rounded-lg bg-white/90 px-3 py-1.5 text-xs font-medium text-gray-800 shadow-sm">
                        <i class="fas fa-expand mr-1"></i> View full size
                    </span>
                </div>
            </div>

            <!-- Back of ID -->
            <div
                @click="focused = imageSrc(idCard.back)"
                class="group relative overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-3 transition-all hover:border-indigo-300 dark:border-gray-600 dark:bg-gray-700/30 dark:hover:border-indigo-500"
            >
                <div class="mb-3 flex items-center">
                    <div class="mr-2 h-2 w-2 rounded-full bg-indigo-500"></div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Back</p>
                </div>
                <img
                    :src="imageSrc(idCard.back)"
                    alt="Back of ID"
                    class="h-52 w-full cursor-zoom-in object-contain transition-transform duration-300 hover:scale-105"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                    <span class="rounded-lg bg-white/90 px-3 py-1.5 text-xs font-medium text-gray-800 shadow-sm">
                        <i class="fas fa-expand mr-1"></i> View full size
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
