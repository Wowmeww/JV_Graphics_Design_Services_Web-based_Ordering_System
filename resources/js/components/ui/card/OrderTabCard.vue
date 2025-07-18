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
            return src.includes('https')
                ? src
                : `/storage/${src}`;
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
                cancel_order: true
            },
        }
    }
    watch(
        () => page.props.confirm?.confirmed,
        (confirmed) => {
            if (confirmed && page.props.confirm.action?.cancel_order) {
                router.patch(route('order.update', { order: props.order.id, status: 'cancelled' }));
                page.props.confirm.confirmed = false;
            }
        }
    );

    function handleReceived() {
        router.patch(route('order.update', { order: props.order.id, status: 'received' }));
    }
    function handleRate() {
        emit('rate', { order: props.order });
    }


</script>


<template>
    <div
        class="rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <!-- Image Section -->
        <div class="relative">
            <a href="#">
                <img class="w-full h-64 object-cover" :src="image_url" :alt="name" />
                <div class="absolute inset-0 bg-gray-900 opacity-0 hover:opacity-25 transition-opacity duration-300">
                </div>
            </a>

            <!-- Label Badge -->
            <div :class="[
                'text-xs absolute top-3 right-3 px-4 py-2 text-white rounded-full font-semibold transition-all duration-300',
                danger ? 'bg-red-600 hover:bg-white hover:text-red-600 border border-transparent hover:border-red-600'
                    : 'bg-indigo-600 hover:bg-white hover:text-indigo-600 hover:border-indigo-600'
            ]">
                {{ label }}
            </div>
        </div>

        <!-- Content Section -->
        <div class="px-6 py-4 flex-grow">
            <a href="#"
                class="text-lg font-semibold text-gray-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-300 block mb-2">
                {{ name }}
            </a>

            <div class="flex justify-between items-center mb-3">
                <span class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ amount.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-xmark mr-1"></i>
                    {{ quantity }}
                </span>
            </div>

            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4" v-if="description">
                <span>{{ description.slice(0, 610) }}</span>
                <span v-if="description.length >= 610" class="font-bold">...</span>
            </p>
        </div>

        <!-- Rating Section -->
        <div v-if="props.order.status == 'rated'" class="px-6 pb-4">
            <div class="flex justify-center gap-1 text-yellow-400 text-xl">
                <span v-for="i in 5" :key="i" class="hover:scale-110 transition-transform">
                    <i :class="i > order.rating?.stars ? 'fa-regular fa-star' : 'fa-solid fa-star'"></i>
                </span>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="px-6 py-3 bg-gray-100 dark:bg-gray-700 flex items-center justify-between">
            <span class="text-xs text-gray-600 dark:text-gray-300 flex items-center">
                <i class="bi bi-clock mr-1"></i>
                {{ timeAgo }}
            </span>

            <div class="flex gap-2">
                <PillPrimary v-if="showCancel" @click="handleCancel" variant="outlineDanger" label="Cancel" />
                <PillPrimary v-if="showReceived" @click="handleReceived" label="Received" />
                <PillPrimary v-if="showRate" @click="handleRate"
                    :label="props.order.status == 'received' ? 'Rate' : 'Update Rating'" />
            </div>
        </div>
    </div>
</template>