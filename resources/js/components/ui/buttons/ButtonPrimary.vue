<script setup>
    const props = defineProps({
        is: {
            type: String,
            default: 'button',
        },
        href: String,
        label: String,
        type: {
            type: String,
            default: 'button',
        },
        variant: {
            type: String,
            default: 'primary',
        },
        disable: {
            type: Boolean,
            default: false,
        }
    });
</script>

<template>
    <component :is="is" :type="type" :href="href" :class="`btn w-full btn-${variant}`" :disabled="disable">
        {{ label }}
        <slot />
        <i v-show="disable" class="fa-solid fa-spinner animate-spin" />
    </component>
</template>