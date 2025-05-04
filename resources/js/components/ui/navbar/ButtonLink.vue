<script setup>
import { computed } from 'vue';


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
        active: {
            type: Boolean,
            default: false
        }
    });
    const styleClass = computed( ()=> props.active ?
        'btn w-full btn-outline-primary bg-primary text-white' :
        'btn w-full btn-outline-primary');
</script>

<template>
    <Link v-if="as === 'Link'" :href="href" :class="styleClass" >
        {{ label }}
        <slot />
    </Link>
    <button v-else :type="type" :class="styleClass">
        {{ label }}
        <slot />
    </button>
</template>