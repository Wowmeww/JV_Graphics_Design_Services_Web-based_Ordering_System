<script setup>
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
    import { useForm } from '@inertiajs/vue3';
    import Dropdown from '@/components/ui/input/Dropdown.vue';
    import AddImages from '../../../components/designer/AddImages.vue';
    import ButtonPrimary from '../../../components/ui/buttons/ButtonPrimary.vue';
    import { ref } from 'vue';

    const form = useForm({
        name: null,
        description: null,
        price: null,
        stock: null,
        type: 'custom',
        category: 'customizable',
        size: null,
        unit: null,
        images: []
    });

    const images = ref([null, null]);

    function handleImagesChange(data) {
        images.value = [...data.images];
    }

    function submit() {
        form.images = images.value.map((img) => (img instanceof File ? img : null));
        form.post(route('product.store'), {
            onSuccess: () => form.reset()
        });
    }
</script>

<template>

    <div class="max-w-7xl py-7 px-1 mx-auto">
        <div class="container-secondary">
            <div>
                <Link as="button" :href="route('product.create')" class="text-link">
                <i class="fa fa-arrow-left-long"></i>
                Back
                </Link>
            </div>
            <form @submit.prevent="submit" class="my-3 space-y-5">
                <div>
                    <h2 class="font-bold text-xl md:text-2xl">Add a Custom Product</h2>
                    <p class="font-medium">Define and add a personalized product to your catalog</p>
                </div>

                <div class="grid md:grid-cols-2 gap-3">
                    <div class="space-y-3">
                        <TextInputPrimary v-model="form.name" :error="form.errors.name" label="Product name"
                            placeholder="Enter product name" variant="secondary" />
                        <TextInputPrimary v-model="form.description" :error="form.errors.description"
                            label="Description" :required="false" placeholder="Enter product description"
                            type="textarea" variant="secondary" />

                        <TextInputPrimary v-model="form.price" :error="form.errors.price" label="Price"
                            placeholder="Enter product price" type="number" variant="secondary" />

                    </div>
                    <div class="space-y-3">
                        <TextInputPrimary v-model="form.stock" :error="form.errors.stock" label="Stock"
                            placeholder="Enter stock" type="number" variant="secondary" />

                        <div class="grid grid-cols-12 gap-2 gap-y-3">
                            <div class="col-span-8">
                                <TextInputPrimary v-model="form.size" :error="form.errors.size" :required="false"
                                    label="Size L*W*H, W*H " placeholder="L*W*H" variant="secondary" />
                            </div>
                            <div class="col-span-4">
                                <Dropdown :required="false" label="Unit" :value="form.unit"
                                    @select="(option) => (form.unit = option)" placeholder="Unit" variant="secondary"
                                    :options="['inc', 'cm', 'foot', 'miter']" />
                            </div>
                        </div>

                        <AddImages :images="form.images" @changed="handleImagesChange" />
                    </div>
                </div>

                <div class="flex gap-3 flex-col sm:flex-row justify-end">
                    <ButtonPrimary is="Link" :href="route('product.index')" class="md:max-w-fit" label="Cancel"
                        type="button" variant="outline-secondary" />
                    <ButtonPrimary class="md:max-w-fit" label="Publish product" type="submit" :disable="false"
                        :with-spinner="form.processing" variant="secondary" />
                </div>
            </form>

        </div>
    </div>
</template>