<script setup>
    import { ref } from 'vue';

    defineProps({
        product: Object
    });
    const expanded = ref(false);

    const toggleExpand = () => {
        expanded.value = !expanded.value;
    };
</script>

<template>
    <div
        class="container-primary rounded-xl bg-white p-4 dark:bg-[#1e293b] w-full min-w-fit text-wrap transition duration-1000">
        <div class="flex justify-between gap-4">
            <div class="flex justify-between items-center gap-3" @click="toggleExpand">
                <div
                    class="border min-h-10 min-w-10 h-10 rounded-lg overflow-hidden border-primary-200 dark:border-light">
                    <img v-if="product.images[0]" :src="product.images[0].image_path" alt="Product Image"
                        class="h-full w-full object-cover">
                    <i v-else class="fa-solid fa-image text-2xl text-gray-400"></i>
                </div>
                <p class="leading-4 font-semibold">{{ product.name }}</p>
            </div>
            <div class="flex items-center gap-3 text-primary dark:text-primary-300">
                <i class="bi bi-caret-down-fill cursor-pointer transition duration-500"
                    :class="{ 'rotate-180': expanded }" @click="toggleExpand"></i>
                <i class="fa-solid fa-eye cursor-pointer"></i>
                <i class="bi bi-pencil-square cursor-pointer"></i>
                <i class="bi bi-three-dots-vertical cursor-pointer"></i>
            </div>
        </div>

        <!-- Transition wrapper -->
        <Transition name="expand">
            <div v-if="expanded" class="mt-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span>Category</span>
                    <span class="font-semibold">{{ product.category.name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Price</span>
                    <span class="font-semibold">{{ product.price }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Stock</span>
                    <span class="font-semibold">{{ product.stock }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Last modified</span>
                    <span class="font-semibold">{{ new Date(product.updated_at).toLocaleDateString('en-GB') }}</span>
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
