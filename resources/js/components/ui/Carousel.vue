<script setup>
    import { ref, watch, onMounted } from 'vue';

    defineProps({
        imagesPath: Array,
    });

    const currentSlide = ref(0);

    function imageUrl(path) {
        const imagePlaceholder = '/images/img-placeholder.jpg';
        if (path?.includes('product_images')) {
            return `/storage/${path}`;
        }
        return path || imagePlaceholder;
    }

    function prevSlide() {
        currentSlide.value = currentSlide.value -= 1;
    }

    function nextSlide() {
        currentSlide.value = currentSlide.value += 1;
    }
</script>

<template>
    <div class="relative w-full h-full">
        <!-- Carousel images -->
        <div class="z-40 absolute inset-x-0 top-1/2 -translate-y-1/2 grid grid-flow-col px-2">
            <button @click="prevSlide" v-if="!(currentSlide <= 0)"
                class="bg-white/50 w-10 h-10 text-center hover:bg-white/80 text-black rounded-full justify-self-start">
                <i class="fa-solid fa-angle-left"></i>
            </button>
            <button @click="nextSlide" v-if="!(currentSlide >= imagesPath?.length - 1)"
                class="bg-white/50 w-10 h-10 text-center hover:bg-white/80 text-black rounded-full justify-self-end">
                <i class="fa-solid fa-angle-right"></i>
            </button>
        </div>
        <div class="w-full h-full">
            <img v-if="imagesPath.length" v-for="(path, i) in imagesPath" :key="i" :src="imageUrl(path)"
                class="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out rounded-lg"
                :class="{ 'opacity-100 z-10': i === currentSlide, 'opacity-0 z-0': i !== currentSlide }"
                alt="Product Image" />
            <img v-else src="/images/img-placeholder.jpg"
                class="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out rounded-lg"
                alt="Product Image" />
        </div>

        <!-- Indicators -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
            <button v-for="(path, i) in imagesPath" :key="'dot-' + i" @click="currentSlide = i"
                class="w-3 h-3 rounded-full" :class="i === currentSlide ? 'bg-white' : 'bg-gray-400/60'"></button>
        </div>

        <!-- Controls -->

    </div>
</template>
