<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    item: Object,
    from: String,
});

const image_src = computed(() => {
    let src = props.item.option?.images[0]?.image_path || props.item.product?.images[0]?.image_path;
    if (src) {
        return src.includes('product_images') ? `/storage/${src}` : src;
    }
    return '/images/img-placeholder.jpg';
});

function handleOrder() {
    router.get(
        route('order.create', {
            items: [props.item.id],
            from: props.from,
        }),
    );
}

function handleShow() {
    router.get(
        route('shop.show', {
            product: props.item.product.id,
            option: props.item.option?.id || null,
        }),
    );
}

const styleClass = {
    // Main container
    container:
        'group animate__animated animate__fadeInUp max-w-xs max-h-fit cursor-pointer overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:scale-[1.03] active:scale-[0.97] border border-gray-100 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-600 backdrop-blur-sm',

    // Content section
    content: {
        container: 'px-5 py-4',
        title: 'text-lg font-black text-gray-800 dark:text-white flex justify-between items-center hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300',
        quantity:
            'text-xs font-bold bg-gradient-to-r from-primary-500 to-primary-600 text-white px-3 py-1.5 rounded-full shadow-md hover:shadow-lg transition-shadow duration-300',
        description:
            'mt-2 text-sm text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2 border border-gray-100 dark:border-gray-600',
        ellipsis: 'font-black text-primary-500 dark:text-primary-400',
    },

    // Image section
    image: 'object-cover w-full h-48 group-hover:scale-110 transition-transform duration-700 ease-out',

    // Image overlay
    imageOverlay:
        'absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500',

    // Footer section
    footer: {
        container:
            'flex items-center justify-between px-5 py-3 bg-gradient-to-r from-gray-900 to-gray-800 dark:from-gray-900 dark:to-gray-800 border-t border-gray-700',
        price: 'text-lg font-black text-white drop-shadow-lg',
        button: 'group/btn px-4 py-2 text-xs font-black text-white uppercase transition-all duration-300 transform bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-xl hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl border border-green-400/30 hover:border-green-300/50',
        buttonShimmer:
            'absolute inset-0 bg-gradient-to-r from-white/20 via-transparent to-white/10 transform -skew-x-12 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-700',
    },
};
</script>

<template>
    <div @click="handleShow" :class="styleClass.container">
        <div :class="styleClass.content.container">
            <h1 :class="styleClass.content.title">
                <span class="max-w-[70%] truncate">{{ item.option?.name || item.product.name }}</span>
                <span :class="styleClass.content.quantity">
                    <i class="fa-solid fa-xmark mr-1"></i>
                    {{ item.quantity }}
                </span>
            </h1>
            <p :class="styleClass.content.description">
                {{ (item.option?.description || item.product.description)?.slice(0, 100) }}
                <span v-if="(item.option?.description || item.product.description)?.length >= 100" :class="styleClass.content.ellipsis">...</span>
            </p>
        </div>

        <div class="relative overflow-hidden">
            <img :class="styleClass.image" :src="image_src" :alt="`${item.option?.name || item.product.name} image`" />
            <div :class="styleClass.imageOverlay"></div>
        </div>

        <div :class="styleClass.footer.container">
            <h4 :class="styleClass.footer.price">
                {{
                    item.total_amount.toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                    })
                }}
            </h4>
            <button @click.stop.prevent="handleOrder" :class="styleClass.footer.button">
                <div :class="styleClass.footer.buttonShimmer"></div>
                <span class="relative z-10 flex items-center gap-2">
                    Order
                    <i class="fa-solid fa-bag-shopping text-xs transition-transform duration-300 group-hover/btn:translate-x-0.5"></i>
                </span>
            </button>
        </div>
    </div>
</template>
