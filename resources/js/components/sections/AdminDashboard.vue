<script setup>
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import DashboardMenuItem from '../ui/card/DashboardMenuItem.vue';
import VueApexCharts from 'vue3-apexcharts';
import { computed } from 'vue';
import dayjs from 'dayjs';
import PageTitleHeader from '../ui/PageTitleHeader.vue';
import DashboardRecentOrder from '../ui/card/DashboardRecentOrder.vue';
import Announcement from '../ui/card/Announcement.vue';
import TopProduct from '../ui/card/TopProduct.vue';
import ContactCard from '../ui/messages/ContactCard.vue';

const props = defineProps({
    admin: Object,
    shop: Object,
    contacts: Array,
});
const sales = computed(() => props.shop.orders.filter((order) => ['received', 'rated'].includes(order.status)));

const revenue = computed(() => {
    return sales.value.reduce((total, order) => total + (Number(order.total_amount) || 0), 0);
});

const salesData = computed(() => {
    const grouped = {};

    sales.value.forEach((order) => {
        const day = dayjs(order.updated_at).format('YYYY-MM-DD');
        if (!grouped[day]) {
            grouped[day] = 0;
        }
        grouped[day] += Math.round(order.total_amount) || 0;
    });

    return Object.entries(grouped)
        .map(([date, total]) => ({
            x: date,
            y: total,
        }))
        .sort((a, b) => new Date(a.x) - new Date(b.x));
});

const categories = computed(() => {
    const unique = new Set();

    sales.value.forEach((order) => {
        const categoryName = order.product?.category?.name;
        if (categoryName) unique.add(categoryName);
    });

    return [...unique]; // returns array of unique category names
});

const categoryRevenue = computed(() => {
    const totals = {};

    sales.value.forEach((order) => {
        const categoryName = order.product?.category?.name || 'Un-categorized';
        totals[categoryName] = Number(totals[categoryName] || 0) + Number(order.total_amount);
    });

    return totals;
});

const dailyVisits = computed(() => {
    const visits = props.shop.visitors?.visits_by_hour || [];
    const data = {};

    visits.forEach((time) => {
        // Normalize to HH:mm format
        const parts = time.split(':');
        const key = `${parts[0].padStart(2, '0')}:${parts[1].padStart(2, '0')}`;
        data[key] = (data[key] || 0) + 1;
    });

    return Object.entries(data).map(([time, visitors]) => {
        let [h, m] = time.split(':').map(Number);
        const period = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return {
            x: `${h}:${m.toString().padStart(2, '0')} ${period}`,
            y: visitors,
        };
    });
});

const charts = computed(() => ({
    sales: {
        type: 'bar',
        height: 300,
        width: '100%',
        series: [
            {
                name: 'Revenue',
                data: salesData.value,
            },
        ],
        options: {
            chart: {
                type: 'bar',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true,
                    },
                },
            },
            zoom: {
                enabled: true,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top',
                    },
                },
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    format: 'dd MMM',
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: "MMM 'yy",
                        day: 'dd MMM',
                        hour: 'HH:mm',
                    },
                },
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy HH:mm',
                },
            },
        },
    },
    visits: {
        type: 'area',
        width: '100%',
        height: 300,
        series: [
            {
                name: 'Visitors this time',
                data: [...dailyVisits.value],
            },
        ],
        options: {
            chart: {
                height: 350,
                type: 'area',
            },
            dataLabels: {
                enabled: true,
            },

            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'category',
                position: 'left',
            },
            yaxis: {
                stepSize: 1,
            },
        },
    },
    categorySales: {
        type: 'donut',
        width: '100%',
        height: 300,
        series: Object.values(categoryRevenue.value),
        options: {
            chart: {
                type: 'donut',
            },
            labels: categories.value,
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200,
                        },
                        legend: {
                            position: 'bottom',
                        },
                    },
                },
            ],
        },
    },
}));

const styleClass = {
    stat: {
        container: 'bg-white p-4 rounded-lg shadow border-l-4 flex items-center space-x-4 dark:bg-slate-500',
        title: 'text-sm text-gray-500 dark:text-white',
        count: 'text-2xl font-bold text-start',
    },
};

const recentOrders = computed(() => props.shop.orders.filter((_, i) => i < 10));

