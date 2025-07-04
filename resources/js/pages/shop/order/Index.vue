<script setup>
    import { computed } from 'vue';
    import Tabs from '../../../components/sections/OrderTabs.vue';

    const props = defineProps({
        orders: Array,
        user: Object,
        tab: String
    });

    const pendingOrders = computed(() => props.orders.filter(order => order.status === 'pending'));
    const processingOrders = computed(() => props.orders.filter(order => order.status === 'processing'));
    const completedOrders = computed(() => props.orders.filter(order => order.status === 'completed'));
    const canceledOrders = computed(() => props.orders.filter(order => order.status === 'cancelled'));
    const rateOrders = computed(() => props.orders.filter(order => order.status === 'received'));
    const ratedOrders = computed(() => props.orders.filter(order => order.status === 'rated'));
</script>

<template>
    <div class=" mx-auto px-2 max-w-6xl py-6 h-full space-y-4">
        <Tabs :tab="tab" :pendingOrders="pendingOrders" :processingOrders="processingOrders" :completedOrders="completedOrders"
            :canceledOrders="canceledOrders" :rateOrders="rateOrders" :ratedOrders="ratedOrders" />
    </div>
</template>