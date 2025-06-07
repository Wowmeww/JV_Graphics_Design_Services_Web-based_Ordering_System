<script setup>
    import { ref } from 'vue';

    const expanded = ref(false);

    const toggleExpand = () => {
        expanded.value = !expanded.value;
    };
</script>

<template>
    <div class="container-primary rounded-xl bg-white p-4 dark:bg-[#1e293b] w-full transition duration-1000">
        <div class="flex justify-between gap-4">
            <div class="flex justify-between items-center gap-3">
                <div class="border h-10 w-10 rounded-lg dark:border-light"></div>
                <p class="leading-4 font-semibold">Lorem ipsum dolor sit amet.</p>
            </div>
            <div class="flex items-center gap-3 text-primary dark:text-primary-300">
                <i class="bi bi-caret-down-fill cursor-pointer" @click="toggleExpand"></i>
                <i class="bi bi-pencil-square cursor-pointer"></i>
                <i class="bi bi-three-dots-vertical cursor-pointer"></i>
            </div>
        </div>

        <!-- Transition wrapper -->
        <Transition name="expand">
            <div v-if="expanded" class="mt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span>Price</span>
                    <span class="font-semibold">400</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Stock</span>
                    <span class="font-semibold">30</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Last modified</span>
                    <span class="font-semibold">04/04/2025</span>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>

    .expand-enter-active,
    .expand-leave-active {
        transition: all 0.4s ease;
        overflow: hidden;
    }

    .expand-enter-from,
    .expand-leave-to {
        max-height: 0;
        opacity: 0;
    }

    .expand-enter-to,
    .expand-leave-from {
        max-height: 500px;
        /* adjust based on expected content height */
        opacity: 1;
    }
</style>
