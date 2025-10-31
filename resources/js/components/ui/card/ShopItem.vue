<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Carousel from '../Carousel.vue';

const props = defineProps({
    product: Object,
    filter: Object,
});

const page = usePage();
const user = page.props.auth?.user;

// ⭐️ Rating star logic

const starIcons = computed(() => {
    const rating = props.product.rating ?? 0;
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating - fullStars >= 0.5 && rating - fullStars < 0.75;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    return {
        full: fullStars,
        half: hasHalfStar,
        empty: emptyStars,
    };
});

function filterCategory() {
    router.get(route('shop.index'), { filter: { ...props.filter, category: props.product.category.name } });
}

const styleClass = {
    // Main card container
    card: {
        base: 'animate__animated animate__fadeIn relative w-full max-w-xs mx-auto space-y-2 rounded-lg border border-white/40 bg-gradient-to-br from-white/20 via-white/15 to-white/10 p-3 text-white shadow-lg backdrop-blur-sm transition-all duration-300 hover:border-white/60 hover:bg-white/25 hover:shadow-xl sm:max-w-72 sm:rounded-xl sm:p-4 sm:backdrop-blur-md md:space-y-3 md:p-5 md:backdrop-blur-lg dark:from-black/25 dark:via-black/20 dark:to-black/15 hover:dark:from-black/30 hover:dark:via-black/25 hover:dark:to-black/20 h-max',
    },

    // Category button
    category: {
        container: 'absolute top-2 right-2 z-20 sm:top-3 sm:right-3 md:top-4 md:right-4',
        button: 'from-primary-500 to-primary-600 hover:from-primary-400 hover:to-primary-500 rounded-lg border border-white/30 bg-gradient-to-r px-2 py-1 text-[9px] font-bold shadow-lg transition-all duration-200 hover:shadow-xl active:scale-95 sm:px-3 sm:py-1 sm:text-[10px] md:px-4 md:py-1.5 md:text-xs',
    },

    // Image section
    image: {
        container: 'group h-24 w-full overflow-hidden rounded-md shadow-lg sm:h-28 md:h-32 lg:h-40 xl:h-48 sm:rounded-lg md:rounded-xl',
        overlay:
            'absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-200 group-hover:opacity-100',
    },

    // Product info section
    productInfo: {
        container: 'space-y-1.5 sm:space-y-2 md:space-y-3',
        title: 'bg-gradient-to-r from-white via-gray-100 to-gray-200 bg-clip-text text-xs font-bold tracking-tight text-transparent line-clamp-2 min-h-[2rem] sm:text-sm sm:min-h-[2.5rem] md:text-base lg:text-lg xl:text-xl',
        size: 'rounded-md bg-gray-800/30 px-1.5 py-0.5 text-[9px] font-medium text-gray-100 sm:px-2 sm:py-1 sm:text-[10px] md:text-xs',
        description: {
            container:
                'line-clamp-2 rounded-md bg-white/10 px-1.5 py-1 text-[10px] leading-tight text-gray-100 backdrop-blur-sm sm:rounded-md sm:px-2 sm:py-1.5 sm:text-xs md:rounded-lg md:px-3 md:py-2 md:text-sm',
            ellipsis: 'text-primary-200 font-bold',
        },
    },

    // Stock and rating section
    meta: {
        container: 'flex flex-wrap items-center justify-between gap-1 sm:gap-1.5 md:gap-2',
        stock: 'rounded-md border border-white/10 bg-gradient-to-r from-gray-800/40 to-gray-900/40 px-1.5 py-0.5 text-[9px] font-bold text-gray-100 sm:px-2 sm:py-1 sm:text-[10px] md:px-3 md:py-1.5 md:text-xs',
        rating: {
            container:
                'flex items-center gap-1 rounded-md border border-white/10 bg-white/15 px-1.5 py-0.5 text-[9px] backdrop-blur-sm sm:gap-1.5 sm:px-2 sm:py-1 sm:text-[10px] md:px-3 md:py-1.5 md:text-xs',
            stars: 'flex text-yellow-300 drop-shadow-[0_1px_1px_rgba(0,0,0,0.2)] dark:text-amber-200',
            score: 'font-bold text-white text-[9px] sm:text-[10px] md:text-xs',
            count: 'font-medium text-gray-100 text-[9px] sm:text-[10px] md:text-xs',
        },
    },

    // Price and action section
    action: {
        container: 'flex items-center justify-between border-t border-white/10 pt-1.5 sm:pt-2 md:pt-3',
        price: 'bg-gradient-to-r from-white via-gray-100 to-gray-200 bg-clip-text text-xs font-bold tracking-tight text-transparent drop-shadow sm:text-sm md:text-base lg:text-lg xl:text-xl',
        button: {
            base: 'group from-secondary-500 to-secondary-600 hover:from-secondary-400 hover:to-secondary-500 relative overflow-hidden rounded-md border border-white/30 bg-gradient-to-r px-2 py-1 text-[9px] font-bold tracking-wide text-white uppercase shadow-lg transition-all duration-300 hover:border-white/50 active:scale-95 sm:rounded-lg sm:px-3 sm:py-1.5 sm:text-[10px] md:px-4 md:py-2 md:text-xs',
            shimmer:
                'absolute inset-0 translate-x-[-100%] -skew-x-12 transform bg-gradient-to-r from-white/20 via-transparent to-white/10 transition-transform duration-600 group-hover:translate-x-[100%]',
            content: 'relative z-10 flex items-center gap-0.5 sm:gap-1',
            icon: 'h-2 w-2 transition-transform duration-200 group-hover:translate-x-0.5 sm:h-2.5 sm:w-2.5 md:h-3 md:w-3',
        },
    },
};
</script>

