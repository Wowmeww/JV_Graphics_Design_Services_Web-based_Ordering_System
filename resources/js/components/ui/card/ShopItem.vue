<script setup>
    import { router, usePage } from "@inertiajs/vue3";
    import { computed } from "vue";
    import Carousel from "../Carousel.vue";


    const props = defineProps({
        product: Object,
        filter: Object
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
        class="h-fit relative animate__animated animate__fadeIn min-w-40 max-w-72 transition md:w-full text-white rounded-xl glass-card p-2 md:p-4 bg-white/5 hover:bg-white/10 dark:bg-black/5 hover:dark:bg-black/10 border border-white/40 space-y-2">
        <!-- Category Button -->
        <button @click="filterCategory"
            class="absolute top-2 right-4 z-20 px-3 py-1 text-xs font-medium bg-primary-500/90 rounded-full dark:bg-blue-400 shadow-md hover:opacity-90 transition">
            {{ product.category.name }}
        </button>

        <!-- Product Image Carousel -->
        <div class="w-full h-32 sm:h-36 md:h-44 lg:h-48 rounded-lg overflow-hidden">
            <Carousel :images-path="product.images.map(e => e.image_path)" />
        </div>

        <!-- Product Info -->
        <div class="md:space-y-2 space-y-1">
            <!-- Product Title -->
            <h3 class="md:text-xl text-lg font-semibold leading-tight">
                {{ product.name }}
            </h3>

            <!-- Description Snippet -->
            <p class="text-sm text-gray-200 line-clamp-2">
                {{ product?.description?.substring(0, 65) }}
                <span v-show="product?.description?.length >= 65">&hellip;</span>
            </p>
            <!-- Rating Stars -->
            <div class="flex gap-1 items-center text-xs md:text-sm">
                <div class="flex dark:text-amber-300 text-yellow-400">
                    <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                    <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                    <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                </div>
                <span>{{ product.rating?.toFixed(1) ?? '0.0' }}</span>
                <span v-if="product.ratings?.length" class="text-gray-400">({{ product.ratings.length }})</span>
            </div>
        </div>

        <!-- Price and Add to Cart -->
        <div class="flex justify-between items-center pt-1">
            <!-- Price -->
            <span class="md:text-xl text-lg font-bold">
                {{ product.price.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>

            <!-- Add to Cart Button -->
            <Link :href="route('shop.show', { product: product.id, filter: props.filter })" v-if="!user?.is_admin"
                class="bg-secondary text-white text-center text-xs md:text-sm px-3 md:px-4 py-1 rounded-lg border border-white hover:opacity-90 active:opacity-80 transition-all uppercase font-medium grid place-content-center shadow-sm hover:shadow">
            Add to cart
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
        transition: background 0.3s ease, border 0.3s ease;
    }

    .glass-card:hover {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
</style>