<script setup>
    const props = defineProps({
        as: {
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
    <Link :disabled="disable" v-if="as === 'Link'" :href="href" :class="styleClass">
    {{ label }}
    <slot />
    <i v-show="disable" class="fa-solid fa-spinner" />
    </Link>
    <button v-else :type="type" :class="styleClass" :disabled="disable">
        {{ label }}
        <slot />
        <i v-show="disable" class="fa-solid fa-spinner animate-spin" />
    </button>
</template>