<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    imagesPath: Array,
    autoPlay: {
        type: Boolean,
        default: true,
    },
    autoPlayInterval: {
        type: Number,
        default: 5000,
    },
});

const currentSlide = ref(0);
const loading = ref(false);
const autoPlayInterval = ref(null);
const touchStartX = ref(0);
const touchEndX = ref(0);

function imageUrl(path) {
    const imagePlaceholder = '/images/img-placeholder.jpg';
    if (!path) return imagePlaceholder;
    if (path.includes('https') || path.includes('http') || path.includes('data:')) {
        return path;
    }
    if (path.includes('product_images')) {
        return `/storage/${path}`;
    }
    return path || imagePlaceholder;
}

function prevSlide() {
    if (props.imagesPath?.length <= 1) return;
    currentSlide.value = currentSlide.value === 0 ? props.imagesPath.length - 1 : currentSlide.value - 1;
}

function nextSlide() {
    if (props.imagesPath?.length <= 1) return;
    currentSlide.value = currentSlide.value === props.imagesPath.length - 1 ? 0 : currentSlide.value + 1;
}

function goToSlide(index) {
    if (props.imagesPath?.length <= 1) return;
    currentSlide.value = index;
}

function handleImageLoad() {
    loading.value = false;
}

function handleImageError(event) {
    loading.value = false;
    event.target.src = '/images/img-placeholder.jpg';
}

function startAutoPlay() {
    if (props.autoPlay && props.imagesPath?.length > 1) {
        autoPlayInterval.value = setInterval(() => {
            nextSlide();
        }, props.autoPlayInterval);
    }
}

function stopAutoPlay() {
    if (autoPlayInterval.value) {
        clearInterval(autoPlayInterval.value);
        autoPlayInterval.value = null;
    }
}

// Touch swipe handling
function handleTouchStart(event) {
    touchStartX.value = event.changedTouches[0].screenX;
    stopAutoPlay();
}

function handleTouchEnd(event) {
    touchEndX.value = event.changedTouches[0].screenX;
    handleSwipe();
    startAutoPlay();
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX.value - touchEndX.value;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextSlide(); // Swipe left
        } else {
            prevSlide(); // Swipe right
        }
    }
}

// Watch for imagesPath changes
watch(
    () => props.imagesPath,
    (newImages) => {
        if (newImages?.length > 0) {
            currentSlide.value = 0;
            stopAutoPlay();
            startAutoPlay();
        }
    },
);

onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});

const styleClass = {
    // Main container
    container: 'relative w-full h-full group select-none',

    // Navigation buttons
    navigation: {
        container: 'z-20 absolute inset-x-0 top-1/2 -translate-y-1/2 grid grid-flow-col px-3 sm:px-4 transition-all duration-300',
        button: 'bg-white/90 backdrop-blur-sm w-6 h-6 cursor-pointer  md:h-8  md:w-8 text-center active:bg-white active:scale-105 text-gray-800 rounded-lg  shadow-lg sm:shadow-2xl border border-white/50 transition-all duration-200 active:scale-95 flex items-center justify-center touch-manipulation',
        prev: 'justify-self-start',
        next: 'justify-self-end',
        icon: 'fa-solid text-xs sm:text-base md:text-sm font-bold',
    },

    // Image container
    imageContainer: 'w-full h-full relative overflow-hidden touch-pan-y',

    // Images
    image: {
        base: 'absolute top-0 left-0 w-full h-full object-cover transition-all duration-400 ease-out rounded sm:rounded-lg touch-none',
        active: 'opacity-100 z-10 scale-100',
        inactive: 'opacity-0 z-0 scale-105',
        placeholder: 'absolute top-0 left-0 w-full h-full object-cover bg-gradient-to-br from-gray-200 to-gray-300 rounded sm:rounded-lg',
    },

    // Indicators
    indicators: {
        container:
            'absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 flex space-x-1.5 sm:space-x-2 z-20 backdrop-blur-sm bg-black/40 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg sm:rounded-xl border border-white/20',
        dot: 'w-1 h-1 sm:w-2.5 sm:h-2.5  rounded-full transition-all duration-300 cursor-pointer active:scale-125 touch-manipulation',
        active: 'bg-white shadow scale-125',
        inactive: 'bg-white/60 active:bg-white/80',
    },

    // Slide counter
    counter: {
        container:
            'absolute top-2 left-2 sm:top-3 sm:left-3 z-20 backdrop-blur-sm bg-black/60 text-white px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-lg sm:rounded-xl border border-white/20 text-[10px] sm:text-xs',
        text: 'font-semibold tracking-wide',
    },

    // Loading state
    loading: {
        container: 'absolute inset-0 z-30 flex items-center justify-center bg-gray-100/90 backdrop-blur-sm rounded sm:rounded-lg',
        spinner: 'w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 border-2 sm:border-2 md:border-3 border-gray-300 border-t-blue-500 rounded-full animate-spin',
    },

    // Mobile overlay for better touch targets
    mobileOverlay: {
        container: 'absolute inset-0 z-30 md:hidden flex',
        left: 'flex-1 touch-pinch-zoom',
        right: 'flex-1 touch-pinch-zoom',
    },
};
</script>

