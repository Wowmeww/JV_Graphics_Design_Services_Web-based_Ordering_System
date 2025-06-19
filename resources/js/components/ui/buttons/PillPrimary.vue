<script setup>
    defineProps({
        label: {
            type: String,
            default: 'Button',
        },
        is: 'button',
        href: {
            type: String,
            default: '',
        },
        variant: {
            type: String,
            default: 'primary',
        }
    });

    const styleClass = {
        primary: 'font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-neutral/10 rounded-full py-2 px-6 cursor-pointer transition bg-primary text-light hover:bg-primary/90 dark:bg-primary-500 gap-x-4 flex justify-center ',
        secondary: 'font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-neutral/10 rounded-full py-2 px-6 cursor-pointer transition bg-secondary-500 text-light hover:bg-secondary/90 dark:bg-secondary-500 gap-x-4 flex justify-center',
        outlinePrimary: 'font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-primary-500 rounded-full py-2 px-6 cursor-pointer transition bg-transparent text-primary-500 hover:bg-primary-100/50 dark:border-white dark:text-white dark:bg-primary-500/30 dark:hover:bg-primary-500/70 gap-x-4 flex justify-center',
        outlineSecondary: 'font-semibold border flex items-center justify-center leading-4 sm:leading-normal border-secondary-500 rounded-full py-2 px-6 cursor-pointer transition bg-transparent text-secondary-500 hover:bg-secondary-100/50 dark:border-white dark:text-white dark:bg-secondary-500/30 dark:hover:bg-secondary-500/70 gap-x-4 flex justify-center',
    }
</script>

<template>
    <Link v-if="is === 'link'" :class="styleClass[variant]" :href="href">
        <span>
            {{ label }}
        </span>
        <slot />
    </Link>
    <button v-else :class="styleClass[variant]">
        <span>
            {{ label }}
        </span>
        <slot />
    </button>
</template>