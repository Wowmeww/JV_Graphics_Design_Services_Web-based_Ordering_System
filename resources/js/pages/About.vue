<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings);
// put the style classes in this object so its cleaner
const styleClass = {
    // Layout
    pageContainer:
        'min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 px-4 py-16 sm:px-6 lg:px-8 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900',
    contentWrapper: 'mx-auto max-w-4xl',

    // Header Section
    headerContainer: 'mb-16 text-center',
    title: 'from-primary-600 to-primary-800 dark:from-primary-400 dark:to-primary-300 mb-6 bg-gradient-to-r bg-clip-text text-5xl font-bold text-transparent sm:text-6xl',
    subtitle: 'mx-auto max-w-2xl text-xl leading-relaxed text-gray-600 dark:text-gray-300',

    // Main Content Card
    mainCard:
        'hover:shadow-3xl overflow-hidden rounded-3xl bg-white/80 shadow-2xl backdrop-blur-sm transition-all duration-500 dark:bg-gray-800/80 dark:shadow-gray-900/30',

    // Hero Section
    heroSection:
        'from-primary-500 via-primary-300 to-secondary-500 dark:from-primary-600 dark:via-primary-400 dark:to-secondary-600 relative flex h-72 items-center justify-center overflow-hidden bg-gradient-to-br',
    heroOverlay: 'absolute inset-0 bg-black/10 dark:bg-black/20',
    heroIcon: 'relative z-10 h-32 w-32 transform text-white transition-transform duration-700 hover:scale-110',
    heroGradientFade: 'absolute right-0 bottom-0 left-0 h-16 bg-gradient-to-t from-white/80 to-transparent dark:from-gray-800/80 dark:to-transparent',

    // Content Sections
    contentContainer: 'space-y-8 p-10',
    sectionsContainer: 'space-y-6 p-6',
    section: 'group hover:bg-primary-50/50 dark:hover:bg-primary-900/20 rounded-2xl p-6 transition-all duration-300',
    sectionTitle: 'group-hover:text-primary-700 dark:group-hover:text-primary-300 mb-3 text-2xl font-semibold text-gray-800 dark:text-gray-100',
    sectionContent: 'leading-relaxed text-gray-600 group-hover:text-gray-700 dark:text-gray-300 dark:group-hover:text-gray-200',
};
</script>

<template>
    <Head title="About" />
    <div :class="styleClass.pageContainer">
        <div :class="styleClass.contentWrapper">
            <!-- Header Section -->
            <div :class="styleClass.headerContainer">
                <h1 :class="styleClass.title">About Us</h1>
                <p :class="styleClass.subtitle">We're passionate about creating solutions that make a difference in people's lives.</p>
            </div>

            <!-- Main Content -->
            <div :class="styleClass.mainCard">
                <!-- Hero Image -->
                <div :class="styleClass.heroSection">
                    <div :class="styleClass.heroOverlay"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.heroIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                        />
                    </svg>
                    <div :class="styleClass.heroGradientFade"></div>
                </div>

                <!-- Content Sections -->
                <div :class="styleClass.contentContainer">
                    <div :class="styleClass.sectionsContainer">
                        <section v-for="(section, i) in settings.app_about" :key="i" :class="styleClass.section">
                            <h2 :class="styleClass.sectionTitle">
                                {{ section.header }}
                            </h2>
                            <p :class="styleClass.sectionContent">
                                {{ section.content }}
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
