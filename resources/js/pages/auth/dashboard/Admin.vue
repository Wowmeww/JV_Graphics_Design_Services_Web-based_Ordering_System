<script setup>
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import VueApexCharts from 'vue3-apexcharts';
import { computed } from 'vue';
import dayjs from 'dayjs';
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import DashboardRecentOrder from '@/components/ui/card/DashboardRecentOrder.vue';
import Announcement from '@/components/ui/card/Announcement.vue';
import TopProduct from '@/components/ui/card/TopProduct.vue';
import ContactCard from '@/components/ui/messages/ContactCard.vue';

const props = defineProps({
    admin: Object,
    shop: Object,
    contacts: Array,
});

const sales = computed(() => props.shop.orders.filter((order) => ['received', 'rated'].includes(order.status)));

const revenue = computed(() => sales.value.reduce((total, order) => total + (Number(order.total_amount) || 0), 0));

const salesData = computed(() => {
    const grouped = {};
    sales.value.forEach((order) => {
        const day = dayjs(order.updated_at).format('YYYY-MM-DD');
        grouped[day] = (grouped[day] || 0) + Math.round(order.total_amount) || 0;
    });
    return Object.entries(grouped)
        .map(([date, total]) => ({ x: date, y: total }))
        .sort((a, b) => new Date(a.x) - new Date(b.x));
});

const categories = computed(() => {
    const unique = new Set();
    sales.value.forEach((order) => {
        const categoryName = order.product?.category?.name;
        if (categoryName) unique.add(categoryName);
    });
    return [...unique];
});

const categoryRevenue = computed(() => {
    const totals = {};
    sales.value.forEach((order) => {
        const categoryName = order.product?.category?.name || 'Un-categorized';
        totals[categoryName] = (totals[categoryName] || 0) + Number(order.total_amount);
    });
    return totals;
});

const charts = computed(() => ({
    sales: {
        type: 'bar',
        height: 300,
        width: '100%',
        series: [{ name: 'Revenue', data: salesData.value }],
        options: {
            chart: { type: 'bar', toolbar: { show: true } },
            plotOptions: {
                bar: { horizontal: false, borderRadius: 4, dataLabels: { position: 'top' } },
            },
            xaxis: {
                type: 'datetime',
                labels: { format: 'dd MMM' },
            },
            tooltip: { x: { format: 'dd MMM yyyy HH:mm' } },
        },
    },
    categorySales: {
        type: 'donut',
        width: '100%',
        height: 300,
        series: Object.values(categoryRevenue.value),
        options: {
            chart: { type: 'donut' },
            labels: categories.value,
            responsive: [
                {
                    breakpoint: 480,
                    options: { chart: { width: 200 }, legend: { position: 'bottom' } },
                },
            ],
        },
    },
}));

const processingOrders = computed(() => props.shop.orders.filter((order) => order.status === 'processing'));
const pendingOrders = computed(() => props.shop.orders.filter((order) => order.status === 'pending'));
const topProducts = computed(() => props.shop.products.sort((a, b) => b.rating - a.rating));

