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

function image_url(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
}

const styleClass = {
    // Text styles
    name: 'font-bold text-2xl md:text-3xl text-gray-900 dark:text-white tracking-tight',
    text: 'text-slate-600 leading-relaxed dark:text-slate-300 text-sm md:text-base',
    price: 'font-bold text-3xl md:text-4xl text-gray-900 dark:text-white',

    // Component styles
    options:
        'border-2 border-slate-300 hover:border-secondary dark:border-slate-600 dark:hover:border-secondary-300 flex items-center gap-2 p-3 rounded-lg cursor-pointer font-medium transition-all duration-200 hover:shadow-md bg-white dark:bg-slate-800',
    ratingBadge: 'bg-secondary rounded-full py-1.5 px-4 inline-flex items-center gap-1.5 text-white font-semibold text-sm shadow-sm',
    backButton:
        'group mb-4 inline-flex items-center gap-2 transition-all duration-300 hover:gap-3 bg-white dark:bg-slate-800 rounded-lg px-4 py-3 shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 hover:border-secondary dark:hover:border-secondary-300',

    // Layout styles
    container: 'min-h-screen bg-gray-50 py-4 md:py-8 dark:bg-slate-900',
    contentArea: 'space-y-4 px-3 pt-6 pb-4 md:px-4 md:pt-12',
    mainCard:
        'mx-auto max-w-7xl rounded-xl border border-slate-200 bg-white px-4 py-6 shadow-sm sm:px-6 lg:px-8 dark:border-slate-700 dark:bg-slate-800',

    // Back button elements
    backIcon:
        'bi bi-arrow-left text-xl transition-all duration-300 group-hover:-translate-x-1 group-hover:text-secondary dark:group-hover:text-secondary-300',
    backText:
        'font-semibold text-slate-700 transition-colors duration-300 group-hover:text-secondary dark:text-slate-300 dark:group-hover:text-secondary-300',

    // Layout containers
    productLayout: 'flex flex-col gap-8 lg:flex-row lg:gap-12',
    imageGallery: 'flex flex-1 gap-4',
    thumbnails: 'flex flex-col space-y-3',
    thumbnail: 'h-20 w-20 cursor-pointer rounded-lg border-2 object-cover object-center transition-all duration-200 md:h-24 md:w-24',
    thumbnailActive: 'border-secondary ring-secondary ring-opacity-50 dark:border-secondary-300 shadow-sm ring-2',
    thumbnailInactive: 'border-slate-200 hover:border-slate-400 dark:border-slate-600 dark:hover:border-slate-400',
    mainImageContainer: 'h-72 flex-1 overflow-hidden rounded-xl bg-gray-100 sm:h-80 md:h-[35rem] dark:bg-slate-700',
    mainImage: 'h-full w-full object-cover object-center transition-transform duration-300 hover:scale-105',

    // Product details
    productDetails: 'h-[32rem] flex-1 overflow-y-auto pr-2',
    productHeader: 'border-b border-slate-200 pb-6 dark:border-slate-600',
    productInfo: 'space-y-3',
    productTitleRow: 'flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between',
    productTitleSection: 'space-y-2',
    stockBadge: 'rounded-full px-2 py-1 text-sm font-semibold',
    stockIn: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
    stockLow: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
    stockText: 'text-xs font-semibold', // Added missing class
    categoryBadge: 'rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-500 dark:bg-slate-700 dark:text-slate-400',
    categoryText: '', // Added missing class
    description: 'line-clamp-3',
    sizeInfo: 'flex gap-3 font-semibold text-slate-700 dark:text-slate-300',
    sizeValue: 'text-secondary dark:text-secondary-300',

    // Pricing section
    pricingSection: 'mt-6 space-y-4',
    priceRow: 'flex flex-col items-start justify-between gap-4 px-1 sm:flex-row sm:items-center',
    pricingRow: 'flex flex-wrap items-center justify-between px-2', // Added missing class
    priceContainer: 'flex items-center gap-3',
    priceLabel: 'text-sm text-slate-500 dark:text-slate-400',
    totalAmountContainer: 'flex items-center gap-3 rounded-lg bg-slate-50 px-4 py-2 dark:bg-slate-700/50',
    totalAmountLabel: 'font-medium text-slate-600 dark:text-slate-300',
    totalAmountValue: 'text-2xl font-bold text-gray-900 dark:text-white',
    ratingContainer: 'flex items-center gap-4',

    // Options section
    optionsSection: 'space-y-6 border-b border-slate-200 pt-6 pb-6 dark:border-slate-600',
    optionsTitle: 'inline-block text-xl font-bold text-gray-900 dark:text-white',
    optionsGrid: 'flex flex-wrap items-center gap-3',
    optionActive: '!border-secondary dark:!border-secondary-300 bg-secondary/5 dark:bg-secondary/10',
    optionNumber: 'text-secondary dark:text-secondary-300 font-bold',

    // Action buttons section
    quantityContainer: 'flex items-center justify-between rounded-lg bg-slate-50 px-2 py-3 dark:bg-slate-700/30',
    quantityRow: 'flex items-center justify-between px-2', // Added missing class
    quantityLabel: 'font-medium text-slate-700 dark:text-slate-300',
    actionButtons: 'flex flex-col gap-3 sm:flex-row sm:flex-wrap',
    actionButton: 'min-w-[150px] flex-1',
    customButton: 'w-full sm:w-auto',

    // Unavailable product
    unavailableContainer: 'rounded-lg border border-red-200 bg-red-50 py-8 text-center dark:border-red-800 dark:bg-red-900/20',
    unavailableTitle: 'text-lg font-medium text-red-700 dark:text-red-300',
    unavailableText: 'mt-1 text-sm text-red-600 dark:text-red-400',

    // Reviews section
    reviewsSection: 'space-y-6 pt-6 pb-5',
    reviewsTitle: 'inline-block text-xl font-bold text-gray-900 dark:text-white',
    ratingDisplay: 'flex flex-wrap items-center gap-4',
    starsContainer: 'text-secondary dark:text-secondary-300 text-2xl',
    ratingValueContainer: 'flex items-center gap-3',
    ratingValue: 'text-3xl font-bold text-gray-900 dark:text-white',
    ratingMax: 'text-xl font-normal text-slate-500 dark:text-slate-400',
    ratingCount: 'text-sm text-slate-600 dark:text-slate-400',
    ratingsList: 'mt-4 space-y-4',

    // No reviews state
    noReviewsContainer: 'space-y-5 pt-6 pb-5',
    noReviewsSection: 'space-y-5 pt-6 pb-5', // Added missing class
    noReviewsCard: 'rounded-lg border border-slate-200 bg-slate-50 py-8 text-center dark:border-slate-600 dark:bg-slate-700/30',
    noReviewsIcon: 'fa-regular fa-star mb-3 text-4xl text-slate-400 dark:text-slate-500',
    noReviewsTitle: 'inline-block text-xl font-bold text-gray-900 dark:text-white',
    noReviewsText: 'mt-2 text-slate-600 dark:text-slate-400',
};
</script>

