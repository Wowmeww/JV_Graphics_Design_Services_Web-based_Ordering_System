<script setup>
    import {
        ref
    } from 'vue';

    const props = defineProps({
        options: Array,
        variant: {
            type: String,
            default: 'primary'
        },
        label: { type: String, default: '' },
        error: { type: String, default: '' },
        required: { type: Boolean, default: true },
        value: { type: String, default: '' },
        placeholder: { type: String, default: 'Select' },
        disabled: { type: Boolean, default: false },
    });


    const expanded = ref(false);

    function openDropdown() {
        if (!props.disabled) {
            expanded.value = !expanded.value;
        }
    }

    function closeDropdown() {
        expanded.value = false;
    }

    const emit = defineEmits(['select']);

    function handleOptionClick(option) {

        closeDropdown();
        emit('select', option);
    }
</script>

<template>
    <div class="relative form-group flex flex-col">
        <label class="input-label">
            {{ label }}
            <span v-show="required" class="text-red-600 dark:text-red-500 font-black">*</span>
        </label>
        <button @click.prevent="openDropdown"
            :class="`form-control-${variant}  bg-white w-full flex cursor-pointer items-center justify-between text-start dark:bg-transparent capitalize ${disabled ? '!cursor-not-allowed opacity-60' : ''}`">
            <span v-if="value">
                {{ value }}
            </span>
            <span v-else class="text-primary/40 dark:text-[#b4cff7] leading-normal lg:leading-normal sm:leading-3  ">
                {{ placeholder }}
            </span>
            <i
                :class="(expanded ? 'rotate-180' : '') + ` fa-solid fa-chevron-down transition  leading-0  duration-500  text-${variant} dark:text-${variant}-200`" />

        </button>
        <small v-show="error" class="form-control-error">{{ error }}</small>
        <div :class="`absolute bg-white overflow-hidden dark:bg-[#1e293b] min-w-max z-30 mt-2 top-20 rounded-lg border-1 border-${variant}-200 dark:border-white/40 animate__animated animate_faster animate__fadeInDown !duration-150`"
            v-if="expanded">

            <p v-for="(option, i) of options" :key="i"
                class="px-4 py-1 hover:bg-primary-100 transition dark:hover:bg-primary-400/10 cursor-pointer"
                :class="option === value ? 'font-semibold' : ''" @click.prevent="handleOptionClick(option)">{{
                    option }}</p>
        </div>
    </div>
    <div v-if="expanded" class="absolute inset-0 z-20" @click="closeDropdown"></div>
</template>