const styleClass = {
    container: 'mx-auto max-w-7xl space-y-6 px-4 pt-8 pb-7',
    statsContainer: '!pt-6 !pb-4',
    statsGrid: 'flex flex-wrap justify-center gap-4',
    statCardBase: 'flex items-center gap-4 rounded-lg border-l-4 bg-white p-4 shadow-xs dark:bg-gray-800',
    statCardClickable: 'transition-all duration-200 hover:shadow-sm',
    statCardBlue: 'border-blue-500',
    statCardPurple: 'border-purple-500',
    statCardGreen: 'border-green-500',
    statCardGreenDark: 'border-green-600',
    statCardYellow: 'border-yellow-500',
    statIconContainer: 'rounded-full p-3',
    statIconBlue: 'bg-blue-100/80 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
    statIconPurple: 'bg-purple-100/80 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
    statIconGreen: 'bg-green-100/80 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    statIconYellow: 'bg-yellow-100/80 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
    statIcon: 'h-6 w-6',
    statTextContainer: 'text-left',
    statLabel: 'text-sm font-medium text-gray-500 dark:text-gray-400',
    statValue: 'text-xl font-semibold text-gray-900 dark:text-white',
    statBreakWords: 'break-words break-all',
    chartsGrid: 'grid grid-cols-1 gap-6 md:grid-cols-12',
    chartCard: 'rounded-xl bg-white p-5 shadow dark:bg-gray-800',
    salesChart: 'lg:col-span-7',
    visitsChart: 'md:col-span-5',
    pendingOrders: 'md:col-span-5',
    chartTitle: 'mb-4 text-lg font-semibold text-gray-900 dark:text-white',
    scrollableContainer: 'max-h-96 space-y-4 overflow-y-auto pr-2',
    bottomGrid: 'grid grid-cols-1 gap-y-6 gap-x-3 lg:grid-cols-12',
    recentOrders: 'rounded-xl bg-white p-5 shadow lg:col-span-4 dark:bg-gray-800',
    categorySales: 'rounded-xl bg-white p-5 shadow lg:col-span-5 dark:bg-gray-800',
    messagesSection: 'rounded-xl bg-white p-5 shadow lg:col-span-3 dark:bg-gray-800',
    messagesHeader: 'mb-4 flex items-center justify-between',
    messagesContainer: 'h-80 space-y-3 overflow-y-auto pr-2',
    productsAnnouncementsGrid: 'grid grid-cols-1 gap-6 md:grid-cols-2',
    topProducts: 'rounded-xl bg-white p-5 shadow dark:bg-gray-800',
    announcementsSection: 'rounded-xl bg-white p-5 shadow dark:bg-gray-800',
    sectionHeader: 'mb-4 flex items-center justify-between',
    announcementsContainer: 'max-h-96 space-y-4 overflow-y-auto py-4 pr-2',
    productsContainer: 'h-80 space-y-3 overflow-y-auto',
};
</script>

