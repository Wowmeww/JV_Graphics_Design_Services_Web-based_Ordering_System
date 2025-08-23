<script setup>
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import Paginator from '../../../components/tables/Paginator.vue';
import { computed, reactive, ref, watch } from 'vue';
import Dropdown from '../../../components/ui/input/Dropdown.vue';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { debounce } from 'lodash';
import { useEcho } from '@laravel/echo-vue';

const props = defineProps({
    orders: Object,
    filters: Object,
    statusOptions: Array,
});

const tempOrders = ref([...props.orders.data]);

const params = route().params;

watch(
    () => props.orders.data,
    () => (tempOrders.value = [...props.orders.data]),
);
const form = reactive({
    search: params.search || null,
    status: params.status || null,
    sort: params.sort || 'Sort by date (new to old)',
});
// am I adding order corectly?
useEcho('order-placed', '.OrderPlaced', ({ order }) => {
    tempOrders.value.unshift(order);
    router.reload({ preserveState: true, preserveScroll: true });
});

const applyFilters = () => {
    router.get(
        route('manage.orders.index'),
        { ...form, page: 1 },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

watch(form, debounce(applyFilters, 400), { deep: true });

function clearFilter() {
    form.search = null;
    form.status = null;

    applyFilters();
}
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-Us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

function avatarSrc(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
}

function clearSearch() {
    form.search = null;
    applyFilters();
}

function clearStatus() {
    form.status = null;
    applyFilters();
}

const styleClass = computed(() => ({
    table: 'min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed',
    tableHead: 'bg-gray-50 dark:bg-gray-800',
    th: 'py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400',
    td: 'px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap capitalize',
    tBody: 'bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900',
    badges: {
        pending: 'inline-flex items-center px-3 py-1 text-yellow-600 bg-yellow-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        processing: 'inline-flex items-center px-3 py-1 text-blue-600 bg-blue-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        completed: 'inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800',
        cancelled: 'inline-flex items-center px-3 py-1 text-red-500 rounded-full gap-x-2 bg-red-100/60 dark:bg-gray-800',
        received: 'inline-flex items-center px-3 py-1 text-indigo-600 bg-indigo-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        rated: 'inline-flex items-center px-3 py-1 text-purple-600 bg-purple-100/60 dark:bg-gray-800 rounded-full gap-x-2',
    },
    user: {
        avatar: 'object-cover w-8 h-8 rounded-full',
        name: 'text-sm font-medium text-gray-800 dark:text-white ',
        email: 'text-xs font-normal text-gray-600 dark:text-gray-400 lowercase',
    },
}));

const badges = {
    pending: { label: 'pending', icon: 'fa-regular fa-clock' },
    processing: { label: 'processing', icon: 'fa-solid fa-arrows-split-up-and-left' },
    completed: { label: 'completed', icon: 'fa-regular fa-circle-check' },
    cancelled: { label: 'cancelled', icon: 'fa-solid fa-xmark' },
    received: { label: 'received', icon: 'bi bi-download' },
    rated: { label: 'rated', icon: 'fa-solid fa-star-half-stroke' },
};

const sortOptionOpen = ref(false);
const sortOptions = [
    'Sort by price (low to high)',
    'Sort by price (high to low)',
    'Sort by date (new to old)',
    'Sort by date (old to new)',
    'Sort by status',
];

function handleSort(sortOption) {
    sortOptionOpen.value = false;

    router.get(
        route('manage.orders.index'),
        { ...form, sort: sortOption, page: 1 },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}
</script>
<template>
    <Head title="Orders" />

    <!-- Overlay for mobile sort options -->
    <div v-if="sortOptionOpen" @click="sortOptionOpen = false" class="fixed inset-0 z-30 bg-black/10 backdrop-blur-sm"></div>
    <div class="px-2 py-3">
        <div class="mx-auto max-w-7xl py-6">
            <PageTitleHeader title="Order" />
            <div class="my-4 rounded-lg bg-white p-4 dark:bg-gray-800">
                <!-- FILTER -->
                <div class="flex flex-col items-center gap-x-5 gap-y-2 sm:flex-row">
                    <input
                        type="search"
                        v-model="form.search"
                        class="form-control form-control-primary flex-1 bg-white dark:bg-transparent"
                        placeholder="Search..."
                    />
                    <div class="w-full sm:w-40">
                        <Dropdown
                            :required="false"
                            :options="statusOptions"
                            :value="form.status"
                            @select="(option) => (form.status = option)"
                            placeholder="Filter status"
                        />
                    </div>

                    <button type="button" @click="clearFilter" class="btn btn-outline-primary w-full sm:w-fit">Clear filters</button>
                </div>
                <!-- APPLIED FILTERS -->

                <div class="flex gap-2 pt-2" v-if="form.search || form.status">
                    <span class="font-medium">Applied filters:</span>

                    <small class="badge badge-secondary" v-if="form.search">
                        Search: "{{ form.search }}"
                        <button @click="clearSearch" class="ml-2 text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </small>

                    <small class="badge badge-secondary" v-if="form.status">
                        Status: "{{ form.status }}"
                        <button @click="clearStatus" class="ml-2 text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </small>
                </div>
            </div>
            <section class="container mx-auto mt-4" v-if="tempOrders.length">
                <div class="flex flex-col">
                    <div class="">
                        <div class="w-full py-2">
                            <div class="border border-gray-200 md:rounded-lg dark:border-gray-700">
                                <table :class="styleClass.table">
                                    <thead :class="styleClass.tableHead">
                                        <tr>
                                            <th scope="col" :class="styleClass.th">
                                                <div class="flex items-center gap-x-3">
                                                    <button class="flex items-center gap-x-2">
                                                        <span>Order</span>

                                                        <!-- Sort Options Dropdown (Shared for both mobile and desktop) -->
                                                        <div
                                                            v-if="sortOptionOpen"
                                                            class="dark:bg-[#1e293b]' fixed z-40 mt-2 min-w-[180px] overflow-hidden rounded-lg border border-gray-200 bg-white text-left shadow-lg md:absolute md:right-auto dark:border-gray-600"
                                                        >
                                                            <span
                                                                v-for="option in sortOptions"
                                                                class="block cursor-pointer px-4 py-2 font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700"
                                                                @click="handleSort(option)"
                                                            >
                                                                {{ option }}
                                                            </span>
                                                        </div>
                                                        <button type="button" @click="sortOptionOpen = true">
                                                            <i class="fa-solid fa-arrow-down-9-1"></i>
                                                        </button>
                                                    </button>
                                                </div>
                                            </th>
                                            <th scope="col" :class="styleClass.th">Date</th>

                                            <th scope="col" :class="styleClass.th">Status</th>

                                            <th scope="col" :class="styleClass.th">Customer</th>
                                            <th scope="col" :class="styleClass.th">Type</th>

                                            <th scope="col" :class="styleClass.th">Customer's note</th>

                                            <th scope="col" class="relative px-4 py-3.5">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody :class="styleClass.tBody">
                                        <tr v-for="order in tempOrders" :key="`order-${order.id}`">
                                            <td :class="styleClass.td">
                                                <span class="font-bold lowercase">{{ order.id }}</span>
                                            </td>
                                            <td :class="styleClass.td">
                                                {{ formatDate(order.created_at) }}
                                            </td>
                                            <td :class="styleClass.td">
                                                <Link
                                                    :href="route('manage.orders.index', { ...form, status: order.status })"
                                                    :class="styleClass.badges[order.status]"
                                                >
                                                    <i :class="badges[order.status].icon"></i>
                                                    <span class="capitalize">{{ order.status }}</span>
                                                </Link>
                                            </td>

                                            <td :class="styleClass.td">
                                                <Link
                                                    :href="route('manage.orders.index', { ...form, search: order.user.name })"
                                                    class="flex items-center gap-x-2"
                                                >
                                                    <img :class="styleClass.user.avatar" :src="avatarSrc(order.user.avatar_url)" alt="" />
                                                    <div>
                                                        <h2 :class="styleClass.user.name">
                                                            {{ order.user.name }}
                                                        </h2>
                                                        <p :class="styleClass.user.email">
                                                            {{ order.user.email }}
                                                        </p>
                                                    </div>
                                                </Link>
                                            </td>
                                            <td :class="styleClass.td">
                                                {{ order.type }}
                                            </td>
                                            <td :class="[styleClass.td]">
                                                <p v-if="order.note" class="max-w-xs text-wrap">
                                                    {{ order.note.slice(0, 100) }}
                                                </p>
                                            </td>
                                            <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                <div class="flex items-center gap-x-6">
                                                    <Link
                                                        :href="route('manage.orders.show', { order, searches: { ...form } })"
                                                        class="text-blue-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none"
                                                    >
                                                        Review
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <!---------------- PAGINATOR ----------------------------------------------->

                    <Paginator :paginator="orders" />
                </div>
            </section>

            <div v-else class="py-6 text-center text-gray-500">
                <p class="text-lg font-medium">No orders have been made yet.</p>
                <p class="text-sm text-gray-400">Once a customer places an order, it will appear here.</p>
            </div>
        </div>
    </div>
</template>
