<script setup>
import Status from '@/components/alert/Status.vue';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ProductImagesInput from '@/components/sections/ProductImagesInput.vue';
import PillPrimary from '@/components/ui/buttons/PillPrimary.vue';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    product: Object,
    status: Object,
    categories: Array,
});

const page = usePage();

const form = useForm({
    name: props.product.name,
    category: props.product.category.name,
    price: props.product.price,
    type: props.product.type,
    stock: props.product.stock,
    size: props.product.size,
    unit: props.product.unit,
    description: props.product.description,
    images: props.product.images,
    _method: 'PATCH',
});
const is_unchanged = ref(true);

const images = ref([null, null, null]);

onMounted(() => {
    images.value = [...form.images];
    is_unchanged.value = true;
    watch(form, () => (is_unchanged.value = false));
});

function submit() {
    const uploadedFiles = images.value.map((img, i) => {
        return img instanceof File || img == 'delete' ? img : null;
    });
    form.images = uploadedFiles;

    form.post(route('product.update', props.product));
}

function handleImagesChange(data) {
    images.value = [...data.images];
    is_unchanged.value = false;
}

function resetForm() {
    form.reset();
    images.value = [...props.product.images];
    is_unchanged.value = true;
}

function handleDelete() {
    page.props.confirm = {
        ...page.props.confirm,
        show: true,
        message: `Do you to delete ${props.product.name}?`,
        action: {
            delete_product: true,
        },
    };
}

watch(
    () => page.props.confirm.confirmed,
    () => {
        if (page.props.confirm.confirmed && page.props.confirm.action?.delete_product) {
            router.delete(route('product.destroy', props.product));
            page.props.confirm.confirmed = false;
        }
    },
);
function goBack() {
    window.history.back();
}
</script>

<template>
    <Head title="Edit Product" />

    <form class="mx-auto max-w-7xl space-y-3 py-10 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product Editor" />
        <!---------- STATUS ALERT ------------------------------------------------------->
        <!-- <Status :status="$page.props.status" @close="() => ($page.props.status = null)" /> -->

        <ContainerPrimary title="Product Setting">
            <div class="grid gap-6 pt-2 md:grid-cols-2">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <ProductImagesInput
                        :images="images"
                        :errors="[form.errors['images.0'], form.errors['images.1'], form.errors['images.2']]"
                        @changed="handleImagesChange"
                        :default-images="form.images"
                        allow-delete
                    />

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
                                allowNull
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
                        label="Product Name"
                        placeholder="Enter product name"
                        variant="secondary"
                    />

                    <div class="grid gap-2 gap-y-3 sm:grid-cols-2">
                        <Dropdown
                            :value="form.category"
                            :error="form.errors.category"
                            label="Category"
                            placeholder="Select category"
                            variant="secondary"
                            @select="(option) => (form.category = option)"
                            :options="categories"
                            allow-custom-value
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
                            :options="['single product', 'main product with variant', 'unavailable']"
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
            <div class="mx-auto grid max-w-3xl gap-3 py-6 pt-8 sm:grid-cols-2 md:grid-cols-4">
                <PillPrimary :disabled="is_unchanged || form.processing" label="Update product" variant="secondary" type="submit" />
                <PillPrimary
                    @click="handleDelete"
                    label="Delete"
                    variant="outlineSecondary"
                    :style="'dark:!bg-red-600/70 !bg-red-600/90  hover:!opacity-80 text-white'"
                />
                <PillPrimary @click="resetForm" label="Reset" :disabled="is_unchanged || form.processing" variant="outlineSecondary" />
                <PillPrimary @click="goBack" label="Cancel" variant="outlineSecondary" />
            </div>
        </ContainerPrimary>
    </form>
</template>
