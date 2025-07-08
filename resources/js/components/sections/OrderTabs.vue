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
        tab: String
    });

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

    const styleClass = {
        active: 'text-blue-600  !border-blue-600 dark:text-blue-500 dark:!border-blue-500',
        tab: 'inline-flex gap-1 hover:text-blue-600 dark:hover:text-blue-500 items-center justify-center p-2 border-b-2 border-transparent rounded-t-lg  hover:border-gray-300  group relative',
        tabsContainer: 'flex flex-wrap border-b border-gray-200 dark:border-gray-700 text-sm font-medium text-center text-gray-500 dark:text-gray-400',
        tabTitle: 'font-semibold text-indigo-600 items-center pb-2 pr-2 border-b-2 border-indigo-600 uppercase inline-block'
    }
</script>

<template>

    <div :class="styleClass.tabsContainer">
        <Link :href="route('dashboard')" is="button" :class="`${styleClass.tab} pr-4`">
        <i class="bi bi-arrow-left text-xl "></i>
        Back
        </Link>
        <button v-for="tab in tabs" :key="tab.name" @click="activeTab = tab.name"
            :class="tab.name === activeTab ? `${styleClass.tab} ${styleClass.active}` : styleClass.tab">
            <span v-if="tab.count"
                class="absolute text-xs top-0 right-0 bg-primary-400 p-0.5 rounded-full h-5 w-5 text-white">{{ tab.count
                }}</span>
            <i :class="tab.icon"></i>
            {{ tab.name }}
        </button>
    </div>

    <div class="h-screen overflow-y-scroll">
        <transition name="tab-fade" mode="out-in">
            <div :key="activeTab">
                <div v-if="activeTab === 'Under Review'">
                    <div class="border-b mb-5 flex justify-between text-sm">
                        <h2 :class="styleClass.tabTitle">
                            Under Review Queue
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
                        <OrderTabCard v-for="order in pendingOrders" :key="`pending-${order.id}`" :order="order"
                            showCancel />
                    </div>
                </div>
                <div v-else-if="activeTab === 'Processing'">
                    <div class="border-b mb-5 flex justify-between text-sm">
                        <h2 :class="styleClass.tabTitle">
                            Processing orders
                        </h2>
                        <span class="text-xs font-medium">Cancellation is not allowed</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

                        <OrderTabCard v-for="order in processingOrders" :key="`processing-${order.id}`" :order="order"
                            label="Processing" />
                    </div>
                </div>
                <div v-else-if="activeTab === 'Completed'">
                    <div class="border-b mb-5 flex justify-between text-sm">
                        <h2 :class="styleClass.tabTitle">
                            Ready for pickup
                        </h2>
                        <!-- <span class="text-xs font-medium">Cancellation is not allowed</span> -->
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

                        <OrderTabCard v-for="order in completedOrders" :key="`completed-${order.id}`" :order="order"
                            label="Processing" showReceived />
                    </div>
                </div>
                <div v-else-if="activeTab === 'Canceled'">
                    <div class="border-b mb-5 flex justify-between text-sm">
                        <h2 :class="styleClass.tabTitle">
                            Cancelled orders
                        </h2>
                        <!-- <span class="text-xs font-medium">Cancellation is not allowed</span> -->
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

                        <OrderTabCard danger v-for="order in canceledOrders" :key="`cancelled-${order.id}`"
                            :order="order" label="Cancelled" />
                    </div>
                </div>
                <div v-else-if="activeTab === 'Rate'">
                    <div class="border-b mb-5 flex justify-between text-sm">
                        <h2 :class="styleClass.tabTitle">
                            Rate received orders
                        </h2>
                        <!-- <span class="text-xs font-medium">Cancellation is not allowed</span> -->
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">

                        <OrderTabCard @rate="({ order }) => forRate = order" showRate v-for="order in rateOrders"
                            :key="`rate-${order.id}`" :order="order" label="Ready for rating" />
                            
                        <OrderTabCard @rate="({ order }) => forRate = order" showRate v-for="order in ratedOrders"
                            :key="`rated-${order.id}`" :order="order" label="Rated" />
                    </div>
                </div>
            </div>
        </transition>
    </div>

    <Rating :order="forRate" v-if="forRate" @closeRatingModal="forRate = null" />
</template>

<style scoped>

    .tab-fade-enter-active,
    .tab-fade-leave-active {
        transition: all 0.3s ease;
        opacity: 1;
        transform: translateY(0);
    }

    .tab-fade-enter-from,
    .tab-fade-leave-to {
        opacity: 0;
        transform: translateY(10px);
    }

</style>