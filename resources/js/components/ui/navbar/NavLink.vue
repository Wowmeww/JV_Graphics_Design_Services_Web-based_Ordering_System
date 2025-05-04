<script setup>
    defineProps({
        routeName: {
            type: String,
            required: true
        },
        label: {
            type: String,
            default: 'Home'
        },
        active: {
            type: Boolean,
            default: false
        }
    });
</script>

<template>
    <Link :href="route(routeName)"
    :class="{ 'bg-primary-100 dark:bg-gray-700': active }"
    class="py-2.5 px-[1.375rem] leading-6 text-gray-700 transition-colors duration-300 transform rounded-xl dark:text-gray-200 hover:bg-primary-100 dark:hover:bg-gray-700 md:mx-2">{{ label }} <slot/></Link>
</template>