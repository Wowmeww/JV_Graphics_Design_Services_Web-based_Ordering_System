<script setup>
    import { computed, reactive, ref } from 'vue';
    import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
    import Quantity from '@/components/ui/input/Quantity.vue';
    import ProductRating from '@/components/ui/card/ProductRating.vue';
    import { router, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        wishlistItem: Object
    });

    const product = reactive(props.wishlistItem.option || props.wishlistItem.product);
    product.category = props.wishlistItem.product.category

    const form = useForm({
        quantity: props.wishlistItem.quantity
    });

    const focusedImage = ref(product.images[0]?.image_path);

    function changeFocusedImage(path) {
        focusedImage.value = path;
    }

    const starIcons = computed(() => {
        const rating = product.rating || 0;
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating - fullStars >= 0.5 && rating - fullStars < 0.75;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        return {
            full: fullStars,
            half: hasHalfStar,
            empty: emptyStars,
        };
    });

    function update() {
        form.patch(route('wishlist.update', props.wishlistItem.id));
    }

    function orderNow() {
        form.post(route('shop.order', routeParameters));
    }

    function handleDelete() {
        router.delete(route('wishlist.destroy', { wishlistItem: props.wishlistItem.id, from: 'edit' }));
    }

    function goBack() {
        // router.get(route('shop.index'));
        window.history.back();
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
    <div class="md:pt-12 pt-5 px-1.5 pb-3 space-y-3">
        <div class="lg:border-2 max-w-7xl mx-auto lg:border-slate-400 rounded-lg py-3 px-2 sm:px-16">
            <button @click="goBack" class="flex items-center gap-3 mb-2 text-link">
                <i class="bi bi-arrow-left text-2xl"></i>
                <span>Exit</span>
            </button>
            <div class="flex flex-col lg:flex-row  gap-6 ">
                <div class="flex-1 flex gap-2">
                    <div class="space-y-2">
                        <img class="h-20 w-20 cursor-pointer"
                            :class="{ 'border-2 border-secondary object-center object-cover dark:border-white': image.image_path === focusedImage }"
                            v-for="image of product.images" :src="image.image_path" :key="image.id" alt=""
                            @click="changeFocusedImage(image.image_path)" />
                    </div>
                    <div class="flex-1 md:h-[30rem] h-60 sm:h-70">
                        <img :src="focusedImage ? focusedImage : '/images/img-placeholder.jpg'" alt=""
                            class="object-center object-cover w-full h-full" />
                    </div>
                </div>
                <div class="flex-1 h-[32rem]  overflow-y-scroll">
                    <div class="border-b-2 pb-5 border-slate-300">
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center px-2">
                                <h4 :class="styleClass.name">{{ product.name }}</h4>
                                <span>{{ product.category.name }}</span>
                            </div>
                            <p :class="styleClass.text">{{ product.description }}</p>
                            <div class="flex font-bold gap-4" v-if="product.size">
                                <span>Size:</span>
                                <p>{{ product.size.replace(',', ' ') }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center flex-wrap px-2">
                                <div class="flex items-center gap-2">
                                    <p :class="styleClass.price">
                                        {{ product.price.toLocaleString('en-PH', {
                                            style: 'currency',
                                            currency: 'PHP'
                                        }) }}
                                    </p>

                                    <span>Per piece</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    Total amount:
                                    <span class="font-semibold text-xl">
                                        {{ (product.price * form.quantity).toLocaleString('en-PH', {
                                            style: 'currency',
                                            currency: 'PHP'
                                        }) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-x-3 items-center">
                                <p :class="styleClass.ratingBadge">
                                    <span class="text-lg">
                                        {{ Number(product.rating || 0).toFixed(2) }}
                                    </span>
                                    <i class="fa-solid fa-star"></i>
                                </p>

                                <p :class="styleClass.text">
                                    {{ product.ratings?.length || 0 }} ratings
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="border-b-2 pb-5 pt-6 border-slate-300 space-y-5">

                        <div class="flex justify-between items-center px-2">
                            <span>
                                Quantity
                            </span>
                            <Quantity v-model="form.quantity" />
                        </div>
                        <div class="flex gap-x-3 gap-y-2 flex-col sm:flex-row">
                            <ButtonPrimary :disabled="form.processing" variant="outline-danger" @click="handleDelete"
                                label="Remove to Wishlist" />
                            <ButtonPrimary :disabled="form.processing" @click="update" variant="secondary"
                                label="Update Wishlist" />
                            <ButtonPrimary :disabled="form.processing" @click="orderNow" variant="outline-secondary"
                                label="Order now" />
                        </div>
                    </div>

                    <div v-if="true" class=" pb-5 pt-6 space-y-5">
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
                                {{ Number(product.rating || 0).toFixed(2) }}
                                / 5
                            </span>
                            <span>
                                Based on {{ product.ratings?.length || 0 }} ratings
                            </span>
                        </div>

                        <!-- Ratings -->
                        <ProductRating v-for="rating of product.ratings" :rating="rating" :key="rating.id" />
                    </div>
                    <div v-else class=" pb-5 pt-6 space-y-5">
                        <div class="space-y-3">
                            <span class="text-lg font-bold inline-block">No Customer Reviews Yet</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>