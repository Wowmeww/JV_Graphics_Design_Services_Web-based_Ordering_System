<script setup>
import BackgroundImage from '@/components/ui/BackgroundImage.vue';
import LargePill from '@/components/ui/buttons/LargePill.vue';
import LargeCategoryCard from '@/components/ui/card/LargeCategoryCard.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    products: Object,
    categories: Array,
});

const settings = computed(() => usePage().props.settings);

// Carousel references and state
const carousel = ref(null);
const currentSlide = ref(0);
const autoScroll = ref(null);
const isHovering = ref(false);
const isAnimating = ref(false);
const touchStartX = ref(0);
const touchEndX = ref(0);
const progress = ref(0);
const progressInterval = ref(null);
const totalSlides = computed(() => (props.products ? Object.keys(props.products).length : 0));
const rotationSpeed = 5000;

// Enhanced scroll function with animation lock
function scroll(direction) {
    if (!carousel.value || !props.products || isAnimating.value) return;

    isAnimating.value = true;

    let newSlide;

    if (direction === 'left') {
        newSlide = currentSlide.value === 0 ? totalSlides.value - 1 : currentSlide.value - 1;
    } else {
        newSlide = currentSlide.value === totalSlides.value - 1 ? 0 : currentSlide.value + 1;
    }

    goToSlide(newSlide);

    setTimeout(() => {
        isAnimating.value = false;
    }, 700);
}

// Enhanced goToSlide
function goToSlide(slideIndex) {
    if (!carousel.value || !props.products) return;

    const scrollAmount = carousel.value.clientWidth * slideIndex;
    carousel.value.scrollTo({
        left: scrollAmount,
        behavior: 'smooth',
    });
    currentSlide.value = slideIndex;
    progress.value = 0;
}

// Touch handling for mobile swipe
function handleTouchStart(e) {
    touchStartX.value = e.touches[0].clientX;
}

function handleTouchMove(e) {
    touchEndX.value = e.touches[0].clientX;
}

function handleTouchEnd() {
    if (!touchStartX.value || !touchEndX.value) return;

    const diff = touchStartX.value - touchEndX.value;
    const minSwipeDistance = 50;

    if (Math.abs(diff) > minSwipeDistance) {
        if (diff > 0) {
            scroll('right');
        } else {
            scroll('left');
        }
    }

    touchStartX.value = 0;
    touchEndX.value = 0;
}

// Keyboard navigation
function handleKeydown(e) {
    if (e.key === 'ArrowLeft') {
        scroll('left');
    } else if (e.key === 'ArrowRight') {
        scroll('right');
    } else if (e.key >= '1' && e.key <= '9') {
        const slideIndex = parseInt(e.key) - 1;
        if (slideIndex < totalSlides.value) {
            goToSlide(slideIndex);
        }
    }
}

// Progress bar for auto-rotation
function startProgressBar() {
    if (progressInterval.value) clearInterval(progressInterval.value);

    progress.value = 0;
    progressInterval.value = setInterval(() => {
        if (!isHovering.value) {
            progress.value += 100 / (rotationSpeed / 100);
            if (progress.value >= 100) {
                progress.value = 0;
            }
        }
    }, 100);
}

function setupAutoScroll() {
    autoScroll.value = setInterval(() => {
        if (carousel.value && !isHovering.value && props.products && !isAnimating.value) {
            scroll('right');
        }
    }, rotationSpeed);
    startProgressBar();
}

// Pause auto-scroll on hover
function pauseAutoScroll() {
    isHovering.value = true;
    if (progressInterval.value) {
        clearInterval(progressInterval.value);
    }
}

function resumeAutoScroll() {
    isHovering.value = false;
    startProgressBar();
}

// Update current slide based on scroll position
function updateCurrentSlide() {
    if (!carousel.value || !props.products) return;
    const scrollAmount = carousel.value.clientWidth;
    const newSlide = Math.round(carousel.value.scrollLeft / scrollAmount);

    if (newSlide !== currentSlide.value) {
        currentSlide.value = newSlide;
        progress.value = 0;
    }
}

function handleScroll() {
    updateCurrentSlide();
}

