<script setup>
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({
    item: Object,
    from: { type: String, default: 'cart' },
});

const emit = defineEmits(['close']);
const image_url = computed(() => {
    let src = (props.item.option || props.item.product)?.images[0]?.image_path;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return props.item.product?.images[0]?.image_path ? `/storage/${props.item.product?.images[0]?.image_path}` : '/images/img-placeholder.jpg';
});

const model = defineModel({
    type: Array,
    default: () => [],
});

function handleDelete() {
    if (props.from === 'wishlist') {
        router.delete(route('wishlist.destroy', props.item.id));
        return;
    }
    router.delete(route('cart.destroy', props.item.id));
}
function handleEdit() {
    emit('close');
    if (props.from === 'wishlist') {
        router.get(
            route('wishlist.edit', {
                wishlistItem: props.item.id,
            }),
        );
        return;
    }
    router.get(
        route('cart.edit', {
            cartItem: props.item.id,
        }),
    );
}
function handleShow() {
    emit('close');
    router.get(
        route('shop.show', {
            product: props.item.product.id,
            option: props.item.option?.id || null,
        }),
    );
}

const styles = {
    container:
        'flex relative items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 group',

    actionButtons: 'absolute top-2 right-2 text-xs space-x-1.5 -mt-3',

    actionButton: 'p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors',

    viewIcon: 'fa-solid fa-eye text-secondary-500 text-sm',
    editIcon: 'bi bi-pencil-square text-yellow-500 dark:text-yellow-400 text-sm',
    deleteIcon: 'bi bi-trash text-red-500 text-sm',

    checkbox: 'h-4 w-4 accent-secondary-500 rounded border-gray-300 dark:border-gray-600 focus:ring-secondary-400 bg-white dark:bg-gray-800',

    productImage: 'w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0',

    productInfo: 'flex-1 min-w-0',
    productName: 'font-medium text-gray-800 dark:text-gray-100 truncate leading-tight',
    productDetails: 'text-xs text-gray-500 dark:text-gray-400 mt-0.5',
    price: 'font-medium',

    totalPrice: 'text-right text-sm font-semibold text-gray-800 dark:text-gray-100 whitespace-nowrap',
};
</script>

<template>
    <div :class="styles.container">
        <!-- Action Buttons -->
        <div :class="styles.actionButtons">
            <button @click.stop="handleShow" type="button" :class="styles.actionButton">
                <i :class="styles.viewIcon"></i>
            </button>
            <button @click.stop="handleEdit" type="button" :class="styles.actionButton">
                <i :class="styles.editIcon"></i>
            </button>
            <button @click.stop="handleDelete" type="button" :class="styles.actionButton">
                <i :class="styles.deleteIcon"></i>
            </button>
        </div>

        <!-- Checkbox -->
        <input v-model="model" :value="item" type="checkbox" :class="styles.checkbox" />

        <!-- Product Image -->
        <img :src="image_url" :alt="item.option?.name || item.product.name" :class="styles.productImage" />

        <!-- Product Info -->
        <div :class="styles.productInfo">
            <h2 :class="styles.productName">
                {{ item.option?.name || item.product.name }}
            </h2>
            <div :class="styles.productDetails">
                {{
                    Number(item.option?.price || item.product.price).toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                    })
                }}
                ×
                <span :class="styles.price">{{ item.quantity }}</span>
            </div>
        </div>

        <!-- Total Price -->
        <div :class="styles.totalPrice">
            {{
                item.total_amount.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                })
            }}
        </div>
    </div>
</template>
