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
</script>

<template>
    <component @click="emit('click')" :type="type" :is="is" :href="href"
        class="flex flex-col-reverse items-center justify-center gap-2 p-3 w-20 md:w-40 min-w-fit relative rounded-lg border border-slate-900 dark:border-slate-100 bg-primary text-light hover:bg-primary-500 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md active:scale-[0.98]">
        <!-- Icon -->
        <i :class="`text-xl md:text-3xl ${icon}`"></i>

        <!-- Label -->
        <span class="text-xs md:text-sm font-semibold text-center capitalize line-clamp-2">
            {{ label }}
        </span>

        <!-- Count Badge -->
        <span v-if="count"
            class="absolute opacity-80 top-2 right-2 text-xs font-semibold bg-white/20 backdrop-blur-sm rounded-full w-5 h-5 flex items-center justify-center">
            {{ count }}
        </span>
    </component>
</template>