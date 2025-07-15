<script setup>

    defineProps({
        order: Object
    });

</script>

<template>
    <div
        class="bg-white border-2 border-dark/20 dark:border-white/10 p-4 text-slate-700 rounded-lg shadow hover:shadow-md transition-shadow duration-200 dark:bg-slate-800 dark:text-white">
        <div class="flex justify-between items-start mb-3">
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
                <span class="font-bold text-green-600"> {{
                    (order.option?.price ||
                        order.product.price).toLocaleString('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        })
                }}</span>
            </div>

            <!-- Customer Name -->
            <button type="button" class="flex justify-between hover:underline cursor-pointer w-full">
                <span class="text-sm text-gray-500 dark:text-slate-200">Customer:</span>
                <span class="font-medium text-slate-900 dark:text-slate-100">
                    {{ order.user.name }}
                </span>
            </button>
        </div>

        <!-- View Button -->
        <div class="mt-4 text-right">
            <Link :href="route('manage.orders.show', { order: order.id })"
                class="text-sm text-blue-600 dark:text-blue-500 hover:underline">View Details</Link>
        </div>
    </div>
</template>