<template>
    <div :class="styleClass.container">
        <div :class="styleClass.contentArea">
            <div :class="styleClass.mainCard">
                <button @click="goBack" :class="styleClass.backButton">
                    <i :class="styleClass.backIcon"></i>
                    <span :class="styleClass.backText">Exit</span>
                </button>
                <div :class="styleClass.productLayout">
                    <!-- Image Gallery -->
                    <div :class="styleClass.imageGallery">
                        <div :class="styleClass.thumbnails">
                            <img
                                :class="[
                                    styleClass.thumbnail,
                                    image.image_path === focusedImage ? styleClass.thumbnailActive : styleClass.thumbnailInactive,
                                ]"
                                v-for="image of product.images"
                                :src="image_url(image.image_path)"
                                :key="image.id"
                                alt="Product thumbnail"
                                @click="changeFocusedImage(image_url(image.image_path))"
                            />
                        </div>
                        <div :class="styleClass.mainImageContainer">
                            <img
                                :src="focusedImage ? focusedImage : '/images/img-placeholder.jpg'"
                                alt="Main product image"
                                :class="styleClass.mainImage"
                            />
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div :class="styleClass.productDetails">
                        <div :class="styleClass.productHeader">
                            <div :class="styleClass.productInfo">
                                <div :class="styleClass.productTitleRow">
                                    <div :class="styleClass.productTitleSection">
                                        <h4 :class="styleClass.name">{{ product.name }}</h4>
                                        <small :class="[styleClass.stockText, product.stock > 20 ? styleClass.stockIn : styleClass.stockLow]"
                                            >Stocks - {{ product.stock }}</small
                                        >
                                    </div>
                                    <span :class="styleClass.categoryBadge">{{ product.category.name }}</span>
                                </div>
                                <p :class="[styleClass.text, styleClass.description]">{{ product.description }}</p>
                                <div :class="styleClass.sizeInfo" v-if="product.show_size">
                                    <span>Size:</span>
                                    <p :class="styleClass.sizeValue">{{ product.show_size }}</p>
                                </div>
                            </div>
                            <div :class="styleClass.pricingSection">
                                <div :class="styleClass.pricingRow">
                                    <div :class="styleClass.priceContainer">
                                        <p :class="styleClass.price">
                                            {{
                                                Number(product.price).toLocaleString('en-PH', {
                                                    style: 'currency',
                                                    currency: 'PHP',
                                                })
                                            }}
                                        </p>
                                        <span :class="styleClass.priceLabel">Per piece</span>
                                    </div>

                                    <div :class="styleClass.totalAmountContainer">
                                        <span :class="styleClass.totalAmountLabel">Total amount:</span>
                                        <span :class="styleClass.totalAmountValue">
                                            {{
                                                (product.price * form.quantity).toLocaleString('en-PH', {
                                                    style: 'currency',
                                                    currency: 'PHP',
                                                })
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div :class="styleClass.ratingContainer">
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

                        <!-- Options Section -->
                        <div :class="styleClass.optionsSection">
                            <div :class="styleClass.quantityRow">
                                <span :class="styleClass.quantityLabel"> Quantity </span>
                                <Quantity v-model="form.quantity" />
                            </div>
                            <div :class="styleClass.actionButtons">
                                <ButtonPrimary
                                    :disabled="form.processing"
                                    variant="outline-danger"
                                    @click="handleDelete"
                                    label="Remove to Wishlist"
                                    :class="styleClass.actionButton"
                                />
                                <ButtonPrimary
                                    :disabled="form.processing"
                                    @click="update"
                                    variant="secondary"
                                    label="Update Wishlist"
                                    :class="styleClass.actionButton"
                                />
                                <ButtonPrimary
                                    :disabled="form.processing"
                                    @click="orderNow"
                                    variant="outline-secondary"
                                    label="Order now"
                                    :class="styleClass.actionButton"
                                />
                            </div>
                        </div>

                        <!-- Reviews Section -->
                        <div v-if="true" :class="styleClass.reviewsSection">
                            <div class="space-y-3">
                                <span :class="styleClass.reviewsTitle">Customer Reviews</span>
                            </div>
                            <div :class="styleClass.ratingDisplay">
                                <div :class="styleClass.starsContainer">
                                    <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                                    <i v-if="starIcons.half" class="fa-regular fa-star-half-stroke"></i>
                                    <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                                </div>
                                <div :class="styleClass.ratingValueContainer">
                                    <span :class="styleClass.ratingValue">
                                        {{ Number(product.rating || 0).toFixed(2) }}
                                        <span :class="styleClass.ratingMax">/ 5</span>
                                    </span>
                                    <span :class="styleClass.ratingCount"> Based on {{ product.ratings?.length || 0 }} ratings </span>
                                </div>
                            </div>

                            <!-- Ratings -->
                            <div :class="styleClass.ratingsList">
                                <ProductRating v-for="rating of product.ratings" :rating="rating" :key="rating.id" />
                            </div>
                        </div>
                        <div v-else :class="styleClass.noReviewsSection">
                            <div class="space-y-3">
                                <span :class="styleClass.noReviewsTitle">No Customer Reviews Yet</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
