<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    options: Array,
    variant: {
        type: String,
        default: 'primary',
    },
    label: { type: String, default: '' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: true },
    value: { type: String, default: '' },
    placeholder: { type: String, default: 'Select' },
    disabled: { type: Boolean, default: false },
    allowCustomValue: { type: Boolean, default: false },
    allowNull: { type: Boolean, default: false },
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
    <div class="form-group relative flex flex-col">
        <label v-if="label" class="input-label">
            {{ label }}
            <span v-show="required" class="font-black text-red-600 dark:text-red-500">*</span>
        </label>
        <button
            @click.prevent="openDropdown"
            :class="`form-control form-control-${variant} relative flex w-full cursor-pointer items-center justify-between pr-10 text-start capitalize dark:bg-transparent ${disabled ? '!cursor-not-allowed opacity-60' : ''} ${props.allowCustomValue ? 'overflow-auto' : ''} ${error && '!border-red-500'}`"
        >
            <span v-if="value">
                {{ value }}
            </span>
            <span v-else class="text-primary/40 overflow-auto leading-normal sm:leading-3 lg:leading-normal dark:text-[#b4cff7]">
                {{ placeholder }}
            </span>
            <i
                :class="
                    (expanded ? 'rotate-180' : '') +
                    ` fa-solid fa-chevron-down leading-0 transition duration-500 text-${variant} dark:text-${variant}-200 `
                "
                class="absolute right-3.5"
            />
        </button>
        <small v-show="error" class="form-control-error">{{ error }}</small>
        <div
            :class="`absolute top-20 z-30 mt-2 min-w-max overflow-hidden rounded-lg border-1 bg-white dark:bg-[#1e293b] border-${variant}-200 animate__animated animate_faster animate__fadeInDown !duration-150 dark:border-white/40`"
            v-if="expanded"
        >
            <div v-if="allowCustomValue" class="relatve">
                <input
                    type="text"
                    v-model="customValue"
                    class="hover:bg-secondary-100 z-40 w-full cursor-pointer px-4 py-1.5 pr-10 transition focus:outline-0 dark:hover:bg-white/20"
                    :class="customValue === value ? 'font-semibold' : ''"
                    placeholder="Enter value then check..."
                />
                <button
                    @click="handleOptionClick(customValue)"
                    class="hover:bg-primary/30 absolute top-2 right-2.5 z-50 h-5 w-5 cursor-pointer rounded-full hover:text-white dark:hover:bg-white/10"
                    type="button"
                >
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
            <p
                v-if="allowNull"
                class="hover:bg-primary-100 dark:hover:bg-primary-400/10 cursor-pointer px-4 py-1 transition first-letter:uppercase dark:text-white"
                :class="!value ? 'font-semibold' : ''"
                @click.prevent="handleOptionClick(null)"
            >
                none
            </p>
            <p
                v-for="(option, i) of options"
                :key="i"
                class="hover:bg-primary-100 dark:hover:bg-primary-400/10 cursor-pointer px-4 py-1 transition first-letter:uppercase dark:text-white"
                :class="option === value ? 'font-semibold' : ''"
                @click.prevent="handleOptionClick(option)"
            >
                {{ option }}
            </p>
        </div>
    </div>
    <div v-if="expanded" class="absolute inset-0 z-20" @click="closeDropdown"></div>
</template>
