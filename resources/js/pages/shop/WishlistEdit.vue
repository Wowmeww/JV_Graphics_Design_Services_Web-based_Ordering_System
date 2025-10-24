<script setup>
import { computed, reactive, ref } from 'vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import Quantity from '@/components/ui/input/Quantity.vue';
import ProductRating from '@/components/ui/card/ProductRating.vue';
import { router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    wishlistItem: Object,
});

const product = reactive(props.wishlistItem.option || props.wishlistItem.product);
product.category = props.wishlistItem.product.category;

const form = useForm({
    quantity: props.wishlistItem.quantity,
});

const focusedImage = ref(image_url(product.images[0]?.image_path));

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
    router.get(route('order.create'), { from: 'wishlist', items: [props.wishlistItem.id] });
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
    options:
        'border border-slate-400 hover:border-secondary dark:hover:border-secondary-300 flex items-center gap-1 p-2 rounded cursor-pointer font-medium',
    ratingBadge: 'bg-secondary rounded-full py-0.5 px-3 inline-flex items-center gap-1.5 text-white',
};

function image_url(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
}
</script>

<template>
    <div class="space-y-3 px-1.5 pt-5 pb-3 md:pt-12">
        <div class="mx-auto max-w-7xl rounded-lg px-2 py-3 sm:px-16 lg:border-2 lg:border-slate-400">
            <button @click="goBack" class="text-link mb-2 flex items-center gap-3">
                <i class="bi bi-arrow-left text-2xl"></i>
                <span>Exit</span>
            </button>
            <div class="flex flex-col gap-6 lg:flex-row">
                <div class="flex flex-1 gap-2">
                    <div class="space-y-2">
                        <img
                            class="h-20 w-20 cursor-pointer"
                            :class="{ 'border-secondary border-2 object-cover object-center dark:border-white': image.image_path === focusedImage }"
                            v-for="image of product.images"
                            :src="image_url(image.image_path)"
                            :key="image.id"
                            alt=""
                            @click="changeFocusedImage(image_url(image.image_path))"
                        />
                    </div>
                    <div class="h-60 flex-1 sm:h-70 md:h-[30rem]">
                        <img
                            :src="focusedImage ? focusedImage : '/images/img-placeholder.jpg'"
                            alt=""
                            class="h-full w-full object-cover object-center"
                        />
                    </div>
                </div>
                <div class="h-[32rem] flex-1 overflow-y-auto">
                    <div class="border-b-2 border-slate-300 pb-5">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between px-2">
                                <h4 :class="styleClass.name">{{ product.name }}</h4>
                                <span>{{ product.category.name }}</span>
                            </div>
                            <p :class="styleClass.text">{{ product.description }}</p>
                            <div class="flex gap-4 font-bold" v-if="product.show_size">
                                <span>Size:</span>
                                <p>{{ product.show_size }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center justify-between px-2">
                                <div class="flex items-center gap-2">
                                    <p :class="styleClass.price">
                                        {{
                                            product.price.toLocaleString('en-PH', {
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
                                            (product.price * form.quantity).toLocaleString('en-PH', {
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
                                        {{ Number(product.rating || 0).toFixed(2) }}
                                    </span>
                                    <i class="fa-solid fa-star"></i>
                                </p>

                                <p :class="styleClass.text">{{ product.ratings?.length || 0 }} ratings</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-5 border-b-2 border-slate-300 pt-6 pb-5">
                        <div class="flex items-center justify-between px-2">
                            <span> Quantity </span>
                            <Quantity v-model="form.quantity" />
                        </div>
                        <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row">
                            <ButtonPrimary :disabled="form.processing" variant="outline-danger" @click="handleDelete" label="Remove to Wishlist" />
                            <ButtonPrimary :disabled="form.processing" @click="update" variant="secondary" label="Update Wishlist" />
                            <ButtonPrimary :disabled="form.processing" @click="orderNow" variant="outline-secondary" label="Order now" />
                        </div>
                    </div>

                    <div v-if="true" class="space-y-5 pt-6 pb-5">
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
                                {{ Number(product.rating || 0).toFixed(2) }}
                                / 5
                            </span>
                            <span> Based on {{ product.ratings?.length || 0 }} ratings </span>
                        </div>

                        <!-- Ratings -->
                        <ProductRating v-for="rating of product.ratings" :rating="rating" :key="rating.id" />
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
