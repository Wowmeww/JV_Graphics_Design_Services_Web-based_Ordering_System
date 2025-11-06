<script setup>
import { router, usePage } from '@inertiajs/vue3';
import PillPrimary from '../buttons/PillPrimary.vue';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    order: Object,
    label: { type: String, default: 'Waiting for approval' },
    showCancel: { type: Boolean, default: false },
    showReceived: { type: Boolean, default: false },
    showRate: { type: Boolean, default: false },
    danger: { type: Boolean, default: false },
});

const emit = defineEmits(['rate']);

const page = usePage();

const name = props.order.option?.name || props.order.product.name;
const description = props.order.option?.description || props.order.product.description;
const image_url = computed(() => {
    let src = (props.order.option?.images[0] || props.order.product?.images[0])?.image_path;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/img-placeholder.jpg';
});
const amount = props.order.total_amount;
const quantity = props.order.quantity;

let timeAgo = getPastTime(props.order.created_at);
let rateLastUpdate = getPastTime(props.order?.rating?.updated_at);

function getPastTime(dateTime) {
    const pastDate = new Date(dateTime);
    const now = new Date();
    const diffMs = now - pastDate;

    const minutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(diffMs / 3600000);
    const days = Math.floor(diffMs / 86400000);

    if (!dateTime) return null;

    if (minutes < 1) {
        return 'just now';
    } else if (minutes < 60) {
        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    } else if (hours < 24) {
        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    } else {
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }
}

function handleCancel() {
    page.props.confirm = {
        show: true,
        message: 'Do you want cancel this order?',
        confirmed: false,
        action: {
            cancel_order: true,
        },
    };
}
watch(
    () => page.props.confirm?.confirmed,
    (confirmed) => {
        if (confirmed && page.props.confirm.action?.cancel_order) {
            router.patch(route('order.update', { order: props.order.id, status: 'cancelled' }));
            page.props.confirm.confirmed = false;
        }
    },
);

function handleReceived() {
    router.patch(route('order.update', { order: props.order.id, status: 'received' }));
}
function handleRate() {
    emit('rate', { order: props.order });
}

// enhance ui to perfection and remove the scale increase at hover
// the ui looks gigantic make it looks smaller and looks good in small devices
const styleClass = {
    // Main container - more compact
    container:
        'group animate__animated animate__fadeInUp rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col h-full border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:border-primary-300 dark:hover:border-primary-600',

    // Image section - smaller height
    image: {
        container: 'relative overflow-hidden',
        image: 'w-full h-48 object-cover transition-all duration-500 ease-out group-hover:brightness-105',
        overlay:
            'absolute inset-0 bg-gradient-to-t from-black/30 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300',
    },

    // Label badge - smaller and more subtle
    label: {
        base: 'text-xs absolute top-3 right-3 px-2.5 py-1.5 text-white rounded-full font-semibold uppercase tracking-wide transition-colors duration-200 z-10 shadow-md',
        danger: 'bg-red-500 hover:bg-red-600',
        default: 'bg-indigo-500 hover:bg-indigo-600',
    },

    // Content section - reduced padding and spacing
    content: {
        container: 'px-4 py-3 flex-grow space-y-3',
        title: 'text-base font-bold text-gray-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 line-clamp-2 leading-tight',
        price: {
            container: 'flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-100 dark:border-gray-600',
            amount: 'text-lg font-bold text-primary-600 dark:text-primary-400',
            quantity: 'text-xs font-medium bg-primary-500 text-white px-2 py-1 rounded-full',
        },
        description: {
            container:
                'text-gray-600 dark:text-gray-300 text-xs leading-relaxed bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-100 dark:border-gray-600 line-clamp-3',
            ellipsis: 'font-bold text-primary-500',
        },
    },

    // Rating section - smaller stars
    rating: {
        container: 'px-4 pb-3',
        stars: {
            container: 'flex justify-center gap-1 text-lg',
            star: 'transition-colors duration-200',
            filled: 'text-yellow-400 dark:text-amber-300',
            empty: 'text-gray-300 dark:text-gray-600',
        },
    },

    // Footer section - more compact
    footer: {
        container: 'px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between',
        time: 'text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5',
        actions: 'flex gap-2',
    },
};
</script>

<template>
    <div :class="styleClass.container">
        <!-- Image Section -->
        <div :class="styleClass.image.container">
            <a href="#">
                <img :class="styleClass.image.image" :src="image_url" :alt="name" />
                <div :class="styleClass.image.overlay"></div>
            </a>

            <!-- Label Badge -->
            <div :class="[styleClass.label.base, danger ? styleClass.label.danger : styleClass.label.default]">
                {{ label }}
            </div>
        </div>

        <!-- Content Section -->
        <div :class="styleClass.content.container">
            <a href="#" :class="styleClass.content.title">
                {{ name }}
            </a>

            <div :class="styleClass.content.price.container">
                <span :class="styleClass.content.price.amount">
                    {{ Number(amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                </span>
                <span :class="styleClass.content.price.quantity">
                    <i class="fa-solid fa-xmark mr-0.5 text-xs"></i>
                    {{ quantity }}
                </span>
            </div>

            <p v-if="description" :class="styleClass.content.description.container">
                <span>{{ description.slice(0, 150) }}</span>
                <span v-if="description.length >= 150" :class="styleClass.content.description.ellipsis">...</span>
            </p>
        </div>

        <!-- Rating Section -->
        <div v-if="props.order.status == 'rated'" :class="styleClass.rating.container">
            <div :class="styleClass.rating.stars.container">
                <span
                    v-for="i in 5"
                    :key="i"
                    :class="[styleClass.rating.stars.star, i > order.rating?.stars ? styleClass.rating.stars.empty : styleClass.rating.stars.filled]"
                >
                    <i :class="i > order.rating?.stars ? 'fa-regular fa-star' : 'fa-solid fa-star'"></i>
                </span>
            </div>
        </div>

        <!-- Footer Section -->
        <div :class="styleClass.footer.container">
            <div>
                <span :class="styleClass.footer.time">
                    <i class="bi bi-clock text-primary-500 text-xs"></i>
                    {{ timeAgo }}
                </span>
                <span v-if="rateLastUpdate" :class="styleClass.footer.time">
                    <i class="fa-solid fa-star text-primary-500 text-xs"></i>
                    {{ rateLastUpdate }}
                </span>
            </div>

            <div :class="styleClass.footer.actions">
                <PillPrimary v-if="showCancel" @click="handleCancel" variant="outlineDanger" label="Cancel" size="sm" />
                <PillPrimary v-if="showReceived" @click="handleReceived" label="Received" size="sm" />
                <PillPrimary v-if="showRate" @click="handleRate" :label="props.order.status == 'received' ? 'Rate' : 'View'" size="sm" />
            </div>
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth focus states */
a:focus-visible,
button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 4px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .container,
    .image img,
    .image .overlay {
        transition: none;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .container {
        border-radius: 8px;
    }

    .content .title {
        font-size: 15px;
    }

    .footer .actions {
        flex-direction: column;
        gap: 1px;
    }
}
</style>
