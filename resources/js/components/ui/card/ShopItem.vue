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
</script>
<template>
    <!-- Enhanced style without changing structure -->
    <div
        class="animate__animated animate__fadeIn glass-card relative h-fit max-w-72 min-w-40 space-y-2 rounded-2xl border border-white/30 bg-gradient-to-br from-white/10 to-white/5 p-3 text-white shadow-lg backdrop-blur-sm transition-all duration-300 hover:scale-[1.02] hover:bg-white/15 hover:shadow-xl md:w-full md:p-4 dark:from-black/10 dark:to-black/5 hover:dark:from-black/15 hover:dark:to-black/10"
    >
        <!-- Category Button -->
        <div class="absolute top-2 right-4 z-20 flex flex-col items-end gap-2">
            <button
                @click="filterCategory"
                class="bg-primary-500/95 hover:bg-primary-400 dark:bg-primary-600 rounded-full px-3 py-1 text-xs font-semibold shadow-lg transition-all duration-200 hover:scale-105 hover:shadow-xl"
            >
                {{ product.category.name }}
            </button>
        </div>

        <!-- Product Image Carousel -->
        <div class="h-32 w-full overflow-hidden rounded-xl shadow-inner sm:h-36 md:h-44 lg:h-48">
            <Carousel :images-path="product.images.map((e) => e.image_path)" />
        </div>

        <!-- Product Info -->
        <div class="space-y-2 md:space-y-3">
            <div>
                <!-- Product Title -->
                <h3 class="bg-gradient-to-r from-white to-gray-200 bg-clip-text text-lg leading-tight font-bold text-transparent md:text-xl">
                    {{ product.name }}
                </h3>

                <span v-if="product.size" class="font-medium text-gray-200">Size: {{ product.size }}</span>
            </div>

            <!-- Description Snippet -->
            <p class="line-clamp-2 text-sm leading-relaxed text-gray-300">
                {{ product?.description?.substring(0, 65) }}
                <span v-show="product?.description?.length >= 65">&hellip;</span>
            </p>
            <div class="flex items-center justify-between flex-wrap gap-1">
                <span class="block rounded-full bg-gray-800/30 px-2 py-1 text-right text-xs font-bold text-gray-200"
                    >Stocks - {{ product.stock }}</span
                >

                <!-- Rating Stars -->
                <div class="flex items-center gap-1 rounded-full bg-white/10 px-2 py-1 text-xs md:text-sm">
                    <div class="flex text-yellow-400 dark:text-amber-300">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                        <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                    </div>
                    <span class="font-semibold">{{ product.rating?.toFixed(1) ?? '0.0' }}</span>
                    <span v-if="product.ratings?.length" class="text-gray-100">({{ product.ratings.length }})</span>
                </div>
            </div>
        </div>

        <!-- Price and Add to Cart -->
        <div class="flex items-center justify-between pt-2">
            <!-- Price -->
            <span class="bg-gradient-to-r from-white to-gray-300 bg-clip-text text-lg font-extrabold text-transparent md:text-xl">
                {{ Number(product.price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>

            <!-- Add to Cart Button -->
            <Link
                :href="route('shop.show', { product: product.id, filter: props.filter })"
                class="bg-secondary hover:bg-secondary-600 grid place-content-center rounded-xl border border-white/20 px-4 py-2 text-center text-xs font-bold tracking-wide text-white uppercase shadow-lg transition-all duration-300 hover:scale-105 hover:border-white/30 hover:shadow-2xl active:scale-95 md:px-5 md:text-sm"
            >
                View
            </Link>
        </div>
    </div>
</template>

<style scoped>
.glass-card {
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
    backdrop-filter: blur(12.6px);
    -webkit-backdrop-filter: blur(12.6px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    transition:
        background 0.3s ease,
        border 0.3s ease;
}

.glass-card:hover {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.6);
}
</style>
