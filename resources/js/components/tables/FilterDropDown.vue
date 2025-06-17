<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        options: Array,
        placeholder: String
    });


    const placeholder = ref(props.placeholder);

    const expanded = ref(false);

    function openDropdown() {
        expanded.value = !expanded.value;
    }
    function closeDropdown() {
        expanded.value = false;
    }

    const emit = defineEmits(['select']);

    function handleOptionClick(option) {
        placeholder.value = option;
        closeDropdown();
        emit('select', option);
    }
</script>

<template>
    <div class="relative capitalize min-w-[280px] lg:max-w-xs ">
        <button @click.prevent="openDropdown"
            class="form-control  bg-white w-full flex cursor-pointer items-center justify-between text-start dark:bg-transparent capitalize">
            <span>
                {{ placeholder }}
            </span>
            <i class="bi bi-caret-down-fill transition  leading-0  duration-500  text-primary dark:text-primary-300"
                :class="expanded ? 'rotate-180' : ''" />
        </button>


        <div class="absolute bg-white overflow-hidden dark:bg-[#1e293b] min-w-max z-30 mt-2 rounded-lg border-1 border-primary dark:border-white/40 animate__animated animate_faster animate__fadeInDown"
            v-if="expanded">

            <p v-for="(option, i) of options" :key="i"
                class="px-4 py-1 hover:bg-primary-100 transition dark:hover:bg-primary-400/10 cursor-pointer"
                :class="option === placeholder ? 'font-semibold' : ''" @click.prevent="handleOptionClick(option)">{{
                    option }}</p>
        </div>
    </div>
    <div v-if="expanded" class="absolute inset-0 z-20" @click="closeDropdown"></div>
</template>