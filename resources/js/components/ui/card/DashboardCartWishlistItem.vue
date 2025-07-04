<script setup>
    import { computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    const props = defineProps({
        item: Object,
        from: String
    });

    const image_src = computed(() => {
        let src = props.item.option?.images[0]?.image_path || props.item.product?.images[0]?.image_path;
        if (src) {
            return src.includes('product_images')
                ? `/storage/${src}`
                : src;
        }
        return '/images/img-placeholder.jpg';
    });

    function handleOrder() {
        router.get(route('order.create', {
            items: [props.item.id],
            from: props.from
        }));
    }

    function handleShow() {
        router.get(route('shop.show', {
            product: props.item.product.id,
            option: props.item.option?.id || null
        }));
    }
</script>

<template>
    <!-- {{ item }} -->
    <div @click="handleShow"
        class="max-w-xs max-h-fit cursor-pointer overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <div class="px-4 py-2">
            <h1 class="text-xl font-bold text-gray-800 uppercase dark:text-white flex justify-between hover:underline">
                {{ item.option?.name || item.product.name }}

                <span class="text-sm">
                    <i class="fa-solid fa-xmark"></i>
                    {{ item.quantity }}
                </span>
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 hover:underline">
                {{ (item.option?.description || item.product.description)?.slice(0, 100) }}
                <span v-if="(item.option?.description || item.product.description)?.length >= 100">...</span>
            </p>
        </div>

        <img class="object-cover w-full h-48 mt-2" :src="image_src" alt="NIKE AIR">

        <div class="flex items-center justify-between px-4 py-2 bg-gray-900">
            <h4 class="text-md font-bold text-white">
                {{
                    item.total_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                }}
            </h4>
            <button @click.stop.prevent="handleOrder"
                class="px-2 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-300 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none">
                Order
            </button>
        </div>
    </div>
</template>