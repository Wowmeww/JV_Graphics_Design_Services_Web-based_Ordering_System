<script setup>
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        search: {
            default: "",
            required: false
        },
        route: String
    });

    const form = useForm({
        search: null
    });

    function submit() {
        alert(form.search);
    }
</script>


<template>
    <form class="relative" @submit.prevent="submit">
        <input type="text" placeholder="Search products" class="form-control pr-16 w-full" v-model="form.search" />
        <button type="submit"
            class="text-2xl absolute right-[1.375rem] top-1/2 -translate-y-1/2 text-neutral cursor-pointer">
            <i class="fa-solid fa-magnifying-glass dark:text-light/80" />
        </button>
    </form>
</template>