const topProducts = computed(() => props.shop.products.sort((a, b) => b.rating - a.rating));
</script>
<template>
    {{ console.dir(dailyVisits) }}
    <div class="mx-auto max-w-7xl space-y-6 px-4 pt-8 pb-7">
        <PageTitleHeader title="Dashboard" />

        <!-- Stats Cards -->
        <ContainerPrimary class="!pt-6 !pb-4">
            <div class="flex flex-wrap justify-center gap-4">
                <!-- Total Products -->
                <Link
                    :href="route('product.index')"
                    as="button"
                    class="flex items-center gap-4 rounded-lg border-l-4 border-blue-500 bg-white p-4 shadow-xs transition-all duration-200 hover:shadow-sm dark:bg-gray-800"
                >
                    <div class="rounded-full bg-blue-100/80 p-3 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ shop.products.length }}</p>
                    </div>
                </Link>

                <!-- Total Orders -->
                <Link
                    :href="route('manage.orders.index')"
                    as="button"
                    class="flex items-center gap-4 rounded-lg border-l-4 border-purple-500 bg-white p-4 shadow-xs transition-all duration-200 hover:shadow-sm dark:bg-gray-800"
                >
                    <div class="rounded-full bg-purple-100/80 p-3 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ shop.orders.length }}</p>
                    </div>
                </Link>

                <!-- Total Customers -->
                <div class="flex items-center gap-4 rounded-lg border-l-4 border-green-500 bg-white p-4 shadow-xs dark:bg-gray-800">
                    <div class="rounded-full bg-green-100/80 p-3 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Customers</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ shop.customers }}</p>
                    </div>
                </div>

                <!-- Revenue Stat Card -->
                <div class="flex items-center gap-4 rounded-lg border-l-4 border-green-600 bg-white p-4 shadow-xs dark:bg-gray-800">
                    <div class="rounded-full bg-green-100/80 p-3 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ revenue.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                        </p>
                    </div>
                </div>

                <!-- Announcements -->
                <Link
                    :href="route('announcement.index')"
                    as="button"
                    class="flex items-center gap-4 rounded-lg border-l-4 border-yellow-500 bg-white p-4 shadow-xs transition-all duration-200 hover:shadow-sm dark:bg-gray-800"
                >
                    <div class="rounded-full bg-yellow-100/80 p-3 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625 1.234 9.366 3.87a1 1 0 01-.127 1.454A3.995 3.995 0 0117 13h-1.832c-2.127 0-4.113.676-5.73 1.832a4.002 4.002 0 00-2.002.626z"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium break-words break-all text-gray-500 dark:text-gray-400">Announcements</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ shop.announcements.length }}</p>
                    </div>
                </Link>

                <!-- Messages -->
                <Link
                    :href="route('message.index')"
                    as="button"
                    class="flex items-center gap-4 rounded-lg border-l-4 border-purple-500 bg-white p-4 shadow-xs transition-all duration-200 hover:shadow-sm dark:bg-gray-800"
                >
                    <div class="rounded-full bg-purple-100/80 p-3 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                            />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Messages</h3>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ shop.messages }}</p>
                    </div>
                </Link>
            </div>
        </ContainerPrimary>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <!-- Sales Report Chart -->
            <div class="rounded-xl bg-white p-5 shadow md:col-span-7 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Sales Report</h3>
                <VueApexCharts
                    :options="charts.sales.options"
                    :type="charts.sales.type"
                    :height="charts.sales.height"
                    :width="charts.sales.width"
                    :series="charts.sales.series"
                />
            </div>

            <!-- Daily Site Visitors -->
            <div class="rounded-xl bg-white p-5 shadow md:col-span-5 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Daily Site Visitors</h3>
                <VueApexCharts
                    :options="charts.visits.options"
                    :type="charts.visits.type"
                    :height="charts.visits.height"
                    :width="charts.visits.width"
                    :series="charts.visits.series"
                />
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-12">
            <!-- Sales by Category -->
            <div class="rounded-xl bg-white p-5 shadow md:col-span-4 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Sales by Category</h3>
                <VueApexCharts
                    :options="charts.categorySales.options"
                    :type="charts.categorySales.type"
                    :height="charts.categorySales.height"
                    :width="charts.categorySales.width"
                    :series="charts.categorySales.series"
                />
            </div>

            <!-- Messages -->
            <div class="rounded-xl bg-white p-5 shadow md:col-span-3 dark:bg-gray-800" v-if="topProducts.length">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Messages</h3>
                </div>
                <div class="h-80 space-y-3 overflow-y-auto pr-2">
                    <ContactCard :user="admin" v-for="contact in contacts" :contact="contact" :key="contact.id" />
                </div>
            </div>

            <!-- Top Products -->
            <div class="rounded-xl bg-white p-5 shadow md:col-span-5 dark:bg-gray-800" v-if="topProducts.length">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Products</h3>
                </div>
                <div class="h-80 space-y-3 overflow-y-auto">
                    <TopProduct v-for="product in topProducts" :key="product.id" :product="product" />
                </div>
            </div>
        </div>

        <!-- Recent Orders & Announcements -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2" v-if="recentOrders.length">
            <!-- Recent Orders -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
                <div class="max-h-96 space-y-4 overflow-y-auto pr-2">
                    <DashboardRecentOrder v-for="order in recentOrders" :key="`order-${order.id}`" :order="order" />
                </div>
            </div>

            <!-- Announcements -->
            <div class="rounded-xl bg-white p-5 shadow dark:bg-gray-800" v-if="shop.announcements.length">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Announcements</h3>
                <div class="max-h-96 space-y-4 overflow-y-auto pr-2">
                    <Announcement v-for="announcement in shop.announcements" :key="`announcement-${announcement.id}`" :announcement="announcement" />
                </div>
            </div>
        </div>
    </div>
</template>
