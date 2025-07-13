<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        product: Object
    });

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

    const image_url = computed(() => {
        let src = props.product?.images[0]?.image_path;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
        return '/images/img-placeholder.jpg';
    });
</script>

<template>
    <Link type="button" as="button" :href="route('product.show', product.id)"
        class="flex w-full items-center hover:bg-gray-100 dark:hover:bg-gray-800 justify-between  p-3 rounded-md transition-all duration-200">
    <div class="flex items-center space-x-3 flex-1">
        <!-- Product Image -->
        <img :src="image_url" alt="Product Image" class="w-12 h-12 object-cover rounded-md shadow-sm">

        <div>
            <p class="font-medium dark:text-gray-300 text-left">{{ product.name }}</p>
            <div class="flex items-center mt-1">
                <!-- Stars -->
                <div class="flex text-yellow-400">
                    <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                    <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                    <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                </div>

                <!-- Review Count -->
                <span class="text-sm ml-1 text-gray-500 dark:text-gray-400">
                    ({{ product.ratings?.length }} reviews)
                </span>
            </div>
        </div>
    </div>

    <!-- Rating Number -->
    <div class="ml-3 font-bold text-yellow-400">
        {{ product.rating?.toFixed(2) }}/5
    </div>
    </Link>
</template>