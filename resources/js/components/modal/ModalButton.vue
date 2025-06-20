<script setup>
    import { defineProps, defineEmits } from 'vue';

    const emit = defineEmits(['click', 'cancel']);

    const props = defineProps({
        is: { type: String, default: 'button' }, // e.g. 'button' or 'a'
        type: { type: String, default: 'button' },
        href: { type: String, default: undefined },
        label: { type: String, default: 'Button' },
        text: { type: String, default: 'gray-900' },
        bg: { type: String, default: 'white' },
    });
</script>

<template>
    <component :is="is" :href="href" :type="is === 'button' ? type : undefined" @click="$emit('click')" :class="[
        'py-2.5 px-5 ml-3 text-sm font-medium border rounded-lg transition-all duration-200',
        `text-${text}`,
        `bg-${bg}`,
        'border-gray-200',
        'hover:bg-gray-100 hover:text-blue-700',
        'dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white',
    ]">
        {{ label }}
        <slot />
    </component>
</template>
