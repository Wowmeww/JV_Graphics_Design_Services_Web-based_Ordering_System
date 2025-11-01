<script setup>
import Carousel from '../Carousel.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        required: true,
        default: null,
        type: Object,
    },
    variant: {
        type: Number,
        default: 1,
    },
});
const imagePlaceholder = '/images/img-placeholder.jpg';

const imagesPath = props.product.images.map((img) => img.image_path);

// Enhanced style classes with perfection touches
const styleClass = {
    // Main Card with enhanced animations
    card: `group relative bg-gradient-to-br from-secondary-400/15 via-secondary-300/5 to-secondary-500/10 
           dark:from-gray-900/80 dark:via-gray-800/60 dark:to-gray-700/50
           transition-all duration-1000 ease-out overflow-hidden
           flex flex-col-reverse items-center gap-8 p-8 rounded-3xl shadow-2xl 
           hover:shadow-4xl text-white border border-white/10 hover:border-white/25
           backdrop-blur-xl transform-gpu will-change-transform
           translate-y-0 opacity-100 scale-100 rotate-0 lg:w-4xl md:w-3xl sm:w-2xl`,

    // Card Layout
    cardLayout: {
        even: 'md:flex-row md:pr-2',
        odd: 'md:flex-row-reverse md:pl-2',
    },

    // Enhanced Background Elements
    backgroundElements: {
        glow: 'absolute inset-0 bg-gradient-to-r from-primary-500/8 via-secondary-500/6 to-accent-500/8 blur-2xl transform scale-150 opacity-0 group-hover:opacity-100 transition-all duration-1200 ease-out',
        sparkle: `absolute top-6 ${props.variant - 1 ? 'left-6' : 'right-6'} w-3 h-3 bg-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-700 delay-400 animate-pulse shadow-glow`,
        pulse: 'absolute -inset-2 bg-gradient-to-r from-primary-600/20 to-secondary-600/20 rounded-3xl opacity-0 group-hover:opacity-10 blur-xl transition-all duration-1000 ease-in-out',
        orb1: 'absolute -top-4 -left-4 w-8 h-8 bg-primary-400/20 rounded-full blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-1000 delay-200',
        orb2: 'absolute -bottom-4 -right-4 w-12 h-12 bg-secondary-400/15 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-1000 delay-400',
    },

    // Content Container with enhanced spacing
    contentContainer: 'flex flex-col items-start gap-8 justify-between flex-1 transform transition-all duration-700 ease-out z-10 relative px-2',

    // Enhanced Title Section
    title: 'text-center md:text-left text-4xl font-black bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent pb-3 relative tracking-tight',
    titleUnderline:
        'absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-primary-400 via-secondary-400 to-accent-400 group-hover:w-full transition-all duration-900 ease-out rounded-full',

    // Premium Category Badge
    categoryBadge:
        'inline-flex items-center bg-gradient-to-r from-secondary-500/40 to-primary-500/40 text-white text-sm font-bold px-5 py-2.5 rounded-2xl border border-white/30 shadow-2xl ml-4 transform transition-all duration-500 group-hover:scale-110 group-hover:shadow-3xl backdrop-blur-sm',

    // Enhanced Description
    description: {
        container: 'text-gray-200 dark:text-gray-300 leading-relaxed transform transition-all duration-600 space-y-3',
        text: 'line-clamp-4 text-xl font-light tracking-wide text-white/90',
        ellipsis: 'font-black tracking-widest text-secondary-300 dark:text-secondary-200 animate-pulse drop-shadow-lg',
        charCount:
            'mt-4 text-xs text-gray-300 dark:text-gray-400 font-mono bg-black/30 px-3 py-1.5 rounded-xl inline-block transform transition-all duration-400 hover:scale-105 border border-white/10',
    },

    // Premium Button with enhanced effects
    button: `group/btn relative bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-600 
            bg-size-200 bg-pos-0 border border-white/20 transition-all duration-600 
            px-10 py-5 rounded-2xl text-white font-extrabold mx-auto md:mx-0 
            hover:bg-pos-100 hover:scale-110 hover:shadow-3xl 
            dark:from-primary-600 dark:to-secondary-600 dark:border-white/30 
            overflow-hidden transform-gpu shadow-2xl
            shadow-glow`,

    buttonEffects: {
        shine: 'absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent transform -skew-x-12 translate-x-[-200%] group-hover/btn:translate-x-[200%] transition-transform duration-1200 ease-in-out',
        particles: 'absolute inset-0 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500',
        particle: 'absolute w-1.5 h-1.5 bg-white rounded-full animate-float shadow-glow',
        ripple: 'absolute inset-0 bg-white/0 group-hover/btn:bg-white/5 transition-all duration-1000 rounded-2xl',
    },

    // Enhanced Image Container
    imageContainer: `relative w-full h-72 sm:h-84 md:h-96 md:flex-1 transform transition-all duration-900 ease-out 
                    rounded-3xl overflow-hidden z-10 shadow-2xl
                   opacity-100 blur-0 border-2 border-white/10 group-hover:border-white/20`,

    image: 'w-full h-full object-cover transform transition-transform duration-900 group-hover:scale-110',

    // Premium Loading States
    skeleton: {
        image: 'absolute inset-0 bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 animate-pulse rounded-3xl',
        shimmer: 'absolute inset-0 bg-gradient-to-r from-transparent via-white/15 to-transparent transform -skew-x-12 animate-shimmer',
        content: 'absolute inset-4 bg-gray-600/20 rounded-2xl animate-pulse',
    },

    // Enhanced Stats & Metadata
    stats: 'flex items-center gap-6 mt-6 text-sm text-gray-200 dark:text-gray-300',
    statItem:
        'flex items-center gap-2 bg-black/40 px-4 py-2.5 rounded-xl border border-white/10 transition-all duration-400 hover:scale-105 hover:bg-black/50 hover:border-white/20 backdrop-blur-sm',

    // Premium Badge for Stock Status
    stockBadge: (stock) =>
        stock > 10
            ? 'text-green-400 bg-green-500/20 border-green-400/30'
            : stock > 0
              ? 'text-yellow-400 bg-yellow-500/20 border-yellow-400/30'
              : 'text-red-400 bg-red-500/20 border-red-400/30',
};
</script>

