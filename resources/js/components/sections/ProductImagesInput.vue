<script setup>
    import { ref, watch, onBeforeUnmount } from 'vue';

    const props = defineProps({
        images: {
            type: Array,
            default: () => []
        },
        error: String,
        disabled: { type: Boolean, default: false }
    });

    const localError = ref('');

    const tempImage = ref([null, null, null]);
    const defaultImages = ref([]);
    const previousUrls = [null, null, null];

    watch(
        () => props.images,
        (newImages) => {
            if (defaultImages.value.length < 1) {
                defaultImages.value = [...props.images]
            }
            tempImage.value = [...newImages];
            clearObjectURLs();
        }
    );

    const imagePlaceholder = '/images/img-placeholder.jpg';
    const emit = defineEmits(['changed']);

    function imageUrl(index) {
        const file = tempImage.value[index];

        if (file instanceof File) {
            if (previousUrls[index]) {
                URL.revokeObjectURL(previousUrls[index]);
            }
            const url = URL.createObjectURL(file);
            previousUrls[index] = url;
            return url;
        }

        // THIS is what restores the original image
        if (props.images[index]?.image_path) {
            return props.images[index].image_path;
        }

        return imagePlaceholder;
    }


    function changed(e, i) {
        if (props.disabled) return;
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            localError.value = 'Please select a valid image file.';
            emit('changed', {
                images: [...tempImage.value],
                error: localError.value,
                index: i
            });
            return;
        }

        const maxSizeMB = 8;
        if (file.size > maxSizeMB * 1024 * 1024) {
            localError.value = `Image size must be less than ${maxSizeMB}MB.`;
            emit('changed', {
                images: [...tempImage.value],
                error: localError.value,
                index: i
            });
            return;
        }

        tempImage.value[i] = file;
        localError.value = '';

        emit('changed', {
            images: [...tempImage.value],
            error: '',
            index: i
        });
    }


    function clearObjectURLs() {
        previousUrls.forEach((url, i) => {
            if (url) {
                URL.revokeObjectURL(url);
                previousUrls[i] = null;
            }
        });
    }

    onBeforeUnmount(() => {
        clearObjectURLs();
    });

    function clearImage(index) {
        if (previousUrls[index]) {
            URL.revokeObjectURL(previousUrls[index]);
            previousUrls[index] = null;
        }

        // Clear file so it falls back to props.images
        tempImage.value[index] = null;

        emit('changed', {
            images: [...tempImage.value],
            error: '',
            index
        });
    }



    const styleClass = {
        imageContainer: 'border border-secondary dark:border-white overflow-hidden border-dashed relative inline-block rounded min-h-26 min-w-26',
        fileInputLabel: 'absolute cursor-pointer z-20 inset-0 grid place-content-center text-center text-secondary-600 hover:text-secondary dark:text-secondary-200 dark:hover:text-secondary-300',
        image: 'z-10 absolute w-full h-full object-cover object-center',
        icon: 'fa-solid fa-image text-2xl md:text-3xl',
        reset: 'absolute right-2 top-2 bg-white/60 dark:bg-white rounded-full w-6 h-6 leading-0 cursor-pointer'
    };
</script>

<template>
    <div>
        <p class="input-label">Product Images</p>

        <div class="pt-1 grid grid-cols-2 gap-3 justify-center items-center md:grid-cols-12 md:grid-rows-2">
            <!-- Large Image -->
            <span :class="styleClass.imageContainer"
                class="md:col-span-8 md:row-span-2 self-stretch justify-self-end sm:justify-self-stretch">
                <label v-if="!disabled" for="img1" :class="styleClass.fileInputLabel">
                    <i :class="styleClass.icon" />
                    <span class="font-medium text-xs">Browse image</span>
                    <button v-if="tempImage[0]" :class="styleClass.reset" @click.prevent="clearImage(0)"><i
                            class="fa-solid fa-rotate"></i></button>
                </label>
                <img :src="imageUrl(0)" alt="" :class="styleClass.image" />
                <input accept="image/*" @change="changed($event, 0)" type="file" id="img1" hidden />
            </span>

            <!-- Top Right Small -->
            <span :class="styleClass.imageContainer"
                class="md:col-span-4 md:row-span-1 justify-self-start sm:justify-self-stretch">
                <label v-if="!disabled" for="img2" :class="styleClass.fileInputLabel">
                    <i :class="styleClass.icon" />
                    <span class="font-medium text-xs">Browse image</span>
                    <button v-if="tempImage[1]" :class="styleClass.reset" @click.prevent="clearImage(1)"><i
                            class="fa-solid fa-rotate"></i></button>
                </label>
                <img :src="imageUrl(1)" alt="" :class="styleClass.image" />
                <input accept="image/*" @change="changed($event, 1)" type="file" id="img2" hidden />
            </span>

            <!-- Bottom Right Small -->
            <span :class="styleClass.imageContainer"
                class="col-span-2 md:col-span-4 md:row-span-1 justify-self-center sm:justify-self-stretch">
                <label v-if="!disabled" for="img3" :class="styleClass.fileInputLabel">
                    <i :class="styleClass.icon" />
                    <span class="font-medium text-xs">Browse image</span>
                    <button v-if="tempImage[2]" :class="styleClass.reset" @click.prevent="clearImage(2)"><i
                            class="fa-solid fa-rotate"></i></button>
                </label>
                <img :src="imageUrl(2)" alt="" :class="styleClass.image" />
                <input accept="image/*" @change="changed($event, 2)" type="file" id="img3" hidden />
            </span>
        </div>

        <small v-if="localError || error" class="form-control-error">
            {{ localError || error }}
        </small>


    </div>
</template>
