<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import SingleProduct from './SingleProduct.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    products: Object,
});

const params = route().params;

const sortOptionOpen = ref(false);
const sortOptions = [
    'Sort by name (A-Z)',
    'Sort by name (Z-A)',
    'Sort by price (low to high)',
    'Sort by price (high to low)',
    'Sort by stock (low to high)',
    'Sort by stock (high to low)',
    'Sort by date (new to old)',
    'Sort by date (old to new)',
];

function handleSort(sortOption) {
    sortOptionOpen.value = false;
    router.get(route('product.index'), { ...params, sort: sortOption }, { preserveState: false, preserveScroll: true });
}
function imageUrl(path) {
    if (path.includes('product_images')) {
        return `/storage/${path}`;
    }

    return path;
}

const styleClass = {
    // Overlay
    overlay: 'fixed inset-0 z-30 bg-black/10 backdrop-blur-sm lg:hidden',

    // Container
    container: 'relative space-y-4',

    // Header Section
    header: 'mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between',
    title: 'text-xl font-semibold text-gray-800 dark:text-gray-200',
    mobileSortButton: 'rounded-lg p-2 transition-colors hover:bg-gray-100 lg:hidden dark:hover:bg-gray-700',
    mobileSortIcon: 'text-primary dark:text-primary-300 text-lg',
    countText: 'text-sm text-gray-600 dark:text-gray-400',
    countHighlight: 'font-medium',

    // Sort Dropdown
    sortDropdown: {
        base: 'animate__animated animate__fadeIn animate-fade-in absolute top-12 left-0 z-40 mt-2 block min-w-[180px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg md:right-auto lg:hidden dark:border-gray-600 dark:bg-[#1e293b]',
        desktop:
            'fixed top-12 right-0 z-40 mt-2 min-w-[180px] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg md:absolute md:right-auto dark:border-gray-600 dark:bg-[#1e293b]',
        item: 'cursor-pointer px-4 py-2 font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700',
        activeItem: 'bg-gray-100 font-medium dark:bg-gray-700',
    },

    // Desktop Table
    table: {
        base: 'hidden overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm lg:block dark:border-gray-700 dark:bg-[#1e293b]',
        head: 'border-b border-gray-200 dark:border-gray-700',
        headRow: 'grid grid-cols-12 items-center px-6 py-4',
        headCell: 'text-left',
        sortButton: 'rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-700',
        sortIcon: 'text-primary dark:text-primary-300 text-sm',
        body: 'divide-y divide-gray-200 dark:divide-gray-700',
        row: 'grid grid-cols-12 items-center px-6 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50',
        cell: {
            base: 'text-gray-600 dark:text-gray-400',
            image: 'flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700',
            name: 'font-medium  text-gray-800 dark:text-gray-200',
            stock: {
                base: '',
                high: 'text-green-600 dark:text-green-400',
                medium: 'text-yellow-600 dark:text-yellow-400',
                low: 'text-red-600 dark:text-red-400',
            },
            price: 'font-medium',
            size: 'text-gray-600 dark:text-gray-400',
            emptySize: 'text-gray-400',
            date: 'text-gray-600 dark:text-gray-400',
            actions: 'flex justify-end gap-3 text-gray-500 dark:text-gray-400',
            actionIcon: 'hover:text-primary dark:hover:text-primary-300',
        },
    },

    // Mobile Product List
    mobileList: 'space-y-3 lg:hidden',

    // Empty State
    emptyState: {
        container: 'rounded-xl border border-gray-200 bg-white p-8 text-center dark:border-gray-700 dark:bg-[#1e293b]',
        icon: 'mb-4 text-4xl text-gray-400',
        title: 'mb-2 text-lg font-semibold text-gray-800 dark:text-gray-200',
        message: 'text-gray-600 dark:text-gray-400',
    },
};
</script>

