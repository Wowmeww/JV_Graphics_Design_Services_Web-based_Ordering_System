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
    <div
        class="animate__animated animate__fadeIn glass-card relative h-fit max-w-72 min-w-40 space-y-2 rounded-xl border border-white/40 bg-white/5 p-2 text-white transition hover:bg-white/10 md:w-full md:p-4 dark:bg-black/5 hover:dark:bg-black/10"
    >
        <!-- Category Button -->
        <div class="absolute top-2 right-4 z-20 flex flex-col items-end gap-2">
            <button
                @click="filterCategory"
                class="bg-primary-500/90 rounded-full px-3 py-1 text-xs font-medium shadow-md transition hover:opacity-90 dark:bg-blue-400"
            >
                {{ product.category.name }}
            </button>
            <!-- <small class="bg-secondary-500/90 rounded-full px-3 py-1 text-xs font-medium shadow-md transition hover:opacity-90 dark:bg-blue-400">
                stocks:
                {{ product.stock || '0' }}
            </small> -->
        </div>

        <!-- Product Image Carousel -->
        <div class="h-32 w-full overflow-hidden rounded-lg sm:h-36 md:h-44 lg:h-48">
            <Carousel :images-path="product.images.map((e) => e.image_path)" />
        </div>

        <!-- Product Info -->
        <div class="space-y-1 md:space-y-2">
            <div>
                <!-- Product Title -->
                <h3 class="text-lg leading-tight font-semibold md:text-xl">
                    {{ product.name }}
                </h3>

                <span v-if="product.size" class="font-medium">Size: {{ product.size }}</span>
            </div>

            <!-- Description Snippet -->
            <p class="line-clamp-2 text-sm text-gray-200">
                {{ product?.description?.substring(0, 65) }}
                <span v-show="product?.description?.length >= 65">&hellip;</span>
            </p>
            <div class="flex items-center justify-between">
                <span class="block text-right text-xs font-semibold">Stocks - {{ product.stock }}</span>

                <!-- Rating Stars -->
                <div class="flex items-center gap-1 text-xs md:text-sm">
                    <div class="flex text-yellow-400 dark:text-amber-300">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                        <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                    </div>
                    <span>{{ product.rating?.toFixed(1) ?? '0.0' }}</span>
                    <span v-if="product.ratings?.length" class="text-gray-400">({{ product.ratings.length }})</span>
                </div>
            </div>
        </div>

        <!-- Price and Add to Cart -->
        <div class="flex items-center justify-between pt-1">
            <!-- Price -->
            <span class="text-lg font-bold md:text-xl">
                {{ Number(product.price).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>

            <!-- Add to Cart Button -->
            <Link
                :href="route('shop.show', { product: product.id, filter: props.filter })"
                class="bg-secondary grid place-content-center rounded-lg border border-white px-3 py-1 text-center text-xs font-medium text-white uppercase shadow-sm transition-all hover:opacity-90 hover:shadow active:opacity-80 md:px-4 md:text-sm"
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
