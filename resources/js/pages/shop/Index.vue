<script setup>
import ShopItem from '@/components/ui/card/ShopItem.vue';
import ShopFilter from '@/components/ui/ShopFilter.vue';
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';

const props = defineProps({
    categories: Array,
    user: Object,
    filter: Object,
});

const products = ref([]);
const page = ref(1);
const perPage = 8;
const loading = ref(false);
const noMore = ref(false);
const scrollArea = ref(null);

const filter = reactive({ ...props.filter });

const fetchProducts = async (filter) => {
    if (loading.value || noMore.value) return;
    loading.value = true;

    try {
        const res = await axios.get(route('shop.fetch'), {
            params: {
                ...filter,
                page: page.value,
                per_page: perPage,
            },
        });

        const data = res.data;
        if (data.data.length === 0) {
            noMore.value = true;
        } else {
            products.value.push(...data.data);
            page.value++;
        }
    } catch (err) {
        console.error('Fetch error:', err);
    }

    loading.value = false;
};

const onScroll = () => {
    const el = scrollArea.value;
    if (el.scrollTop + el.clientHeight >= el.scrollHeight - 10) {
        fetchProducts(filter);
    }
};

onMounted(() => {
    handleFilter(props.filter);
});

function handleFilter(filters) {
    products.value = [];
    page.value = 1;
    noMore.value = false;
    Object.assign(filter, filters);
    fetchProducts(filters);
}
</script>

<template>
    <Head title="Shop" />
    <!-- Enhanced Shop Layout -->
    <div class="relative flex min-h-screen flex-col bg-gray-50 lg:flex-row dark:bg-gray-900">
        <!-- Filter Sidebar -->
        <ShopFilter :defaults="filter" :categories="categories" @filter="handleFilter" />
        <!-- Main Content Area -->
        <div class="flex max-h-screen flex-1 overflow-hidden">
            <!-- Products Grid -->
            <div
                ref="scrollArea"
                class="scroll-container mx-auto max-h-full max-w-7xl overflow-y-auto px-4 py-6 sm:px-6 lg:px-8"
                @scroll.passive="onScroll"
            >
                <!-- Products Grid -->
                <div class="grid grid-cols-2 gap-1 pt-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-4">
                    <ShopItem
                        :filter="filter"
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        class="transform transition-all duration-300 hover:-translate-y-2"
                    />
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="mt-12 flex flex-col items-center justify-center space-y-4 py-8">
                    <!-- Enhanced Loader -->
                    <div class="relative">
                        <div
                            class="border-primary-200 border-t-primary-600 dark:border-primary-800 dark:border-t-primary-400 h-16 w-16 animate-spin rounded-full border-4"
                        ></div>
                        <div
                            class="border-t-secondary-500 absolute inset-0 h-16 w-16 animate-spin rounded-full border-4 border-transparent opacity-70"
                        ></div>
                    </div>
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">Loading more products...</p>
                </div>

                <!-- End of Results -->
                <div v-if="noMore && products.length > 0" class="py-12 text-center">
                    <div class="inline-flex items-center gap-3 rounded-2xl bg-white px-6 py-4 shadow-sm dark:bg-gray-800">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-medium text-gray-700 dark:text-gray-300">
                            You've reached the end! {{ products.length }} products shown.
                        </span>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!loading && products.length === 0" class="text-center">
                    <div class="mx-auto max-w-md">
                        <svg class="mx-auto h-24 w-24 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                            />
                        </svg>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">No products found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your filters to see more results.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Smooth scrolling */
.scroll-container {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
.scroll-container::-webkit-scrollbar {
    width: 6px;
}

.scroll-container::-webkit-scrollbar-track {
    background: transparent;
}

.scroll-container::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.scroll-container::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Dark mode scrollbar */
@media (prefers-color-scheme: dark) {
    .scroll-container::-webkit-scrollbar-thumb {
        background: #4b5563;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
}

/* Fade in animation for new items */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Apply animation to product items */
.grid > * {
    animation: fadeInUp 0.5s ease-out;
}
</style>
