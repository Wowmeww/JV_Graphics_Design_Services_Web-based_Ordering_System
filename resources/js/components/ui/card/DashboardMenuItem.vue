<script setup>
defineProps({
    label: { type: String, default: 'Label' },
    icon: { type: String, default: 'icon-class' },
    count: { type: [Number, String], default: 0 },
    is: { type: String, default: 'button' },
    href: { type: String, default: null },
    type: { type: String, default: null },
});

const emit = defineEmits(['click']);

const styleClass = {
    // Main container
    container:
        'group flex flex-col-reverse items-center justify-center gap-3 p-4 w-24 md:w-44 min-w-fit relative rounded-2xl border-2 border-primary-600 dark:border-primary-400 bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 text-white hover:from-primary-600 hover:to-primary-700 dark:hover:from-primary-500 dark:hover:to-primary-600 transition-all duration-300 ease-out shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 backdrop-blur-sm',

    // Icon
    icon: 'text-2xl md:text-4xl transition-transform duration-300 group-hover:scale-110 group-hover:-translate-y-1 drop-shadow-lg',

    // Label
    label: 'text-sm md:text-base font-bold text-center capitalize line-clamp-2 tracking-wide text-white/95 group-hover:text-white transition-colors duration-300',

    // Count badge
    count: 'absolute -top-2 -right-2 text-xs font-black bg-gradient-to-br from-red-500 to-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center border-2 border-white shadow-lg hover:scale-110 transition-transform duration-300 backdrop-blur-sm',
};
</script>

<template>
    <component @click="emit('click')" :type="type" :is="is" :href="href" :class="styleClass.container">
        <!-- Icon -->
        <i :class="`${styleClass.icon} ${icon}`"></i>

        <!-- Label -->
        <span :class="styleClass.label">
            {{ label }}
        </span>

        <!-- Count Badge -->
        <span v-if="count" :class="styleClass.count">
            {{ count }}
        </span>
    </component>
</template>
