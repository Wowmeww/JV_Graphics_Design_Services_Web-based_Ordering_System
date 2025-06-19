<script setup>
    import { computed } from 'vue';


    const props = defineProps({
        is: {
            type: String,
            default: 'Link',
        },
        routeName: String,
        label: String,
        type: {
            type: String,
            default: null,
        },
        active: {
            type: Boolean,
            default: false
        },
    });

    const styleClass = computed(() => props.active ?
        'flex items-center p-2 text-gray-900 bg-gray-100 dark:bg-gray-700 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group' :
        'flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group');
</script>

<template>
    <component :is="is" :href="routeName" :class="styleClass">
        {{ label }}
        <slot />
    </component>
</template>