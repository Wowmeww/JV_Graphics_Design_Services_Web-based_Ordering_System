<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import OrderStatus from '../../../components/modal/OrderStatus.vue';
    import { computed, ref } from 'vue';
    const props = defineProps({
        order: Object,
        searches: Object
    });

    const page = usePage();

    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-Us', {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
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

        }
        return `px-3 py-1 rounded-full text-xs font-semibold flex items-center ${classes[status] || 'bg-gray-100 text-gray-800 border border-gray-200'
            }`
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
        }
        return icons[status] || 'fas fa-info-circle'
    }

    function image_src(src) {
        if (src) {
            return src.includes('https') ? src : `/storage/${src}`;
        }

        return '/images/img-placeholder.jpg';
    }

    function formatCurrency(amount) {
        return amount.toLocaleString('en-PH', {
            style: 'currency',
            currency: 'PHP'
        })
    }

    function handleUpdateStatus(data) {
        router.patch(route('manage.orders.update', { order: props.order.id, ...data }));
    }

    const styleClass = {
        orderId: 'text-3xl font-bold  bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent',
        card: {
            container: 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md',
            head: 'px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900',
            title: 'text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2'
        }
    }

    const modalIsOpen = ref(false);

    const image_url = computed(() => {
        let src = props.order.user.avatar_url;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
        return '/images/avatar-placeholder.webp';
    });
</script>

<template>

    <Head title="Manage Order" />

    <div class="py-3 px-2">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center container-secondary ">
                <div>
                    <h1 :class="styleClass.orderId">
                        Order # {{ order.id }}
                    </h1>
                    <p class="text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-4 capitalize">
                        <span :class="statusBadgeClass(order.status)" class="animate-pulse">
                            <i :class="statusIconClass(order.status)" class="mr-1.5"></i>
                            {{ order.status }}
                        </span>
                        <button @click="modalIsOpen = true"
                            class="text-sm text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1.5">
                            <i class="fas fa-pen-fancy text-xs"></i>
                            Update Status
                        </button>
                    </div>
                    <div class="flex gap-3">
                        <Link as="button" :href="route('message.index', {receiver: order.user.id})" class="btn btn-primary">
                            <i class="fas fa-envelope"></i>
                            Contact Customer
                        </Link>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ">
                <div class="lg:col-span-2 space-y-6 ">
                    <!-- Order Items Card -->
                    <div :class="styleClass.card.container">
                        <div :class="styleClass.card.head">
                            <h2 :class="styleClass.card.title">
                                <i class="fas fa-boxes text-blue-500"></i>
                                Order Item
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <div class="flex gap-4">
                                    <div class="relative">
                                        <img :src="image_src((order.option?.images[0] || order.product.images[0])?.image_path)"
                                            class="w-20 h-20 rounded-lg object-cover border border-gray-200 dark:border-gray-600" />
                                        <span
                                            class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                            {{ order.quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h3
                                                    class="font-medium text-gray-900 dark:text-white hover:text-blue-600 transition-colors">
                                                    {{ (order.option || order.product)?.name }}
                                                </h3>
                                                <p v-if="(order.option || order.product)?.size"
                                                    class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    <i class="fas fa-tag text-xs mr-1"></i>
                                                    {{ (order.option || order.product)?.size }}
                                                </p>
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ formatCurrency((order.option || order.product)?.price) }}
                                            </p>
                                        </div>
                                        <div class="mt-3 flex justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-calculator text-xs mr-1"></i>
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
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-end">
                                <div class="w-72 space-y-3 text-sm">
                                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                        <span>Subtotal</span>
                                        <span>{{ formatCurrency(order.total_amount) }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                        <span>Priority/ Rush fee</span>
                                        <span>{{ formatCurrency(0) }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-600 font-semibold text-lg text-gray-900 dark:text-white">
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
                                <div class="absolute -left-4 top-0 h-full w-1 bg-blue-500 rounded-full"></div>
                                <p class="text-gray-700 dark:text-gray-300 italic pl-3">"{{ order.note }}"</p>
                            </div>
                            <div v-else class="text-center py-6">
                                <i class="fas fa-comment-slash text-3xl text-gray-300 dark:text-gray-600 mb-2"></i>
                                <p class="text-gray-400 dark:text-gray-500">No notes from customer</p>
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
                            <div class="flex items-center gap-4 mb-5">
                                <div class="relative">
                                    <img :src="image_url"
                                        class="w-14 h-14 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow">

                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ order.user.name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ order.user.email }}</p>
                                    <div class="flex gap-2 mt-1">
                                        <Link :href="route('manage.orders.index', { search: order.user.name })"
                                            class="text-xs bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full">
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



    <OrderStatus :status="order.status" @updateStatus="handleUpdateStatus" v-if="modalIsOpen"
        @close="modalIsOpen = false" />

</template>
