<script setup>
    import { computed, ref } from 'vue';
    import ButtonPrimary from '../../components/ui/buttons/ButtonPrimary.vue';
    import Quantity from '../../components/ui/input/Quantity.vue';
    import ProductRating from '../../components/ui/card/ProductRating.vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        product: Object,
        filter: Object
    });

    const tempProduct = ref({ ...props.product, index: 0 });

    const focusedImage = ref(tempProduct.value.images?.[0]?.image_path || '');

    function changeFocusedImage(path) {
        focusedImage.value = path;
    }

    function changeTempProduct(p) {
        tempProduct.value = {
            ...props.product,
            ...p,
            index: p.index ?? 0,
            images: p.images ?? props.product.images,
        };
        focusedImage.value = tempProduct.value.images?.[0]?.image_path || '';
    }

    const starIcons = computed(() => {
        const rating = tempProduct.value.rating ?? 0;
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating - fullStars >= 0.5 && rating - fullStars < 0.75;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        return {
            full: fullStars,
            half: hasHalfStar,
            empty: emptyStars,
        };
    });

    function goBack() {
        router.get(route('shop.index'), { filter: props.filter });
    }

    const styleClass = {
        name: 'font-semibold text-xl',
        text: 'text-slate-600 leading-tight dark:text-slate-200',
        price: 'font-bold text-2xl',
        options: 'border border-slate-400 hover:border-secondary dark:hover:border-secondary-300 flex items-center gap-1 p-2 rounded cursor-pointer font-medium',
        ratingBadge: 'bg-secondary rounded-full py-0.5 px-3 inline-flex items-center gap-1.5 text-white',
    };
</script>


<template>
    <div class="md:pt-12 pt-2 px-1.5 pb-3">
        <div class="lg:border-2 max-w-7xl mx-auto lg:border-slate-400 rounded-lg py-3 px-2 sm:px-16">
            <button @click="goBack" class="flex items-center gap-3 mb-2 text-link">
                <i class="bi bi-arrow-left text-2xl"></i>
                <span>Back</span>
            </button>
            <div class="flex flex-col lg:flex-row  gap-6 ">
                <div class="flex-1 flex gap-2">
                    <div class="space-y-2">
                        <img class="h-20 cursor-pointer"
                            :class="{ 'border-2 border-secondary dark:border-white': image.image_path === focusedImage }"
                            v-for="image of tempProduct.images" :src="image.image_path" :key="image.id" alt=""
                            @click="changeFocusedImage(image.image_path)" />
                    </div>
                    <div class="flex-1">
                        <img :src="focusedImage ? focusedImage : '/images/img-placeholder.jpg'" alt=""
                            class="object-center object-cover w-full" />
                    </div>
                </div>
                <div class="flex-1 h-[32rem]  overflow-y-scroll">
                    <div class="border-b-2 pb-5 border-slate-300">
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <h4 :class="styleClass.name">{{ tempProduct.name }}</h4>
                                <span>{{ product.category.name }}</span>
                            </div>
                            <p :class="styleClass.text">{{ tempProduct.description }}</p>
                            <div class="flex font-bold gap-4" v-if="tempProduct.size">
                                <span>Size:</span>
                                <p>{{ tempProduct.size.replace(',', ' ') }}</p>
                            </div>
                        </div>
                        <div>
                            <p :class="styleClass.price">
                                {{ tempProduct.price.toLocaleString('en-PH', {
                                    style: 'currency',
                                    currency: 'PHP'
                                }) }}
                            </p>
                            <div class="flex gap-x-3 items-center">
                                <p :class="styleClass.ratingBadge">
                                    <span class="text-lg">
                                        {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                    </span>
                                    <i class="fa-solid fa-star"></i>
                                </p>

                                <p :class="styleClass.text">
                                    {{ tempProduct.ratings?.length || 0 }} ratings
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="border-b-2 pb-5 pt-6 border-slate-300 space-y-5">
                        <div class="space-y-3">
                            <span class="text-lg font-bold inline-block">Options</span>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <p @click="changeTempProduct({ ...props.product, index: 0 })"
                                    :class="[styleClass.options, tempProduct.index == 0 ? '!border-secondary dark:!border-secondary-300' : '']">
                                    <span class="font-bold">
                                        1 :
                                    </span>
                                    {{ props.product.name }}
                                </p>
                                <p v-for="(option, i) of product.options" :key="i"
                                    @click="changeTempProduct({ ...option, index: i + 1 })"
                                    :class="[styleClass.options, tempProduct.index == i + 1 ? '!border-secondary dark:!border-secondary-300' : '']">
                                    <span class="font-bold">
                                        {{ `${i + 2} :` }}
                                    </span>
                                    {{ option.name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span>
                                Quantity
                            </span>
                            <Quantity />
                        </div>
                        <div class="flex gap-x-3 gap-y-2 flex-col sm:flex-row">
                            <ButtonPrimary variant="outline-secondary" label="Add to Wishlist" />
                            <ButtonPrimary variant="secondary" label="Add to Cart" />
                            <ButtonPrimary variant="outline-secondary" label="Order now" />
                        </div>
                    </div>

                    <div class=" pb-5 pt-6 space-y-5">
                        <div class="space-y-3">
                            <span class="text-lg font-bold inline-block">Customer Reviews</span>
                        </div>
                        <div class="text-secondary dark:text-secondary-300 text-xl">
                            <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                            <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                            <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <span class="font-semibold text-3xl ">
                                {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                / 5
                            </span>
                            <span>
                                Based on {{ tempProduct.ratings?.length || 0 }} ratings
                            </span>
                        </div>

                        <!-- Ratings -->
                        <ProductRating v-for="rating of tempProduct.ratings" :rating="rating" :key="rating.id" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>