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

</script>
<template>
    <!-- Overlay for mobile sort options -->
    <div v-if="sortOptionOpen" @click="sortOptionOpen = false"
        class="fixed inset-0 z-30 bg-black/10 backdrop-blur-sm md:hidden"></div>

    <div v-if="products && products.data.length" class="space-y-4 relative">
        <!-- Header Section -->
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-6">
            <div class="flex items-center gap-3 relative">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">View Products</h2>

                <!-- Mobile Sort Trigger -->
                <button @click="sortOptionOpen = true"
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-arrow-up-z-a text-lg text-primary dark:text-primary-300"></i>
                </button>

                <div v-if="sortOptionOpen"
                    class="animate__animated animate__fadeIn absolute left-0 top-12 md:right-auto mt-2 z-40 min-w-[180px] bg-white dark:bg-[#1e293b] rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 overflow-hidden animate-fade-in">
                    <div v-for="(option, i) of sortOptions" :key="i" @click.prevent="handleSort(option)"
                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-colors cursor-pointer"
                        :class="{ 'bg-gray-100 dark:bg-gray-700 font-medium': option === params.sort }">
                        <span class="capitalize">{{ option }}</span>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                Showing <span class="font-medium">{{ products.from }}-{{ products.to }}</span> of
                <span class="font-medium">{{ products.total }}</span> products
            </p>
        </div>

        <!-- Desktop Table -->
        <div
            class="hidden md:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1e293b] shadow-sm">
            <table class="w-full">
                <thead class="border-b border-gray-200 dark:border-gray-700">
                    <tr class="grid grid-cols-12 items-center px-6 py-4">
                        <th class="col-span-1 text-left">
                            <i class="fa-solid fa-image text-xl text-gray-400"></i>
                        </th>
                        <th class="col-span-3 text-left flex items-center gap-2 relative">
                            <span>Product Name</span>
                            <!-- Desktop Sort Trigger -->
                            <button @click="sortOptionOpen = !sortOptionOpen"
                                class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fa-solid fa-arrow-up-z-a text-sm text-primary dark:text-primary-300"></i>
                            </button>

                            <!-- Sort Options Dropdown (Shared for both mobile and desktop) -->
                            <div v-if="sortOptionOpen"
                                class="animate__animated animate__fadeIn fixed md:absolute right-0 top-12 md:right-auto mt-2 z-40 min-w-[180px] bg-white dark:bg-[#1e293b] rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 overflow-hidden animate-fade-in">
                                <div v-for="(option, i) of sortOptions" :key="i" @click.prevent="handleSort(option)"
                                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-colors cursor-pointer"
                                    :class="{ 'bg-gray-100 dark:bg-gray-700 font-medium': option === params.sort }">
                                    <span class="capitalize">{{ option }}</span>
                                </div>
                            </div>
                        </th>
                        <th class="col-span-1 text-left">Type</th>
                        <th class="col-span-1 text-left">Category</th>
                        <th class="col-span-1 text-center">Stock</th>
                        <th class="col-span-1 text-center">Price</th>
                        <th class="col-span-1 text-left">Size</th>
                        <th class="col-span-2 text-left">Last Modified</th>
                        <th class="col-span-1 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="product of products.data" :key="product.id"
                        class="grid grid-cols-12 items-center px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="col-span-1">
                            <div
                                class="h-12 w-12 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                <img v-if="product.images[0]" :src="imageUrl(product.images[0].image_path)"
                                    alt="Product Image" class="h-full w-full object-cover">
                                <i v-else class="fa-solid fa-image text-xl text-gray-400"></i>
                            </div>
                        </td>
                        <td class="col-span-3 font-medium text-gray-800 dark:text-gray-200">
                            {{ product.name }}
                        </td>
                        <td class="col-span-1 text-gray-600 dark:text-gray-400">
                            {{ product.type }}
                        </td>
                        <td class="col-span-1 text-gray-600 dark:text-gray-400">
                            {{ product.category.name }}
                        </td>
                        <td class="col-span-1 text-center">
                            <span :class="{
                                'text-green-600 dark:text-green-400': product.stock > 10,
                                'text-yellow-600 dark:text-yellow-400': product.stock > 0 && product.stock <= 10,
                                'text-red-600 dark:text-red-400': product.stock === 0
                            }">
                                {{ product.stock }}
                            </span>
                        </td>
                        <td class="col-span-1 text-center font-medium">
                            {{ product.price.toLocaleString('en-PH', {
                                style: 'currency',
                                currency: 'PHP'
                            }) }}
                        </td>
                        <td class="col-span-1 text-gray-600 dark:text-gray-400">
                            <span v-if="product.size">
                                {{ product.size.replaceAll(",", " ") }}
                            </span>
                            <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="col-span-2 text-gray-600 dark:text-gray-400">
                            {{ new Date(product.updated_at).toLocaleDateString('en-GB', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            }) }}
                        </td>
                        <td class="col-span-1 flex justify-end gap-3 text-gray-500 dark:text-gray-400">
                            <Link :href="route('product.show', { product })"
                                class="hover:text-primary dark:hover:text-primary-300">
                            <i class="fa-solid fa-eye"></i>
                            </Link>
                            <Link :href="route('product.edit', { product })"
                                class="hover:text-primary dark:hover:text-primary-300">
                            <i class="bi bi-pencil-square"></i>
                            </Link>
                            <button class="hover:text-primary dark:hover:text-primary-300">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Product List -->
        <div class="md:hidden space-y-3">
            <SingleProduct v-for="product of products.data" :key="product.id" :product="product" />
        </div>


    </div>

    <!-- Empty State -->
    <div v-else
        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1e293b] p-8 text-center">
        <div class="max-w-md mx-auto">
            <i class="fa-solid fa-box-open text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">No products available</h3>
            <p class="text-gray-600 dark:text-gray-400">Add products to start managing your inventory</p>
        </div>
    </div>
</template>