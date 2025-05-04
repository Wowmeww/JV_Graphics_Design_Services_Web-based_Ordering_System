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
        variant:{
            type: String,
            default: 'primary',
        }
    });

    const styleClass = props.variant === 'primary'? 'btn btn-primary w-full': 'btn btn-outline-primary';
</script>

<template>
    <Link v-if="as==='Link'" :href="href" :class="styleClass">
        {{ label }}
        <slot />
    </Link>
    <button v-else :type="type" :class="styleClass">
        {{ label }}
        <slot />
    </button>
</template>