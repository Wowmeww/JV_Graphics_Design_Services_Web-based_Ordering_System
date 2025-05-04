<script setup>
    defineProps({
        label: {
            type: String,
            default: 'Button',
        },

    });
</script>

<template>
    <button class="md:py-6 md:px-9 md:text-2xl py-5 px-8 text-xl leading-4 flex justify-center gap-2 items-center border-2 border-white  font-bold rounded-full mx-auto transition-all duration-200 ease-in-out disabled:bg-muted disabled:hover:bg-muted cursor-pointer dark:bg-secondary hover:bg-secondary dark:hover:bg-secondary/70">{{ label }}
        <slot />
    </button>
</template>