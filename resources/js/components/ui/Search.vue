<script setup>
    import { reactive, ref } from 'vue';

    const model = defineModel()

    const emit = defineEmits(['search']);

    function submit() {
        if (model.value.trim() === "") {
            return;
        }

        emit('search', model.value);
    }
</script>


<template>
    <form class="relative" @submit.prevent="submit">
        <input type="text" placeholder="Search products"
            class="form-control md:min-w-xs lg:min-w-sm pr-16 w-full bg-white dark:bg-transparent" v-model="model" />
        <button type="submit"
            class="text-2xl absolute right-[1.375rem] top-1/2 -translate-y-1/2 text-neutral cursor-pointer">
            <i class="fa-solid fa-magnifying-glass dark:text-light/80" />
        </button>
    </form>
</template>