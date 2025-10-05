<script setup>
import { router, usePage } from '@inertiajs/vue3';
import OrderStatus from '../../../components/modal/OrderStatus.vue';
import { computed, ref } from 'vue';
import OrderResource from '../../../components/ui/card/OrderResource.vue';
const props = defineProps({
    order: Object,
    searches: Object,
});

const priorityFee = ref(0);

const page = usePage();

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-Us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

function statusBadgeClass(status) {
    const classes = {
        pending: 'dark:border inline-flex items-center px-3 py-1 text-yellow-600 bg-yellow-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        processing: 'dark:border inline-flex items-center px-3 py-1 text-blue-600 bg-blue-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        completed: 'dark:border inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800',
        cancelled: 'dark:border inline-flex items-center px-3 py-1 text-red-500 rounded-full gap-x-2 bg-red-100/60 dark:bg-gray-800',
        received: 'dark:border inline-flex items-center px-3 py-1 text-indigo-600 bg-indigo-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        rated: 'dark:border inline-flex items-center px-3 py-1 text-purple-600 bg-purple-100/60 dark:bg-gray-800 rounded-full gap-x-2',
    };
    return `px-3 py-1 rounded-full text-xs font-semibold flex items-center ${classes[status] || 'bg-gray-100 text-gray-800 border border-gray-200'}`;
}
// Status icon
function statusIconClass(status) {
    const icons = {
        pending: 'fa-regular fa-clock',
        processing: 'fa-solid fa-arrows-split-up-and-left',
        completed: 'fa-regular fa-circle-check',
        cancelled: 'fa-solid fa-xmark',
        received: 'bi bi-download',
        rated: 'fa-solid fa-star-half-stroke',
    };
    return icons[status] || 'fas fa-info-circle';
}

function image_src(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }

    return '/images/img-placeholder.jpg';
}

function formatCurrency(amount) {
    amount = Number(amount);
    return amount.toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP',
    });
}

function handleUpdateStatus(data) {
    router.patch(route('manage.orders.update', { order: props.order.id, ...data, priorityFee: priorityFee.value }));
}

const styleClass = {
    orderId: 'text-3xl font-bold  bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent',
    card: {
        container:
            'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md',
        head: 'px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900',
        title: 'text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2',
    },
};

const modalIsOpen = ref(false);

const image_url = computed(() => {
    let src = props.order.user.avatar_url;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
});
</script>

