<script setup>
    import SingleProduct from './SingleProduct.vue';

    defineProps({
        products: Object
    });
</script>

<template>

    <div v-if="products && products.data.length">
        <table class="container-primary rounded-xl bg-white p-8 dark:bg-[#1e293b] w-full min-w-max hidden md:table ">
            <thead class="border-b border-b-dark/20 dark:border-b-light/30">
                <tr class="w-full flex justify-between items-center p-4 md:p-6 uppercase">

                    <th class="inline-flex items-center justify-start flex-1/12">
                        <i class="fa-solid fa-image text-2xl md:text-3xl"></i>
                    </th>
                    <th class="inline-flex items-center justify-start flex-3/12">
                        Product Name
                    </th>
                    <th class="inline-flex items-center justify-start flex-2/12">
                        Category
                    </th>
                    <th class="inline-flex items-center justify-start flex-1/12">
                        STOCK
                    </th>
                    <th class="inline-flex items-center justify-center flex-1/12 px-3">
                        PRICE
                    </th>
                    <th class="inline-flex items-center justify-start flex-3/12">
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
                    <td class="inline-flex items-center justify-start flex-1/12">
                        {{ product.stock }}
                    </td>
                    <td class="inline-flex items-center justify-center flex-1/12 px-3">
                        {{ product.price.toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }) }}

                    </td>
                    <td class="inline-flex items-center justify-between flex-wrap flex-3/12 gap-1">
                        <span>Last modified:</span>
                        <span class="font-semibold">{{ new Date(product.updated_at).toLocaleDateString('en-GB') }}</span>
                    </td>
                    <td
                        class="inline-flex items-center justify-end flex-1/12 text-right text-xl gap-6 text-primary dark:text-primary-300">
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