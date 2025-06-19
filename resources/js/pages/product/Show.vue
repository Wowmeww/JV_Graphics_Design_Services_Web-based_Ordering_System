<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '../../components/ContainerPrimary.vue';
    import PillPrimary from '../../components/ui/buttons/PillPrimary.vue';
    import Dropdown from '../../components/ui/input/Dropdown.vue';
    import PageTitleHeader from '../../components/ui/PageTitleHeader.vue';
    import TextInputPrimary from '../../components/ui/TextInputPrimary.vue';

    const props = defineProps({
        product: Object
    });

    const form = useForm({
        name: props.product.name,
        category: props.product.category.name,
        price: props.product.price,
        type: props.product.type,
        stock: props.product.stock,
        size: props.product.size,
        description: props.product.description,
        images: props.product.images
    });

    function submit() {
        console.dir(form);
    }

    const styleClass = {
        imageContainer: 'border border-secondary dark:border-white overflow-hidden border-dashed relative inline-block rounded min-h-26 min-w-26',
        fileInputLabel: 'absolute cursor-pointer  z-20 inset-0 grid place-content-center text-center text-secondary-600 hover:text-secondary dark:text-secondary-200 dark:hover:text-secondary-300',
        image: 'z-10 absolute w-full h-full object-cover',
        icon: 'fa-solid fa-image text-2xl md:text-3xl'
    };


</script>
<template>

    <Head title="Edit Product" />

    <form class="space-y-3 md:space-y-8" @submit.prevent="submit">
        <PageTitleHeader title="Product Editor" />

        <ContainerPrimary title="Product Setting">
            <div class="pt-2 grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <!---------- Product Images Section ------------------------------------------------------->
                    <div>
                        <p class="input-label">Product Images</p>

                        <div
                            class="pt-1 grid grid-cols-2 grid- gap-3 justify-center items-center md:grid-cols-12 md:grid-rows-2">
                            <span :class="styleClass.imageContainer"
                                class="md:col-span-8 md:row-span-2 self-stretch justify-self-end sm:justify-self-stretch">
                                <label for="img1" :class="styleClass.fileInputLabel">
                                    <i :class="styleClass.icon"></i>
                                    <span class="font-medium text-xs">Browse image</span>
                                </label>
                                <img src="/images/img-placeholder.jpg" alt="" :class="styleClass.image" />
                                <input type="file" id="img1" hidden />
                            </span>
                            <span :class="styleClass.imageContainer"
                                class="md:col-span-4 md:row-span-1 justify-self-start sm:justify-self-stretch">
                                <label for="img3" :class="styleClass.fileInputLabel">
                                    <i :class="styleClass.icon"></i>
                                    <span class="font-medium text-xs">Browse image</span>
                                </label>
                                <img src="/images/img-placeholder.jpg" alt="" :class="styleClass.image" />
                                <input type="file" id="img2" hidden />
                            </span>
                            <span :class="styleClass.imageContainer"
                                class="col-span-2 md:col-span-4 md:row-span-1 justify-self-center sm:justify-self-stretch">
                                <label for="img3" :class="styleClass.fileInputLabel">
                                    <i :class="styleClass.icon"></i>
                                    <span class="font-medium text-xs">Browse image</span>
                                </label>
                                <img src="/images/img-placeholder.jpg" alt="" :class="styleClass.image" />
                                <input type="file" id="img3" hidden />
                            </span>

                        </div>


                    </div>

                    <div class="grid grid-cols-12 gap-2 gap-y-3 pt-2">
                        <div class="col-span-8">
                            <TextInputPrimary :required="false" type="number" label="Size L*W*H, W*H "
                                placeholder="L*W*H" variant="secondary" />
                        </div>
                        <div class="col-span-4">
                            <Dropdown  label="Unit" :value="null" placeholder="Unit" variant="secondary"
                                :options="['inc', 'cm', 'foot', 'miter']" />
                        </div>
                    </div>
                    <TextInputPrimary v-model="form.description" :error="form.errors.description"  :required="false" type="textarea" label="Product Description"
                        placeholder="Enter product description" variant="secondary" />
                </div>

                <!-- Another Column -->
                <div class="space-y-3">
                    <TextInputPrimary v-model="form.name" :error="form.errors.name" label="Product Name"
                        placeholder="Enter product name" variant="secondary" />

                    <div class="grid sm:grid-cols-2 gap-2 gap-y-3">
                        <Dropdown :value="form.category" :error="form.errors.category" label="Category"
                            placeholder="Select category" variant="secondary" :options="['Option I', 'OptionII']" />

                        <TextInputPrimary v-model="form.price" :error="form.errors.price" type="number" label="Price"
                            placeholder="Enter product price" variant="secondary" />

                        <Dropdown :value="form.type" :error="form.errors.type" label="Product Type" placeholder="Select type" variant="secondary"
                            :options="['Option I', 'OptionII']" />

                        <TextInputPrimary v-model="form.stock" :error="form.errors.stock"  type="number" label="Stock" placeholder="Enter product stocks"
                            variant="secondary" />
                    </div>

                    <div class="py-6 grid grid-cols-2 gap-3">
                        <PillPrimary label="Update product" variant="secondary" type="submit" />
                        <PillPrimary label="Cancel" variant="outlineSecondary" />
                    </div>
                </div>
            </div>
        </ContainerPrimary>
    </form>
</template>
