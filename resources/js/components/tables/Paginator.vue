<script setup>

    defineProps({
        products: Object
    });
    const styleClass = {
        disabled: 'px-4 py-2 mx-1 text-gray-400 transition-colors duration-300 transform bg-white/80 rounded-md inline dark:bg-gray-800/50 capitalize dark:text-gray-200/40 hover:cursor-not-allowed',
        link: 'hidden sm:inline px-4 py-2 mx-1 text-gray-700 transition-colors duration-300 transform bg-white rounded-md  dark:bg-gray-800 capitalize dark:text-gray-200 hover:bg-primary dark:hover:bg-primary-500 hover:text-white dark:hover:text-gray-200',
        prevNext: 'px-4 py-2 mx-1 text-gray-700 transition-colors duration-300 transform bg-white rounded-md  dark:bg-gray-800 capitalize dark:text-gray-200 hover:bg-primary dark:hover:bg-primary-500 hover:text-white dark:hover:text-gray-200',
        active: '!dark:bg-primary-500 !bg-primary !text-white !dark:text-gray-200'
    }

    const element = (link) => {
        if ((link.label.includes('Previous') || link.label.includes('Next')) && !link.url) {
            return 'span';
        }
        return 'Link';
    }

    const label = (label) => {
        if (label.includes('Previous')) {
            return `<div class="flex items-center -mx-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-1 rtl:-scale-x-100" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                </svg>

                <span class="mx-1">
                    previous
                </span>
            </div>`;
        } else if (label.includes('Next')) {
            return `<div class="flex items-center -mx-1">
                <span class="mx-1">
                    Next
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-1 rtl:-scale-x-100" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>`;
        }
        return label;
    }
</script>

<template>
    <div class="flex py-6 pt-8 justify-center">
        <component 
            v-for="link of products.links"
            :href="link.url" 
            :key="link.label" 
            :is="element(link)"
            :class="{
                [styleClass.prevNext]: (link.label.includes('Previous') || link.label.includes('Next')) && link.url,
                [styleClass.disabled]: !link.url,
                [styleClass.link]: !link.label.includes('Previous') && !link.label.includes('Next') && link.url,
                [styleClass.active]: link.active
            }"
            v-html="label(link.label)"
        />
    </div>
</template>