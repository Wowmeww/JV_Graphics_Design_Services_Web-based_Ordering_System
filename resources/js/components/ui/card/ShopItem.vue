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
        base: 'animate__animated animate__fadeIn glass-card relative h-fit max-w-72 min-w-40 space-y-2 rounded-3xl border border-white/40 bg-gradient-to-br from-white/15 via-white/10 to-white/5 p-3 text-white shadow-2xl backdrop-blur-lg transition-all duration-500 hover:scale-[1.03] hover:border-white/60 hover:bg-white/20 hover:shadow-3xl md:w-full md:space-y-3 md:p-5 dark:from-black/20 dark:via-black/15 dark:to-black/10 hover:dark:from-black/25 hover:dark:via-black/20 hover:dark:to-black/15',
    },

    // Category button
    category: {
        container: 'absolute top-2 right-3 z-20 flex flex-col items-end gap-1 md:top-3 md:right-4 md:gap-2',
        button: 'from-primary-500 to-primary-600 hover:from-primary-400 hover:to-primary-500 rounded-full border border-white/20 bg-gradient-to-r px-2 py-1 text-[10px] font-bold shadow-xl transition-all duration-300 hover:scale-110 hover:shadow-2xl active:scale-105 md:px-4 md:py-1.5 md:text-xs',
    },

    // Image section
    image: {
        container: 'group h-28 w-full overflow-hidden rounded-xl shadow-2xl sm:h-32 md:h-40 lg:h-48 md:rounded-2xl',
        overlay:
            'absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100',
    },

    // Product info section
    productInfo: {
        container: 'space-y-2 md:space-y-4',
        title: 'bg-gradient-to-r from-white via-gray-100 to-gray-200 bg-clip-text text-sm leading-tight font-black tracking-tight text-transparent md:text-xl lg:text-2xl',
        size: 'rounded-full bg-gray-800/30 px-1.5 py-0.5 text-[10px] font-semibold text-gray-100 md:px-2 md:py-1 md:text-xs',
        description: {
            container:
                'line-clamp-2 rounded-lg bg-white/5 px-2 py-1.5 text-xs leading-relaxed text-gray-100 backdrop-blur-sm md:rounded-xl md:px-3 md:py-2 md:text-sm',
            ellipsis: 'text-primary-200 font-bold',
        },
    },

    // Stock and rating section
    meta: {
        container: 'flex flex-wrap items-center justify-between gap-1 md:gap-2',
        stock: 'block rounded-full border border-white/10 bg-gradient-to-r from-gray-800/40 to-gray-900/40 px-2 py-1 text-[10px] font-black text-gray-100 md:px-3 md:py-1.5 md:text-xs',
        rating: {
            container:
                'flex items-center gap-1 rounded-full border border-white/10 bg-white/15 px-2 py-1 text-[10px] backdrop-blur-sm md:gap-1.5 md:px-3 md:py-1.5 md:text-xs lg:text-sm',
            stars: 'flex text-yellow-300 drop-shadow-lg dark:text-amber-200',
            score: 'font-black text-white text-[10px] md:text-xs',
            count: 'font-semibold text-gray-100 text-[10px] md:text-xs',
        },
    },

    // Price and action section
    action: {
        container: 'flex items-center justify-between border-t border-white/10 pt-2 md:pt-3',
        price: 'bg-gradient-to-r from-white via-gray-100 to-gray-200 bg-clip-text text-sm font-black tracking-tight text-transparent drop-shadow-lg md:text-xl lg:text-2xl',
        button: {
            base: 'group from-secondary-500 to-secondary-600 hover:from-secondary-400 hover:to-secondary-500 hover:shadow-3xl relative overflow-hidden rounded-xl border border-white/30 bg-gradient-to-r px-3 py-1.5 text-[10px] font-black tracking-wider text-white uppercase shadow-2xl transition-all duration-400 hover:scale-110 hover:border-white/50 active:scale-105 md:rounded-2xl md:px-5 md:py-2.5 md:text-xs',
            shimmer:
                'absolute inset-0 translate-x-[-100%] -skew-x-12 transform bg-gradient-to-r from-white/20 via-transparent to-white/10 transition-transform duration-700 group-hover:translate-x-[100%]',
            content: 'relative z-10 flex items-center gap-1',
            icon: 'h-2.5 w-2.5 transition-transform duration-300 group-hover:translate-x-0.5 md:h-3.5 md:w-3.5',
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
            <div class="space-y-1 md:space-y-2">
                <!-- Product Title -->
                <h3 :class="styleClass.productInfo.title">
                    {{ product.name }}
                </h3>

                <span v-if="product.size" :class="styleClass.productInfo.size"> Size: {{ product.size }} </span>
            </div>

            <!-- Description Snippet -->
            <p :class="styleClass.productInfo.description.container">
                {{ product?.description?.substring(0, 65) }}
                <span v-show="product?.description?.length >= 65" :class="styleClass.productInfo.description.ellipsis">&hellip;</span>
            </p>

            <div :class="styleClass.meta.container">
                <span :class="styleClass.meta.stock"> Stocks - {{ product.stock }} </span>

                <!-- Rating Stars -->
                <div :class="styleClass.meta.rating.container">
                    <div :class="styleClass.meta.rating.stars">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star text-shadow text-[10px] md:text-xs"></i>
                        <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke text-shadow text-[10px] md:text-xs"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star text-shadow text-[10px] md:text-xs"></i>
                    </div>
                    <span :class="styleClass.meta.rating.score">{{ product.rating?.toFixed(1) ?? '0.0' }}</span>
                    <span v-if="product.ratings?.length" :class="styleClass.meta.rating.count">({{ product.ratings.length }})</span>
                </div>
            </div>
        </div>

        <!-- Price and Add to Cart -->
        <div :class="styleClass.action.container">
            <!-- Price -->
            <span :class="styleClass.action.price">
                {{ Number(product.price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>

            <!-- Add to Cart Button -->
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

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.18);
    box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.18),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.45);
    transition:
        background 0.4s ease,
        border 0.4s ease,
        transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
        box-shadow 0.4s ease;
}

.glass-card:hover {
    background: rgba(255, 255, 255, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.7);
    box-shadow:
        0 12px 40px rgba(0, 0, 0, 0.25),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
}

.text-shadow {
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mobile-specific optimizations */
@media (max-width: 768px) {
    .glass-card {
        backdrop-filter: blur(12px) saturate(160%);
        -webkit-backdrop-filter: blur(12px) saturate(160%);
    }
}
</style>
