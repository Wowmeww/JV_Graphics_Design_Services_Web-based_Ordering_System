<script setup>
    import { computed, reactive, ref } from 'vue';
    import ContainerPrimary from "../ContainerPrimary.vue"
    import Dropdown from './input/Dropdown.vue';
    import Search from './Search.vue';
    import ButtonPrimary from './buttons/ButtonPrimary.vue';

    const params = route().params;

    const props = defineProps({
        categories: Object,
        defaults: Object
    });

    const isOpen = ref(false);
    const emit = defineEmits(['filter']);

    const filters = reactive({
        search: props.defaults.search,
        sort: props.defaults.sort,
        category: props.defaults.category,
    });

    function handleFilter() {
        isOpen.value = false;
        emit('filter', filters);
    }
    function clearFilter() {
        filters.search = null;
        filters.sort = null;
        filters.category = null;
        handleFilter();
    }


    const sorts = [
        "Sort by name (A-Z)",
        "Sort by name (Z-A)",
        "Sort by price (low to high)",
        "Sort by price (high to low)",
        "Sort by stock (low to high)",
        "Sort by stock (high to low)",
        "Sort by date (new to old)",
        "Sort by date (old to new)"
    ];
</script>



<template>
    <!-- OVERLAY -->
    <div class="absolute inset-0 z-10 lg:hidden" v-if="isOpen" @click="isOpen = false"></div>
    <div class="pt-6 px-2 lg:hidden">
        <div class="relative inline-block ">
            <!-- Dropdown toggle button -->
            <button @click="isOpen = !isOpen"
                class="relative z-20 flex items-center px-4 py-3 text-sm text-gray-600 bg-white border border-transparent rounded-md focus:border-blue-500 focus:ring-opacity-40 dark:focus:ring-opacity-40 focus:ring-blue-300 dark:focus:ring-blue-400 focus:ring dark:text-white dark:bg-gray-800 focus:outline-none">
                <span class="mx-1">Filters</span>
                <svg class="w-5 h-5 mx-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 15.713L18.01 9.70299L16.597 8.28799L12 12.888L7.40399 8.28799L5.98999 9.70199L12 15.713Z"
                        fill="currentColor"></path>
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div class="relative" ref="dropdownRef">

                <Transition enter-active-class="transition ease-out duration-100" enter-from-class="opacity-0 scale-90"
                    enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-90">
                    <div v-if="isOpen"
                        class="absolute left-0 z-20 w-max mt-2  bg-white rounded-md shadow-xl dark:bg-gray-800">

                        <div class="inline-flex flex-col gap-1 md:gap-4 p-4">
                            <Dropdown :required="false" :value="filters.category"
                                @select="(opt) => filters.category = opt" :options="categories" variant="secondary"
                                label="Category" />
                            <Dropdown :required="false" :value="filters.sort"
                                @select="(option) => filters.sort = option" :options="sorts" variant="secondary"
                                label="Sort" />
                            <div>
                                <Search v-model="filters.search" @search="handleFilter" label="Search"
                                    variant="secondary" />
                            </div>

                            <div class="pt-4 space-y-2">
                                <ButtonPrimary variant="outline-secondary" label="Clear" @click.prevent="clearFilter" />
                                <ButtonPrimary variant="secondary" label="Filter" @click.prevent="handleFilter" />
                            </div>
                        </div>

                    </div>
                </Transition>
            </div>
        </div>
    </div>

    <ContainerPrimary class="rounded-none px-2 hidden lg:block lg:w-70">

        <div class="flex flex-col gap-1 md:gap-4 p-4">
            <p class="font-bold text-xl">FIlters:</p>
            <Dropdown :required="false" :value="filters.category" @select="(opt) => filters.category = opt"
                :options="categories" variant="secondary" label="Category" />
            <Dropdown :required="false" :value="filters.sort" @select="(opt) => filters.sort = opt" :options="sorts"
                variant="secondary" label="Sort" />

            <Search v-model="filters.search" label="Search" @search="handleFilter" variant="secondary" />

            <div class="pt-4 space-y-2">
                <ButtonPrimary variant="outline-secondary" label="Clear" @click.prevent="clearFilter" />
                <ButtonPrimary variant="secondary" label="Filter" @click.prevent="handleFilter" />
            </div>
        </div>
    </ContainerPrimary>

</template>