onMounted(() => {
    if (carousel.value && props.products) {
        carousel.value.addEventListener('scroll', handleScroll);
        setupAutoScroll();
        window.addEventListener('keydown', handleKeydown);
        carousel.value.addEventListener('touchstart', handleTouchStart);
        carousel.value.addEventListener('touchmove', handleTouchMove);
        carousel.value.addEventListener('touchend', handleTouchEnd);
    }
});

onUnmounted(() => {
    if (carousel.value) {
        carousel.value.removeEventListener('scroll', handleScroll);
        carousel.value.removeEventListener('touchstart', handleTouchStart);
        carousel.value.removeEventListener('touchmove', handleTouchMove);
        carousel.value.removeEventListener('touchend', handleTouchEnd);
    }
    if (autoScroll.value) clearInterval(autoScroll.value);
    if (progressInterval.value) clearInterval(progressInterval.value);
    window.removeEventListener('keydown', handleKeydown);
});

const styles = {
    // Main container
    container: 'relative overflow-hidden',

    // Enhanced Hero Section
    hero: {
        container: 'relative flex h-screen min-h-[800px] w-full items-center justify-center text-center overflow-hidden',
        background: 'absolute inset-0 z-0 h-full w-full',
        overlay: 'absolute inset-0 z-0 bg-gradient-to-br from-gray-900/80 via-purple-900/60 to-blue-900/80',
        content: 'relative z-10 flex w-full max-w-7xl flex-col items-center gap-y-8 px-6 py-16 text-white',
        title: 'text-5xl md:text-7xl lg:text-8xl font-black leading-tight bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent',
        subtitle: 'text-xl md:text-2xl lg:text-3xl font-medium text-gray-200 max-w-3xl leading-relaxed',
        button: 'pt-8 transform transition-transform duration-500 hover:scale-105',
        pill: 'from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 hover:shadow-3xl transform bg-gradient-to-r shadow-2xl transition-all duration-500',
    },

    // Enhanced Products Section
    products: {
        section: 'relative py-20 lg:py-32 px-4',
        background: 'absolute inset-0 z-0 h-full w-full',
        title: 'text-4xl md:text-5xl lg:text-6xl font-black text-center mb-6 bg-gradient-to-r from-primary-500 via-secondary-500 to-accent-500 bg-clip-text text-transparent',
        subtitle: 'text-lg md:text-xl text-gray-600 dark:text-gray-300 text-center max-w-2xl mx-auto mb-16',
    },

    // Categories Section
    categories: {
        section: 'relative lg:py-24 px-4 bg-gray-50 dark:bg-gray-800',
        title: 'text-3xl md:text-4xl lg:text-5xl font-black text-center mb-6 bg-gradient-to-r from-primary-600 via-secondary-600 to-accent-600 bg-clip-text text-transparent',
        grid: 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-7xl mx-auto',
        categoryCard:
            'group bg-white dark:bg-gray-700 rounded-2xl p-6 text-center shadow-lg hover:shadow-2xl transform transition-all duration-500 hover:scale-105 border border-gray-100 dark:border-gray-600',
        categoryIcon:
            'from-primary-500 to-secondary-500 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-r text-2xl font-bold text-white',
        categoryName: 'text-lg font-semibold text-gray-800 dark:text-white mt-4',
        categoryCount: 'text-sm text-gray-500 dark:text-gray-400',
    },

    // Enhanced Carousel styles
    carousel: {
        container:
            'relative overflow-hidden group lg:w-4xl md:w-3xl sm:w-2xl mx-auto bg-white/5 dark:bg-gray-900/5 backdrop-blur-sm rounded-3xl border border-white/10 dark:border-gray-600/10 shadow-2xl',
        track: 'scrollbar-hide flex snap-x snap-mandatory overflow-x-auto scroll-smooth',
        item: 'w-full flex-shrink-0 transform snap-center transition-all duration-700 px-8 py-6',
        itemActive: 'scale-100 opacity-100',
        itemInactive: 'scale-95 opacity-40',
        arrows: 'absolute bg-gradient-to-br from-primary-500 to-secondary-500 dark:from-primary-400 dark:to-secondary-400 top-1/2 -translate-y-1/2 rounded-2xl dark:bg-gray-800/95 p-4 shadow-2xl hover:shadow-3xl transition-all duration-300 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 hover:scale-110  border border-gray-100 dark:border-gray-600',
        leftArrow: 'left-4 lg:left-6',
        rightArrow: 'right-4 lg:right-6',
        arrowIcon: 'text-xl',
        progressContainer: 'absolute top-0 left-0 h-1 w-full rounded-t-3xl bg-gray-200 dark:bg-gray-700',
        progressBar: 'absolute bottom-0 left-0 h-1 bg-gradient-to-r from-primary-500 to-secondary-500 transition-all duration-100 ease-linear',
        overlay:
            'absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-transparent pointer-events-none from-white/5 dark:from-gray-900/5',
        indicators: 'flex justify-center mt-8 space-x-3',
        indicator: 'relative flex h-12 w-12 items-center justify-center rounded-xl border-2 font-semibold transition-all duration-300',
        indicatorActive: 'from-primary-500 to-secondary-500 border-primary-500 scale-110 bg-gradient-to-br text-white shadow-lg',
        indicatorInactive:
            'border-gray-200 bg-white text-gray-600 hover:scale-105 hover:bg-gray-50 hover:shadow-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600',
        controlsContainer: 'mt-8 space-y-4',
        statusBar: 'flex flex-col items-center justify-between gap-4 px-4 sm:flex-row',
        slideCounter: 'flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400',
        currentSlide: 'bg-primary-500 rounded-full px-3 py-1 text-sm font-bold text-white',
        slideSeparator: 'text-gray-400',
        totalSlides: 'font-bold text-gray-800 dark:text-gray-200',
        rotationStatus: 'flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400',
        statusDot: 'h-2 w-2 rounded-full transition-all duration-300',
        statusDotActive: 'animate-pulse bg-green-500',
        statusDotPaused: 'bg-yellow-500',
        controlButtons: 'flex items-center gap-2',
        controlButton: 'rounded-lg bg-gray-100 p-2 transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600',
        playPauseButton:
            'rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600',
        keyboardHint: 'text-center text-xs text-gray-400 dark:text-gray-500',
    },

    // Loading and Empty States
    states: {
        loading: 'flex h-64 items-center justify-center',
        spinner: 'h-12 w-12 animate-spin rounded-full border-b-2 border-primary-500',
        empty: 'py-20 text-center',
        emptyTitle: 'text-2xl font-semibold text-gray-600 dark:text-gray-300',
        emptyText: 'mt-2 text-gray-500 dark:text-gray-400',
    },
};
</script>

