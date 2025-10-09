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
        default: false,
    },
    badge: {
        type: [String, Number],
    },
});

const styleClass = computed(() =>
    props.active
        ? 'flex relative items-center p-2 text-gray-900 bg-gray-100 dark:bg-gray-700 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group'
        : 'flex relative items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group',
);
</script>

<template>
    <component :is="is" :href="routeName" :class="styleClass">
        {{ label }}
        <slot />

        <small v-if="badge" class="absolute top-0 right-0 inline-block rounded-full bg-red-500/75 px-2 py-0.5 text-white">{{ badge }}</small>
    </component>
</template>