<template>
    <!-- Overlay for mobile sort options -->
    <div v-if="sortOptionOpen" @click="sortOptionOpen = false" :class="styleClass.overlay"></div>

    <div v-if="products && products.data.length" :class="styleClass.container">
        <!-- Header Section -->
        <div :class="styleClass.header">
            <div class="relative flex items-center gap-3">
                <h2 :class="styleClass.title">View Products</h2>

                <!-- Mobile Sort Trigger -->
                <button @click="sortOptionOpen = true" :class="styleClass.mobileSortButton">
                    <i class="fa-solid fa-arrow-up-z-a" :class="styleClass.mobileSortIcon"></i>
                </button>

                <div v-if="sortOptionOpen" :class="styleClass.sortDropdown.base">
                    <div
                        v-for="(option, i) of sortOptions"
                        :key="i"
                        @click.prevent="handleSort(option)"
                        :class="[styleClass.sortDropdown.item, option === params.sort ? styleClass.sortDropdown.activeItem : '']"
                    >
                        <span class="capitalize">{{ option }}</span>
                    </div>
                </div>
            </div>

            <p :class="styleClass.countText">
                Showing <span :class="styleClass.countHighlight">{{ products.from }}-{{ products.to }}</span> of
                <span :class="styleClass.countHighlight">{{ products.total }}</span> products
            </p>
        </div>

        <!-- Desktop Table -->
        <div :class="styleClass.table.base">
            <table class="w-full">
                <thead :class="styleClass.table.head">
                    <tr :class="styleClass.table.headRow">
                        <th :class="styleClass.table.headCell + ' col-span-1'">
                            <i class="fa-solid fa-image text-xl text-gray-400"></i>
                        </th>
                        <th :class="styleClass.table.headCell + ' relative col-span-3 flex items-center gap-2'">
                            <span>Product Name</span>
                            <!-- Desktop Sort Trigger -->
                            <button @click="sortOptionOpen = !sortOptionOpen" :class="styleClass.table.sortButton">
                                <i class="fa-solid fa-arrow-up-z-a" :class="styleClass.table.sortIcon"></i>
                            </button>

                            <!-- Sort Options Dropdown (Shared for both mobile and desktop) -->
                            <div v-if="sortOptionOpen" :class="styleClass.sortDropdown.desktop">
                                <div
                                    v-for="(option, i) of sortOptions"
                                    :key="i"
                                    @click.prevent="handleSort(option)"
                                    :class="[styleClass.sortDropdown.item, option === params.sort ? styleClass.sortDropdown.activeItem : '']"
                                >
                                    <span class="capitalize">{{ option }}</span>
                                </div>
                            </div>
                        </th>
                        <th :class="styleClass.table.headCell + ' col-span-1'">Type</th>
                        <th :class="styleClass.table.headCell + ' col-span-1'">Category</th>
                        <th :class="styleClass.table.headCell + ' col-span-1 text-center'">Stock</th>
                        <th :class="styleClass.table.headCell + ' col-span-1 text-center'">Price</th>
                        <th :class="styleClass.table.headCell + ' col-span-1'">Size</th>
                        <th :class="styleClass.table.headCell + ' col-span-2'">Last Modified</th>
                        <th :class="styleClass.table.headCell + ' col-span-1 text-right'">Actions</th>
                    </tr>
                </thead>

                <tbody :class="styleClass.table.body">
                    <tr v-for="product of products.data" :key="product.id" :class="styleClass.table.row">
                        <td class="col-span-1">
                            <div :class="styleClass.table.cell.image">
                                <img
                                    v-if="product.images[0]"
                                    :src="imageUrl(product.images[0].image_path)"
                                    alt="Product Image"
                                    class="h-full w-full object-cover"
                                />
                                <i v-else class="fa-solid fa-image text-xl text-gray-400"></i>
                            </div>
                        </td>
                        <td :class="styleClass.table.cell.name + ' col-span-3'">
                            {{ product.name }}
                        </td>
                        <td :class="styleClass.table.cell.base + ' col-span-1'">
                            {{ product.type }}
                        </td>
                        <td :class="styleClass.table.cell.base + ' col-span-1'">
                            {{ product.category.name }}
                        </td>
                        <td class="col-span-1">
                            <span
                                :class="
                                    product.stock > 10
                                        ? styleClass.table.cell.stock.high
                                        : product.stock > 0 && product.stock <= 10
                                          ? styleClass.table.cell.stock.medium
                                          : styleClass.table.cell.stock.low
                                "
                            >
                                {{ product.stock }}
                            </span>
                        </td>
                        <td :class="styleClass.table.cell.price + ' col-span-1'">
                            {{
                                product.price.toLocaleString('en-PH', {
                                    style: 'currency',
                                    currency: 'PHP',
                                })
                            }}
                        </td>
                        <td :class="styleClass.table.cell.size + ' col-span-1'">
                            <span v-if="product.show_size">
                                {{ product.show_size }}
                            </span>
                            <span v-else :class="styleClass.table.cell.emptySize">none</span>
                        </td>
                        <td :class="styleClass.table.cell.date + ' col-span-2'">
                            {{
                                new Date(product.updated_at).toLocaleDateString('en-GB', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                })
                            }}
                        </td>
                        <td :class="styleClass.table.cell.actions + ' col-span-1'">
                            <Link :href="route('product.show', { product })" :class="styleClass.table.cell.actionIcon">
                                <i class="fa-solid fa-eye"></i>
                            </Link>
                            <Link :href="route('product.edit', { product })" :class="styleClass.table.cell.actionIcon">
                                <i class="bi bi-pencil-square"></i>
                            </Link>
                            <!-- <button :class="styleClass.table.cell.actionIcon">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Product List -->
        <div :class="styleClass.mobileList">
            <SingleProduct v-for="product of products.data" :key="product.id" :product="product" />
        </div>
    </div>

    <!-- Empty State -->
    <div v-else :class="styleClass.emptyState.container">
        <div class="mx-auto max-w-md">
            <i class="fa-solid fa-box-open" :class="styleClass.emptyState.icon"></i>
            <h3 :class="styleClass.emptyState.title">No products available</h3>
            <p :class="styleClass.emptyState.message">Add products to start managing your inventory</p>
        </div>
    </div>
</template>
