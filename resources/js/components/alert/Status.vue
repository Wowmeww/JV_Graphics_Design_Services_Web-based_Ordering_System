<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { computed, onMounted, ref, watch } from 'vue'

    const props = defineProps({
        status: Object
    });
    const colorMap = {
        success: 'emerald',
        warning: 'yellow',
        error: 'red',
        default: 'blue'
    };

    const page = usePage();


    const color = computed(() => colorMap[props.status.type] || colorMap.default);

    const iconContainerClass = computed(() => {
        return {
            'flex items-center justify-center w-12': true,
            'bg-emerald-500': color.value === 'emerald',
            'bg-yellow-400': color.value === 'yellow',
            'bg-red-500': color.value === 'red',
            'bg-blue-500': color.value === 'blue',
        };
    });

    const typeLabelClass = computed(() => {
        return {
            'font-semibold': true,
            'text-emerald-500 dark:text-emerald-400': color.value === 'emerald',
            'text-yellow-400 dark:text-yellow-300': color.value === 'yellow',
            'text-red-500 dark:text-red-400': color.value === 'red',
            'text-blue-500 dark:text-blue-400': color.value === 'blue',
        };
    });
    onMounted(() => setTimeout(close, 5000));

    const emit = defineEmits(['close']);

    function close() {
        page.props.status = null;
        // emit('close');
    }
    const styleClass = {
        container: 'flex relative right-0 max-w-sm overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800',
        icon: 'w-6 h-6 text-white fill-current ',
        message: 'text-sm text-gray-600 dark:text-gray-200'
    };

</script>

<template>
    <div v-if="status" class="fixed inset-x-0 top-[75vh] px-3 md:px-12 flex justify-center md:justify-end z-50">
        <Transition name="fade">
            <div
                class="flex relative min-w-sm max-w-md overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800 ">
                <div :class="iconContainerClass">
                    <svg v-if="status.type === 'success'" :class="styleClass.icon" viewBox="0 0 40 40"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z" />
                    </svg>

                    <svg v-else-if="status.type === 'warning'" :class="styleClass.icon" viewBox="0 0 40 40"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
                    </svg>

                    <svg v-else-if="status.type === 'error'" :class="styleClass.icon" viewBox="0 0 40 40"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.36667C10.8167 3.36667 3.3667 10.8167 3.3667 20C3.3667 29.1833 10.8167 36.6333 20 36.6333C29.1834 36.6333 36.6334 29.1833 36.6334 20C36.6334 10.8167 29.1834 3.36667 20 3.36667ZM19.1334 33.3333V22.9H13.3334L21.6667 6.66667V17.1H27.25L19.1334 33.3333Z" />
                    </svg>

                    <svg v-else :class="styleClass.icon" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z" />
                    </svg>
                </div>
                <div class="px-4 py-2 -mx-3 first-letter:uppercase">
                    <div class="mx-3">
                        <span :class="typeLabelClass">{{ status.type }}</span>
                        <p :class="styleClass.message">{{ status.message }}</p>
                    </div>
                </div>

                <button @click="close" type="button"
                    class="p-1 absolute right-1 top-1 transition-colors duration-300 transform rounded-md hover:bg-opacity-25 dark:hover:bg-gray-600 hover:bg-gray-300 focus:outline-none">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </Transition>
    </div>
</template>

<style scoped>

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.5s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>