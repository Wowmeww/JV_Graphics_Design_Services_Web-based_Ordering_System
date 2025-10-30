<script setup>
import { usePage } from '@inertiajs/vue3';
import { indexOf } from 'lodash';
import { computed } from 'vue';

const props = defineProps({
    transaction: { type: Object, required: true },
});

const page = usePage();
const auth = computed(() => page.props.auth?.user);

function totalAmount() {
    const amount = props.transaction.order.total_amount;
    return Number(amount).toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP',
    });
}

function formatDate() {
    const date = new Date(props.transaction.created_at);

    return date.toLocaleDateString('en-us', { month: 'long', day: 'numeric', year: 'numeric' });
}
</script>

<template>
    <!-- make this look perfect -->
    <div
        class="mx-auto w-full rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition-all hover:scale-[1.01] hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600"
    >
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-700">
            <Link as="button" :href="route('transactions', { search: transaction.id })" class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-receipt mr-2 text-blue-500"></i>
                #{{ transaction.id }}
            </Link>
        </div>

        <!-- Transaction Details (Grid Layout) -->
        <div class="grid gap-3 text-sm text-gray-700 dark:text-gray-300">
            <!-- Row 1: Header -->
            <div class="flex items-start">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">Transaction</span>
                <Link as="button" :href="route('transactions', { search: transaction.header })" class="font-semibold text-gray-900 dark:text-white">
                    {{ transaction.header }}
                </Link>
            </div>

            <!-- Row 2: Description -->
            <div class="flex items-start">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">Details</span>
                <span class="text-gray-600 dark:text-gray-300">{{ transaction.content }}</span>
            </div>

            <!-- Row 3: Order -->
            <div class="flex items-center">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">Order</span>
                <Link as="button" :href="route('transactions', { search: transaction.order.id })" class="flex items-center gap-1">
                    <i class="fas fa-hashtag text-xs text-gray-400"></i>
                    {{ transaction.order.id }}
                </Link>
            </div>

            <!-- Row 4: Amount (with currency icon) -->
            <div class="flex items-center">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">Amount</span>
                <Link
                    as="button"
                    :href="route('transactions', { search: transaction.order.total_amount })"
                    class="font-bold text-gray-900 dark:text-white"
                >
                    {{ totalAmount() }}
                </Link>
            </div>

            <!-- Row 5: Date (with calendar icon) -->
            <div class="flex items-center">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">Date</span>
                <Link
                    as="button"
                    :href="route('transactions', { search: new Date(transaction.created_at).toISOString().split('T')[0] })"
                    class="flex items-center gap-1"
                >
                    <i class="far fa-calendar text-gray-400"></i>
                    {{ formatDate() }}
                </Link>
            </div>

            <!-- Conditional: Admin User -->
            <div v-if="auth.is_admin" class="flex items-center">
                <span class="w-24 shrink-0 font-medium text-gray-500 dark:text-gray-400">User</span>
                <Link as="button" :href="route('transactions', { search: transaction.user.name })" class="flex items-center gap-1">
                    <i class="fas fa-user-circle text-gray-400"></i>
                    {{ transaction.user.name }}
                </Link>
            </div>
        </div>

        <!-- Footer (Timestamp) -->
        <Link
            as="button"
            :href="route('transactions', { search: new Date(transaction.created_at).toISOString().split('T')[0] })"
            class="mt-4 border-t border-gray-100 pt-3 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400"
        >
            <i class="far fa-clock mr-1"></i> Processed at {{ formatDate() }}
        </Link>
    </div>
</template>
