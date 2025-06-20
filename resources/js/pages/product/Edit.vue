<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
    import Dropdown from '@/components/ui/input/Dropdown.vue';
    import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
    import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
    import Product from '@/components/ui/card/Product.vue';
    import { ref } from 'vue';

    const props = defineProps({
        product: Object,
        option: Object
    });

    const form = useForm({
        name: props.product.name,
        category: props.product.category.name,
        price: props.product.price,
        type: props.product.type,
        stock: props.product.stock,
        size: props.product.size,
        unit: props.product.unit,
        description: props.product.description,
        images: props.product.images
    });

    const is_option = Boolean(props.option);

    if (is_option) {
        const option = props.option;
        form.name = option.name;
        form.price = option.price;
        form.type = option.type;
        form.stock = option.stock;
        form.size = option.size;
        form.unit = option.unit;
        form.description = option.description;
        form.images = [...option.images];
    }

    const images = ref([null, null, null]);

    function submit() {
        form.images = [...images.value];

        console.log(form.images);
    }

    function handleImagesChange(data) {
        images.value = [...data.images]
    }

</script>
<template>

    <Head title="Edit Product" />

    <form class="space-y-3 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product Editor" />

        <ContainerPrimary title="Product Setting">
            <div class="pt-2 grid md:grid-cols-2 gap-6">
                <div class="space-y-3">

                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput :images="form.images" :error="form.errors.images"
                        @changed="handleImagesChange" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <TextInputPrimary v-model="form.size" :required="false" label="Size L*W*H, W*H "
                                placeholder="L*W*H" variant="secondary" />
                        </div>
                        <div class="col-span-4">
                            <Dropdown label="Unit" :value="form.unit" @select="(option) => form.unit = option"
                                placeholder="Unit" variant="secondary" :options="['inc', 'cm', 'foot', 'miter']" />
                        </div>
                    </div>
                    <TextInputPrimary v-model="form.description" :error="form.errors.description" :required="false"
                        type="textarea" label="Product Description" placeholder="Enter product description"
                        variant="secondary" />
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <TextInputPrimary v-model="form.name" :error="form.errors.name" label="Product Name"
                        placeholder="Enter product name" variant="secondary" />

                    <div class="grid sm:grid-cols-2 gap-2 gap-y-3">
                        <Dropdown :disabled="is_option" :value="form.category" :error="form.errors.category"
                            label="Category" placeholder="Select category" variant="secondary"
                            :options="['Option I', 'OptionII']" />

                        <TextInputPrimary v-model="form.price" :error="form.errors.price" type="number" label="Price"
                            placeholder="Enter product price" variant="secondary" />

                        <Dropdown :value="form.type" :error="form.errors.type" @select="(option) => form.type = option"
                            label="Product Type" placeholder="Select type" variant="secondary"
                            :options="['single product', 'main product with variant', 'unavailable']" />

                        <TextInputPrimary v-model="form.stock" :error="form.errors.stock" type="number" label="Stock"
                            placeholder="Enter product stocks" variant="secondary" />
                    </div>

                    <div>
                        <label class="input-label mb-2 mt-2 inline-block">
                            {{ option ? 'Parent product' : 'Variant/s' }}
                        </label>
                        <div class="max-h-90 h-fit overflow-y-scroll space-y-2 rounded-xl pr-3">
                            <Product v-if="!option" v-for="opt of product.options" :key="opt.id" :product="product"
                                :option="opt" />
                            <Product v-else :product="product" />

                            <button v-if="!option" type="button"
                                class="container-primary border-2 text-secondary border-secondary dark:border-white/10 rounded-xl bg-white p-4 text-4xl dark:text-secondary-100 :text-6xl dark:bg-[#1e293b] w-full min-w-fit text-wrap transition duration-1000 hover:bg-secondary-200/60 dark:hover:bg-secondary-200/10">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
            <div class="py-6 pt-8 grid grid-cols-2 gap-3 max-w-3xl mx-auto">
                <PillPrimary label="Update product" variant="secondary" type="submit" />
                <PillPrimary label="Cancel" variant="outlineSecondary" style="" />
            </div>
        </ContainerPrimary>
    </form>
</template>
