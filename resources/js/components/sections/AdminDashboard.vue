<script setup>
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import DashboardMenuItem from '../ui/card/DashboardMenuItem.vue';
    import VueApexCharts from "vue3-apexcharts";
    import { computed } from 'vue';
    import dayjs from 'dayjs';
    import PageTitleHeader from '../ui/PageTitleHeader.vue';
    import DashboardRecentOrder from '../ui/card/DashboardRecentOrder.vue';
    import Announcement from '../ui/card/Announcement.vue';
    import TopProduct from '../ui/card/TopProduct.vue';

    const props = defineProps({
        admin: Object,
        shop: Object
    });
    const sales = computed(() => props.shop.orders.filter(order =>
        ['received', 'rated'].includes(order.status)
    ));

    const revenue = computed(() => {
        return sales.value.reduce((total, order) => total + (order.total_amount || 0), 0);
    });


    const salesData = computed(() => {
        const grouped = {};

        sales.value.forEach(order => {
            const day = dayjs(order.created_at).format('YYYY-MM-DD');
            if (!grouped[day]) {
                grouped[day] = 0;
            }
            grouped[day] += order.total_amount || 0;
        });

        return Object.entries(grouped).map(([date, total]) => ({
            x: date,
            y: total
        })).sort((a, b) => new Date(a.x) - new Date(b.x));
    });

    const categories = computed(() => {
        const unique = new Set();

        sales.value.forEach(order => {
            const categoryName = order.product?.category?.name;
            if (categoryName) unique.add(categoryName);
        });

        return [...unique]; // returns array of unique category names
    });

    const categoryRevenue = computed(() => {
        const totals = {};

        sales.value.forEach(order => {
            const categoryName = order.product?.category?.name || 'Uncategorized';
            totals[categoryName] = (totals[categoryName] || 0) + order.total_amount;
        });

        return totals;
    });

    const charts = computed(() => ({
        sales: {
            type: 'bar',
            height: 300,
            width: '100%',
            series: [
                {
                    name: 'Revenue',
                    data: salesData.value
                }
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
                            reset: true
                        }
                    },
                },
                zoom: {
                    enabled: true
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 4,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                xaxis: {
                    type: 'datetime',
                    labels: {
                        format: 'dd MMM',
                        datetimeFormatter: {
                            year: 'yyyy',
                            month: "MMM 'yy",
                            day: 'dd MMM',
                            hour: 'HH:mm'
                        }
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy HH:mm'
                    }
                }

            }
        },
        visits: {
            type: "area",
            width: '100%',
            height: 300,
            series: [{
                name: 'series1',
                data: [31, 40, 28, 51]
            }],
            options: {
                chart: {
                    type: 'area',
                    zoom: {
                        enabled: true
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z"]
                }
            }
        },
        categorySales: {
            type: "donut",
            width: '100%',
            height: 300,
            series: Object.values(categoryRevenue.value),
            options: {
                chart: {
                    type: 'donut',
                },
                labels: categories.value,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            },
        }
    }));

    const styleClass = {
        stat: {
            container: 'bg-white p-4 rounded-lg shadow border-l-4 flex items-center space-x-4 dark:bg-slate-500',
            title: 'text-sm text-gray-500 dark:text-white',
            count: 'text-2xl font-bold text-start'
        }
    }

    const recentOrders = computed(() => props.shop.orders.filter((_, i) => i < 10));


    const topProducts = computed(() => (props.shop.products.sort((a, b) => b.rating - a.rating)));
</script>

