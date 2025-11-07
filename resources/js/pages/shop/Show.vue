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
    // window.history.back();
    router.get(route('shop.index'), { filter: props.filter });
}

function image_url(src) {
    // let src = props.product?.images[0];
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/img-placeholder.jpg';
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
    categoryBadge: 'rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-500 dark:bg-slate-700 dark:text-slate-400',
    description: 'line-clamp-3',
    sizeInfo: 'flex gap-3 font-semibold text-slate-700 dark:text-slate-300',
    sizeValue: 'text-secondary dark:text-secondary-300',

    // Pricing section
    pricingSection: 'mt-6 space-y-4',
    priceRow: 'flex flex-col items-start justify-between gap-4 px-1 sm:flex-row sm:items-center',
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
                <!-- Enhanced Back Button -->
                <button @click="goBack" :class="styleClass.backButton">
                    <i :class="styleClass.backIcon"></i>
                    <span :class="styleClass.backText">Back to Products</span>
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
                                v-for="image of tempProduct.images"
                                :src="image_url(image.image_path)"
                                :key="image.id"
                                alt="Product thumbnail"
                                @click="changeFocusedImage(image.image_path)"
                            />
                        </div>
                        <div :class="styleClass.mainImageContainer">
                            <img :src="image_url(focusedImage)" alt="Main product image" :class="styleClass.mainImage" />
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div :class="styleClass.productDetails">
                        <div :class="styleClass.productHeader">
                            <div :class="styleClass.productInfo">
                                <div :class="styleClass.productTitleRow">
                                    <div :class="styleClass.productTitleSection">
                                        <h4 :class="styleClass.name">{{ tempProduct.name }}</h4>
                                        <small :class="[styleClass.stockBadge, tempProduct.stock > 20 ? styleClass.stockIn : styleClass.stockLow]">
                                            {{ tempProduct.stock > 20 ? 'In Stock' : 'Low Stock' }} - {{ tempProduct.stock }} units
                                        </small>
                                    </div>
                                    <span :class="styleClass.categoryBadge">
                                        {{ product.category.name }}
                                    </span>
                                </div>
                                <p :class="[styleClass.text, styleClass.description]">{{ tempProduct.description }}</p>
                                <div :class="styleClass.sizeInfo" v-if="tempProduct.show_size">
                                    <span>Size:</span>
                                    <p :class="styleClass.sizeValue">{{ tempProduct.show_size }}</p>
                                </div>
                            </div>

                            <div :class="styleClass.pricingSection">
                                <div :class="styleClass.priceRow">
                                    <div :class="styleClass.priceContainer">
                                        <p :class="styleClass.price">
                                            {{
                                                Number(tempProduct.price).toLocaleString('en-PH', {
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
                                                (Number(tempProduct.price) * form.quantity).toLocaleString('en-PH', {
                                                    style: 'currency',
                                                    currency: 'PHP',
                                                })
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <div :class="styleClass.ratingContainer">
                                    <p :class="styleClass.ratingBadge">
                                        <span class="text-base">
                                            {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                        </span>
                                        <i class="fa-solid fa-star text-sm"></i>
                                    </p>
                                    <p :class="styleClass.text">{{ tempProduct.ratings?.length || 0 }} ratings</p>
                                </div>
                            </div>
                        </div>

                        <!-- Options Section -->
                        <div :class="styleClass.optionsSection">
                            <div class="space-y-4" v-if="product.options.length">
                                <span :class="styleClass.optionsTitle">Available Options</span>
                                <div :class="styleClass.optionsGrid">
                                    <p
                                        @click="changeTempProduct({ ...props.product, index: 0 })"
                                        :class="[styleClass.options, tempProduct.index == 0 ? styleClass.optionActive : '']"
                                    >
                                        <span :class="styleClass.optionNumber"> 1 : </span>
                                        {{ props.product.name }}
                                    </p>
                                    <p
                                        v-for="(option, i) of product.options"
                                        :key="i"
                                        @click="changeTempProduct({ ...option, index: i + 1 })"
                                        :class="[styleClass.options, tempProduct.index == i + 1 ? styleClass.optionActive : '']"
                                    >
                                        <span :class="styleClass.optionNumber">
                                            {{ `${i + 2} :` }}
                                        </span>
                                        {{ option.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <template v-if="product.type !== 'unavailable'">
                                <div class="space-y-4" v-if="product.type !== 'custom'">
                                    <div :class="styleClass.quantityContainer">
                                        <span :class="styleClass.quantityLabel"> Quantity </span>
                                        <Quantity v-model="form.quantity" />
                                    </div>
                                    <div :class="styleClass.actionButtons">
                                        <ButtonPrimary
                                            :disabled="form.processing"
                                            @click="addToWishlist"
                                            variant="outline-secondary"
                                            label="Add to Wishlist"
                                            :class="styleClass.actionButton"
                                        />
                                        <ButtonPrimary
                                            :disabled="form.processing"
                                            @click="addToCart"
                                            variant="secondary"
                                            label="Add to Cart"
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
                                <div class="flex flex-col gap-3 sm:flex-row" v-else-if="product.type === 'custom'">
                                    <ButtonPrimary
                                        :href="route('designer', { product: product.id })"
                                        is="Link"
                                        variant="secondary"
                                        label="Customize Product"
                                        :class="styleClass.customButton"
                                    />
                                </div>
                            </template>
                            <template v-if="product.type == 'unavailable'">
                                <div :class="styleClass.unavailableContainer">
                                    <p :class="styleClass.unavailableTitle">This product is currently unavailable</p>
                                    <p :class="styleClass.unavailableText">Please check back later</p>
                                </div>
                            </template>
                        </div>

                        <!-- Reviews Section -->
                        <div v-if="tempProduct.ratings?.length" :class="styleClass.reviewsSection">
                            <div class="space-y-4">
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
                                        {{ Number(tempProduct.rating || 0).toFixed(2) }}
                                        <span :class="styleClass.ratingMax">/ 5</span>
                                    </span>
                                    <span :class="styleClass.ratingCount"> Based on {{ tempProduct.ratings?.length || 0 }} ratings </span>
                                </div>
                            </div>
                            <!-- Ratings -->
                            <div :class="styleClass.ratingsList">
                                <ProductRating v-for="rating of tempProduct.ratings" :rating="rating" :key="rating.id" />
                            </div>
                        </div>
                        <div v-else :class="styleClass.noReviewsContainer">
                            <div :class="styleClass.noReviewsCard">
                                <i :class="styleClass.noReviewsIcon"></i>
                                <span :class="styleClass.noReviewsTitle">No Customer Reviews Yet</span>
                                <p :class="styleClass.noReviewsText">Be the first to review this product!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
