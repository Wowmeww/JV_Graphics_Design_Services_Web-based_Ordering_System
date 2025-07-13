<script setup>
    import { computed, ref, onMounted } from 'vue';
    import Tabs from '@/components/sections/OrderTabs.vue';
    import { router } from '@inertiajs/vue3';
    import echo from '@/echo';
    // Props
    const props = defineProps({
        orders: Array,
        user: Object,
        tab: String
    });

    const orderList = ref([...props.orders]); // make a reactive copy

    const activeTab = ref(props.tab);
    // Order filters
    const pendingOrders = computed(() => orderList.value.filter(order => order.status === 'pending'));
    const processingOrders = computed(() => orderList.value.filter(order => order.status === 'processing'));
    const completedOrders = computed(() => orderList.value.filter(order => order.status === 'completed'));
    const canceledOrders = computed(() => orderList.value.filter(order => order.status === 'cancelled'));
    const rateOrders = computed(() => orderList.value.filter(order => order.status === 'received'));
    const ratedOrders = computed(() => orderList.value.filter(order => order.status === 'rated'));


    echo.private(`update-order-status.${props.user.id}`)
        .listen('.OrderStatusUpdated', () => {
            router.get(route('order.index'), { tab: activeTab.value }, { preserveScroll: true });
        });


</script>

<template>

    <div class="mx-auto px-2 max-w-6xl py-6 h-full space-y-4">
        <Tabs :tab="activeTab" :pendingOrders="pendingOrders" :processingOrders="processingOrders"
            :completedOrders="completedOrders" :canceledOrders="canceledOrders" :rateOrders="rateOrders"
            :ratedOrders="ratedOrders" @changeTab="(tab) => activeTab = tab" />
    </div>
</template>