<template>

    <!-- {{ console.log(topProducts) }} -->
    <div class="pt-8 px-2 max-w-7xl mx-auto space-y-3">

        <PageTitleHeader title="Dashboard" />
        <ContainerPrimary class="!pt-6">
            <div class="pt-3 flex flex-wrap gap-4 justify-center">
                <!-- Total Products -->
                <Link :href="route('product.index')" as="button"
                    :class="[styleClass.stat.container, 'border-blue-500']">
                <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div>
                    <h3 :class="styleClass.stat.title">Total Products</h3>
                    <p :class="styleClass.stat.count">{{ shop.products.length }}</p>
                </div>
                </Link>

                <!-- Total Orders -->
                <Link :href="route('manage.orders.index')" as="button"
                    :class="[styleClass.stat.container, 'border-purple-500']">
                <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h3 :class="styleClass.stat.title">Total Orders</h3>
                    <p :class="styleClass.stat.count">{{ shop.orders.length }}</p>
                </div>
                </Link>

                <!-- Total Customers -->
                <div :class="[styleClass.stat.container, 'border-green-500']">
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 :class="styleClass.stat.title">Total Customers</h3>
                        <p :class="styleClass.stat.count">{{ shop.customers }}</p>
                    </div>
                </div>
                <!-- Revenue Stat Card -->
                <div :class="[styleClass.stat.container, 'border-green-600']">
                    <!-- Revenue Icon -->
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Text Content -->
                    <div>
                        <h3 :class="styleClass.stat.title">Total Revenue</h3>
                        <p :class="styleClass.stat.count">
                            {{ revenue.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                        </p>
                    </div>
                </div>
                <!-- Announcements Stat Card -->
                <div :class="[styleClass.stat.container, 'border-yellow-600']">
                    <!-- Revenue Icon -->
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625 1.234 9.366 3.87a1 1 0 01-.127 1.454A3.995 3.995 0 0117 13h-1.832c-2.127 0-4.113.676-5.73 1.832a4.002 4.002 0 00-2.002.626z" />
                        </svg>
                    </div>

                    <!-- Text Content -->
                    <div>
                        <h3 :class="styleClass.stat.title">Announcements</h3>
                        <p :class="styleClass.stat.count">
                            {{ shop.announcements.length }}
                        </p>
                    </div>
                </div>

                <!-- Messages Stat Card -->
                <div :class="[styleClass.stat.container, 'border-purple-500']">
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>

                    <div>
                        <h3 :class="styleClass.stat.title">Messages</h3>
                        <p :class="styleClass.stat.count">{{ shop.messages }}</p>
                    </div>
                </div>
            </div>
        </ContainerPrimary>

        <div class="py-10 ">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-600 ">
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <!-- Sales Report Chart -->
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4 md:col-span-7">
                        <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Sales Report</h3>
                        <VueApexCharts :options="charts.sales.options" :type="charts.sales.type"
                            :height="charts.sales.height" :width="charts.sales.width" :series="charts.sales.series" />
                    </div>
                    <!-- Daily Site Visitors -->
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4 md:col-span-5">
                        <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Daily Site Visitors</h3>
                        <VueApexCharts :options="charts.visits.options" :type="charts.visits.type"
                            :height="charts.visits.height" :width="charts.visits.width"
                            :series="charts.visits.series" />
                    </div>
                </div>
                <div class="md:col-span-2 grid gap-2 md:grid-cols-12">
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4  flex flex-col md:col-span-4"
                        v-if="sales.length">
                        <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Sales by Category</h3>
                        <VueApexCharts :options="charts.categorySales.options" :type="charts.categorySales.type"
                            :height="charts.categorySales.height" :width="charts.categorySales.width"
                            :series="charts.categorySales.series" />
                    </div>
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4 flex flex-col md:col-span-3"
                        v-if="topProducts.length">
                        <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Messages</h3>

                        <div class="h-96 overflow-y-scroll px-2">
                            <TopProduct v-for="product in topProducts" :key="product.id" :product="product" />
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4 flex flex-col md:col-span-5"
                        v-if="topProducts.length">
                        <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Top Products</h3>

                        <div class="h-96 overflow-y-scroll px-2">
                            <TopProduct v-for="product in topProducts" :key="product.id" :product="product" />
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 " v-if="recentOrders.length">
                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4">
                        <div class="h-full  flex flex-col">
                            <h3 class="text-lg dark:text-slate-300 font-semibold mb-2 ">Recent Orders</h3>

                            <div class=" space-y-3 pb-7 overflow-y-scroll h-screen">
                                <DashboardRecentOrder v-for="order in recentOrders" :key="`order-${order.id}`"
                                    :order="order" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800/5 shadow rounded-lg p-4" v-if="shop.announcements.length">
                        <div class="h-full  flex flex-col">
                            <h3 class="text-lg dark:text-slate-300 font-semibold mb-2 ">Announcements</h3>

                            <div class=" space-y-3 pb-7 overflow-y-scroll h-screen">
                                <Announcement v-for="announcement in shop.announcements"
                                    :key="`announcement-${announcement.id}`" :announcement="announcement" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
