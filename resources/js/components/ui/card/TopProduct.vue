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
        class="flex w-full items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 justify-between p-3.5 rounded-lg transition-all duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
    <div class="flex items-center space-x-3.5 flex-1 min-w-0">
        <!-- Product Image -->
        <img :src="image_url" :alt="product.name"
            class="w-14 h-14 object-cover rounded-lg shadow-xs border border-gray-100 dark:border-gray-700">

        <div class="min-w-0">
            <p class="font-medium text-gray-900 dark:text-gray-100 text-left truncate">
                {{ product.name }}
            </p>
            <div class="flex items-center mt-1.5">
                <!-- Stars -->
                <div class="flex text-yellow-400 mr-1.5">
                    <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star text-[15px]"></i>
                    <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke text-[15px]"></i>
                    <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star text-[15px]"></i>
                </div>

                <!-- Review Count -->
                <span class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    ({{ product.ratings?.length }} reviews)
                </span>
            </div>
        </div>
    </div>

    <!-- Rating Number -->
    <div class="ml-3 font-bold text-yellow-400 text-lg whitespace-nowrap">
        {{ product.rating?.toFixed(2) }}/5
    </div>
    </Link>
</template>