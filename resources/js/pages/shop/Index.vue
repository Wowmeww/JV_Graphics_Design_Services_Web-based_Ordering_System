<script setup>
    import BackgroundImage from '@/components/ui/BackgroundImage.vue';
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
    const perPage = 10;
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

    <BackgroundImage path="/images/background/blob.jpg" class="h-full overflow-y-auto " />
    <div class="flex flex-col lg:flex-row h-full min-h-screen ">
        <ShopFilter :defaults="filter" :categories="categories" @filter="handleFilter" />
        <div ref="scrollArea"
            class="scroll-container  h-full mx-auto grid max-h-screen max-w-7xl flex-1 grid-cols-2 gap-1.5 overflow-y-scroll px-2 pt-8 md:grid-cols-3 md:gap-4 xl:grid-cols-4"
            @scroll.passive="onScroll">
            <ShopItem :filter="filter" v-for="product in products" :key="product.id" :product="product" />

            <div v-if="loading"
                class="absolute inset-x-1/2 inset-y-1/2 inline-grid flex-1 place-content-center text-center text-xl text-white">
                Loading more... <i class="loader-gear"></i>
            </div>
            <div v-if="noMore"
                class="col-span-2 pt-4 pb-8 text-center text-xl text-white/80 md:col-span-3 xl:col-span-4">
                No more products
                <i class="fa-solid fa-martini-glass-empty animate-bounce"></i>
            </div>
        </div>
    </div>
</template>

<style scoped>

    /* HTML: <div class="loader"></div> */
    .loader {
        width: 100px;
        aspect-ratio: 1;
        padding: 10px;
        box-sizing: border-box;
        display: grid;
        background: #fff;
        filter: blur(3px) contrast(7) hue-rotate(290deg);
        mix-blend-mode: darken;
    }

    .loader:before {
        content: '';
        margin: auto;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        color: #ff0909;
        background: currentColor;
        box-shadow:
            -30px 0,
            30px 0,
            0 30px,
            0 -30px;
        animation: l6 1s infinite alternate;
    }

    @keyframes l6 {

        90%,
        100% {
            box-shadow:
                -10px 0,
                10px 0,
                0 10px,
                0 -10px;
            transform: rotate(180deg);
        }
    }

    .loader-gear {
        width: 60px;
        height: 40px;
        position: relative;
        display: inline-block;
        --base-color: #263238;
        /*use your base color*/
    }

    .loader-gear::before {
        content: '';
        left: 0;
        top: 0;
        position: absolute;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #fff;
        background-image:
            radial-gradient(circle 8px at 18px 18px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 18px 0px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 0px 18px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 36px 18px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 18px 36px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 30px 5px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 30px 5px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 30px 30px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 5px 30px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 4px at 5px 5px, var(--base-color) 100%, transparent 0);
        background-repeat: no-repeat;
        box-sizing: border-box;
        animation: rotationBack 3s linear infinite;
    }

    .loader-gear::after {
        content: '';
        left: 35px;
        top: 15px;
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #fff;
        background-image:
            radial-gradient(circle 5px at 12px 12px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 12px 0px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 0px 12px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 24px 12px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 12px 24px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 20px 3px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 20px 3px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 20px 20px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 3px 20px, var(--base-color) 100%, transparent 0),
            radial-gradient(circle 2.5px at 3px 3px, var(--base-color) 100%, transparent 0);
        background-repeat: no-repeat;
        box-sizing: border-box;
        animation: rotationBack 4s linear infinite reverse;
    }

    @keyframes rotationBack {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(-360deg);
        }
    }
</style>
