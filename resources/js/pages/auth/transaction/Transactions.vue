<script setup>
import Transaction from '@/components/ui/card/Transaction.vue';
import Search from '@/components/ui/Search.vue';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
const props = defineProps({
    transactions: Array,
});

const params = route().params;

const searchTerm = ref(params.search);

watch(
    () => searchTerm.value,
    debounce(function (search) {
        router.get(
            route('transactions'),
            { search },
            {
                preserveState: true,
            },
        );
    }, 600),
);

useEcho('transaction', '.Transaction', ({ transaction }) => {
    // tempOrders.value.unshift(order);
    router.reload({ preserveState: true, preserveScroll: true });
    console.dir(transaction);
});
</script>

<template>
    <Head title="Transactions" />

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col justify-center md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>

            <input
                v-model="searchTerm"
                type="search"
                class="form-control form-control-primary bg-light md:max-w-sm dark:bg-slate-800"
                placeholder="Search . . ."
            />
        </div>

        <div class="space-y-2">
            <Transaction v-for="transaction in transactions" :transaction="transaction" :key="`transaction-${transaction.id}`" />
        </div>
    </div>
</template>
