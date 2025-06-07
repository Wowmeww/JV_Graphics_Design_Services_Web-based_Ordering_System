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
        }
    });

    const styleClass = {
        button: 'font-semibold border border-neutral/10 rounded-full py-2 px-6 cursor-pointer transition bg-primary text-light hover:bg-primary/90 gap-x-4 flex justify-center dark:bg-primary-500'
    }
</script>

<template>
    <Link v-if="is === 'link'" :class="styleClass.button" :href="href">
        <span>
            {{ label }}
        </span>
        <slot />
    </Link>
    <button v-else :class="styleClass.button">
        <span>
            {{ label }}
        </span>
        <slot />
    </button>
</template>