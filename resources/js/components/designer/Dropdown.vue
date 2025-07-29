<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import WebFont from 'webfontloader';

const props = defineProps({
    value: String,
    label: String,
});

const fonts = [
    'Roboto',
    'Poppins',
    'Caveat',
    'Bitcount Prop Single',
    'Open Sans',
    'Intel One Mono',
    'Special Gothic Expanded One',
    'Pacifico',
    'Luckiest Guy',
    'Rubik Mono One',
    'Silkscreen',
    'Monoton',
]; // Remove Bitcount if not in Google Fonts

WebFont.load({
    google: {
        families: fonts,
    },
});

const selectedFont = ref(props.modelValue || fonts[0]);

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);

const closeDropdown = (e) => {
    if (!e.target.closest('.dropdown-container')) {
        isOpen.value = false;
    }
};

const selectOption = (option) => {
    selectedFont.value = option;
    emit('update:modelValue', option);
    isOpen.value = false;
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<template>
    <div>
        <label class="block text-sm text-gray-500 dark:text-gray-300">Font Family</label>
        <div class="dropdown-container relative mt-2">
            <!-- Toggle Button -->
            <button
                @click="isOpen = !isOpen"
                class="focus:ring-opacity-40 relative mt-2 flex w-full justify-between rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:border-blue-300"
            >
                <span :style="{ fontFamily: value }">
                    {{ value }}
                </span>
                <svg class="h-5 w-5 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>

            <!-- Dropdown -->
            <transition name="fade">
                <div v-if="isOpen" class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white py-2 shadow-xl dark:bg-gray-800">
                    <button
                        v-for="font in fonts"
                        :key="font"
                        @click="selectOption(font)"
                        :style="{ fontFamily: font }"
                        class="block w-full transform px-4 py-2 text-left text-sm text-gray-600 capitalize transition-colors duration-300 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        {{ font }}
                    </button>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