<template>
    <Head title="Manage Order" />

    <div class="px-2 py-3">
        <div class="mx-auto max-w-7xl px-4 py-8">
            <div class="container-secondary flex items-center justify-between">
                <div>
                    <h1 :class="styleClass.orderId">Order # {{ order.id }}</h1>
                    <p class="mt-2 flex items-center text-gray-500">
                        <i class="fas fa-calendar-day mr-2 text-gray-400"></i>
                        Placed on {{ formatDate(order.created_at) }}
                    </p>
                </div>
                <Link :href="route('manage.orders.index', { ...searches })" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Orders
                </Link>
            </div>
            <!-- Status Card with subtle shadow -->
            <div class="container-secondary">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-4 capitalize">
                        <span :class="statusBadgeClass(order.status)" class="animate-pulse">
                            <i :class="statusIconClass(order.status)" class="mr-1.5"></i>
                            {{ order.status }}
                        </span>
                        <button
                            @click="modalIsOpen = true"
                            class="flex items-center gap-1.5 text-sm text-blue-600 transition-colors hover:text-blue-800"
                        >
                            <i class="fas fa-pen-fancy text-xs"></i>
                            Update Status
                        </button>
                    </div>
                    <div class="flex gap-3">
                        <Link as="button" :href="route('message.index', { receiver: order.user.id })" class="btn btn-primary">
                            <i class="fas fa-envelope"></i>
                            Contact Customer
                        </Link>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <!-- Order Items Card -->
                    <div :class="styleClass.card.container">
                        <div :class="styleClass.card.head">
                            <h2 :class="styleClass.card.title">
                                <i class="fas fa-boxes text-blue-500"></i>
                                Order Item
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            <div class="p-6 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex gap-4">
                                    <div class="relative">
                                        <img
                                            :src="image_src((order.option?.images[0] || order.product.images[0])?.image_path)"
                                            class="h-20 w-20 rounded-lg border border-gray-200 object-cover dark:border-gray-600"
                                        />
                                        <span
                                            class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-blue-500 text-xs font-bold text-white"
                                        >
                                            {{ order.quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <Link
                                                    :href="
                                                        order.option
                                                            ? route('product.option.show', { product: order.product.id, option: order.option.id })
                                                            : route('product.show', { product: order.product.id })
                                                    "
                                                    class="text-2xl font-medium text-gray-900 transition-colors hover:text-blue-600 dark:text-white"
                                                >
                                                    {{ (order.option || order.product)?.name }}
                                                </Link>
                                                <p v-if="(order.option || order.product)?.size" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-tag mr-1 text-xs"></i>
                                                    {{ (order.option || order.product)?.size }}
                                                </p>
                                            </div>
                                            <div class="flex flex-col items-end font-medium text-gray-900 dark:text-white">
                                                <p>
                                                    {{ formatCurrency((order.option || order.product)?.price) }}
                                                </p>

                                                <span class="flex items-center gap-2">
                                                    <i class="fa fa-xmark text-xs"></i>
                                                    {{ order.quantity }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-calculator mr-1 text-xs"></i>
                                                Subtotal: {{ formatCurrency(order.total_amount) }}
                                            </span>
                                            <!-- <button
                                                class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                                                <i class="fas fa-ellipsis-h"></i>
                                                Actions
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900">
                            <div class="flex justify-end">
                                <div class="w-72 space-y-3 text-sm">
                                    <!-- <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                        <span>Subtotal</span>
                                        <span>{{ order.total_amount }}</span>
                                    </div> -->
                                    <!-- <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                        <span>Priority/ Rush fee</span>
                                        <input type="number" v-model="priorityFee" class="w-25 border-b text-right focus:outline-0" min="0" />
                                    </div> -->
                                    <div
                                        class="flex justify-between border-t border-gray-200 pt-3 text-lg font-semibold text-gray-900 dark:border-gray-600 dark:text-white"
                                    >
                                        <span>Total</span>
                                        <span>{{ formatCurrency(order.total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Notes Card -->
                    <div :class="styleClass.card.container">
                        <div :class="styleClass.card.head">
                            <h2 :class="styleClass.card.title">
                                <i class="fas fa-sticky-note text-purple-500"></i>
                                Customer Notes
                            </h2>
                        </div>
                        <div class="p-6">
                            <div v-if="order.note" class="relative">
                                <div class="absolute top-0 -left-4 h-full w-1 rounded-full bg-blue-500"></div>
                                <p class="pl-3 text-gray-700 italic dark:text-gray-300">"{{ order.note }}"</p>
                            </div>
                            <div v-else class="py-6 text-center">
                                <i class="fas fa-comment-slash mb-2 text-3xl text-gray-300 dark:text-gray-600"></i>
                                <p class="text-gray-400 dark:text-gray-500">No notes from customer</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="order.resource?.length" :class="styleClass.card.container">
                        <div :class="styleClass.card.head">
                            <h2 :class="styleClass.card.title">
                                <i class="fa-solid fa-file-image text-amber-500"></i>
                                Order resources
                            </h2>
                        </div>
                        <div class="p-6">
                            <!-- Resource Cards Grid -->
                            <div class="grid gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                                <!-- Resource Card 1 -->
                                <OrderResource v-for="resource in order.resource" :key="resource.id" :resource="resource" />
                            </div>

                            <!-- View All Button -->

                            <div class="mt-6 text-center">
                                <a
                                    :href="route('manage.orders.download.resource.zip', { order: order.id })"
                                    class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-amber-600 focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:outline-none dark:focus:ring-offset-gray-800"
                                >
                                    <i class="fa-solid fa-download"></i>
                                    Download all Resources
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Customer Info Card -->
                    <div :class="styleClass.card.container">
                        <div :class="styleClass.card.head">
                            <h2 :class="styleClass.card.title">
                                <i class="fas fa-user-circle text-green-500"></i>
                                Customer
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="mb-5 flex items-center gap-4">
                                <div class="relative">
                                    <img
                                        :src="image_url"
                                        class="h-14 w-14 rounded-full border-2 border-white object-cover shadow dark:border-gray-800"
                                    />
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ order.user.name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.user.email }}</p>
                                    <div class="mt-1 flex gap-2">
                                        <Link
                                            :href="route('manage.orders.index', { search: order.user.name })"
                                            class="rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600 dark:bg-blue-900/20 dark:text-blue-400"
                                        >
                                            <i class="fas fa-shopping-bag mr-1"></i>
                                            {{ order.user.orders?.length }} orders
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <OrderStatus :status="order.status" @updateStatus="handleUpdateStatus" v-if="modalIsOpen" @close="modalIsOpen = false" />
</template>
