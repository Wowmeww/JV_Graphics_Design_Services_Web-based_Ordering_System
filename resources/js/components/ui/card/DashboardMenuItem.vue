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
        class="flex items-center flex-col-reverse p-2.5 border bg-primary text-light border-slate-900 dark:border-slate-100 justify-center gap-y-2 md:flex-col-reverse md:items-start text-xs md:text-sm hover:bg-primary-500 transition rounded-md md:w-40 w-20 min-w-fit relative">
        <span class="capitalize text-wrap font-semibold">
            {{ label }}
        </span>
        <i :class="`md:text-3xl text-xl ${icon}`"></i>

        <span v-if="count" class="absolute top-1 right-2 text-xs font-semibold">{{ count }}</span>
    </component>
</template>