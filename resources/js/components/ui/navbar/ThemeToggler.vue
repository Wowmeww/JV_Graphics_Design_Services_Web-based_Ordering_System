<script setup>
    import { ref } from 'vue';
    import { useAppearance } from '../../../composables/useAppearance';

    const { appearance, updateAppearance } = useAppearance();

    const options = [
        { value: 'light', html: `Light <i class="w-5 h-5 pt-1 fa-solid fa-sun text-gray-800 dark:text-white" />` },
        { value: 'dark', html: `Dark <i class="w-5 h-5 pt-1 fa-solid fa-moon fa-sun text-gray-800 dark:text-white" />` },
        { value: 'system', html: `System <i class="w-5 pt-1 h-5 fa-solid fa-moon fa-desktop text-gray-800 dark:text-white" />` },
    ]

    const isOpen = ref(false);


</script>

<template>
    <div class="relative inline-block">
        <!-- Dropdown toggle button -->
        <button @click="isOpen = !isOpen"
            class="relative z-10 w-fit h-fit p-0.5 text-gray-700 bg-white border  rounded-full dark:text-white hover:border-secondary-500 border-secondary-200 focus:ring-opacity-40 dark:focus:ring-opacity-40 focus:ring-secondary-300 transition dark:focus:ring-secondary-400 focus:ring dark:bg-gray-800 pb-1 focus:outline-none flex justify-center gap-x-1.5 items-center">
            <i class="w-5 h-5 fa-solid  text-gray-800 dark:text-white pt-1" :class="{
                'fa-moon': appearance === 'dark',
                'fa-desktop': appearance === 'system',
                'fa-sun': appearance === 'light',
            }" />

            <!-- <i class="fa-solid fa-chevron-down pb-1"></i> -->

        </button>

        <!-- Dropdown menu -->
        <transition v-show="isOpen" @click="isOpen = false" enter-active-class="transition ease-out duration-100"
            enter-from-class="opacity-0 scale-90" enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90"
            class="absolute left-0 md:right-0 md:left-auto z-20 w-30 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg dark:shadow-white/10 dark:bg-gray-800">
            <div>
                <button v-for="option in options" :key="option.value" v-html="option.html"
                    @click="updateAppearance(option.value)"
                    class="flex flex-row-reverse justify-end gap-2 items-center px-4  py-1 text-sm text-gray-600 capitalize transition-colors duration-300 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white w-full text-left " />
            </div>
        </transition>
    </div>
</template>
