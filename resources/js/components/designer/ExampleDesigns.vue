<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';

const props = defineProps({
    designs: Array,
});
const page = usePage();
const tempImage = reactive({
    file: null,
    preview: '',
});

const emit = defineEmits(['change:new']);

const error = ref('');

function handleChange(e) {
    const file = e.target.files[0];

    if (!file) return; // No file selected

    if (file.size >= 1024 * 1024) {
        error.value = 'Image should be less than 1MB.';
    } else if (!file.type.startsWith('image/')) {
        error.value = 'Please upload a valid image file.';
    } else {
        error.value = '';
        tempImage.file = file;
        tempImage.preview = URL.createObjectURL(file);
        emit('change:new', { file: tempImage.file });
    }
}

function removeTempImage() {
    tempImage.file = null;
    tempImage.preview = '';
    emit('change:new', { file: null });
}

const styleClass = {
    container: 'grid gap-4 md:grid-cols-2',

    uploadBox: {
        base: 'relative rounded-xl border-2 flex items-center justify-center border-dashed size-36 text-center transition-colors duration-200',
        normal: 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
        error: 'border-red-400',
    },

    emptyState: 'flex w-full h-full  flex-col items-center justify-center',
    emptyIcon: 'h-12 w-12 text-gray-400',
    emptyText: 'mt-2 text-gray-500 dark:text-gray-400',

    uploadButton:
        'items-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-xs font-medium text-white transition-colors duration-200 hover:bg-blue-600',

    previewContainer: 'relative h-full w-full rounded-lg overflow-hidden',
    previewImage: 'h-full w-full object-contain bg-gray-100 dark:bg-gray-700',

    controls: 'absolute top-3 right-3 flex gap-2',
    controlButton: 'grid size-6 place-content-center rounded-full p-1 text-white transition-colors duration-200',
    editButton: 'bg-amber-400 hover:bg-amber-500',
    deleteButton: 'bg-red-500 hover:bg-red-600',

    errorMessage: 'mt-1 text-sm text-red-500 dark:text-red-400',
};

function imageSrc(src) {
    return `/storage/${src}`;
}
watch(page, () => {
    let confirm = page.props.confirm;
    if (confirm.confirmed && confirm.action.delete_product_element_design) {
        router.delete(route('product.design.destroy', { design: confirm.action.design.id }));
    }
});
function handleDeleteDesign(design) {
    page.props.confirm = {
        show: true,
        message: 'Confirm delete?',
        confirmed: false,
        action: { delete_product_element_design: true, design },
    };
}
</script>

<template>
    <input type="file" @change="handleChange" accept="image/*" hidden id="new-design" />
    <div>
        <span class="input-label">Example designs</span>
        <div class="flex flex-wrap gap-2 pt-2">
            <div
                v-for="design in props.designs"
                :class="[styleClass.uploadBox.base, error ? styleClass.uploadBox.error : styleClass.uploadBox.normal]"
            >
                <div v-if="!design" :class="styleClass.emptyState">
                    <label for="new-design" :class="styleClass.uploadButton">
                        <i class="fas fa-upload"></i>
                        Upload Design
                    </label>
                </div>
                <div v-else :class="styleClass.previewContainer">
                    <img :src="imageSrc(design.image)" alt="New design Preview" :class="styleClass.previewImage" />
                    <div :class="styleClass.controls">
                        <button
                            @click.prevent="handleDeleteDesign(design)"
                            :class="[styleClass.controlButton, styleClass.deleteButton]"
                            type="button"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div>
                    <div
                        :class="[
                            styleClass.uploadBox.base,
                            error ? styleClass.uploadBox.error : styleClass.uploadBox.normal,
                            { '!dark:border-green-400 !border-green-500': tempImage.file },
                        ]"
                    >
                        <div v-if="!tempImage.file" :class="styleClass.emptyState">
                            <label for="new-design" :class="styleClass.uploadButton">
                                <i class="fas fa-upload"></i>
                                Upload Design
                            </label>
                        </div>
                        <div v-else :class="styleClass.previewContainer">
                            <img :src="tempImage.preview" alt="New design Preview" :class="styleClass.previewImage" />
                            <div :class="styleClass.controls">
                                <label for="new-design" :class="[styleClass.controlButton, styleClass.editButton]">
                                    <i class="fas fa-pen text-sm"></i>
                                </label>
                                <button
                                    v-if="'tempImage[0] && !images[0]?.id'"
                                    type="button"
                                    @click="removeTempImage"
                                    :class="[styleClass.controlButton, styleClass.deleteButton]"
                                >
                                    <i class="fas fa-times"></i>
                                </button>
                                <button
                                    v-else-if="'allowDelete && tempImage[0]'"
                                    @click.prevent="'handleDelete(0)'"
                                    :class="[styleClass.controlButton, styleClass.deleteButton]"
                                    type="button"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <small v-if="tempImage.file" class="font-medium text-green-500 dark:text-green-400">
                    {{ tempImage.file.name }}
                </small>
                <small v-if="error" class="form-control-error">
                    {{ error }}
                </small>
            </div>
        </div>
    </div>
</template>
