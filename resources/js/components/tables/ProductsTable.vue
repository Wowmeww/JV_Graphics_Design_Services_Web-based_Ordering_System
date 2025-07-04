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
    <div v-if="sortOptionOpen" @click="sortOptionOpen = false" class=" absolute inset-0 z-30"></div>

    <div v-if="products && products.data.length">
        <div class="flex gap-2 justify-between items-center md:justify-start mb-4">
            <div class="text-lg font-semibold flex gap-2 items-center ">
                <span>View Products</span>
                <!---------------   SORT OPTIONS  ----------------------------------------------------------->
                <div class="relative md:hidden">
                    <button @click="sortOptionOpen = true">
                        <i class="fa-solid fa-arrow-up-z-a text-primary dark:text-primary-300 cursor-pointer"></i>
                    </button>
                    <div v-if="sortOptionOpen"
                        class="absolute animate__animated animate__faster animate__fadeInDown font-normal text-left  lowercase bg-white overflow-hidden dark:bg-[#1e293b] min-w-max z-40 mt-2 rounded-lg border-1 border-primary dark:border-white/40 animate__animated animate_faster animate__fadeInDown">

                        <p v-for="(option, i) of sortOptions" :key="i"
                            class="px-4 py-1 hover:bg-primary-100 transition first-letter:uppercase dark:hover:bg-primary-400/10 cursor-pointer"
                            :class="option === placeholder ? 'font-semibold' : ''" @click.prevent="handleSort(option)">
                            {{
                                option }}</p>
                    </div>
                </div>
            </div>
            <p>
                <span>{{ products.from }} to {{ products.to }}</span> of <span>{{ products.total }} products</span>
            </p>
        </div>
        <table
            class="container-primary text-sm rounded-xl bg-white p-8 dark:bg-[#1e293b] min-w-3xl md:w-full hidden md:table md:min-w-6xl ">
            <thead class="border-b border-b-dark/20 dark:border-b-light/30">
                <tr class="grid grid-cols-12 items-center p-6 justify-items-start">
                    <th class="justify-self-start col-span-1">
                        <i class="fa-solid fa-image text-2xl md:text-3xl"></i>
                    </th>
                    <th class="flex gap-3 col-span-3">
                        Product Name

                        <!---------------   SORT OPTIONS  ----------------------------------------------------------->
                        <div class="relative">
                            <button @click="() => sortOptionOpen = true">
                                <i
                                    class="fa-solid fa-arrow-up-z-a text-primary dark:text-primary-300 cursor-pointer"></i>
                            </button>
                            <div v-if="sortOptionOpen"
                                class="absolute animate__animated animate__faster animate__fadeInDown font-normal text-left  lowercase bg-white overflow-hidden dark:bg-[#1e293b] min-w-max z-40 mt-2 rounded-lg border-1 border-primary dark:border-white/40 animate__animated animate_faster animate__fadeInDown">

                                <p v-for="(option, i) of sortOptions" :key="i"
                                    class="px-4 py-1 hover:bg-primary-100 transition first-letter:uppercase dark:hover:bg-primary-400/10 cursor-pointer"
                                    :class="option === placeholder ? 'font-semibold' : ''"
                                    @click.prevent="handleSort(option)">{{
                                        option }}</p>
                            </div>
                        </div>
                    </th>
                    <th class="col-span-1 justify-self-start ">
                        Type
                    </th>
                    <th class="col-span-1 justify-self-start ">
                        Category
                    </th>
                    <th class="col-span-1">
                        STOCK
                    </th>
                    <th class="col-span-1">
                        PRICE
                    </th>
                    <th class="col-span-1 justify-self-start">
                        Size
                    </th>
                    <th class="col-span-2 justify-self-start">
                        DATE
                    </th>
                    <th class="justify-self-end col-span-1">
                        ACTION
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="product of products.data" :key="product.id"
                    class="grid grid-cols-12 items-center p-6 font-medium justify-items-start leading-4 hover:bg-secondary-100/60 dark:hover:bg-secondary-100/10 transition">
                    <td class="inline-flex items-center justify-start flex-1/12">
                        <div class="border h-12 w-12 rounded-lg border-primary-200 dark:border-light overflow-hidden">
                            <img v-if="product.images[0]" :src="imageUrl(product.images[0].image_path)"
                                alt="Product Image" class="h-full w-full object-cover">
                            <i v-else class="fa-solid fa-image text-2xl text-gray-400"></i>
                        </div>
                    </td>
                    <td class="col-span-3">
                        {{ product.name }}
                    </td>
                    <td class="col-span-1">
                        {{ product.type }}
                    </td>
                    <td class="col-span-1">
                        {{ product.category.name }}
                    </td>
                    <td class="flex-1/12 flex justify-center pl-3">
                        {{ product.stock }}
                    </td>
                    <td class="flex-1/12 flex justify-center">
                        {{ product.price.toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }) }}

                    </td>
                    <td class="">
                        <span v-if="product.size">
                            {{ product.size.replaceAll(",", " ") }}
                        </span>
                    </td>
                    <td class="col-span-2 flex justify-between flex-wrap justify-self-stretch">
                        <span>Last modified:</span>
                        <span class="font-semibold">{{ new Date(product.updated_at).toLocaleDateString('en-GB')
                            }}</span>
                    </td>
                    <td class=" justify-self-end space-x-3 text-primary dark:text-primary-300">
                        <Link :href="route('product.show', { product })">
                        <i class="fa-solid fa-eye cursor-pointer"></i>
                        </Link>
                        <Link :href="route('product.edit', { product })">
                        <i class="bi bi-pencil-square cursor-pointer"></i>
                        </Link>
                        <i class="bi bi-three-dots-vertical cursor-pointer"></i>
                    </td>
                </tr>

            </tbody>

        </table>

        <div class="md:hidden space-y-2.5">
            <SingleProduct v-for="product of products.data" :key="product.id" :product="product" />
        </div>
    </div>
    <div v-else class="container-primary mt-5 rounded-xl bg-white p-8 dark:bg-[#1e293b] w-full min-w-max text-center">
        <p class="text-lg font-semibold">No products available.</p>
        <p class="text-sm text-gray-500">Please add some products to manage them.</p>
    </div>
</template>