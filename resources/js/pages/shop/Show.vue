<script setup>
    import { computed, reactive, ref } from 'vue';
    import ButtonPrimary from '../../components/ui/buttons/ButtonPrimary.vue';
    import Quantity from '../../components/ui/input/Quantity.vue';
    import ProductRating from '../../components/ui/card/ProductRating.vue';
    import { router, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        product: Object,
        option: Object,
        quantity: 12,
        filter: Object,
        // status: Object
    });

    const tempProduct = ref({
        ...(props.option ?? props.product),
        index: props.product.options.findIndex(
            (e) => e.id === props.option?.id
        ) + 1

    });

    const routeParameters = reactive({
        product: props.product,
        option: props.option?.id || null,
    });
    const form = useForm({
        quantity: props.quantity,
    });

    const focusedImage = ref(tempProduct.value.images?.[0]?.image_path || '');

    function changeFocusedImage(path) {
        focusedImage.value = path;
    }

    function changeTempProduct(p) {
        if (p.type == 'Variant') {
            routeParameters.option = p;
        }
        else {
            routeParameters.option = null;
        }
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

    function addToCart() {
        form.post(route('cart.store', routeParameters));
    }

    function addToWishlist() {
        form.post(route('wishlist.store', routeParameters));
    }
    function orderNow() {
        router.get(route('order.create'), {
            product_id: props.product.id,
            option_id: tempProduct.value.type === 'Variant' ? tempProduct.value.id : null,
            quantity: form.quantity,
            total_amount: tempProduct.value.price,
        });
    }

    function goBack() {
        window.history.back();
        // router.get(route('shop.index'), { filter: props.filter });
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
                <span>Back</span>
            </button>
            <div class="flex flex-col lg:flex-row  gap-6 ">
                <div class="flex-1 flex gap-2">
                    <div class="space-y-2">
                        <img class="h-20 w-20 cursor-pointer"
                            :class="{ 'border-2 border-secondary object-center object-cover dark:border-white': image.image_path === focusedImage }"
                            v-for="image of tempProduct.images" :src="image.image_path" :key="image.id" alt=""
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
                            <div class="flex justify-between items-center flex-wrap px-2">
                                <div class="flex items-center gap-2">
                                    <p :class="styleClass.price">
                                        {{ tempProduct.price.toLocaleString('en-PH', {
                                            style: 'currency',
                                            currency: 'PHP'
                                        }) }}
                                    </p>

                                    <span>Per piece</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    Total amount:
                                    <span class="font-semibold text-xl">
                                        {{ (tempProduct.price * form.quantity).toLocaleString('en-PH', {
                                            style: 'currency',
                                            currency: 'PHP'
                                        }) }}
                                    </span>
                                </div>
                            </div>
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

                        <div class="flex justify-between items-center px-2">
                            <span>
                                Quantity
                            </span>
                            <Quantity v-model="form.quantity" />
                        </div>
                        <div class="flex gap-x-3 gap-y-2 flex-col sm:flex-row">
                            <ButtonPrimary :disabled="form.processing" @click="addToWishlist"
                                variant="outline-secondary" label="Add to Wishlist" />
                            <ButtonPrimary :disabled="form.processing" @click="addToCart" variant="secondary"
                                label="Add to Cart" />
                            <ButtonPrimary :disabled="form.processing" @click="orderNow" variant="outline-secondary"
                                label="Order now" />
                        </div>
                    </div>

                    <div v-if="tempProduct.ratings?.length" class=" pb-5 pt-6 space-y-5">
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