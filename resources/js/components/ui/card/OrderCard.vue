<script setup>
    import TextInputPrimary from '../TextInputPrimary.vue';

    defineProps({
        item: Object
    });

    const model = defineModel({
        type: String,
    });



</script>

<template>
    <div
        class="animate__animated animate__fadeIn transition text-white glass bg-white/5 hover:bg-white/10 dark:bg-black/5 hover:dark:bg-black/10 border-1 border-white/40 flex flex-col p-2 rounded-lg  gap-2 max-h-fit">
        <div class="flex gap-2">
            <img :src="item.option?.images[0]?.image_path || item.product.images[0]?.image_path || '/images/img-placeholder.jpg'"
                alt="" class="md:max-w-44 md:max-h-44 max-w-20 rounded-lg object-center object-cover" />
            <div class="flex-1">
                <h2 class="font-semibold text-md md:text-xl">{{ item.option?.name || item.product?.name }}</h2>

                <p class="text-medium flex justify-between items-center px-1 pb-2">
                    <span>
                        {{
                            item.total_amount.toLocaleString('en-PH', {
                                style: 'currency',
                                currency: 'PHP',
                            })
                        }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-xmark"></i>
                        {{ item.quantity }}
                    </span>
                </p>

                <div class="hidden md:block">
                    <TextInputPrimary v-model="model" customStyle="placeholder:text-white/60" :row="2"
                        placeholder="Optional message to seller for this order" :required="false" label="Message/ Note"
                        variant="secondary" type="textarea" />
                </div>
            </div>
        </div>

        <div class=" md:hidden">
            <TextInputPrimary v-model="model" customStyle="placeholder:text-white/60" :row="2"
                placeholder="Optional message to seller for this order" :required="false" label="Message/ Note"
                variant="secondary" type="textarea" />
        </div>
    </div>
</template>


<style scoped>

    .glass {
        /* From https://css.glass */
        /* background: rgba(255, 255, 255, 0.21); */
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(12.6px);
        -webkit-backdrop-filter: blur(12.6px);
        /* border: 1px solid rgba(255, 255, 255, 0.89); */
    }

</style>
