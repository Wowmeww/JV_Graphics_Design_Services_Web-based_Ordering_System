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

const pastDate = new Date(props.order.created_at);
const now = new Date();
const diffMs = now - pastDate;

const minutes = Math.floor(diffMs / 60000);
const hours = Math.floor(diffMs / 3600000);
const days = Math.floor(diffMs / 86400000);

let timeAgo = '';

if (minutes < 1) {
    timeAgo = 'just now';
} else if (minutes < 60) {
    timeAgo = `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
} else if (hours < 24) {
    timeAgo = `${hours} hour${hours > 1 ? 's' : ''} ago`;
} else {
    timeAgo = `${days} day${days > 1 ? 's' : ''} ago`;
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

const styleClass = {
    // Main container
    container:
        'group animate__animated animate__fadeInUp rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 flex flex-col h-full border-2 border-gray-100 dark:border-gray-700 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:border-primary-200 dark:hover:border-primary-700 hover:scale-[1.02]',

    // Image section
    image: {
        container: 'relative overflow-hidden',
        image: 'w-full h-72 object-cover group-hover:scale-105 transition-transform duration-700 ease-out',
        overlay:
            'absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500',
    },

    // Label badge
    label: {
        base: 'text-xs absolute top-4 right-4 px-4 py-2 text-white rounded-full font-black uppercase tracking-wide transition-all duration-300 z-10 shadow-lg border-2 border-white/30 backdrop-blur-sm',
        danger: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-white hover:to-gray-100 hover:text-red-600 hover:border-red-600 hover:scale-105',
        default:
            'bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-white hover:to-gray-100 hover:text-indigo-600 hover:border-indigo-600 hover:scale-105',
    },

    // Content section
    content: {
        container: 'px-6 py-5 flex-grow',
        title: 'text-xl font-black text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300 block mb-3 line-clamp-2',
        price: {
            container:
                'flex justify-between items-center mb-4 p-4 bg-white dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm',
            amount: 'text-xl font-black bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent',
            quantity: 'text-sm font-semibold bg-primary-500 text-white px-3 py-1.5 rounded-full border-2 border-white/50 shadow-md',
        },
        description: {
            container:
                'text-gray-600 dark:text-gray-300 text-sm mb-4 leading-relaxed bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600',
            ellipsis: 'font-black text-primary-500',
        },
    },

    // Rating section
    rating: {
        container: 'px-6 pb-5',
        stars: {
            container: 'flex justify-center gap-2 text-2xl',
            star: 'hover:scale-125 transition-transform duration-300 cursor-pointer drop-shadow-lg',
            filled: 'text-yellow-400 dark:text-amber-300',
            empty: 'text-gray-300 dark:text-gray-600',
        },
    },

    // Footer section
    footer: {
        container:
            'px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 border-t-2 border-gray-200 dark:border-gray-600 flex items-center justify-between',
        time: 'text-sm font-medium text-gray-600 dark:text-gray-300 flex items-center gap-2 bg-white dark:bg-gray-600 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-500',
        actions: 'flex gap-3',
    },

    // Additional effects
    shimmer:
        'absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -skew-x-12 transform -translate-x-full group-hover:translate-x-full transition-transform duration-1000',
};
</script>

<template>
    <div :class="styleClass.container">
        <!-- Image Section -->
        <div :class="styleClass.image.container">
            <a href="#">
                <img :class="styleClass.image.image" :src="image_url" :alt="name" />
                <div :class="styleClass.image.overlay"></div>
                <div :class="styleClass.shimmer"></div>
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
                    <i class="fa-solid fa-xmark mr-1"></i>
                    {{ quantity }}
                </span>
            </div>

            <p :class="styleClass.content.description.container" v-if="description">
                <span>{{ description.slice(0, 610) }}</span>
                <span v-if="description.length >= 610" :class="styleClass.content.description.ellipsis">...</span>
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
            <span :class="styleClass.footer.time">
                <i class="bi bi-clock text-primary-500"></i>
                {{ timeAgo }}
            </span>

            <div :class="styleClass.footer.actions">
                <PillPrimary v-if="showCancel" @click="handleCancel" variant="outlineDanger" label="Cancel" />
                <PillPrimary v-if="showReceived" @click="handleReceived" label="Received" />
                <PillPrimary v-if="showRate" @click="handleRate" :label="props.order.status == 'received' ? 'Add rating' : 'View Rating'" />
            </div>
        </div>
    </div>
</template>
