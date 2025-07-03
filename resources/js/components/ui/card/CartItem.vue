<script setup>
    import { router } from '@inertiajs/vue3';
    const props = defineProps({
        item: Object,
        from: { type: String, default: 'cart' }
    });

    const emit = defineEmits(['close']);
    function image_url() {
        // const path = props.item.option?.images[0].image_path || props.item.product?.images[0].image_path;
        const path = props.item.option?.images[0]?.image_path || props.item.product?.images[0]?.image_path;
        return (path?.includes('storage') ? `/storage/${path}` : path) || '/images/img-placeholder.jpg';
    }

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
        class="flex relative items-center gap-3 p-2 bg-white dark:bg-slate-100 rounded-xl border shadow-sm hover:shadow-md transition text-sm">
        <div class="absolute top-1.5 right-1.5 text-xs space-x-2">
            <button @click="handleShow" type="button">
                <i class="fa-solid fa-eye text-secondary-200"></i>
            </button>
            <button @click="handleEdit" type="button">
                <i class="bi bi-pencil-square text-yellow-900/80"></i>
            </button>
            <button @click="handleDelete" type="button">
                <i class="bi bi-trash  text-red-600"></i>
            </button>
        </div>
        <input v-model="model" :value="item" type="checkbox" class="accent-secondary" />
        <!-- Product Image -->
        <img :src="image_url()" alt="Product" class="w-12 h-12 rounded-lg object-cover border">

        <!-- Info -->
        <div class="flex-1">
            <div class="font-medium text-gray-800 truncate text-wrap leading-3">
                {{ item.option?.name || item.product.name }}
            </div>
            <!-- <div class="text-xs text-gray-500">
                Option: <span class="font-medium text-gray-700">Variant</span>
            </div> -->
            <div class="text-xs text-gray-500 mt-0.5">
                {{
                    (item.option?.price || item.product.price).toLocaleString('en-PH', {
                        style: 'currency',
                        currency: 'PHP'
                    })
                }}
                ×
                {{ item.quantity }}
            </div>
        </div>

        <!-- Total -->
        <div class="text-right text-xs font-semibold text-gray-700">
            {{
                item.total_amount.toLocaleString('en-PH', {
                    style: 'currency',
                    currency: 'PHP'
                })
            }}
        </div>

        <!-- <div
            class="border text-xs absolute w-20 bottom-1.5 right-1.5 gap-1 border-dark/25 text-dark  py-0.5 overflow-hidden h-4  flex justify-between items-center rounded-2xl">
            <button class="bg-secondary/20 hover:opacity-60 text-dark px-1">
                <i class="fa-solid fa-minus"></i>
            </button>
            <input type="text" class="font-medium focus:outline-0 text-center text-dark  py-0.5 w-full " />

            <button class="bg-secondary/20 hover:opacity-60 text-dark px-1">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div> -->
    </div>

</template>
