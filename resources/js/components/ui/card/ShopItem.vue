<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Carousel from '../Carousel.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
        validator: (product) => product.name && typeof product.price === 'number',
    },
    filter: Object,
});

const page = usePage();
const user = page.props.auth?.user;

// Computed properties
const starIcons = computed(() => {
    const rating = props.product.rating ?? 0;
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating - fullStars >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    return { full: fullStars, half: hasHalfStar, empty: emptyStars };
});

const formattedPrice = computed(() => {
    return Number(props.product.price).toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP',
    });
});

const descriptionSnippet = computed(() => {
    if (!props.product?.description) return '';
    return props.product.description.length > 60 ? props.product.description.substring(0, 60) + '...' : props.product.description;
});

const stockStatus = computed(() => {
    const stock = props.product.stock || 0;
    if (stock === 0) return { text: 'Out of stock', class: 'text-red-600' };
    if (stock < 5) return { text: `Only ${stock} left`, class: 'text-orange-600' };
    if (stock < 10) return { text: `${stock} left`, class: 'text-yellow-600' };
    return { text: 'In stock', class: 'text-green-600 dark:text-green-500' };
});

const isNewProduct = computed(() => {
    const createdAt = new Date(props.product.created_at);
    return (Date.now() - createdAt) / (1000 * 60 * 60 * 24) < 7;
});

function filterCategory() {
    router.get(route('shop.index'), {
        filter: { ...props.filter, category: props.product.category.name },
    });
}

// Style object with all Tailwind classes
const style = {
    card: {
        base: 'group relative overflow-hidden rounded-lg border border-gray-100 bg-white shadow-sm transition-all duration-300  hover:shadow-md sm:rounded-2xl sm:duration-500 sm:hover:shadow-xl dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-700',
    },
    category: {
        container: 'absolute top-2 right-2 z-20 sm:top-3 sm:right-3',
        button: 'bg-secondary-600 hover:bg-secondary-700 dark:bg-secondary-700 dark:hover:bg-secondary-600 rounded-lg px-2 py-1 text-[10px] font-medium text-white shadow-sm transition-all duration-200 hover:scale-105 hover:shadow-md sm:rounded-xl sm:px-4 sm:py-2 sm:text-xs sm:font-semibold sm:shadow-lg',
    },
    image: {
        container: 'relative aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900',
        overlay:
            'absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-0 transition-all duration-300 group-hover:opacity-100 sm:from-black/20 sm:duration-500 dark:from-black/30',
        badgesContainer: 'absolute bottom-2 left-2 flex gap-1 sm:bottom-3 sm:left-3 sm:gap-2',
        badgeNew:
            'z-20 rounded-full bg-green-500 px-2 py-0.5 text-[10px] font-bold text-white shadow sm:px-3 sm:py-1 sm:text-xs sm:shadow-lg dark:bg-green-600',
        badgeSale:
            'rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white shadow sm:px-3 sm:py-1 sm:text-xs sm:shadow-lg dark:bg-red-600',
    },
    productInfo: {
        container: 'space-y-3 p-3 sm:space-y-4 sm:p-5',
        titleSection: 'space-y-2 sm:space-y-3',
        productTitle: 'line-clamp-2 text-sm leading-tight font-semibold text-gray-900 sm:text-lg sm:font-bold dark:text-white',
        sizeBadge:
            'inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 sm:px-3 sm:py-1.5 sm:text-xs sm:font-semibold dark:bg-blue-900/30 dark:text-blue-300',
        sizeIcon: 'h-3 w-3',
        productDescription:
            ' h-fit rounded-lg bg-slate-100/50 p-2 text-xs leading-tight text-gray-600 sm:text-sm dark:bg-slate-100/10 dark:text-gray-300',
        metaSection: 'flex flex-col sm:flex-row sm:items-center sm:justify-between sm:gap-2',
        stockStatus: {
            base: 'inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium sm:px-3 sm:py-1.5 sm:text-xs sm:font-semibold',
            outOfStock: 'text-red-600 bg-red-50 dark:text-red-400 dark:bg-red-900/20',
            lowStock: 'text-orange-600 bg-orange-50 dark:text-orange-400 dark:bg-orange-900/20',
            mediumStock: 'text-yellow-600 bg-yellow-50 dark:text-yellow-400 dark:bg-yellow-900/20',
            inStock: 'text-green-600 bg-green-50 dark:text-green-400 dark:bg-green-900/20',
        },
        statusDot: 'h-1.5 w-1.5 rounded-full bg-current opacity-70',
        ratingSection: 'flex items-center gap-1.5 sm:gap-2',
        starsContainer: 'flex text-amber-500 dark:text-amber-400',
        starIcon: 'text-xs sm:text-sm',
        ratingScore: 'text-xs font-semibold text-gray-900 sm:text-sm sm:font-bold dark:text-white',
    },
    action: {
        container: 'flex items-center justify-between border-t border-gray-100 sm:pt-4 dark:border-gray-800',
        priceSection: 'flex flex-col',
        currentPrice: 'text-base font-bold text-gray-900 sm:text-xl dark:text-white',
        originalPrice: 'text-xs text-gray-500 line-through sm:text-sm dark:text-gray-400',
        viewButton:
            'bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 flex items-center gap-1 rounded-lg px-3 py-2 text-xs font-semibold text-white shadow-sm transition-all duration-200 hover:scale-105 hover:shadow-md active:scale-95 sm:gap-2 sm:rounded-xl sm:px-5 sm:py-3 sm:text-sm sm:shadow-lg flex justify-center',
        buttonIcon: 'h-3 w-3 transition-transform duration-300 group-hover:translate-x-0.5 sm:h-4 sm:w-4',
    },
};

