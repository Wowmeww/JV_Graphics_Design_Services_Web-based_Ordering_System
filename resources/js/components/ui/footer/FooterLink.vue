<script setup>
    defineProps({
        routeName: {
            type: String,
            default: '#',
        },
        label: {
            type: String,
            default: 'Privacy',
        },
    });
</script>

<template>
    <Link :href="route(routeName)"
        class="mx-4 text-sm text-gray-600 transition-colors duration-300 hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-400"
        :aria-label="label"> {{ label }}
    <slot />
    </Link>
</template>