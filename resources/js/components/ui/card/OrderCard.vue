<script setup>
    import TextInputPrimary from '../TextInputPrimary.vue';
    import { computed } from 'vue';
    const props = defineProps({
        item: Object
    });

    const model = defineModel({
        type: String,
    });

    const image_url = computed(() => {
        let src = (props.item.option?.images[0] || props.item.product.images[0])?.image_path
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
        return '/images/avatar-placeholder.webp';
    });


</script>
<template>
    <div
        class="animate__animated animate__fadeIn glass  transition-all duration-300 dark:bg-black/5 hover:dark:bg-black/10 border border-slate-300 dark:border-white/10 flex flex-col p-3 rounded-xl gap-3 max-h-fit hover:shadow-lg">
        <div class="flex gap-3 items-start">
            <!-- Product Image -->
            <img :src="image_url" :alt="item.option?.name || item.product?.name"
                class="md:w-44 md:h-44 w-20 h-20 rounded-lg object-cover flex-shrink-0 shadow-sm" />

            <!-- Product Details -->
            <div class="flex-1 flex flex-col">
                <h2 class="font-semibold text-md md:text-lg leading-tight">
                    {{ item.option?.name || item.product?.name }}
                </h2>

                <!-- Price & Quantity -->
                <div class="flex justify-between items-center mt-1 mb-2 px-1">
                    <span class="text-medium font-medium">
                        {{
                            item.total_amount.toLocaleString('en-PH', {
                                style: 'currency',
                                currency: 'PHP',
                            })
                        }}
                    </span>
                    <span class="flex items-center gap-1 text-sm text-white/80">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        {{ item.quantity }}
                    </span>
                </div>

                <!-- Desktop Message Input -->
                <div class="hidden md:block mt-auto">
                    <TextInputPrimary v-model="model"
                        customStyle="placeholder:text-white/60 bg-white/5 hover:bg-white/10 !placeholder-slate-500 focus:bg-white/15 transition-colors"
                        :row="2" placeholder="Optional message to seller for this order" :required="false"
                        label="Message/Note" variant="secondary" type="textarea" />
                </div>
            </div>
        </div>

        <!-- Mobile Message Input -->
        <div class="md:hidden">
            <TextInputPrimary v-model="model"
                customStyle="placeholder:text-white/60 bg-white/5 hover:bg-white/10 focus:bg-white/15 transition-colors"
                :row="2" placeholder="Optional message to seller for this order" :required="false" label="Message/Note"
                variant="secondary" type="textarea" />
        </div>
    </div>
</template>

<style scoped>
    .glass {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .glass:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }
</style>