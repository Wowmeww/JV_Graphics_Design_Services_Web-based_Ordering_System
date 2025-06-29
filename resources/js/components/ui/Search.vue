<script setup>
    import { reactive, ref } from 'vue';

    const model = defineModel();

    defineProps({
        label: { type: String, default: '' },
        variant: { type: String, default: 'primary' }
    });

    const emit = defineEmits(['search']);

    function submit() {
        if (model.value?.trim() === "") {
            return;
        }

        emit('search', model.value);
    }
</script>


<template>
    <form @submit.prevent="submit" class="">
        <label for="" class="input-label inline-block">{{ label }}</label>
        <div class="relative">
            <input type="text" placeholder="Search products"
                :class="`form-control form-control-${variant}  pr-16 w-full bg-white dark:bg-transparent`"
                v-model="model" />
            <button type="submit"
                class="md:text-2xl text-md absolute right-[1.375rem] top-1/2 -translate-y-1/2 text-neutral cursor-pointer">
                <i class="fa-solid fa-magnifying-glass dark:text-light/80" />
            </button>
        </div>
    </form>
</template>