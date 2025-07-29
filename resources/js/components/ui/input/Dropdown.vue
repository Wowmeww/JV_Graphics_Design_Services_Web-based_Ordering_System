<script setup>
    import {
        ref,
        watch
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
        allowCustomValue: { type: Boolean, default: false },
    });

    const customValue = ref('');

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
        <label v-if="label" class="input-label">
            {{ label }}
            <span v-show="required" class="text-red-600 dark:text-red-500 font-black">*</span>
        </label>
        <button @click.prevent="openDropdown"
            :class="`form-control form-control-${variant} relative pr-10  w-full flex cursor-pointer items-center justify-between text-start dark:bg-transparent  capitalize ${disabled ? '!cursor-not-allowed opacity-60' : ''} ${props.allowCustomValue ? 'overflow-auto' : ''}`">
            <span v-if="value">
                {{ value }}
            </span>
            <span v-else
                class="text-primary/40 overflow-auto dark:text-[#b4cff7] leading-normal lg:leading-normal sm:leading-3  ">
                {{ placeholder }}
            </span>
            <i :class="(expanded ? 'rotate-180' : '') + ` fa-solid fa-chevron-down transition  leading-0  duration-500  text-${variant} dark:text-${variant}-200 `"
                class="absolute right-3.5" />

        </button>
        <small v-show="error" class="form-control-error">{{ error }}</small>
        <div :class="`absolute bg-white overflow-hidden dark:bg-[#1e293b] min-w-max z-30 mt-2 top-20 rounded-lg border-1 border-${variant}-200 dark:border-white/40 animate__animated animate_faster animate__fadeInDown !duration-150`"
            v-if="expanded">
            <div v-if="allowCustomValue" class="relatve">
                <input type="text" v-model="customValue"
                    class="px-4 py-1.5 w-full hover:bg-secondary-100 pr-10 transition dark:hover:bg-white/20 cursor-pointer focus:outline-0 z-40"
                    :class="customValue === value ? 'font-semibold' : ''" placeholder="Enter value then check..." />
                <button @click="handleOptionClick(customValue)"
                    class="absolute hover:bg-primary/30 dark:hover:bg-white/10 hover:text-white w-5 h-5 right-2.5 top-2 cursor-pointer z-50 rounded-full"
                    type="button">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
            <p v-for="(option, i) of options" :key="i"
                class="px-4 py-1 hover:bg-primary-100 transition dark:hover:bg-primary-400/10 dark:text-white cursor-pointer"
                :class="option === value ? 'font-semibold' : ''" @click.prevent="handleOptionClick(option)">{{
                    option }}</p>
        </div>
    </div>
    <div v-if="expanded" class="absolute inset-0 z-20" @click="closeDropdown"></div>
</template>