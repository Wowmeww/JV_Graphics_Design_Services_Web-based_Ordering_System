<script setup>
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import DashboardMenuItem from '../ui/card/DashboardMenuItem.vue';
    import VueApexCharts from "vue3-apexcharts";
    import { computed } from 'vue';
    import dayjs from 'dayjs';
    import PageTitleHeader from '../ui/PageTitleHeader.vue';

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

</script>

<template>
    {{ console.log(categories) }}
    <div class="pt-8 px-2 max-w-7xl mx-auto space-y-3">

        <PageTitleHeader title="Dashboard" />
        <ContainerPrimary class="!pt-6">
            <div class="pt-3 flex flex-wrap gap-4 justify-center">
                <DashboardMenuItem label="Total Products" :count="shop.products.length" icon="bi bi-box-seam" />
                <DashboardMenuItem label="Total Orders" :count="shop.orders.length" icon="bi bi-clipboard-minus" />
                <DashboardMenuItem label="Total Customers" :count="shop.customers" icon="bi bi-people-fill" />
                <div
                    class="flex items-center flex-col-reverse p-2.5 border bg-primary text-light border-slate-900 dark:border-slate-100 justify-center gap-y-2 md:flex-col-reverse md:items-start text-xs md:text-sm hover:bg-primary-500 transition rounded-md md:w-40 w-20 min-w-fit relative">
                    <span class="capitalize text-wrap font-semibold">
                        Total Revenue
                    </span>

                    <div class="flex items-center md:justify-between flex-wrap w-full gap-2 justify-center">
                        <i class="md:text-3xl text-xl fa-solid fa-coins"></i>

                        <span class="top-1 right-2 text-lg font-semibold">{{
                            revenue.toLocaleString('en-PH', {
                                style: 'currency',
                                currency: 'PHP'
                            }) }}</span>
                    </div>
                </div>
            </div>

        </ContainerPrimary>
        <div class="py-10 ">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-600">
                <!-- Sales Report Chart -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Sales Report</h3>
                    <VueApexCharts :options="charts.sales.options" :type="charts.sales.type"
                        :height="charts.sales.height" :width="charts.sales.width" :series="charts.sales.series" />
                </div>
                <!-- Daily Site Visitors -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Daily Site Visitors</h3>
                    <VueApexCharts :options="charts.visits.options" :type="charts.visits.type"
                        :height="charts.visits.height" :width="charts.visits.width" :series="charts.visits.series" />
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Recent Orders</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <h3 class="text-lg dark:text-slate-300 font-semibold mb-2">Sales by Category</h3>
                    <VueApexCharts :options="charts.categorySales.options" :type="charts.categorySales.type"
                        :height="charts.categorySales.height" :width="charts.categorySales.width"
                        :series="charts.categorySales.series" />
                </div>
            </div>
        </div>

    </div>
</template>