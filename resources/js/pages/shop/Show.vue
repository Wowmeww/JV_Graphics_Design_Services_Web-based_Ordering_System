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
    index: props.product.options.findIndex((e) => e.id === props.option?.id) + 1,
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
    } else {
        routeParameters.option = null;
    }
    tempProduct.value = {
        ...props.product,
        ...p,
        index: p.index ?? 0,
        images: p.images?.length ? p?.images : props?.product?.images,
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
    const item = {
        product_id: props.product.id,
        option_id: tempProduct.value.type === 'Variant' ? tempProduct.value.id : null,
        quantity: form.quantity,
        total_amount: tempProduct.value.price,
    };
    router.get(route('order.create'), {
        item,
        from: null,
    });
}

function goBack() {
    window.history.back();
    // router.get(route('shop.index'), { filter: props.filter });
}

function image_url(src) {
    // let src = props.product?.images[0];
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/img-placeholder.jpg';
}

const styleClass = {
    name: 'font-semibold text-xl',
    text: 'text-slate-600 leading-tight dark:text-slate-200',
    price: 'font-bold text-2xl',
    options:
        'border border-slate-400 hover:border-secondary dark:hover:border-secondary-300 flex items-center gap-1 p-2 rounded cursor-pointer font-medium',
    ratingBadge: 'bg-secondary rounded-full py-0.5 px-3 inline-flex items-center gap-1.5 text-white',
};
</script>

<template>
    <div class="space-y-3 px-1.5 pt-5 pb-3 md:pt-12">
        <div class="mx-auto max-w-7xl rounded-lg px-2 py-3 sm:px-16 lg:border-2 lg:border-slate-400">
            <button @click="goBack" class="text-link mb-2 flex items-center gap-3">
                <i class="bi bi-arrow-left text-2xl"></i>
                <span>Back</span>
            </button>
            <div class="flex flex-col gap-6 lg:flex-row">
                <div class="flex flex-1 gap-2">
                    <div class="space-y-2">
                        <img
                            class="h-20 w-20 cursor-pointer"
                            :class="{ 'border-secondary border-2 object-cover object-center dark:border-white': image.image_path === focusedImage }"
                            v-for="image of tempProduct.images"
                            :src="image_url(image.image_path)"
                            :key="image.id"
                            alt=""
                            @click="changeFocusedImage(image.image_path)"
                        />
                    </div>
                    <div class="h-60 flex-1 sm:h-70 md:h-[30rem]">
                        <img :src="image_url(focusedImage)" alt="" class="h-full w-full object-cover object-center" />
                    </div>
                </div>
                <div class="h-[32rem] flex-1 overflow-y-auto">
                    <div class="border-b-2 border-slate-300 pb-5">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 :class="styleClass.name">{{ tempProduct.name }}</h4>
                                    <small
                                        :class="[
                                            'text-xs font-semibold',
                                            tempProduct.stock > 20 ? 'text-green-500 dark:text-green-400' : 'text-orange-500 dark:text-orange-400',
                                        ]"
                                        >Stocks - {{ tempProduct.stock }}</small
                                    >
                                </div>
                                <span>{{ product.category.name }}</span>
                            </div>
                            <p :class="styleClass.text">{{ tempProduct.description }}</p>
                            <div class="flex gap-4 font-bold" v-if="tempProduct.show_size">
                                <span>Size:</span>
                                <p>{{ tempProduct.show_size }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center justify-between px-2">
                                <div class="flex items-center gap-2">
                                    <p :class="styleClass.price">
                                        {{
                                            Number(tempProduct.price).toLocaleString('en-PH', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })
                                        }}
                                    </p>
                                    <span>Per piece</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    Total amount:
                                    <span class="text-xl font-semibold">
                                        {{
                                            (Number(tempProduct.price) * form.quantity).toLocaleString('en-PH', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <p :class="styleClass.ratingBadge">
                                    <span class="text-lg">
                                        {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                    </span>
                                    <i class="fa-solid fa-star"></i>
                                </p>
                                <p :class="styleClass.text">{{ tempProduct.ratings?.length || 0 }} ratings</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-5 border-b-2 border-slate-300 pt-6 pb-5">
                        <div class="space-y-3" v-if="product.options.length">
                            <span class="inline-block text-lg font-bold">Options</span>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <p
                                    @click="changeTempProduct({ ...props.product, index: 0 })"
                                    :class="[styleClass.options, tempProduct.index == 0 ? '!border-secondary dark:!border-secondary-300' : '']"
                                >
                                    <span class="font-bold"> 1 : </span>
                                    {{ props.product.name }}
                                </p>
                                <p
                                    v-for="(option, i) of product.options"
                                    :key="i"
                                    @click="changeTempProduct({ ...option, index: i + 1 })"
                                    :class="[styleClass.options, tempProduct.index == i + 1 ? '!border-secondary dark:!border-secondary-300' : '']"
                                >
                                    <span class="font-bold">
                                        {{ `${i + 2} :` }}
                                    </span>
                                    {{ option.name }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2" v-if="product.type !== 'custom'">
                            <div class="flex items-center justify-between px-2">
                                <span> Quantity </span>
                                <Quantity v-model="form.quantity" />
                            </div>
                            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row">
                                <ButtonPrimary
                                    :disabled="form.processing"
                                    @click="addToWishlist"
                                    variant="outline-secondary"
                                    label="Add to Wishlist"
                                />
                                <ButtonPrimary :disabled="form.processing" @click="addToCart" variant="secondary" label="Add to Cart" />
                                <ButtonPrimary :disabled="form.processing" @click="orderNow" variant="outline-secondary" label="Order now" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row" v-else-if="product.type === 'custom'">
                            <ButtonPrimary :href="route('designer', { product: product.id })" is="Link" variant="secondary" label="Customize" />
                        </div>
                    </div>

                    <div v-if="tempProduct.ratings?.length" class="space-y-5 pt-6 pb-5">
                        <div class="space-y-3">
                            <span class="inline-block text-lg font-bold">Customer Reviews</span>
                        </div>
                        <div class="text-secondary dark:text-secondary-300 text-xl">
                            <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                            <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                            <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <span class="text-3xl font-semibold">
                                {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                / 5
                            </span>
                            <span> Based on {{ tempProduct.ratings?.length || 0 }} ratings </span>
                        </div>

                        <!-- Ratings -->
                        <ProductRating v-for="rating of tempProduct.ratings" :rating="rating" :key="rating.id" />
                    </div>
                    <div v-else class="space-y-5 pt-6 pb-5">
                        <div class="space-y-3">
                            <span class="inline-block text-lg font-bold">No Customer Reviews Yet</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
