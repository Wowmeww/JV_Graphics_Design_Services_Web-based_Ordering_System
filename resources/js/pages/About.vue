<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings);

// Enhanced style classes
const styleClass = {
    // Layout
    page: 'min-h-screen bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800',
    container: 'max-w-4xl mx-auto px-6 py-16',

    // Header
    header: 'text-center mb-16',
    title: 'text-5xl font-bold text-gray-900 dark:text-white mb-6',
    subtitle: 'text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed',

    // Content Card
    card: 'bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700',

    // Hero Image
    hero: 'w-full h-80 object-cover',

    // Content
    content: 'p-10 space-y-10',
    section: 'group p-8 rounded-2xl transition-all duration-300 hover:bg-gray-50 dark:hover:bg-gray-700/50',
    sectionTitle: 'text-2xl font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-3',
    sectionIcon: 'text-primary-600 dark:text-primary-400 text-xl transition-transform duration-300 group-hover:scale-110',
    sectionText: 'text-gray-600 dark:text-gray-300 leading-relaxed text-lg',
};
</script>

<template>
    <Head title="About" />

    <div :class="styleClass.page">
        <div :class="styleClass.container">
            <!-- Header -->
            <div :class="styleClass.header">
                <h1 :class="styleClass.title">About Us</h1>
                <p :class="styleClass.subtitle">
                    Discover our story, mission, and the passionate team dedicated to creating exceptional experiences for our community.
                </p>
            </div>

            <!-- Content Card -->
            <div :class="styleClass.card">
                <!-- Hero Image -->
                <div class="relative">
                    <img src="/images/shop-img.jpg" alt="About our company" :class="styleClass.hero" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                </div>

                <!-- Content Sections -->
                <div :class="styleClass.content">
                    <section v-for="(section, index) in settings.app_about" :key="index" :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionIcon">●</span>
                            {{ section.header }}
                        </h2>
                        <p :class="styleClass.sectionText">
                            {{ section.content }}
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
