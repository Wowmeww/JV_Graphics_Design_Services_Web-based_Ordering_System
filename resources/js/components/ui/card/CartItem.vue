<script setup>
    import { router } from '@inertiajs/vue3';
    import { computed } from 'vue';
    const props = defineProps({
        item: Object,
        from: { type: String, default: 'cart' }
    });

    const emit = defineEmits(['close']);
    const image_url = computed(() => {
        let src = (props.item.option || props.item.product)?.images[0]?.image_path;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
        return '/images/avatar-placeholder.webp';
    });

    const model = defineModel({
        type: Array,
        default: () => []
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
            router.get(route('wishlist.edit', {
                wishlistItem: props.item.id
            }));
            return;
        }
        router.get(route('cart.edit', {
            cartItem: props.item.id
        }));
    }
    function handleShow() {
        emit('close');
        router.get(route('shop.show', {
            product: props.item.product.id,
            option: props.item.option?.id || null
        }));
    }

</script>
<template>
    <div
        class="flex relative items-center gap-3 p-3 bg-white dark:bg-gray-50 rounded-lg border border-gray-200 dark:border-gray-300 shadow-xs hover:shadow-md transition-all duration-200 group">
        <!-- Action Buttons -->
        <div
            class="absolute top-2 right-2 text-xs space-x-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
            <button @click.stop="handleShow" type="button"
                class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors">
                <i class="fa-solid fa-eye text-secondary-500 text-sm"></i>
            </button>
            <button @click.stop="handleEdit" type="button"
                class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors">
                <i class="bi bi-pencil-square text-yellow-600 text-sm"></i>
            </button>
            <button @click.stop="handleDelete" type="button"
                class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors">
                <i class="bi bi-trash text-red-500 text-sm"></i>
            </button>
        </div>

        <!-- Checkbox -->
        <input v-model="model" :value="item" type="checkbox"
            class="h-4 w-4 accent-secondary-500 rounded border-gray-300 focus:ring-secondary-400">

        <!-- Product Image -->
        <img :src="image_url" :alt="item.option?.name || item.product.name"
            class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-300 flex-shrink-0">

        <!-- Product Info -->
        <div class="flex-1 min-w-0">
            <h2 class="font-medium text-gray-800 dark:text-gray-900 truncate leading-tight">
                {{ item.option?.name || item.product.name }}
            </h2>
            <div class="text-xs text-gray-500 dark:text-gray-600 mt-0.5">
                {{
                    (item.option?.price || item.product.price).toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                }}
                ×
                <span class="font-medium">{{ item.quantity }}</span>
            </div>
        </div>

        <!-- Total Price -->
        <div class="text-right text-sm font-semibold text-gray-800 dark:text-gray-900 whitespace-nowrap">
            {{
                item.total_amount.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                })
            }}
        </div>
    </div>
</template>