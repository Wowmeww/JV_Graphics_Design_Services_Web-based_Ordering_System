<script setup>
    import { reactive } from 'vue';
    import PillPrimary from '../ui/buttons/PillPrimary.vue';
    import Search from '../ui/Search.vue';
    import FilterDropDown from './FilterDropDown.vue';
    import { router } from '@inertiajs/vue3'

    const params = route().params;

    const props = defineProps({
        categories: Object
    });

    const filter = reactive({
        stock: params.stock,
        category: params.category,
        search: params.search,
    });


    function handleSearch(search) {
        router.get(route('product.index', { ...params, ...filter, search, page: null }), {}, { preserveState: false, preserveScroll: true });
    }

    function handleFilter() {
        router.get(route('product.index', { ...params, ...filter, page: null }), {}, { preserveState: false, preserveScroll: true });
    }
    function clearFilter() {
        filter.stock = null;
        filter.category = null;
        filter.search = null;

        router.get(route('product.index', { ...params, ...filter }), {}, { preserveState: false, preserveScroll: true });
    }

    const dropdownOptions = {
        stock: ['In stock (10+)', 'Low inventory (10-)', 'Out of stock'],
        category: props.categories.map(category => category.name)
    }
</script>

<template>

    <!--------------------------- Small screen ----------------------------------------------->
    <div class="grid lg:hidden gap-2 grid-rows-5 grid-cols-2 justify-center items-center pb-6 bt-3 ">
        <!--------------------------- FILTERS ----------------------------------------------->
        <div class="col-span-2 h-full flex gap-2 items-end flex-wrap">
            <p>Filters:</p>
            <div class="flex gap-4">
                <span v-if="filter.stock" class="badge badge-primary">
                    {{ filter.stock }}
                    <i class="bi bi-x-lg cursor-pointer" @click="() => filter.stock = ''"></i>
                </span>
                <span v-if="filter.category" class="badge badge-primary">
                    {{ filter.category }}
                    <i class="bi bi-x-lg cursor-pointer" @click="() => filter.category = ''"></i>
                </span>
                <span v-if="filter.search" class="badge badge-primary">
                    {{ filter.search }}
                    <i class="bi bi-x-lg cursor-pointer" @click="() => filter.search = ''"></i>
                </span>
                <span v-if="!filter.stock && !filter.category && !filter.search" class="badge badge-secondary">No
                    filters
                    applied</span>
            </div>

        </div>

        <!--------------------------- SEARCH ----------------------------------------------->
        <div class="col-span-2">
            <Search v-model="filter.search" @search="handleSearch" />
        </div>

        <!--------------------------- FILTER DROPDOWN ----------------------------------------------->
        <div class="col-span-2 sm:col-span-1">
            <FilterDropDown placeholder="Stock Status" :options="dropdownOptions.stock"
                @select="(option) => filter.stock = option" />
        </div>

        <div class="col-span-2 sm:col-span-1">
            <FilterDropDown placeholder="Product category" :options="dropdownOptions.category"
                @select="(option) => filter.category = option" />
        </div>
        <div class="flex gap-3 justify-center col-span-2">
            <!--------------------------- Filter apply ----------------------------------------------->
            <PillPrimary label="Apply" variant="secondary" @click="handleFilter">
                <i class="bi bi-chevron-right"></i>
            </PillPrimary>
            <PillPrimary label="CLear" variant="outlineSecondary" @click="clearFilter" />
        </div>

        <div class="col-span-2 inline-flex justify-stretch md:justify-center md:grid-">
            <PillPrimary label="Add new product" class="w-full md:max-w-xs">
                <i class="bi bi-plus-circle"></i>
            </PillPrimary>
        </div>
    </div>

    <!--------------------------- LARGE SCREEN ----------------------------------------------->
    <div class="lg:flex hidden flex-col-reverse md:flex-col my-6 gap-3">
        <div class="flex justify-between md:items-center flex-col-reverse md:flex-row gap-3  items-stretch ">
            <PillPrimary label="Add new product" class="text-left">
                <i class="bi bi-plus-circle"></i>
            </PillPrimary>

            <!--------------------------- SEARCH ----------------------------------------------->
            <Search v-model="filter.search" @search="handleSearch" />
        </div>
        <div>
            <div class="flex gap-2 items-center mb-2">
                <p>Filters:</p>
                <div class="flex gap-4">
                    <span v-if="filter.stock" class="badge badge-primary">
                        {{ filter.stock }}
                        <i class="bi bi-x-lg cursor-pointer" @click="() => filter.stock = ''"></i>
                    </span>
                    <span v-if="filter.category" class="badge badge-primary">
                        {{ filter.category }}
                        <i class="bi bi-x-lg cursor-pointer" @click="() => filter.category = ''"></i>
                    </span>
                    <span v-if="filter.search" class="badge badge-primary">
                        {{ filter.search }}
                        <i class="bi bi-x-lg cursor-pointer" @click="() => filter.search = ''"></i>
                    </span>
                    <span v-if="!filter.stock && !filter.category && !filter.search" class="badge badge-secondary">No
                        filters
                        applied</span>
                </div>
            </div>
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-3  ">
                <div class="grid sm:grid-flow-col  sm:content-stretch lg:justify-start gap-3 ">
                    <!--------------------------- FILTER DROPDOWN ----------------------------------------------->

                    <FilterDropDown :placeholder="filter.stock ? filter.stock : 'Stock Status'"
                        :options="dropdownOptions.stock" @select="(option) => filter.stock = option" />

                    <FilterDropDown :placeholder="filter.category ? filter.category : 'Product Category'"
                        :options="dropdownOptions.category" @select="(option) => filter.category = option" />

                </div>
                <div class="flex gap-3 justify-center">
                    <!--------------------------- Filter apply ----------------------------------------------->
                    <PillPrimary label="Apply" variant="secondary" @click="handleFilter">
                        <i class="bi bi-chevron-right"></i>
                    </PillPrimary>
                    <PillPrimary label="CLear" variant="outlineSecondary" @click="clearFilter" />
                </div>
            </div>
        </div>
    </div>
</template>