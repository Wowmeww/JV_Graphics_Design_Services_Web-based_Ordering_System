<script setup>
    import GuestLayout from './GuestLayout.vue';
    import AuthLayout from './AuthLayout.vue';
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';


    const page = usePage();
    const user = computed(() => page.props.auth?.user);
</script>

<template>
    <AuthLayout v-if="user">
        <slot />
    </AuthLayout>
    <GuestLayout v-else>
        <slot />
    </GuestLayout>
</template>