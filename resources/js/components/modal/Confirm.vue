<script setup>
    import { usePage } from '@inertiajs/vue3';
    import ModalButton from './ModalButton.vue';
    import { onMounted, onUnmounted } from 'vue';

    const page = usePage();
    const defaults = {
        message: 'Are you sure you want to continue?'
    };

    function cancel() {
        page.props.confirm.show = false;
    }
    function confirm() {
        page.props.confirm.confirmed = true;
        page.props.confirm.show = false;
    }

    onMounted(() => {
        page.props.confirm.confirmed = false;
    });
    onUnmounted(() => {
        page.props.confirm.confirmed = false;
    });
</script>
<template>
    <div class="fixed inset-0 z-80 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="relative w-full max-w-md max-h-full p-4">
            <div class="relative bg-white dark:bg-gray-700 rounded-lg shadow">
                <!-- Close Button -->
                <button type="button" @click="cancel"
                    class="absolute top-3 right-2.5 text-gray-400 hover:text-gray-900 hover:bg-gray-200 dark:hover:text-white dark:hover:bg-gray-600 rounded-lg text-sm w-8 h-8 flex justify-center items-center">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>

                <!-- Modal Content -->
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        {{ page.props.confirm.message ? page.props.confirm.message : defaults.message }}
                    </h3>
                    <button @click="confirm"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <ModalButton label="No, cancel" @click="cancel" />
                </div>
            </div>
        </div>
    </div>
</template>
