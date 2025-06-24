<script setup>
    import { defineProps, defineEmits } from 'vue'

    const emit = defineEmits(['click'])

    const props = defineProps({
        label: { type: String, default: 'Button' },
        is: { type: String, default: 'button' },
        href: { type: String, default: '' },
        variant: { type: String, default: 'primary' },
        style: { type: String, default: '' },
        type: { type: String, default: 'button' },
        disabled: { type: Boolean, default: false },
    })

    const styleClass = {
        primary: `text-center font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-neutral/10 rounded-full py-2 px-6 cursor-pointer transition bg-primary text-light hover:bg-primary/90 dark:bg-primary-500 gap-x-4`,
        secondary: `text-center font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-neutral/10 rounded-full py-2 px-6 cursor-pointer transition bg-secondary-500 text-light hover:bg-secondary/90 dark:bg-secondary-500 gap-x-4`,
        outlinePrimary: `text-center font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-primary-500 rounded-full py-2 px-6 cursor-pointer transition bg-transparent text-primary-500 hover:bg-primary-100/50 dark:border-white dark:text-white dark:bg-primary-500/30 dark:hover:bg-primary-500/70 gap-x-4`,
        outlineSecondary: `text-center font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-secondary-500 rounded-full py-2 px-6 cursor-pointer transition bg-transparent text-secondary-500 hover:bg-secondary-100/50 dark:border-white dark:text-white dark:bg-secondary-500/30 dark:hover:bg-secondary-500/70 gap-x-4`,
    }
</script>

<template>
    <component @click="() => $emit('click')" :type="type" :disabled="disabled" :is="is" :href="href" :class="[
        `${styleClass[variant] || styleClass.primary}`,
        style,
        { '!opacity-50 !cursor-not-allowed': disabled }
    ]">
        <span>{{ label }}</span>
        <slot />
    </component>
</template>
