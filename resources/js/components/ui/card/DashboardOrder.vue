<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        order: Object
    });

    const image_src = computed(() => {
        let src = props.order.option?.images[0]?.image_path || props.order.product?.images[0]?.image_path
        if (src) {
            return src.includes('product_images')
                ? `/storage/${src}`
                : src;
        }
        return '/images/img-placeholder.jpg';
    });


</script>

<template>
    <Link as="button" :href="route('order.index')"
        class="flex cursor-pointer flex-col items-center justify-center w-full max-w-sm mx-auto relative">
    <button
        class=" absolute rounded px-2 py-1  text-xs font-semibold uppercase transition-colors duration-300 transform  focus:outline-none top-1 right-2 hover:opacity-90"
        :class="{
            'bg-yellow-400 dark:bg-yellow-300 text-slate-700': order.status === 'pending',
            'bg-secondary-500 text-white': order.status === 'processing'
        }">
        {{ order.status }}
    </button>
    <div class="w-full h-64 bg-gray-300 bg-center bg-cover rounded-lg shadow-md"
        :style="`background-image: url(${image_src})`">
    </div>
    <div class="w-56 -mt-10 overflow-hidden bg-white rounded-lg shadow-lg md:w-64 dark:bg-gray-800">
        <h4 class="py-2 font-bold tracking-wide text-center text-gray-800 uppercase dark:text-white">
            {{ order.option?.name || order.product.name }}
        </h4>

        <div class="flex items-center justify-between px-3 py-2 bg-gray-300 dark:bg-gray-700">
            <span class="font-bold text-gray-00 dark:text-gray-200">
                {{
                    order.total_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                }}
            </span>

            <span class="text-xs font-medium">
                <i class="fa fa-xmark"></i>
                {{ order.quantity }}
            </span>
        </div>
    </div>
    </Link>
</template>
