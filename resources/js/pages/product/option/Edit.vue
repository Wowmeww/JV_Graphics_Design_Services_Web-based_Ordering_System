<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
    import Dropdown from '@/components/ui/input/Dropdown.vue';
    import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
    import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
    import SingleProduct from '@/components/tables/SingleProduct.vue';
    import Product from '@/components/ui/card/Product.vue';
    import { ref } from 'vue';
    import { route } from 'ziggy-js';

    const props = defineProps({
        option: Object,
    });

    const form = useForm({
        ...props.option, category: props.option.product.category,
        _method: 'PATCH'
    });

    const images = ref([null, null, null]);

    function submit() {
        const temp = {
            images: [...props.option.images]
        }
        form.images = [...images.value];

        form.post(route('option.update', props.option));
        form.images = [...temp.images];

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
                            <Dropdown :disabled="true" label="Unit" :value="form.unit"
                                @select="(option) => form.unit = option" placeholder="Unit" variant="secondary"
                                :options="['inc', 'cm', 'foot', 'miter']" />
                        </div>
                    </div>
                    <TextInputPrimary v-model="form.description" :error="form.errors.description" :required="false"
                        type="textarea" label="Product Description" placeholder="Enter product description"
                        variant="secondary" />
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <TextInputPrimary v-model="form.name" :error="form.errors.name" label="Product Option Name"
                        placeholder="Enter product name" variant="secondary" />

                    <div class="grid sm:grid-cols-2 gap-2 gap-y-3">
                        <Dropdown disabled :value="form.category.name" :error="form.errors.category" label="Category"
                            placeholder="Select category" variant="secondary" :options="['Option I', 'OptionII']" />

                        <TextInputPrimary v-model="form.price" :error="form.errors.price" type="number" label="Price"
                            placeholder="Enter product price" variant="secondary" />

                        <Dropdown disabled :value="form.type" :error="form.errors.type"
                            @select="(option) => form.type = option" label="Product Type" placeholder="Select type"
                            variant="secondary"
                            :options="['single product', 'main product with variant', 'unavailable']" />

                        <TextInputPrimary v-model="form.stock" :error="form.errors.stock" type="number" label="Stock"
                            placeholder="Enter product stocks" variant="secondary" />
                    </div>

                    <div class="max-h-90 h-fit overflow-y-scroll space-y-2 rounded-xl pr-3">
                        <Product :product="option.product" />
                    </div>


                </div>
            </div>
            <div class="py-6 pt-8 grid grid-cols-2 gap-3 max-w-3xl mx-auto">
                <PillPrimary label="Update product" variant="secondary" type="submit" />
                <PillPrimary label="Cancel" variant="outlineSecondary"
                    @click="$inertia.visit(route('option.show', option))" />

            </div>
        </ContainerPrimary>
    </form>
</template>
