<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings);

</script>

<template>
    <Head title="About" />

    <div class="min-h-screen bg-gradient-to-br px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <!-- Header Section -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 text-4xl font-bold text-indigo-800">About Us</h1>
                <p class="mx-auto max-w-2xl text-xl text-gray-600">
                    We're passionate about creating solutions that make a difference in people's lives.
                </p>
            </div>

            <!-- Main Content -->
            <div class="overflow-hidden rounded-xl bg-white shadow-lg">
                <!-- Hero Image -->
                <div class="flex h-64 items-center justify-center bg-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                        />
                    </svg>
                </div>

                <!-- Content Sections -->
                <div class="p-8 space-y-4">
                    <section v-for="(section, i) in settings.app_about" :key="i">
                        <h2 class="mb-1 text-2xl font-semibold text-gray-800">
                            {{ section.header }}
                        </h2>
                        <p class="leading-relaxed text-gray-600">
                             {{ section.content }}
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