<template>
    <Head title="Dashboard" />
    <div :class="styleClass.container">
        <PageTitleHeader title="Dashboard" />

        <!-- Stats Cards -->
        <ContainerPrimary :class="styleClass.statsContainer">
            <div :class="styleClass.statsGrid">
                <!-- Total Products -->
                <Link
                    :href="route('product.index')"
                    as="button"
                    :class="[styleClass.statCardBase, styleClass.statCardClickable, styleClass.statCardBlue]"
                >
                    <div :class="[styleClass.statIconContainer, styleClass.statIconBlue]">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.statIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                            />
                        </svg>
                    </div>
                    <div :class="styleClass.statTextContainer">
                        <h3 :class="styleClass.statLabel">Total Products</h3>
                        <p :class="styleClass.statValue">{{ shop.products.length }}</p>
                    </div>
                </Link>

                <!-- Total Orders -->
                <Link
                    :href="route('manage.orders.index')"
                    as="button"
                    :class="[styleClass.statCardBase, styleClass.statCardClickable, styleClass.statCardPurple]"
                >
                    <div :class="[styleClass.statIconContainer, styleClass.statIconPurple]">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.statIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                    </div>
                    <div :class="styleClass.statTextContainer">
                        <h3 :class="styleClass.statLabel">Total Orders</h3>
                        <p :class="styleClass.statValue">{{ shop.orders.length }}</p>
                    </div>
                </Link>

                <!-- Total Customers -->
                <div :class="[styleClass.statCardBase, styleClass.statCardGreen]">
                    <div :class="[styleClass.statIconContainer, styleClass.statIconGreen]">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.statIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <div :class="styleClass.statTextContainer">
                        <h3 :class="styleClass.statLabel">Total Customers</h3>
                        <p :class="styleClass.statValue">{{ shop.customers }}</p>
                    </div>
                </div>

                <!-- Revenue Stat Card -->
                <div :class="[styleClass.statCardBase, styleClass.statCardGreenDark]">
                    <div :class="[styleClass.statIconContainer, styleClass.statIconGreen]">
                        <svg xmlns="http://www.w3.org/2000/svg" :class="styleClass.statIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div :class="styleClass.statTextContainer">
                        <h3 :class="styleClass.statLabel">Total Revenue</h3>
                        <p :class="styleClass.statValue">
                            {{ revenue.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                        </p>
                    </div>
                </div>
            </div>
        </ContainerPrimary>
        <!-- Charts Section -->
        <div :class="styleClass.chartsGrid">
            <!-- Sales Report Chart -->
            <div :class="[styleClass.chartCard, styleClass.salesChart, !pendingOrders.length && 'md:!col-span-12']">
                <h3 :class="styleClass.chartTitle">Sales Report</h3>
                <VueApexCharts
                    :options="charts.sales.options"
                    :type="charts.sales.type"
                    :height="charts.sales.height"
                    :width="charts.sales.width"
                    :series="charts.sales.series"
                />
            </div>

            <!-- Pending Orders -->
            <div v-if="pendingOrders.length" :class="[styleClass.chartCard, styleClass.pendingOrders]">
                <h3 :class="styleClass.chartTitle">Pending Orders</h3>
                <div :class="styleClass.scrollableContainer">
                    <DashboardRecentOrder v-for="order in pendingOrders" :key="`order-${order.id}`" :order="order" />
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div :class="[styleClass.bottomGrid, !processingOrders.length && 'lg:!gap-6']">
            <!-- Processing Orders -->
            <div v-if="processingOrders.length" :class="[styleClass.recentOrders]">
                <h3 :class="styleClass.chartTitle">Processing Orders</h3>
                <div :class="styleClass.scrollableContainer">
                    <DashboardRecentOrder v-for="order in processingOrders" :key="`order-${order.id}`" :order="order" />
                </div>
            </div>

            <!-- Sales by Category -->
            <div :class="[styleClass.categorySales, !processingOrders.length && 'lg:!col-span-7']">
                <h3 :class="styleClass.chartTitle">Sales by Category</h3>
                <VueApexCharts
                    :options="charts.categorySales.options"
                    :type="charts.categorySales.type"
                    :height="charts.categorySales.height"
                    :width="charts.categorySales.width"
                    :series="charts.categorySales.series"
                />
            </div>

            <!-- Messages -->
            <div :class="[styleClass.messagesSection, !processingOrders.length && 'lg:!col-span-5']">
                <div :class="styleClass.messagesHeader">
                    <h3 :class="styleClass.chartTitle">Messages</h3>
                </div>
                <div :class="styleClass.messagesContainer">
                    <ContactCard :user="admin" v-for="contact in contacts" :contact="contact" :key="contact.id" />
                </div>
            </div>
        </div>
        <div :class="styleClass.productsAnnouncementsGrid">
            <!-- Top Products -->
            <div :class="[styleClass.topProducts]" v-if="topProducts.length">
                <div :class="styleClass.sectionHeader">
                    <h3 :class="styleClass.chartTitle">Top Products</h3>
                </div>
                <div :class="styleClass.productsContainer">
                    <TopProduct v-for="product in topProducts" :key="product.id" :product="product" />
                </div>
            </div>
            <!-- Announcements -->
            <div :class="[styleClass.announcementsSection]" v-if="shop.announcements.length">
                <h3 :class="styleClass.chartTitle">Announcements</h3>
                <div :class="styleClass.announcementsContainer">
                    <Announcement v-for="announcement in shop.announcements" :key="`announcement-${announcement.id}`" :announcement="announcement" />
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Daily Site Visitors -->
<!-- <div class="rounded-xl bg-white p-5 shadow md:col-span-5 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Daily Site Visitors</h3>
                <VueApexCharts
                    :options="charts.visits.options"
                    :type="charts.visits.type"
                    :height="charts.visits.height"
                    :width="charts.visits.width"
                    :series="charts.visits.series"
                />
            </div> -->
