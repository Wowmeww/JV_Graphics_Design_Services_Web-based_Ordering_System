<script setup>
import TextInputPrimary from '../TextInputPrimary.vue';
import { computed } from 'vue';
const props = defineProps({
    item: Object,
});

const model = defineModel({
    type: String,
});

const image_url = computed(() => {
    let src = (props.item.option?.images[0] || props.item.product.images[0])?.image_path;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
});

const price = computed(() => Number((props.item.option || props.item.product).price));

function formatPrice() {
    return price.value.toLocaleString('en-PH', { currency: 'php', style: 'currency' });
}

const styleClass = {
    // Main container
    container:
        'animate__animated animate__fadeIn backdrop-blur-xl bg-white/95 dark:bg-gray-800/70 border border-gray-200 dark:border-gray-700 rounded-xl p-4 transition-all duration-300 hover:shadow-xl max-w-lg flex-1 dark:hover:shadow-2xl hover:border-gray-300 dark:hover:border-gray-600 flex flex-col gap-4 max-h-fit focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:ring-offset-2',

    // Content wrapper
    contentWrapper: 'flex items-start gap-4',

    // Product image
    image: 'h-20 w-20 flex-shrink-0 rounded-xl object-cover shadow-md md:h-24 md:w-24 lg:h-28 lg:w-28 transition-all duration-300 hover:shadow-lg',

    // Product details container
    detailsContainer: 'flex flex-1 flex-col gap-3',

    // Product title
    title: 'text-base font-bold text-gray-900 dark:text-white leading-tight md:text-lg line-clamp-2',

    // Price display section
    priceDisplay: 'flex items-center justify-between gap-2 mb-1',

    // Format price text
    formatPrice: 'text-sm text-gray-600 dark:text-gray-400 font-medium',

    // Price and quantity wrapper
    priceQuantityWrapper: 'flex flex-col gap-2 mt-2',

    // Price and quantity container
    priceQuantityContainer: 'flex items-center justify-between bg-gray-50 dark:bg-gray-700/30 rounded-lg px-3 py-2',

    // Price
    price: 'text-lg font-black text-primary-600 dark:text-primary-400',

    // Quantity
    quantity: 'flex items-center gap-1 text-sm font-semibold bg-primary-500 text-white px-3 py-1.5 rounded-full shadow-sm',

    // Desktop message input
    desktopInput: 'mt-1 hidden md:block',

    // Mobile message input
    mobileInput: 'mt-1 md:hidden',

    // Text input custom style
    textInputStyle:
        'placeholder:text-gray-500 bg-gray-50 dark:bg-gray-700/60 hover:bg-gray-100 dark:hover:bg-gray-700/80 focus:bg-white dark:focus:bg-gray-600/90 transition-all duration-200 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500',
};
</script>

<template>
    <div :class="styleClass.container">
        <div :class="styleClass.contentWrapper">
            <!-- Product Image -->
            <img :src="image_url" :alt="item.option?.name || item.product?.name" :class="styleClass.image" />

            <!-- Product Details -->
            <div :class="styleClass.detailsContainer">
                <h2 :class="styleClass.title">
                    {{ item.option?.name || item.product?.name }}
                </h2>

                <!-- Format Price Display -->
                <div :class="styleClass.priceDisplay">
                    <span :class="styleClass.formatPrice">{{ formatPrice() }}</span>
                    <span :class="styleClass.quantity">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        {{ item.quantity }}
                    </span>
                </div>

                <!-- Price & Quantity -->
                <div :class="styleClass.priceQuantityWrapper">
                    <div :class="styleClass.priceQuantityContainer">
                        <span :class="styleClass.price">
                            {{
                                (item.total_amount || price * item.quantity).toLocaleString('en-PH', {
                                    style: 'currency',
                                    currency: 'PHP',
                                })
                            }}
                        </span>
                    </div>
                </div>

                <!-- Desktop Message Input -->
                <div :class="styleClass.desktopInput">
                    <TextInputPrimary
                        v-model="model"
                        :customStyle="styleClass.textInputStyle"
                        :row="2"
                        placeholder="Optional message to seller for this order"
                        :required="false"
                        label="Message/Note"
                        variant="secondary"
                        type="textarea"
                    />
                </div>
            </div>
        </div>
        <!-- Mobile Message Input -->
        <div :class="styleClass.mobileInput">
            <TextInputPrimary
                v-model="model"
                :customStyle="styleClass.textInputStyle"
                :row="2"
                placeholder="Optional message to seller for this order"
                :required="false"
                label="Message/Note"
                variant="secondary"
                type="textarea"
            />
        </div>
    </div>
</template>
