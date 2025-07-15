<script setup>
    import { computed, ref, onMounted, watch } from 'vue';
    import Tabs from '@/components/sections/OrderTabs.vue';
    import { router } from '@inertiajs/vue3';
    import { useEcho } from "@laravel/echo-vue";
    // Props
    const props = defineProps({
        orders: Array,
        user: Object,
        tab: String
    });

    const orderList = ref([...props.orders]);
    watch(() => props.orders, (newOrders) => {
        orderList.value = [...newOrders];
    });
    const activeTab = ref(props.tab);
    // Order filters
    const pendingOrders = computed(() => orderList.value.filter(order => order.status === 'pending'));
    const processingOrders = computed(() => orderList.value.filter(order => order.status === 'processing'));
    const completedOrders = computed(() => orderList.value.filter(order => order.status === 'completed'));
    const canceledOrders = computed(() => orderList.value.filter(order => order.status === 'cancelled'));
    const rateOrders = computed(() => orderList.value.filter(order => order.status === 'received'));
    const ratedOrders = computed(() => orderList.value.filter(order => order.status === 'rated'));


    useEcho(
        `update-order-status.${props.user.id}`,
        '.OrderStatusUpdated',
        (e) => {
            const index = orderList.value.findIndex(order => order.id === e.order.id);
            if (index !== -1) {
                orderList.value[index] = e.order;
            } else {
                orderList.value.unshift(e.order); // Add new order at the top if needed
            }
        },
    );


</script>

<template>

    <div class="mx-auto px-2 max-w-6xl py-6 h-full space-y-4">
        <Tabs :tab="activeTab" :pendingOrders="pendingOrders" :processingOrders="processingOrders"
            :completedOrders="completedOrders" :canceledOrders="canceledOrders" :rateOrders="rateOrders"
            :ratedOrders="ratedOrders" @changeTab="(tab) => activeTab = tab" />
    </div>
</template>
