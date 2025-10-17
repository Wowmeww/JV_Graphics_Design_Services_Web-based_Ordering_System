<script setup>
import { ref } from 'vue';

const props = defineProps({
    product: Object,
});

const is_option = Boolean(props.product.type === 'Variant');

// console.log(is_option);

const product = ref({ ...props.product });
if (is_option) {
    product.value.category = props.product.product.category;
}

const expanded = ref(false);

const toggleExpand = () => {
    expanded.value = !expanded.value;
};
function imageUrl(path) {
    if (path.includes('product_images')) {
        return `/storage/${path}`;
    }

    return path;
}
</script>

<template>
    <!-- {{ console.dir(product) }} -->
    <div
        class="container-primary border-secondary w-full min-w-fit rounded-xl border-2 bg-white p-4 text-wrap transition duration-1000 dark:border-white/10 dark:bg-[#1e293b]"
        :class="{ 'dark:!bg-secondary-100/10 !bg-secondary-100/40': expanded }"
    >
        <div class="flex justify-between gap-4">
            <div class="flex items-center justify-between gap-3" @click="toggleExpand">
                <div class="border-primary-200 dark:border-light h-12 min-h-12 w-12 min-w-12 overflow-hidden rounded-lg border">
                    <img
                        v-if="product.images[0]"
                        :src="imageUrl(product.images[0].image_path)"
                        alt="Product Image"
                        class="h-full w-full object-cover"
                    />
                    <i v-else class="fa-solid fa-image text-2xl text-gray-400"></i>
                </div>
                <p class="leading-4 font-semibold">
                    {{ product.name }}
                </p>
            </div>
            <div class="text-primary dark:text-primary-300 flex items-center gap-3">
                <i class="bi bi-caret-down-fill cursor-pointer transition duration-500" :class="{ 'rotate-180': expanded }" @click="toggleExpand"></i>
                <Link
                    :href="
                        is_option
                            ? route('product.option.show', { product: props.product.product, option: props.product })
                            : route('product.show', { product: props.product })
                    "
                >
                    <i class="fa-solid fa-eye cursor-pointer"></i>
                </Link>
                <Link
                    :href="
                        is_option
                            ? route('product.option.edit', { product: props.product.product, option: props.product })
                            : route('product.edit', { product: props.product })
                    "
                >
                    <i class="bi bi-pencil-square cursor-pointer"></i>
                </Link>
                <i class="bi bi-three-dots-vertical cursor-pointer"></i>
            </div>
        </div>

        <!------- Transition wrapper --------------------------------------------------->
        <Transition name="expand">
            <div v-if="expanded" class="mt-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span>Category</span>
                    <span class="font-semibold">{{ product.category.name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Price</span>
                    <span class="font-semibold">{{ product.price }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Type</span>
                    <span class="font-semibold">{{ product.type }}</span>
                </div>
                <div v-if="product.show_size" class="flex items-center justify-between">
                    <span>Size</span>
                    <span class="font-semibold">{{ product.show_size }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Stock</span>
                    <span class="font-semibold">{{ product.stock }}</span>
                </div>
                <div class="flex items-center justify-between">
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
