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
        card: "bg-secondary-400/20 transition hover:bg-secondary-400/30 flex flex-col-reverse  items-center gap-4 p-4 max-w-5xl rounded-xl shadow text-white dark:bg-dark/70 dark:hover:bg-dark/40 dark:shadow-xs dark:shadow-white"
    }

</script>

<template>
    <div :class="[styleClass.card, variant % 2? 'md:flex-row': 'md:flex-row-reverse']">
        <!-- <img class="rounded-xl object-center object-cover h-70 lg:h-86 w-70 lg:w-86"
            src="storage/product_images/X3P6ORI5DfIavwqhbdOLuvWXl9R49HlSKZGzzvF4.jpg" alt="image" /> -->
        <div class="flex flex-col items-start gap-2 justify-between flex-1">
            <h3 class="text-center md:text-left">{{ product.name }}</h3>

             <p v-if="product.description">
                <span>
                    {{ product.description.slice(0, 680)}}
                </span>
              <span v-if="product.description.length >= 680" class="font-black tracking-widest">
                . . .
              </span>
            </p>
            <button
                class="bg-secondary/40 border-transparent transition px-4 py-2 rounded-full text-white mx-auto md:mx-0 hover:bg-secondary/60 dark:border dark:border-white dark:bg-secondary/30">Shop
                now</button>
        </div>
         <img v-if="product?.images.length" class="rounded-xl object-center object-cover h-70 lg:h-86 w-70 lg:w-86"
            :src="imageUrl(product.images[0].image_path)" alt="image" />
        <img v-else class="rounded-xl object-center object-cover h-70 lg:h-86 w-70 lg:w-86" :src="imagePlaceholder"
            alt="image" />
    </div>
 

</template>