<template>
    <div :class="styleClass.card.base">
        <!-- Category Button -->
        <div :class="styleClass.category.container">
            <button @click="filterCategory" :class="styleClass.category.button">
                {{ product.category.name }}
            </button>
        </div>

        <!-- Product Image Carousel -->
        <div :class="styleClass.image.container">
            <Carousel :images-path="product.images.map((e) => e.image_path)" />
            <div :class="styleClass.image.overlay"></div>
        </div>

        <!-- Product Info -->
        <div :class="styleClass.productInfo.container">
            <div class="space-y-1 sm:space-y-1.5 md:space-y-2">
                <!-- Product Title -->
                <h3 :class="styleClass.productInfo.title">
                    {{ product.name }}
                </h3>

                <span v-if="product.show_size" :class="styleClass.productInfo.size">
                    {{ product.show_size }}
                </span>
            </div>

            <!-- Description Snippet -->
            <p v-if="product?.description" :class="styleClass.productInfo.description.container">
                {{ product?.description?.substring(0, 50) }}
                <span v-show="product?.description?.length >= 50" :class="styleClass.productInfo.description.ellipsis"> &hellip; </span>
            </p>

            <div :class="styleClass.meta.container">
                <span :class="styleClass.meta.stock"> {{ product.stock }} left </span>

                <!-- Rating Stars -->
                <div :class="styleClass.meta.rating.container">
                    <div :class="styleClass.meta.rating.stars">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star text-[8px] sm:text-[9px] md:text-[10px]"></i>
                        <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke text-[8px] sm:text-[9px] md:text-[10px]"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star text-[8px] sm:text-[9px] md:text-[10px]"></i>
                    </div>
                    <span :class="styleClass.meta.rating.score">
                        {{ product.rating?.toFixed(1) ?? '0.0' }}
                    </span>
                    <span v-if="product.ratings?.length" :class="styleClass.meta.rating.count"> ({{ product.ratings.length }}) </span>
                </div>
            </div>
        </div>

        <!-- Price and Action -->
        <div :class="styleClass.action.container">
            <!-- Price -->
            <span :class="styleClass.action.price">
                {{ Number(product.price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>

            <!-- View Product Button -->
            <Link :href="route('shop.show', { product: product.id, filter: props.filter })" :class="styleClass.action.button.base">
                <div :class="styleClass.action.button.shimmer"></div>
                <span :class="styleClass.action.button.content">
                    View
                    <svg :class="styleClass.action.button.icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </Link>
        </div>
    </div>
</template>
