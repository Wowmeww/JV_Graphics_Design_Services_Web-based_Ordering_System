<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import CartItem from '../card/CartItem.vue';

    defineProps({
        expanded: Boolean
    });
    const page = usePage();
    const cartItems = computed(() => page.props.auth.user?.cart.items || []);
    const emit = defineEmits(['close']);

    function close() {
        emit('close');
    }

    const styleClass = {
        aside: '',
        icon: 'w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white'
    }
</script>

<template>
    <div v-if="expanded"
        class="fixed inset-0 z-20 bg-secondary/20 backdrop-blur-xs transition dark:bg-black/30 dark:backdrop-blur-xs"
        @click="close">
    </div>

    <aside :class="[
        'fixed top-0 right-0 z-30 w-full sm:w-90 h-screen pt-20 transition-transform bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700',
        expanded ? 'translate-x-0' : 'translate-x-full'
    ]" aria-label="Sidebar" id="sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 space-y-2 font-medium">
            <div class="flex justify-between items-center border-b-2 border-slate-300 pb-2">
                <button @click="close" type="button" class="text-2xl h-8 w-8 rounded-full hover:bg-secondary-100/20">
                    <i class="bi bi-x"></i>
                </button>

                <h4 class="font-medium text-lg md:text-xl">
                    Shopping Cart
                    <span v-if="cartItems.length">{{ `(${cartItems.length})` }}</span>
                </h4>
            </div>

            <div class="scroll-auto space-y-2">
                <CartItem @closeCart="close" v-for="(item, i) in cartItems" :item="item" :key="i" />
            </div>
        </div>
    </aside>
</template>