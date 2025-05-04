<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        password: String
    })
    const passwordStrength = computed(() => {
        const val = props.password;
        let score = 0;

        if (val.length >= 6) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        if (score <= 1) return 'weak';
        if (score === 2) return 'medium';
        return 'strong';
    });</script>

<template>
    <small v-if="password" :class="{
        'text-red-500': passwordStrength === 'weak',
        'text-yellow-500': passwordStrength === 'medium',
        'text-green-500': passwordStrength === 'strong',
    }">
        Password strength: {{ passwordStrength }}
    </small>
</template>