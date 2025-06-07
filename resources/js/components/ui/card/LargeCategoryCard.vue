<script setup>
    defineProps({
        product: {
            required: true,
            default: null,
            type: Object
        },
        variant: {
            type: String,
            default: 'one'
        }
    });


    const styleClass = {
        card: "bg-secondary-400/20 transition hover:bg-secondary-400/30 flex flex-col md:flex-row items-center gap-4 p-4 max-w-5xl rounded-xl shadow text-white dark:bg-dark/70 dark:hover:bg-dark/40 dark:shadow-xs dark:shadow-white"
    }

</script>

<template>
    <div v-if="variant === 'one'" :class="styleClass.card">
        <img class="rounded-xl object-center object-cover h-70 lg:h-86 w-70 lg:w-86" :src="product.images[0].image_path"
            alt="image" />
        <div class="flex flex-col items-start gap-2 justify-between flex-1">
            <h3 class="text-center md:text-left">{{ product.name }}</h3>

            <p>
                {{ product.description.slice(0, 680) }}
            </p>
            <button
                class="bg-secondary/40 border-transparent transition px-4 py-2 rounded-full text-white mx-auto md:mx-0 hover:bg-secondary/60 dark:border dark:border-white dark:bg-secondary/30">Shop
                now</button>
        </div>
    </div>
    <div v-else :class="styleClass.card+ ' flex-col-reverse'">
        <div class="flex flex-col items-start gap-2 justify-between flex-1">
            <h3 class="text-center md:text-left">{{ product.name }}</h3>
            <p>
                {{ product.description.slice(0, 680) }}
            </p>
            <button
                class="bg-secondary/40 border-transparent transition px-4 py-2 rounded-full text-white mx-auto md:mx-0 hover:bg-secondary/60 dark:border dark:border-white dark:bg-secondary/30">Shop
                now</button>
        </div>
        <img class="rounded-xl object-center object-cover h-70 lg:h-86 w-70 lg:w-86" :src="product.images[0].image_path"
            alt="image" />
    </div>
</template>