<template>
    <div
        :class="[styleClass.card, variant % 2 ? styleClass.cardLayout.odd : styleClass.cardLayout.even]"
        :style="`transition-delay: ${variant * 50}ms`"
    >
        <!-- Enhanced Background Elements -->
        <div :class="styleClass.backgroundElements.glow" />
        <div :class="styleClass.backgroundElements.pulse" />
        <div :class="styleClass.backgroundElements.sparkle" />
        <div :class="styleClass.backgroundElements.orb1" />
        <div :class="styleClass.backgroundElements.orb2" />

        <!-- Content Container -->
        <div :class="styleClass.contentContainer">
            <div class="w-full space-y-6">
                <!-- Enhanced Title Section -->
                <div class="relative">
                    <h3 :class="styleClass.title">
                        {{ product.name }}
                        <span :class="styleClass.categoryBadge">
                            {{ product.category?.name || 'Uncategorized' }}
                            <svg
                                class="ml-2 h-4 w-4 transform transition-all duration-500 group-hover:scale-125 group-hover:rotate-180"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </h3>
                    <div :class="styleClass.titleUnderline" />
                </div>

                <!-- Enhanced Description -->
                <div v-if="product.description" :class="styleClass.description.container">
                    <p :class="styleClass.description.text">
                        {{ product.description.slice(0, 680) }}
                        <span v-if="product.description.length >= 680" :class="styleClass.description.ellipsis"> ... </span>
                    </p>

                    <!-- Enhanced Character Count -->
                    <div v-if="product.description.length >= 680" :class="styleClass.description.charCount">
                        📝 {{ product.description.length }} characters
                    </div>
                </div>

                <!-- Enhanced Stats -->
                <div :class="styleClass.stats">
                    <div :class="styleClass.statItem" v-if="product.price">
                        <span class="font-semibold">{{ Number(product.price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</span>
                    </div>

                    <div
                        :class="[styleClass.statItem, styleClass.stockBadge(product.stock)]"
                        v-if="product.stock !== undefined && product.stock !== null"
                    >
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span class="font-semibold">{{ product.stock }} in stock</span>
                    </div>
                </div>
            </div>

            <!-- Premium CTA Button -->
            <Link
                :href="route('shop.index', { filter: { category: product.category?.name } })"
                :class="styleClass.button"
                class="group/btn transform-gpu"
            >
                <!-- Enhanced Button Effects -->
                <div :class="styleClass.buttonEffects.ripple" />
                <div :class="styleClass.buttonEffects.shine" />

                <!-- Enhanced Button Content -->
                <span class="relative z-10 flex items-center gap-3 tracking-wide">
                    Shop Now
                    <svg
                        class="h-6 w-6 transition-all duration-500 group-hover/btn:translate-x-2 group-hover/btn:scale-110 group-hover/btn:-rotate-12"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </Link>
        </div>

        <!-- Enhanced Image Container -->
        <div :class="styleClass.imageContainer">
            <!-- Image Content -->
            <Carousel v-if="product?.images?.length" :imagesPath="imagesPath" class="h-full" />
            <img v-else :class="styleClass.image" :src="imagePlaceholder" :alt="`${product.name} - Premium product showcase`" loading="lazy" />
        </div>
    </div>
</template>