<template>
    <Head title="Welcome" />

    <div :class="styles.container">
        <!-- Enhanced Hero Section -->
        <section :class="styles.hero.container">
            <!-- Background with overlay -->
            <div :class="styles.hero.overlay"></div>
            <BackgroundImage v-cloak :class="styles.hero.background" />

            <!-- Hero content -->
            <div :class="styles.hero.content">
                <h1 :class="styles.hero.title">
                    {{ settings.landing_page_title }}
                </h1>
                <h2 :class="styles.hero.subtitle">
                    {{ settings.landing_page_subtitle }}
                </h2>
                <Link :href="route('shop.index')" :class="styles.hero.button">
                    <LargePill label="View All Products" :class="styles.hero.pill" />
                </Link>
            </div>
        </section>

        <!-- Categories Section -->
        <section v-if="categories && categories.length" :class="styles.categories.section">
            <div class="relative z-10">
                <h3 :class="styles.categories.title">Shop by Category</h3>
                <div :class="styles.categories.grid">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('shop.index', { category: category.slug || category.name })"
                        :class="styles.categories.categoryCard"
                    >
                        <div class="flex flex-col items-center">
                            <div :class="styles.categories.categoryIcon">
                                {{ category.name.charAt(0) }}
                            </div>
                            <h4 :class="styles.categories.categoryName">{{ category.name }}</h4>
                            <span :class="styles.categories.categoryCount">{{ category.products_count || 0 }} products</span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <section v-if="products && Object.keys(products).length" :class="styles.products.section">
            <BackgroundImage v-cloak path="images/background/blob.jpg" :class="styles.products.background" />

            <!-- Section content -->
            <div class="relative z-10">
                <div class="-mt-10 pb-4">
                    <h3 :class="styles.products.title">Featured Products & Services</h3>
                </div>

                <!-- Ultra Enhanced Carousel -->
                <div
                    :class="styles.carousel.container"
                    @mouseenter="pauseAutoScroll"
                    @mouseleave="resumeAutoScroll"
                    tabindex="0"
                    @keydown="handleKeydown"
                >
                    <!-- Progress Bar -->
                    <div :class="styles.carousel.progressContainer">
                        <div :class="styles.carousel.progressBar" :style="{ width: `${progress}%` }"></div>
                    </div>

                    <!-- Carousel Track -->
                    <div ref="carousel" :class="styles.carousel.track">
                        <LargeCategoryCard
                            v-for="(product, i) in products"
                            :key="i"
                            :product="product"
                            :variant="Number(i)"
                            :class="[styles.carousel.item, i === currentSlide ? styles.carousel.itemActive : styles.carousel.itemInactive]"
                            :tabindex="i === currentSlide ? 0 : -1"
                        />
                    </div>

                    <!-- Enhanced Navigation Arrows -->
                    <button @click="scroll('left')" aria-label="Previous product" :class="[styles.carousel.arrows, styles.carousel.leftArrow]">
                        <i class="fa-solid fa-chevron-left" :class="styles.carousel.arrowIcon" aria-hidden="true"></i>
                    </button>

                    <button @click="scroll('right')" aria-label="Next product" :class="[styles.carousel.arrows, styles.carousel.rightArrow]">
                        <i class="fa-solid fa-chevron-right" :class="styles.carousel.arrowIcon" aria-hidden="true"></i>
                    </button>

                    <!-- Overlay gradients -->
                    <div :class="styles.carousel.overlay" />
                </div>

                <!-- Enhanced Carousel Controls -->
                <div :class="styles.carousel.controlsContainer">
                    <!-- Indicators with numbers -->
                    <div :class="styles.carousel.indicators">
                        <button
                            v-for="index in totalSlides"
                            :key="index"
                            @click="goToSlide(index - 1)"
                            :class="[
                                styles.carousel.indicator,
                                currentSlide === index - 1 ? styles.carousel.indicatorActive : styles.carousel.indicatorInactive,
                            ]"
                            :aria-label="`Go to product ${index}`"
                            :aria-current="currentSlide === index - 1"
                        >
                            {{ index }}
                        </button>
                    </div>

                    <!-- Enhanced Status Bar -->
                    <div :class="styles.carousel.statusBar">
                        <div class="flex items-center gap-4">
                            <!-- Slide Counter -->
                            <div :class="styles.carousel.slideCounter">
                                <span :class="styles.carousel.currentSlide">
                                    {{ currentSlide + 1 }}
                                </span>
                                <span :class="styles.carousel.slideSeparator">of</span>
                                <span :class="styles.carousel.totalSlides">{{ totalSlides }}</span>
                            </div>

                            <!-- Auto-rotation Status -->
                            <div :class="styles.carousel.rotationStatus">
                                <div class="flex items-center gap-1">
                                    <div
                                        :class="[
                                            styles.carousel.statusDot,
                                            isHovering ? styles.carousel.statusDotPaused : styles.carousel.statusDotActive,
                                        ]"
                                    ></div>
                                    <span>{{ isHovering ? 'Paused' : 'Auto-rotating' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Control Buttons -->
                        <div :class="styles.carousel.controlButtons">
                            <button @click="scroll('left')" :class="styles.carousel.controlButton" aria-label="Previous">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button
                                @click="isHovering = !isHovering"
                                :class="styles.carousel.playPauseButton"
                                :aria-label="isHovering ? 'Resume auto-rotation' : 'Pause auto-rotation'"
                            >
                                {{ isHovering ? '▶️ Play' : '⏸️ Pause' }}
                            </button>
                            <button @click="scroll('right')" :class="styles.carousel.controlButton" aria-label="Next">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Keyboard Shortcuts Hint -->
                    <div :class="styles.carousel.keyboardHint">
                        <p>💡 Use arrow keys or swipe to navigate • Press 1-{{ Math.min(9, totalSlides) }} to jump to slides</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Loading and Empty States -->
        <div v-if="!products && !categories" :class="styles.states.loading">
            <div :class="styles.states.spinner"></div>
        </div>

        <section
            v-else-if="(!products || Object.keys(products).length === 0) && (!categories || categories.length === 0)"
            :class="styles.states.empty"
        >
            <h3 :class="styles.states.emptyTitle">No products or categories available</h3>
            <p :class="styles.states.emptyText">Check back later for new items.</p>
        </section>
    </div>
</template>