const showFullDescription = ref(false);
</script>

<template>
    <div :class="[style.card.base, 'flex flex-col']">
        <!-- Category Badge -->
        <div :class="style.category.container">
            <button @click="filterCategory" :class="style.category.button" :title="`Filter by ${product.category.name}`">
                {{ product.category.name }}
            </button>
        </div>

        <!-- Product Image with Enhanced Overlay -->
        <div :class="style.image.container">
            <Carousel :images-path="product.images.map((e) => e.image_path)" />
            <div :class="style.image.overlay"></div>
            <div :class="style.image.badgesContainer">
                <span v-if="isNewProduct" :class="style.image.badgeNew">NEW</span>
                <span v-if="isOnSale" :class="style.image.badgeSale">-{{ discountPercentage }}%</span>
            </div>
        </div>

        <!-- Product Info -->
        <div :class="[style.productInfo.container, 'flex flex-1 flex-col']">
            <!-- Title & Size -->
            <div :class="style.productInfo.titleSection">
                <h3 :class="style.productInfo.productTitle">
                    {{ product.name }}
                </h3>
                <span v-if="product.show_size" :class="style.productInfo.sizeBadge">
                    <svg :class="style.productInfo.sizeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5"
                        />
                    </svg>
                    {{ product.show_size }}
                </span>
            </div>
            <!-- Description -->
            <p v-if="product?.description" :class="style.productInfo.productDescription">
                {{ showFullDescription ? product?.description : descriptionSnippet }}
                <button
                    v-if="product?.description?.length > 60"
                    @click="() => (showFullDescription = !showFullDescription)"
                    class="text-primary-500 dark:text-primary-400 ms-1 inline-block cursor-pointer"
                >
                    {{ showFullDescription ? 'hide full' : 'show full' }}
                </button>
            </p>

            <!-- Stock & Rating -->
            <div :class="style.productInfo.metaSection">
                <span :class="[style.productInfo.stockStatus.base, stockStatus.class]">
                    <span :class="style.productInfo.statusDot"></span>
                    {{ stockStatus.text }}
                </span>

                <div :class="style.productInfo.ratingSection">
                    <div :class="style.productInfo.starsContainer">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star" :class="style.productInfo.starIcon"></i>
                        <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke" :class="style.productInfo.starIcon"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star" :class="style.productInfo.starIcon"></i>
                    </div>
                    <span :class="style.productInfo.ratingScore">
                        {{ product.rating?.toFixed(1) ?? '0.0' }}
                    </span>
                </div>
            </div>
            <!-- Price & Action -->
            <div :class="[style.action.container, 'flex flex-1 flex-col items-stretch justify-end']">
                <div :class="style.action.priceSection">
                    <span :class="style.action.currentPrice">
                        {{ formattedPrice }}
                    </span>
                    <span v-if="isOnSale" :class="style.action.originalPrice">
                        {{ Number(product.original_price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                    </span>
                </div>

                <Link :href="route('shop.show', { product: product.id, filter: props.filter })" :class="style.action.viewButton">
                    <span>View</span>
                    <svg :class="style.action.buttonIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </div>
</template>
