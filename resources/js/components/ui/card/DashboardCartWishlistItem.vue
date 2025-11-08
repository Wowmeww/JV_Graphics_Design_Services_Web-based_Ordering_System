<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    item: Object,
    from: String,
});

const image_src = computed(() => {
    let src = props.item.option?.images[0]?.image_path || props.item.product?.images[0]?.image_path;
    if (src) {
        return src.includes('product_images') ? `/storage/${src}` : src;
    }
    return '/images/img-placeholder.jpg';
});

function handleOrder() {
    router.get(
        route('order.create', {
            items: [props.item.id],
            from: props.from,
        }),
    );
}

function handleShow() {
    router.get(
        route('shop.show', {
            product: props.item.product.id,
            option: props.item.option?.id || null,
        }),
    );
}

// Enhanced UI with improved design and removed scale transforms
const styleClass = {
    // Main container
    container:
        'group animate__animated animate__fadeInUp max-w-xs max-h-fit cursor-pointer overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-500 backdrop-blur-sm',

    // Content section
    content: {
        container: 'px-4 py-3',
        title: 'text-base font-bold text-gray-800 dark:text-white flex justify-between items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200',
        quantity: 'text-xs font-semibold bg-primary-500 text-white px-2 py-1 rounded-full shadow-sm',
        description:
            'mt-2 text-sm text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-700/30 rounded-lg px-3 py-2 border border-gray-100 dark:border-gray-600',
        ellipsis: 'font-bold text-primary-500 dark:text-primary-400',
    },

    // Image section
    image: 'object-cover w-full h-40 transition-all duration-500 ease-out group-hover:brightness-105',
    imageContainer: 'relative overflow-hidden',

    // Image overlay
    imageOverlay:
        'absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300',

    // Footer section
    footer: {
        container:
            'flex items-center justify-between px-4 py-3 bg-gradient-to-r from-primary-800 to-primary-900 dark:from-secondary-800 dark:to-primary-900 border-t border-secondary-600',
        price: 'text-base font-bold text-white',
        button: 'group/btn px-3 py-2 text-xs font-semibold text-white uppercase transition-all duration-200 bg-green-500 hover:bg-green-600 rounded-lg shadow-sm hover:shadow-md border border-green-400/30 hover:border-green-300/50 flex items-center gap-1.5',
    },
};
</script>

<template>
    <div @click="handleShow" :class="styleClass.container">
        <!-- Content Section -->
        <div :class="styleClass.content.container">
            <h1 :class="styleClass.content.title">
                <span class="max-w-[70%] truncate">{{ item.option?.name || item.product.name }}</span>
                <span :class="styleClass.content.quantity">
                    <i class="fa-solid fa-xmark mr-0.5 text-xs"></i>
                    {{ item.quantity }}
                </span>
            </h1>
            <p :class="styleClass.content.description">
                {{ (item.option?.description || item.product.description)?.slice(0, 100) }}
                <span v-if="(item.option?.description || item.product.description)?.length >= 100" :class="styleClass.content.ellipsis">...</span>
            </p>
        </div>

        <!-- Image Section -->
        <div :class="styleClass.imageContainer">
            <img :class="styleClass.image" :src="image_src" :alt="`${item.option?.name || item.product.name} image`" />
            <div :class="styleClass.imageOverlay"></div>
        </div>

        <!-- Footer Section -->
        <div :class="styleClass.footer.container">
            <h4 :class="styleClass.footer.price">
                {{
                    item.total_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                    })
                }}
            </h4>
            <button @click.stop.prevent="handleOrder" :class="styleClass.footer.button">
                <span class="flex items-center gap-1.5">
                    Order
                    <i class="fa-solid fa-bag-shopping text-xs transition-transform duration-200 group-hover/btn:translate-x-0.5"></i>
                </span>
            </button>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced focus states for accessibility */
button:focus-visible,
div:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 8px;
}

/* Smooth transitions for all interactive elements */
.container,
.image,
.button {
    transition: all 0.2s ease-in-out;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .container,
    .image,
    .button,
    .image-overlay {
        transition: none;
    }

    .button i {
        transition: none;
    }
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .container {
        max-width: 100%;
        margin: 0 auto;
    }

    .content .title {
        font-size: 15px;
    }

    .footer .price {
        font-size: 14px;
    }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
    .container {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }

    .footer {
        background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
    }
}

/* Print styles */
@media print {
    .container {
        background: white !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
    }

    .footer {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
    }
}
</style>
