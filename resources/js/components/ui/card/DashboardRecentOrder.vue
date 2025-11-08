<script setup>
defineProps({
    order: Object,
});
</script>

<template>
    <div
        class="border-dark/20 rounded-lg border-2 bg-white p-4 text-slate-700 shadow transition-shadow duration-200 hover:shadow-md dark:border-white/10 dark:bg-slate-800 dark:text-white"
    >
        <div class="mb-3 flex items-start justify-between">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                <span>Order #</span>
                <span>{{ order.id }}</span>
            </h3>
            <span class="text-sm text-gray-500 dark:text-slate-200">2025-04-01</span>
        </div>

        <div class="space-y-2">
            <!-- Product Name -->
            <div class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-slate-200">Product:</span>
                <span class="font-medium">{{ order.option?.name || order.product.name }}</span>
            </div>

            <!-- Quantity -->
            <div class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-slate-200">Quantity:</span>
                <span class="font-medium">{{ order.quantity }}</span>
            </div>

            <!-- Order Total -->
            <div class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-slate-200">Total:</span>
                <span class="font-bold text-green-600">
                    {{
                        Number(order.total_amount).toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP',
                        })
                    }}</span
                >
            </div>

            <!-- Customer Name -->
            <button type="button" class="flex w-full cursor-pointer justify-between hover:underline">
                <span class="text-sm text-gray-500 dark:text-slate-200">Customer:</span>
                <span class="font-medium text-slate-900 dark:text-slate-100">
                    {{ order.user.name }}
                </span>
            </button>
        </div>

        <!-- View Button -->
        <div class="mt-4 text-right">
            <Link :href="route('manage.orders.show', { order: order.id })" class="text-sm text-blue-600 hover:underline dark:text-blue-500"
                >View Details</Link
            >
        </div>
    </div>
</template>
