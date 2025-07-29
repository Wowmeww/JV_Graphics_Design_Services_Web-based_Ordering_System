<script setup>
    import { router } from '@inertiajs/vue3';
    import { computed, reactive } from 'vue';
    import PillPrimary from '../ui/buttons/PillPrimary.vue';
    import Search from '../ui/Search.vue';
    import FilterDropDown from './FilterDropDown.vue';

    const params = route().params;

    const props = defineProps({
        categories: Object,
        productTypes: Array
    });

    const filter = reactive({
        stock: params.stock,
        category: params.category,
        search: params.search,
        type: params.type,
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
        filter.type = null;

        router.get(route('product.index', { ...params, ...filter }), {}, { preserveState: false, preserveScroll: true });
    }

    const dropdownOptions = {
        stock: ['In stock (10+)', 'Low inventory (10-)', 'Out of stock'],
        category: props.categories.map((category) => category.name),
        type: props.productTypes
    };
    const hasFilters = computed(() => Object.values(filter).some((val) => val && val !== ''));

    function clearFilterKey(key) {
        filter[key] = null;
    }
</script>

<template>
    <!--------------------------- Small screen ----------------------------------------------->
    <div class="bt-3 grid grid-cols-2 grid-rows-5 items-center justify-center gap-2 pb-6 lg:hidden">
        <!--------------------------- FILTERS ----------------------------------------------->
        <div class="col-span-2 flex h-full flex-wrap items-end gap-2">
            <p>Filters:</p>
            <div class="flex flex-wrap gap-2">
                <span v-if="!hasFilters" class="badge badge-secondary"> No filters applied </span>

                <template v-for="(value, key) in filter" :key="key">
                    <span v-if="value" class="badge badge-primary flex items-center gap-1">
                        {{ value }}
                        <i class="bi bi-x-lg cursor-pointer" @click="clearFilterKey(key)"></i>
                    </span>
                </template>
            </div>
        </div>

        <!--------------------------- SEARCH ----------------------------------------------->
        <div class="col-span-2">
            <Search v-model="filter.search" @search="handleSearch" />
        </div>

        <!--------------------------- FILTER DROPDOWN ----------------------------------------------->
        <div class="col-span-2 sm:col-span-1">
            <FilterDropDown placeholder="Stock Status" :options="dropdownOptions.stock"
                @select="(option) => (filter.stock = option)" />
        </div>
        <div class="col-span-2 sm:col-span-1">
            <FilterDropDown placeholder="Product Type" :options="dropdownOptions.type"
                @select="(option) => (filter.type = option)" />
        </div>

        <div class="col-span-2 sm:col-span-1">
            <FilterDropDown placeholder="Product category" :options="dropdownOptions.category"
                @select="(option) => (filter.category = option)" />
        </div>
        <div class="col-span-2 flex justify-center gap-3">
            <!--------------------------- Filter apply ----------------------------------------------->
            <PillPrimary label="Apply" variant="secondary" @click="handleFilter">
                <i class="bi bi-chevron-right"></i>
            </PillPrimary>
            <PillPrimary label="CLear" variant="outlineSecondary" @click="clearFilter" />
        </div>

        <div class="md:grid- col-span-2 inline-flex justify-stretch md:justify-center">
            <PillPrimary is="Link" :href="route('product.create')" label="Add new product" class="w-full md:max-w-xs">
                <i class="bi bi-plus-circle"></i>
            </PillPrimary>
        </div>
    </div>

    <!--------------------------- LARGE SCREEN ----------------------------------------------->
    <div class="my-6 hidden flex-col-reverse gap-3 md:flex-col lg:flex">
        <div class="flex flex-col-reverse items-stretch justify-between gap-3 md:flex-row md:items-center">
            <PillPrimary is="Link" :href="route('product.create')" label="Add new product" class="text-left">
                <i class="bi bi-plus-circle"></i>
            </PillPrimary>

            <!--------------------------- SEARCH ----------------------------------------------->
            <Search v-model="filter.search" @search="handleSearch" />
        </div>
        <div>
            <div class="mb-2 flex items-center gap-2">
                <p>Filters:</p>
                <div class="flex flex-wrap gap-2">
                    <span v-if="!hasFilters" class="badge badge-secondary"> No filters applied </span>

                    <template v-for="(value, key) in filter" :key="key">
                        <span v-if="value" class="badge badge-primary flex items-center gap-1">
                            {{ value }}
                            <i class="bi bi-x-lg cursor-pointer" @click="clearFilterKey(key)"></i>
                        </span>
                    </template>
                </div>
            </div>
            <div class="flex flex-col gap-3 md:flex-row lg:items-center lg:justify-between">
                <div class="grid gap-3 sm:grid-flow-col sm:content-stretch lg:justify-start">
                    <!--------------------------- FILTER DROPDOWN ----------------------------------------------->
                    <FilterDropDown :placeholder="filter.stock ? filter.stock : 'Stock Status'"
                        :options="dropdownOptions.stock" @select="(option) => (filter.stock = option)" />

                    <FilterDropDown :placeholder="filter.type ? filter.type : 'Product Type'"
                        :options="dropdownOptions.type" @select="(option) => (filter.type = option)" />

                    <FilterDropDown :placeholder="filter.category ? filter.category : 'Product Category'"
                        :options="dropdownOptions.category" @select="(option) => (filter.category = option)" />
                </div>
                <div class="flex justify-center gap-3">
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
