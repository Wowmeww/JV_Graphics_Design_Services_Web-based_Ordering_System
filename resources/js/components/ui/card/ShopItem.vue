<script setup>
    import { usePage } from "@inertiajs/vue3";
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
</script>


<template>
    <div
        class="h-fit animate__animated animate__fadeIn min-w-40 max-w-74 transition md:w-full text-white rounded-md glass p-4 bg-white/5 hover:bg-white/10 dark:bg-black/5 hover:dark:bg-black/10 border-1 border-white/40 space-y-2">
        <div class=" w-full h-40 md:h-60 lg:h-62">
            <Carousel :images-path="product.images.map(e => e.image_path)" />
        </div>
        <div class="flex justify-end gap-0.5 py-2">
            <i class="fa-solid fa-eye p-1 rounded hover:bg-white/40 cursor-pointer"></i>
            <i class="fa-solid fa-heart p-1 rounded hover:bg-white/40 cursor-pointer"></i>
            <i class="fa-regular fa-heart p-1 rounded hover:bg-white/40 cursor-pointer"></i>
        </div>
        <div class="md:space-y-3 space-y-1.5">
            <h3 class="md:text-xl text-lg leading-4 md:leading-5 lg:leading-8">
                {{ product.name }}
            </h3>

            <p>
                {{ product?.description?.substring(0, 65) }}<span v-show="product?.description?.length >= 65">...</span>
            </p>

            <div class="flex gap-2 items-center text-xs md:text-base">
                <div class="flex dark:text-amber-300 text-yellow-500">
                    <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                    <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                    <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                </div>
                <span>{{ product.rating?.toFixed(1) ?? '0.0' }}</span>
                <span v-if="product.ratings?.length">({{ product.ratings.length }})</span>
            </div>

        </div>

        <div class="flex justify-between gap-2">
            <span class="md:text-xl text-lg font-bold">
                {{ product.price.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                }) }}
            </span>

            <Link :href="route('shop.show', { product: product.id, filter: props.filter })"
                v-if="!user?.is_admin"
                class="bg-secondary text-center md:px-5 px-2 rounded-lg py-1 border text-[8px] sm:text-xs  uppercase font-medium border-white hover:opacity-70 transition grid place-content-center">
            Add to cart
            </Link>
        </div>
    </div>
</template>

<style scoped>

    .glass {
        /* From https://css.glass */
        /* background: rgba(255, 255, 255, 0.21); */
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(12.6px);
        -webkit-backdrop-filter: blur(12.6px);
        /* border: 1px solid rgba(255, 255, 255, 0.89); */
    }

</style>
