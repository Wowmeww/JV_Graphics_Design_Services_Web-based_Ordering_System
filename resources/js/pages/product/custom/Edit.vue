<script setup>
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import AddImages from '@/components/designer/AddImages.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import { onMounted, ref, watch } from 'vue';
import ExampleDesigns from '@/components/designer/ExampleDesigns.vue';
const page = usePage();

const props = defineProps({
    product: Object,
});
const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    size: props.product.size,
    type: props.product.type,
    category: props.product.category.name,
    unit: props.product.unit,
    images: props.product.images,
    design: null,
    _method: 'PATCH',
});
const is_unchanged = ref(true);

const images = ref([null, null]);
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

watch(
    () => images.value,
    (imgs) => {
        is_unchanged.value = !(imgs[0] instanceof File || imgs[0] === 'delete' || imgs[1] instanceof File || imgs[1] === 'delete');
    },
);

function goBack() {
    let url = page.props.urlPrevious;
    router.get(url);
}
</script>

<template>
    <div class="mx-auto max-w-7xl px-1 py-7">
        <div class="container-secondary">
            <div>
                <button @click="goBack" class="text-link">
                    <i class="fa fa-arrow-left-long"></i>
                    Back
                </button>
            </div>
            <form @submit.prevent="submit" class="my-3 space-y-5">
                <div>
                    <h2 class="text-xl font-bold md:text-2xl">Edit this Custom Product</h2>
                    <p class="font-medium">Define and add a personalized product to your catalog</p>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    <div class="space-y-3">
                        <TextInputPrimary
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Product name"
                            placeholder="Enter product name"
                            variant="secondary"
                        />
                        <TextInputPrimary
                            v-model="form.description"
                            :error="form.errors.description"
                            label="Description"
                            :required="false"
                            placeholder="Enter product description"
                            type="textarea"
                            variant="secondary"
                        />
                        <TextInputPrimary
                            v-model="form.price"
                            :error="form.errors.price"
                            label="Price"
                            placeholder="Enter product price"
                            type="number"
                            variant="secondary"
                        />

                        <TextInputPrimary
                            v-model="form.stock"
                            :error="form.errors.stock"
                            label="Stock"
                            placeholder="Enter stock"
                            type="number"
                            variant="secondary"
                        />

                        <div class="grid grid-cols-12 gap-2 gap-y-3">
                            <div class="col-span-8">
                                <TextInputPrimary
                                    v-model="form.size"
                                    :error="form.errors.size"
                                    :required="false"
                                    label="Size L*W*H, W*H "
                                    placeholder="L*W*H"
                                    variant="secondary"
                                />
                            </div>
                            <div class="col-span-4">
                                <Dropdown
                                    :required="false"
                                    label="Unit"
                                    allowNull
                                    :value="form.unit"
                                    @select="(option) => (form.unit = option)"
                                    placeholder="Unit"
                                    variant="secondary"
                                    :options="['inc', 'cm', 'foot', 'miter']"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <AddImages :images="images" @changed="handleImagesChange" :default-images="form.images" allow-delete />
                        <ExampleDesigns :designs="product.designs" @change:new="({ file }) => (form.design = file)" />
                        <small class="form-control-error" v-if="form.errors.design">{{ form.errors.design }}</small>
                    </div>
                </div>

                <div class="flex flex-col justify-end gap-3 sm:flex-row">
                    <button type="button" class="btn btn-danger" @click="handleDelete">Delete</button>
                    <ButtonPrimary @click="goBack" class="md:max-w-fit" label="Cancel" type="button" variant="outline-secondary" />
                    <ButtonPrimary
                        class="md:max-w-fit"
                        label="Update product"
                        type="submit"
                        :disable="is_unchanged"
                        :with-spinner="form.processing"
                        variant="secondary"
                    />
                </div>
            </form>
        </div>
    </div>
</template>
