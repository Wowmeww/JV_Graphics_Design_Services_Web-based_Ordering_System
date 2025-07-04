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
    const image_url = props.order.option?.images[0]?.image_path || props.order.product?.images[0]?.image_path || '/images/img-placeholder.jpg';
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
    <!-- CARD 1 -->
    <div class="rounded overflow-hidden shadow-lg flex flex-col">
        <a href="#"></a>
        <div class="relative"><a href="#">
                <img class="w-full max-h-64 h-64 object-cover" :src="image_url" alt="" />
                <div
                    class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25">
                </div>
            </a>

            <div
                :class="danger ? 'text-xs absolute top-0 right-0 bg-red-600 px-4 py-2 text-white mt-3 mr-3 rounded transition duration-500 ease-in-out hover:bg-white hover:text-red-600 border border-transparent hover:border-red-600' : 'text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out rounded'">
                {{ label }}
            </div>

        </div>
        <div class="px-6 py-4 mb-auto">
            <a href="#"
                class="font-medium text-lg leading-3  hover:text-indigo-600 transition duration-500 ease-in-out  mb-2">
                {{ name }}</a>

            <div class="flex justify-between text-xs font-medium items-center">
                <span class="text-lg">{{
                    amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                }}</span>
                <span>
                    <i class="fa-solid fa-xmark"></i>
                    {{ quantity }}
                </span>
            </div>

            <p class="text-gray-500 text-sm" v-if="description">
                <span>
                    {{ description.slice(0, 610) }}
                </span>
                <span v-if="description.length >= 610" class="font-black tracking-widest">
                    . . .
                </span>
            </p>
        </div>

        <div v-if="props.order.status == 'rated'" class="px-6">
            <div class="flex justify-center gap-2 text-yellow-400 text-2xl">
                <p v-for="i in 5" :key="i" type="button" class="hover:scale-125 transition transform">
                    <i v-if="i > order.rating.stars" class="fa-regular fa-star"></i>
                    <i v-else class="fa-solid fa-star"></i>
                </p>
            </div>
        </div>
        <div class="px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
            <span href="#" class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                <i class="bi bi-clock"></i>
                <span class="ml-1">{{ timeAgo }}</span>
            </span>
            <PillPrimary v-if="showCancel" @click="handleCancel" variant="outlineDanger" label="Cancel" />
            <PillPrimary v-if="showReceived" @click="handleReceived" label="Received" />
            <PillPrimary v-if="showRate" @click="handleRate"
                :label="props.order.status == 'received' ? 'Rate' : 'Update Rating'" />
        </div>
    </div>

</template>