<script setup>
    import Status from '@/components/alert/Status.vue';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
    import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
    import Dropdown from '@/components/ui/input/Dropdown.vue';
    import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    const props = defineProps({
        categories: Array
    });

    const form = useForm({
        name: null,
        category: null,
        price: null,
        type: 'single product',
        stock: null,
        size: null,
        unit: null,
        description: null,
        images: [],
    });

    const images = ref([null, null, null]);

    function submit() {
        form.images = images.value.map((img) => (img instanceof File ? img : null));

        form.post(route('product.store'), {
            forceFormData: true,
            preserveScroll: true,
        });
    }

    function handleImagesChange(data) {
        images.value = [...data.images];
    }
    function goBack() {
        window.history.back();
    }
    function reset() {
        images.value = [null, null, null];
        form.reset();
    }
</script>
<template>

    <Head title="Create Product" />

    <div class="pt-10 px-2 max-w-7xl mx-auto flex justify-center md:justify-end">
        <Link as="button" :href="route('customize.create')"
            class="btn w-full md:w-max bg-linear-65 from-secondary-500 hover:from-secondary to-primary-500 hover:to-primary transition text-white gap-4">
        <span>
            Create a
            Customizable
            Product
        </span>
        <i class="fa-solid fa-circle-plus text-xl "></i>
        </Link>
    </div>
    <form class="space-y-3 px-2 md:space-y-8 py-10 max-w-7xl mx-auto" @submit.prevent="submit">
        <PageTitleHeader title="Add new Product" />
        <ContainerPrimary title="Product Setting">
            <div class="grid gap-6 pt-2 md:grid-cols-2">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput :images="form.images" :error="form.errors" @changed="handleImagesChange" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <TextInputPrimary v-model="form.size" :required="false" label="Size L*W*H, W*H "
                                placeholder="L*W*H" variant="secondary" />
                        </div>
                        <div class="col-span-4">
                            <Dropdown label="Unit" :value="form.unit" @select="(option) => (form.unit = option)"
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

                    <div class="grid gap-2 gap-y-3 sm:grid-cols-2">
                        <Dropdown :value="form.category" :error="form.errors.category"
                            @select="(opt) => (form.category = opt)" label="Category"
                            placeholder="Select or add category" variant="secondary"
                            :options="categories.map((e) => e.name)" allow-custom-value />

                        <TextInputPrimary v-model="form.price" :error="form.errors.price" type="number" label="Price"
                            placeholder="Enter product price" variant="secondary" />

                        <Dropdown :value="form.type" :error="form.errors.type"
                            @select="(option) => (form.type = option)" label="Product Type" placeholder="Select type"
                            variant="secondary"
                            :options="['single product', 'main product with variant', 'custom']" />

                        <TextInputPrimary v-model="form.stock" :error="form.errors.stock" type="number" label="Stock"
                            placeholder="Enter product stocks" variant="secondary" />
                    </div>
                </div>
            </div>
            <div class="mx-auto grid max-w-3xl sm:grid-cols-3 gap-3 py-6 pt-8">
                <PillPrimary label="" variant="secondary" type="submit" :disabled="form.processing">
                    <span v-if="form.processing">Publishing...</span>
                    <span v-else>Publish Product</span>
                </PillPrimary>
                <PillPrimary is="Link" :href="route('product.index')" label="Cancel" variant="outlineSecondary" />
                <PillPrimary label="Clear/ reset" variant="outlineSecondary" @click="reset" type="reset"
                    :style="'dark:!bg-red-600/70 !bg-red-600/90  hover:!opacity-80 text-white'" />
            </div>
        </ContainerPrimary>
    </form>
</template>
