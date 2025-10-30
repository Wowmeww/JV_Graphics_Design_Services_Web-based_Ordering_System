<script setup>
import { computed, ref } from 'vue';
import OrderTabCard from '../ui/card/OrderTabCard.vue';
import Rating from '../modal/Rating.vue';

const props = defineProps({
    pendingOrders: Array,
    processingOrders: Array,
    completedOrders: Array,
    canceledOrders: Array,
    rateOrders: Array,
    ratedOrders: Array,
    tab: String,
});

const emit = defineEmits(['changeTab']);

const tabs = computed(() => [
    { name: 'Under Review', icon: 'bi bi-hourglass text-lg', count: props.pendingOrders.length },
    { name: 'Processing', icon: 'bi bi-paint-bucket text-xl', count: props.processingOrders.length },
    { name: 'Completed', icon: 'bi bi-check2-circle text-xl', count: props.completedOrders.length },
    { name: 'Canceled', icon: 'bi bi-x-circle text-xl', count: props.canceledOrders.length },
    { name: 'Rate', icon: 'bi bi-star text-xl', count: props.rateOrders.length },
]);

const activeTab = ref(props.tab || tabs.value[0].name);

const forRate = ref(null);

// pending, processing, completed, cancelled

// enhance ui to perfection and remove the scale increase at hover
const styleClass = {
    active: 'text-blue-600 !border-blue-600 dark:text-blue-500 dark:!border-blue-500 bg-blue-50 dark:bg-blue-900/20',
    tab: 'inline-flex gap-2 hover:text-blue-600 dark:hover:text-blue-500 items-center justify-center p-3 border-b-2 border-transparent rounded-t-lg hover:border-gray-300 dark:hover:border-gray-600 group relative transition-all duration-200 ease-in-out',
    tabsContainer:
        'flex flex-wrap border-b border-gray-200 dark:border-gray-700 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-4 rounded-t-lg',
    tabTitle:
        'font-bold text-indigo-700 dark:text-indigo-300 items-center pb-3 text-lg uppercase tracking-wide inline-block border-b-2 border-indigo-600 dark:border-indigo-400',
};

function changeTab(tab) {
    emit('changeTab', tab);
    activeTab.value = tab;
}
</script>

