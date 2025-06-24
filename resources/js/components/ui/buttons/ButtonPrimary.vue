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

    const styleClass = props.variant === 'primary' ? 'btn btn-primary w-full' : 'btn btn-outline-primary';
</script>

<template>
    <component :is="is" :type="type" :href="href" :class="styleClass" :disabled="disable">
        {{ label }}
        <slot />
        <i v-show="disable" class="fa-solid fa-spinner animate-spin" />
    </component>
</template>