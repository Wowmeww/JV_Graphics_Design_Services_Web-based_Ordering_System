<script setup>

    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import SingleProduct from './SingleProduct.vue';

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
        <table class="container-primary rounded-xl bg-white p-8 dark:bg-[#1e293b] min-w-3xl md:w-full hidden md:table ">
            <thead class="border-b border-b-dark/20 dark:border-b-light/30">
                <tr class="w-full flex justify-between items-center p-4 md:p-6 uppercase">

                    <th class="inline-flex items-center justify-start flex-1/12">
                        <i class="fa-solid fa-image text-2xl md:text-3xl"></i>
                    </th>
                    <th class="inline-flex items-center justify-start flex-3/12 gap-3">
                        Product Name

                        <!---------------   SORT OPTIONS  ----------------------------------------------------------->
                        <!-- <div class="relative">
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
                        </div> -->
                    </th>
                    <th class="inline-flex items-center justify-start flex-2/12">
                        Category
                    </th>
                    <th class="inline-flex items-center justify-start flex-1/12">
                        STOCK
                    </th>
                    <th class="inline-flex items-center justify-center flex-2/12 lg:flex-1/12 px-3">
                        PRICE
                    </th>
                    <th class="inline-flex items-center justify-start flex-2/12 lg:flex-3/12">
                        DATE
                    </th>
                    <th class="inline-flex items-center justify-end flex-1/12">
                        ACTION
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product of products.data" :key="product.id"
                    class="w-full flex justify-between items-center p-4 md:p-6 font-medium leading-4">
                    <td class="inline-flex items-center justify-start flex-1/12">
                        <div class="border h-10 w-10 rounded-lg border-primary-200 dark:border-light overflow-hidden">
                            <img v-if="product.images[0]" :src="product.images[0].image_path" alt="Product Image"
                                class="h-full w-full object-cover">
                            <i v-else class="fa-solid fa-image text-2xl text-gray-400"></i>
                        </div>
                    </td>
                    <td class="inline-flex items-center justify-start flex-3/12 ">
                        {{ product.name }}
                    </td>
                    <td class="inline-flex items-center justify-start flex-2/12">
                        {{ product.category.name }}
                    </td>
                    <td class="inline-flex items-center justify-center lg:justify-start flex-1/12 ">
                        {{ product.stock }}
                    </td>
                    <td class="inline-flex items-center justify-center flex-2/12 lg:flex-1/12 px-3">
                        {{ product.price.toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }) }}

                    </td>
                    <td class="inline-flex items-center justify-between flex-wrap flex-2/12 lg:flex-3/12 gap-1">
                        <span>Last modified:</span>
                        <span class="font-semibold">{{ new Date(product.updated_at).toLocaleDateString('en-GB')
                        }}</span>
                    </td>
                    <td
                        class="inline-flex items-center justify-end flex-1/12 pl-3 text-right text-xl gap-4 text-primary dark:text-primary-300">
                        <i class="fa-solid fa-eye cursor-pointer"></i>
                        <i class="bi bi-pencil-square cursor-pointer"></i>
                        <i class="bi bi-three-dots-vertical cursor-pointer"></i>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="md:hidden space-y-2.5">
            <SingleProduct v-for="product of products.data" :key="product.id" :product="product" />
        </div>
    </div>
    <div v-else class="container-primary rounded-xl bg-white p-8 dark:bg-[#1e293b] w-full min-w-max text-center">
        <p class="text-lg font-semibold">No products available.</p>
        <p class="text-sm text-gray-500">Please add some products to manage them.</p>
    </div>
</template>