<template>
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <!-- Tabs Navigation -->
        <div :class="styleClass.tabsContainer">
            <Link
                :href="route('dashboard')"
                is="button"
                :class="`${styleClass.tab} mr-2 rounded-lg pr-4 hover:bg-gray-50 dark:hover:bg-gray-700`"
                aria-label="Go back to dashboard"
            >
                <i class="bi bi-arrow-left text-xl transition-transform duration-200 group-hover:-translate-x-0.5"></i>
                <span class="font-medium">Back</span>
            </Link>

            <button
                v-for="tab in tabs"
                :key="tab.name"
                @click="changeTab(tab.name)"
                @keyup.enter="changeTab(tab.name)"
                @keyup.space="changeTab(tab.name)"
                :class="tab.name === activeTab ? `${styleClass.tab} ${styleClass.active}` : styleClass.tab"
                class="relative mx-1 rounded-t-lg px-4 py-3 transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700"
                :aria-selected="tab.name === activeTab"
                role="tab"
            >
                <span
                    v-if="tab.count"
                    class="bg-primary-500 absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full text-xs font-semibold text-white shadow-sm"
                    aria-label="${tab.count} items"
                >
                    {{ tab.count }}
                </span>
                <i :class="tab.icon" class="text-lg transition-colors duration-200"></i>
                <span class="font-semibold transition-colors duration-200">{{ tab.name }}</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="h-screen overflow-y-auto bg-gray-50/50 p-6 dark:bg-gray-900/50">
            <transition name="tab-fade" mode="out-in">
                <div :key="activeTab" class="h-full">
                    <!-- Under Review Tab -->
                    <div v-if="activeTab === 'Under Review'" class="h-full">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-600">
                            <h2 :class="styleClass.tabTitle">Under Review Queue</h2>

                             <span
                                class="rounded-full bg-amber-50 px-3 py-1 text-sm font-medium text-amber-600 dark:bg-amber-900/20 dark:text-amber-400"
                            >
                                Cancellation is allowed at this state
                            </span>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <OrderTabCard v-for="order in pendingOrders" :key="`pending-${order.id}`" :order="order" showCancel />
                        </div>
                    </div>

                    <!-- Processing Tab -->
                    <div v-else-if="activeTab === 'Processing'" class="h-full">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-600">
                            <h2 :class="styleClass.tabTitle">Processing Orders</h2>
                            <span
                                class="rounded-full bg-amber-50 px-3 py-1 text-sm font-medium text-amber-600 dark:bg-amber-900/20 dark:text-amber-400"
                            >
                                Cancellation is not allowed
                            </span>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <OrderTabCard v-for="order in processingOrders" :key="`processing-${order.id}`" :order="order" label="Processing" />
                        </div>
                    </div>

                    <!-- Completed Tab -->
                    <div v-else-if="activeTab === 'Completed'" class="h-full">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-600">
                            <h2 :class="styleClass.tabTitle">Ready for Pickup</h2>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <OrderTabCard v-for="order in completedOrders" :key="`completed-${order.id}`" :order="order" label="Ready" showReceived />
                        </div>
                    </div>

                    <!-- Canceled Tab -->
                    <div v-else-if="activeTab === 'Canceled'" class="h-full">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-600">
                            <h2 :class="styleClass.tabTitle">Cancelled Orders</h2>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <OrderTabCard danger v-for="order in canceledOrders" :key="`cancelled-${order.id}`" :order="order" label="Cancelled" />
                        </div>
                    </div>

                    <!-- Rate Tab -->
                    <div v-else-if="activeTab === 'Rate'" class="h-full">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4 dark:border-gray-600">
                            <h2 :class="styleClass.tabTitle">Rate Received Orders</h2>
                        </div>

                        <!-- Ready for Rating Section -->
                        <div v-if="rateOrders.length > 0" class="mb-8">
                            <h3
                                class="text-md mb-4 border-b border-gray-200 pb-2 font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-300"
                            >
                                Ready for Rating
                            </h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                <OrderTabCard
                                    @rate="({ order }) => (forRate = order)"
                                    showRate
                                    v-for="order in rateOrders"
                                    :key="`rate-${order.id}`"
                                    :order="order"
                                    label="Ready for Rating"
                                />
                            </div>
                        </div>

                        <!-- Already Rated Section -->
                        <div v-if="ratedOrders.length > 0">
                            <h3
                                class="text-md mb-4 border-b border-gray-200 pb-2 font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-300"
                            >
                                Already Rated
                            </h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                <OrderTabCard
                                    @rate="({ order }) => (forRate = order)"
                                    showRate
                                    v-for="order in ratedOrders"
                                    :key="`rated-${order.id}`"
                                    :order="order"
                                    label="Rated"
                                />
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="rateOrders.length === 0 && ratedOrders.length === 0" class="py-12 text-center">
                            <i class="bi bi-star mb-4 text-4xl text-gray-300 dark:text-gray-600"></i>
                            <p class="text-lg text-gray-500 dark:text-gray-400">No orders available for rating</p>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>

    <!-- Rating Modal -->
    <Rating :order="forRate" v-if="forRate" @closeRatingModal="forRate = null" />
</template>

<style scoped>
.tab-fade-enter-active,
.tab-fade-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 1;
    transform: translateY(0);
}

.tab-fade-enter-from {
    opacity: 0;
    transform: translateY(8px);
}

.tab-fade-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

/* Smooth focus states for accessibility */
button:focus-visible,
a:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
    border-radius: 6px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .tab-fade-enter-active,
    .tab-fade-leave-active {
        transition: none;
    }

    .tab,
    i {
        transition: none;
    }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
    .tabsContainer {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
}
</style>