<template>
    <div
        :class="styleClass.container"
        @mouseenter="stopAutoPlay"
        @mouseleave="startAutoPlay"
        @touchstart="handleTouchStart"
        @touchend="handleTouchEnd"
    >
        <!-- Loading State -->
        <div v-if="loading" :class="styleClass.loading.container">
            <div :class="styleClass.loading.spinner"></div>
        </div>

        <!-- Navigation buttons - Always visible on mobile -->
        <div v-if="imagesPath?.length > 1" :class="styleClass.navigation.container">
            <button @click.prevent="prevSlide" :class="[styleClass.navigation.button, styleClass.navigation.prev]" aria-label="Previous image">
                <i :class="`${styleClass.navigation.icon} fa-chevron-left`"></i>
            </button>
            <button @click.prevent="nextSlide" :class="[styleClass.navigation.button, styleClass.navigation.next]" aria-label="Next image">
                <i :class="`${styleClass.navigation.icon} fa-chevron-right`"></i>
            </button>
        </div>

        <!-- Mobile touch overlay for swipe areas -->
        <div v-if="imagesPath?.length > 1" :class="styleClass.mobileOverlay.container">
            <div :class="styleClass.mobileOverlay.left" @click.prevent="prevSlide"></div>
            <div :class="styleClass.mobileOverlay.right" @click.prevent="nextSlide"></div>
        </div>

        <!-- Image container -->
        <div :class="styleClass.imageContainer">
            <template v-if="imagesPath?.length">
                <img
                    v-for="(path, i) in imagesPath"
                    :key="i"
                    :src="imageUrl(path)"
                    :class="[styleClass.image.base, i === currentSlide ? styleClass.image.active : styleClass.image.inactive]"
                    :alt="`Product image ${i + 1}`"
                    @load="handleImageLoad"
                    @error="handleImageError"
                    loading="lazy"
                    draggable="false"
                />
            </template>
            <img v-else src="/images/img-placeholder.jpg" :class="styleClass.image.placeholder" alt="No product image available" draggable="false" />
        </div>

        <!-- Slide counter -->
        <div v-if="imagesPath?.length > 1" :class="styleClass.counter.container">
            <span :class="styleClass.counter.text"> {{ currentSlide + 1 }} / {{ imagesPath.length }} </span>
        </div>

        <!-- Indicators -->
        <div v-if="imagesPath?.length > 1" :class="styleClass.indicators.container">
            <button
                v-for="(path, i) in imagesPath"
                :key="'dot-' + i"
                @click.prevent="goToSlide(i)"
                :class="[styleClass.indicators.dot, i === currentSlide ? styleClass.indicators.active : styleClass.indicators.inactive]"
                :aria-label="`Go to slide ${i + 1}`"
                :aria-current="i === currentSlide"
            ></button>
        </div>

        <!-- Auto-play indicator -->
        <div v-if="autoPlay && imagesPath?.length > 1" class="absolute top-2 left-2 z-20 sm:top-3 sm:left-3">
            <div class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-400 sm:h-2 sm:w-2" title="Auto-play enabled"></div>
        </div>
    </div>
</template>
