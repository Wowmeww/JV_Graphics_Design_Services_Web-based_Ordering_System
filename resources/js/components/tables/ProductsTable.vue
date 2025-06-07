<script setup>
    import SingleProduct from './SingleProduct.vue';

</script>

<template>
    <div>
        <table class="container-primary rounded-xl bg-white p-8 dark:bg-[#1e293b] w-full hidden md:table ">
            <thead class="border-b border-b-dark/20 dark:border-b-light/30">
                <tr class="flex justify-between">

                    <th class="uppercase py-4 md:py-6 text-left px-4 md:px-6 inline-flex items-center flex-1/12">
                        <i class="fa-solid fa-image text-2xl md:text-3xl"></i>
                    </th>
                    <th class="uppercase py-4 md:py-6 text-left inline-flex items-center flex-4/12">
                        Product Name
                    </th>
                    <th class="uppercase py-4 md:py-6 text-left inline-flex items-center flex-1/12">
                        STOCK
                    </th>
                    <th class="uppercase py-4 md:py-6 text-left inline-flex items-center flex-1/12">
                        PRICE
                    </th>
                    <th class="uppercase py-4 md:py-6 text-left inline-flex items-center flex-2/12">
                        DATE
                    </th>
                    <th
                        class="uppercase py-4 pr-4 md:pr-6 md:py-6 text-right justify-end inline-flex items-center flex-2/12">
                        ACTION
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="flex justify-between">

                    <td class="inline-flex items-center py-4 px-4 md:px-6 md:py-6 text-left flex-1/12">
                        <div class="border h-10 w-10 rounded-lg dark:border-light">

                        </div>
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-4/12 font-semibold">
                        Lorem ipsum dolor sit.
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-1/12">
                        300
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-1/12">
                        5000
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-2/12 gap-2">
                        <span>Last modified:</span>
                        <span class="font-semibold">02/04/2025</span>
                    </td>
                    <td
                        class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-right justify-end flex-2/12 text-xl gap-6 text-primary dark:text-primary-300">
                        <i class="bi bi-pencil-square cursor-pointer"></i>
                        <i class="bi bi-three-dots-vertical cursor-pointer"></i>
                    </td>
                </tr>
                <tr class="flex justify-between">
                    <td class="inline-flex items-center py-4 px-4 md:px-6 md:py-6 text-left flex-1/12">
                        <div class="border h-10 w-10 rounded-lg dark:border-light">

                        </div>
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-4/12 font-semibold">
                        Lorem ipsum dolor sit.
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-1/12">
                        300
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-1/12">
                        5000
                    </td>
                    <td class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-left flex-2/12 gap-2">
                        <span>Last modified:</span>
                        <span class="font-semibold">02/04/2025</span>
                    </td>
                    <td
                        class="inline-flex items-center py-4 pr-4 md:pr-6 md:py-6 text-right justify-end flex-2/12 text-xl gap-6 text-primary dark:text-primary-300">
                        <i class="bi bi-pencil-square cursor-pointer"></i>
                        <i class="bi bi-three-dots-vertical cursor-pointer"></i>
                    </td>
                </tr>

            </tbody>
        </table>

        <div class="md:hidden space-y-2.5">
            <SingleProduct />
            <SingleProduct />
        </div>
    </div>
</template>