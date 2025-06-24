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
    product: Object,
});

const form = useForm({
    name: null,
    category: props.product.category.name,
    price: props.product.price,
    type: 'Variant',
    stock: null,
    size: null,
    unit: null,
    description: null,
    images: [],
});

const images = ref([null, null, null]);

function submit() {
    form.images = images.value.map((img) => (img instanceof File ? img : null));

    form.post(route('option.store', { product: props.product }), {
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
    <Head title="Edit Product" />

    <form class="space-y-3 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product Editor" />
        <Status :status="$page.props.status" @close="$page.props.status = null" />
        <ContainerPrimary title="Product Setting">
            <div class="grid gap-6 pt-2 md:grid-cols-2">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput :images="form.images" :error="form.errors" @changed="handleImagesChange" />

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <TextInputPrimary
                                v-model="form.size"
                                :required="false"
                                label="Size L*W*H, W*H "
                                placeholder="L*W*H"
                                variant="secondary"
                            />
                        </div>
                        <div class="col-span-4">
                            <Dropdown
                                label="Unit"
                                :value="form.unit"
                                @select="(option) => (form.unit = option)"
                                placeholder="Unit"
                                variant="secondary"
                                :options="['inc', 'cm', 'foot', 'miter']"
                            />
                        </div>
                    </div>
                    <TextInputPrimary
                        v-model="form.description"
                        :error="form.errors.description"
                        :required="false"
                        type="textarea"
                        label="Product Description"
                        placeholder="Enter product description"
                        variant="secondary"
                    />
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <TextInputPrimary
                        v-model="form.name"
                        :error="form.errors.name"
                        label="Product Variant Name"
                        placeholder="Enter product name"
                        variant="secondary"
                    />

                    <div class="grid gap-2 gap-y-3 sm:grid-cols-2">
                        <Dropdown
                            :value="form.category"
                            :error="form.errors.category"
                            @select="(opt) => (form.category = opt)"
                            label="Category"
                            placeholder="Select category"
                            variant="secondary"
                            allow-custom-value
                            disabled
                        />
                        <TextInputPrimary
                            v-model="form.price"
                            :error="form.errors.price"
                            type="number"
                            label="Price"
                            placeholder="Enter product price"
                            variant="secondary"
                        />

                        <Dropdown
                            :value="form.type"
                            :error="form.errors.type"
                            @select="(option) => (form.type = option)"
                            label="Product Type"
                            placeholder="Select type"
                            variant="secondary"
                            disabled
                        />

                        <TextInputPrimary
                            v-model="form.stock"
                            :error="form.errors.stock"
                            type="number"
                            label="Stock"
                            placeholder="Enter product stocks"
                            variant="secondary"
                        />
                    </div>
                </div>
            </div>
            <div class="mx-auto grid max-w-3xl gap-3 py-6 pt-8 md:grid-cols-3">
                <PillPrimary label="" variant="secondary" type="submit" :disabled="form.processing">
                    <span> <span v-if="form.processing">Publishing...</span><span v-else>Publish Variant</span> </span>
                </PillPrimary>

                <PillPrimary @click="goBack" label="Cancel" variant="outlineSecondary" />
                <PillPrimary
                    label="Clear/ reset"
                    variant="outlineSecondary"
                    @click="reset"
                    type="reset"
                    :style="'dark:!bg-red-600/70 !bg-red-600/90  hover:!opacity-80 text-white'"
                />
            </div>
        </ContainerPrimary>
    </form>
</template>
