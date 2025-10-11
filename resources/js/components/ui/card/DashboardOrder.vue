<script setup>
import { computed } from 'vue';

const props = defineProps({
    order: Object,
});

const image_src = computed(() => {
    let src = props.order.option?.images[0]?.image_path || props.order.product?.images[0]?.image_path;
    if (src) {
        return src.includes('product_images') ? `/storage/${src}` : src;
    }
    return '/images/img-placeholder.jpg';
});

// enhance the style to be more aestetic and use friendly
const styleClass = {
    // Main container
    container:
        'group flex cursor-pointer flex-col items-center justify-center w-full max-w-sm mx-auto relative hover:scale-[1.03] transition-all duration-500 ease-out',

    // Status button
    statusButton: {
        base: 'absolute rounded-full px-4 py-2 text-xs font-black uppercase transition-all duration-500 transform focus:outline-none top-4 right-4 hover:scale-110 z-20 shadow-2xl border-2 border-white/30 backdrop-blur-sm',
        pending:
            'bg-gradient-to-br from-yellow-400 via-yellow-500 to-amber-500 dark:from-yellow-300 dark:via-yellow-400 dark:to-amber-400 text-slate-900 hover:shadow-yellow-500/25 hover:shadow-lg',
        processing: 'bg-gradient-to-br from-secondary-500 via-secondary-600 to-primary-600 text-white hover:shadow-secondary-500/25 hover:shadow-lg',
    },

    // Image container
    imageContainer:
        'w-full h-72 bg-gray-300 bg-center bg-cover rounded-3xl shadow-2xl group-hover:shadow-3xl transition-all duration-700 ease-out group-hover:scale-105',

    // Image overlay
    imageOverlay:
        'absolute inset-0 bg-gradient-to-t from-black/30 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 rounded-3xl',

    // Card container
    cardContainer:
        'w-60 -mt-16 overflow-hidden bg-gradient-to-br from-white via-white to-gray-50 rounded-3xl shadow-2xl md:w-72 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 border-2 border-white/50 dark:border-gray-600/50 group-hover:shadow-3xl transition-all duration-500 backdrop-blur-md group-hover:border-primary-200/50 dark:group-hover:border-primary-500/30',

    // Title
    title: 'py-4 font-black tracking-tight text-center text-gray-900 uppercase dark:text-white text-xl truncate px-6 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300 bg-gradient-to-r from-transparent via-white/50 to-transparent dark:via-gray-700/50',

    // Footer
    footer: {
        container:
            'flex items-center justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-t-2 border-gray-200/80 dark:border-gray-600/80',
        price: 'font-black text-gray-900 dark:text-gray-100 text-xl drop-shadow-sm bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent',
        quantity:
            'text-sm font-black bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 py-2 rounded-full border-2 border-white/50 shadow-lg hover:shadow-xl transition-shadow duration-300',
    },

    // Additional effects
    shimmer:
        'absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -skew-x-12 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000',
};
</script>

<template>
    <Link as="button" :href="route('order.index')" :class="styleClass.container">
        <div class="relative">
            <button
                :class="[
                    styleClass.statusButton.base,
                    order.status === 'pending' ? styleClass.statusButton.pending : styleClass.statusButton.processing,
                ]"
            >
                {{ order.status }}
            </button>

            <div class="relative overflow-hidden rounded-3xl">
                <img :class="styleClass.imageContainer" :src="image_src" alt="order-image" />
                <div :class="styleClass.imageOverlay"></div>
                <div :class="styleClass.shimmer"></div>
            </div>
        </div>

        <div :class="styleClass.cardContainer">
            <h4 :class="styleClass.title">
                {{ order.option?.name || order.product.name }}
            </h4>

            <div :class="styleClass.footer.container">
                <span :class="styleClass.footer.price">
                    {{
                        order.total_amount.toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP',
                        })
                    }}
                </span>

                <span :class="styleClass.footer.quantity">
                    <i class="fa fa-xmark mr-2"></i>
                    {{ order.quantity }}
                </span>
            </div>
        </div>
    </Link>
</template>
