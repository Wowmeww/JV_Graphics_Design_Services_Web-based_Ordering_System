<script setup>
defineProps({
    product: {
        required: true,
        default: null,
        type: Object,
    },
    variant: {
        type: Number,
        default: 1,
    },
});
const imagePlaceholder = '/images/img-placeholder.jpg';
function imageUrl(path) {
    if (path.includes('product_images')) {
        return `/storage/${path}`;
    }

    return path;
}

// enhance the ui
const styleClass = {
    card: 'group bg-gradient-to-br from-secondary-400/20 to-secondary-500/10 transition-all duration-500 hover:from-secondary-400/30 hover:to-secondary-500/20 flex flex-col-reverse items-center gap-6 p-6 rounded-2xl shadow-lg hover:shadow-xl text-white dark:from-dark/80 dark:to-dark/60 dark:hover:from-dark/70 dark:hover:to-dark/50 dark:shadow-2xl dark:hover:shadow-3xl max-w-4xl border border-white/10 hover:border-white/20 backdrop-blur-sm',
    cardLayout: {
        even: 'md:flex-row',
        odd: 'md:flex-row-reverse',
    },
    contentContainer: 'flex flex-col items-start gap-4 justify-between flex-1 transform transition-transform duration-300 group-hover:scale-[1.02]',
    title: 'text-center md:text-left text-2xl font-bold bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent pb-1',
    description: {
        container: 'text-gray-200 dark:text-gray-300 leading-relaxed',
        text: 'line-clamp-4',
        ellipsis: 'font-black tracking-widest text-secondary-300 dark:text-secondary-200',
    },
    button: 'group relative bg-gradient-to-r from-secondary-500 to-secondary-600 border border-transparent transition-all duration-300 px-6 py-3 rounded-full text-white font-semibold mx-auto md:mx-0 hover:from-secondary-600 hover:to-secondary-700 hover:scale-105 hover:shadow-lg dark:from-secondary-600 dark:to-secondary-700 dark:border-white/20 dark:hover:border-white/30 overflow-hidden',
    buttonEffect:
        'absolute inset-0 bg-gradient-to-r from-white/20 to-transparent transform -skew-x-12 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000',
    imageContainer: 'w-full h-65 sm:h-80 md:h-96 md:flex-1 transform transition-transform duration-500 group-hover:scale-[1.03]',
    image: 'rounded-2xl object-center object-cover w-full h-full shadow-md group-hover:shadow-lg transition-shadow duration-300',
    categoryBadge:
        'inline-block bg-secondary-500/20 text-secondary-200 text-sm font-medium px-3 py-1 rounded-full border border-secondary-400/30 ml-2',
};
</script>

<template>
    <div :class="[styleClass.card, variant % 2 ? styleClass.cardLayout.odd : styleClass.cardLayout.even]">
        <div :class="styleClass.contentContainer">
            <div class="w-full">
                <h3 :class="styleClass.title">
                    {{ product.name }}
                    <span :class="styleClass.categoryBadge">{{ product.category.name }}</span>
                </h3>

                <div v-if="product.description" :class="styleClass.description.container">
                    <p :class="styleClass.description.text">
                        {{ product.description.slice(0, 680) }}
                        <span v-if="product.description.length >= 680" :class="styleClass.description.ellipsis"> . . . </span>
                    </p>
                    <div v-if="product.description.length >= 680" class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                        {{ product.description.length }} characters
                    </div>
                </div>
            </div>

            <Link :href="route('shop.index', { filter: { category: product.category.name } })" :class="styleClass.button">
                <span :class="styleClass.buttonEffect"></span>
                <span class="relative z-10 flex items-center gap-2">
                    Shop now
                    <svg
                        class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </Link>
        </div>

        <div :class="styleClass.imageContainer">
            <img
                v-if="product?.images.length"
                :class="styleClass.image"
                :src="imageUrl(product.images[0].image_path)"
                :alt="`${product.name} - ${product.category.name}`"
                loading="lazy"
            />
            <img v-else :class="styleClass.image" :src="imagePlaceholder" :alt="`${product.name} placeholder`" loading="lazy" />
        </div>
    </div>
</template>
