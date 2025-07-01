<script setup>
    defineProps({
        product: {
            required: true,
            default: null,
            type: Object
        },
        variant: {
            type: Number,
            default: 1
        }
    });
    const imagePlaceholder = '/images/img-placeholder.jpg';
    function imageUrl(path) {
        if (path.includes('product_images')) {
            return `/storage/${path}`;
        }

        return path;
    }


    const styleClass = {
        card: "bg-secondary-400/20 transition hover:bg-secondary-400/30 flex flex-col-reverse  items-center gap-4 p-4 rounded-xl shadow text-white dark:bg-dark/70 dark:hover:bg-dark/40 dark:shadow-xs dark:shadow-white max-w-4xl"
    }

</script>

<template>
    <div :class="[styleClass.card, variant % 2 ? 'md:flex-row' : 'md:flex-row-reverse']">
        <div class="flex flex-col items-start gap-2 justify-between flex-1">
            <h3 class="text-center md:text-left">{{ product.name }} {{ ` - ${product.category.name}` }}</h3>

            <p v-if="product.description">
                <span>
                    {{ product.description.slice(0, 680) }}
                </span>
                <span v-if="product.description.length >= 680" class="font-black tracking-widest">
                    . . .
                </span>
            </p>
            <Link :href="route('shop.index', { filter: { category: product.category.name } })"
                class="bg-secondary/40 border-transparent transition px-4 py-2 rounded-full text-white mx-auto md:mx-0 hover:bg-secondary/60 dark:border dark:border-white dark:bg-secondary/30">
            Shop
            now</Link>
        </div>
        <div class=" w-full h-65 sm:h-80 md:h-96 md:flex-1">
            <img v-if="product?.images.length" class="rounded-xl object-center object-cover w-full h-full"
                :src="imageUrl(product.images[0].image_path)" alt="image" />
            <img v-else class="rounded-xl object-center object-cover w-full h-full" :src="imagePlaceholder"
                alt="image" />
        </div>
    </div>


</template>