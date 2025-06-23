<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        products: Object,
    });

    const styleClass = {
        disabled: ' px-4 py-2 mx-1 text-gray-400 transition-colors duration-300 transform bg-white/80 rounded-md inline dark:bg-gray-800/50 capitalize dark:text-gray-200/40 hover:cursor-not-allowed',
        link: ' hidden sm:inline px-4 py-2 mx-1 text-gray-700 transition-colors duration-300 transform bg-white rounded-md dark:bg-gray-800 capitalize dark:text-gray-200 hover:bg-primary dark:hover:bg-primary-500 hover:text-white dark:hover:text-gray-200',
        prevNext: ' px-4 py-2 mx-1 text-gray-700 transition-colors duration-300 transform bg-white rounded-md dark:bg-gray-800 capitalize dark:text-gray-200 hover:bg-primary dark:hover:bg-primary-500 hover:text-white dark:hover:text-gray-200',
        active: ' !dark:bg-primary-500 !bg-primary !text-white !dark:text-gray-200 font-semibold'
    };

    const processedLinks = computed(() => {
        const links = props.products.links;
        const total = links.length;

        // Always include first 3 and last 3
        const first = links.slice(0, 3);
        const last = links.slice(-3);

        // Filter out the "Previous" and "Next" buttons
        const prev = links.find(l => l.label.toLowerCase().includes('previous'));
        const next = links.find(l => l.label.toLowerCase().includes('next'));

        const numericLinks = links.filter(l => !isNaN(l.label));

        const middle = [];
        const firstPage = parseInt(numericLinks[0]?.label);
        const lastPage = parseInt(numericLinks[numericLinks.length - 1]?.label);
        const currentPage = numericLinks.find(l => l.active);

        // Determine if we need ellipsis in the middle
        const output = [];

        if (prev) output.push(prev);

        numericLinks.forEach((link, i) => {
            const page = parseInt(link.label);

            if (
                page <= firstPage + 2 ||
                page >= lastPage - 2 ||
                (currentPage && Math.abs(page - parseInt(currentPage.label)) <= 1)
            ) {
                output.push(link);
            } else {
                const lastItem = output[output.length - 1];
                if (lastItem && lastItem.label !== '...') {
                    output.push({ label: '...', url: null });
                }
            }
        });

        if (next) output.push(next);

        return output;
    });

    const element = (link) => {
        return link.url ? 'Link' : 'span';
    };
</script>

<template>
    <div class="flex py-6 px-3 pt-8 justify-center">
        <component v-for="(link, index) in processedLinks" :key="index" preserve-scroll :is="element(link)"
            :href="link.url" :class="{
                [styleClass.prevNext]: (link.label.includes('Previous') || link.label.includes('Next')) && link.url,
                [styleClass.disabled]: !link.url,
                [styleClass.link]: !link.label.includes('Previous') && !link.label.includes('Next') && link.url,
                [styleClass.active]: link.active
            }">
            <template v-if="link.label.includes('Previous')">
                <div class="flex items-center -mx-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-1 rtl:-scale-x-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                    <span class="mx-1">Previous</span>
                </div>
            </template>
            <template v-else-if="link.label.includes('Next')">
                <div class="flex items-center -mx-1">
                    <span class="mx-1">Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-1 rtl:-scale-x-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </template>
            <template v-else>
                {{ link.label }}
            </template>
        </component>
    </div>
</template>
