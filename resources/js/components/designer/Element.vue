<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import Dropdown from './Dropdown.vue';

const props = defineProps({
    element: { required: true, type: Object },
});

const emit = defineEmits(['update:element', 'delete:element']);

const text = ref({ ...props.element.text });
const image = ref({ ...props.element.image, scale: 100 });
const design = ref({ ...props.element.design, scale: 100 });

const originalImage = reactive({
    width: 0,
    height: 0,
});
const originalDesign = reactive({
    width: 0,
    height: 0,
});
onMounted(() => {
    switch (props.element.type) {
        case 'text':
            text.value = { ...props.element.text };
            break;

        case 'image':
            image.value = { ...props.element.image, scale: 100 };

            // Set original only if not set yet
            if (!originalImage.width && !originalImage.height) {
                originalImage.width = props.element.image.width;
                originalImage.height = props.element.image.height;
            }

            // Initialize current dimensions
            image.value.width = originalImage.width;
            image.value.height = originalImage.height;
            break;

        case 'design':
            design.value.rotate = design.value.rotate || 0;
            break;
    }
});

watch(
    () => image.value?.scale,
    (newScale) => {
        if (originalImage.width && originalImage.height) {
            image.value.width = Math.round(originalImage.width * newScale) / 100;
            image.value.height = Math.round(originalImage.height * newScale) / 100;
        }
    },
);
watch(
    () => design.value?.scale,
    (newScale) => {
        if (originalDesign.width && originalDesign.height) {
            design.value.width = Math.round(originalDesign.width * newScale) / 100;
            design.value.height = Math.round(originalDesign.height * newScale) / 100;
        }
    },
);

watch(
    () => props.element,
    (element) => {
        if (element.type === 'text') {
            text.value = { ...element.text };
        }

        if (element.type === 'image') {
            image.value = { ...element.image, scale: image.value.scale };

            // Only set original if not yet set
            if (!originalImage.width && !originalImage.height) {
                originalImage.width = element.image.width;
                originalImage.height = element.image.height;
            }
        }
        if (element.type === 'design') {
            design.value = { ...element.design, scale: design.value.scale };
        }
    },
    { deep: true },
);

// Emits updates
watch(
    () => text.value,
    (newText) => {
        emit('update:element', { value: newText, type: 'text' });
    },
    { deep: true },
);

watch(
    () => image.value,
    (img) => {
        emit('update:element', { value: img, type: 'image' });
    },
    { deep: true },
);
watch(
    () => design.value,
    (d) => {
        emit('update:element', { value: d, type: 'design' });
    },
    { deep: true },
);

function deleteElement() {
    emit('delete:element', {
        type: props.element.type,
        value: { ...props.element },
    });
}

const styleClass = {
    container: 'container-secondary m-0',
    heading: 'text-lg font-semibold',
    formGroup: 'space-y-4 pt-3 ',
    label: 'block text-sm text-gray-500 dark:text-gray-300',
    input: {
        base: 'mt-2 block w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:border-blue-300',
        color: 'h-10 !py-1', // Additional classes for color input
    },
    deleteButton: 'btn w-full bg-red-400 text-white hover:bg-red-500',
    sizeLabel: 'flex justify-between text-sm text-gray-500 dark:text-gray-300',
};
</script>

<template>
    <div class="h-fit">
        <!-- Text Element Form -->
        <div v-if="element.type === 'text'" :class="styleClass.container">
            <h1 :class="styleClass.heading">Edit Element</h1>
            <div :class="styleClass.formGroup">
                <div>
                    <label :for="`text-content-${element.from}`" :class="styleClass.label">Text Content</label>
                    <input
                        v-model="text.text"
                        type="text"
                        placeholder="Text Content"
                        :id="`text-content-${element.from}`"
                        :class="styleClass.input.base"
                        :style="{ fontFamily: text.font }"
                    />
                </div>
                <Dropdown @update:model-value="(e) => (text.font = e)" :value="text.font" />

                <div>
                    <label :for="`text-color-${element.from}`" :class="styleClass.label">Text Color</label>
                    <input
                        v-model="text.color"
                        :id="`text-color-${element.from}`"
                        type="color"
                        placeholder="Text Color"
                        :class="[styleClass.input.base, styleClass.input.color]"
                    />
                </div>

                <div>
                    <label :for="`text-size-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Font Size</span>
                        <span>{{ text.size }}</span>
                    </label>
                    <input v-model="text.size" type="number" min="6" :id="`text-size-${element.from}`" :class="styleClass.input.base" />
                </div>

                <div>
                    <label :for="`text-rotate-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Angle</span>
                        <span>{{ text.rotate }}</span>
                    </label>
                    <input
                        type="range"
                        min="0"
                        max="360"
                        v-model="text.rotate"
                        :id="`text-rotate-${element.from}`"
                        :class="[
                            'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                            'focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700',
                            'focus:ring-opacity-50 transition-all duration-200',
                            '[&::-webkit-slider-thumb]:appearance-none',
                            '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4',
                            '[&::-webkit-slider-thumb]:rounded-full',
                            '[&::-webkit-slider-thumb]:bg-blue-600',
                            '[&::-webkit-slider-thumb]:dark:bg-blue-500',
                            '[&::-webkit-slider-thumb]:hover:scale-125',
                            '[&::-webkit-slider-thumb]:transition-transform',
                            '[&::-webkit-slider-thumb]:duration-100',
                        ]"
                    />
                </div>
                <button @click="deleteElement" :class="styleClass.deleteButton">Delete</button>
            </div>
        </div>

        <!-- Image Element Form -->
        <div v-if="element.type === 'image'" :class="styleClass.container">
            <h1 :class="styleClass.heading">Edit Element</h1>
            <div :class="styleClass.formGroup">
                <div>
                    <label :for="`image-width-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Width</span>
                    </label>
                    <input type="number" min="1" v-model="image.width" :id="`image-width-${element.from}`" :class="styleClass.input.base" />
                </div>
                <div>
                    <label :for="`image-height-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Height</span>
                    </label>
                    <input type="number" min="1" v-model="image.height" :id="`image-height-${element.from}`" :class="styleClass.input.base" />
                </div>
                <div>
                    <label :for="`image-scale-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Size</span>
                        <span>{{ image.scale }}</span>
                    </label>
                    <input
                        type="range"
                        min="1"
                        max="200"
                        v-model="image.scale"
                        :id="`image-scale-${element.from}`"
                        :class="[
                            'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                            'focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700',
                            'focus:ring-opacity-50 transition-all duration-200',
                            '[&::-webkit-slider-thumb]:appearance-none',
                            '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4',
                            '[&::-webkit-slider-thumb]:rounded-full',
                            '[&::-webkit-slider-thumb]:bg-blue-600',
                            '[&::-webkit-slider-thumb]:dark:bg-blue-500',
                            '[&::-webkit-slider-thumb]:hover:scale-125',
                            '[&::-webkit-slider-thumb]:transition-transform',
                            '[&::-webkit-slider-thumb]:duration-100',
                        ]"
                    />
                </div>

                <div>
                    <label :for="`image-rotate-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Angle</span>
                        <span>{{ image.rotate }}</span>
                    </label>
                    <input
                        type="range"
                        min="0"
                        max="360"
                        v-model="image.rotate"
                        :id="`image-rotate-${element.from}`"
                        :class="[
                            'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                            'focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700',
                            'focus:ring-opacity-50 transition-all duration-200',
                            '[&::-webkit-slider-thumb]:appearance-none',
                            '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4',
                            '[&::-webkit-slider-thumb]:rounded-full',
                            '[&::-webkit-slider-thumb]:bg-blue-600',
                            '[&::-webkit-slider-thumb]:dark:bg-blue-500',
                            '[&::-webkit-slider-thumb]:hover:scale-125',
                            '[&::-webkit-slider-thumb]:transition-transform',
                            '[&::-webkit-slider-thumb]:duration-100',
                        ]"
                    />
                </div>

                <button @click="deleteElement" :class="styleClass.deleteButton">Delete</button>
            </div>
        </div>

        <!-- Design Element Form -->
        <div v-if="element.type === 'design'" :class="styleClass.container">
            <h1 :class="styleClass.heading">Edit Element</h1>
            <div :class="styleClass.formGroup">
                <div>
                    <label :for="`design-scale-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Size</span>
                        <span>{{ design.scale }}</span>
                    </label>
                    <input
                        type="range"
                        min="1"
                        max="200"
                        v-model="design.scale"
                        :id="`design-scale-${element.from}`"
                        :class="[
                            'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                            'focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700',
                            'focus:ring-opacity-50 transition-all duration-200',
                            '[&::-webkit-slider-thumb]:appearance-none',
                            '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4',
                            '[&::-webkit-slider-thumb]:rounded-full',
                            '[&::-webkit-slider-thumb]:bg-blue-600',
                            '[&::-webkit-slider-thumb]:dark:bg-blue-500',
                            '[&::-webkit-slider-thumb]:hover:scale-125',
                            '[&::-webkit-slider-thumb]:transition-transform',
                            '[&::-webkit-slider-thumb]:duration-100',
                        ]"
                    />
                </div>

                <div>
                    <label :for="`design-rotate-${element.from}`" :class="styleClass.sizeLabel">
                        <span>Angle</span>
                        <span>{{ design.rotate }}</span>
                    </label>
                    <input
                        type="range"
                        min="0"
                        max="360"
                        v-model="design.rotate"
                        :id="`design-rotate-${element.from}`"
                        :class="[
                            'h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200',
                            'focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700',
                            'focus:ring-opacity-50 transition-all duration-200',
                            '[&::-webkit-slider-thumb]:appearance-none',
                            '[&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4',
                            '[&::-webkit-slider-thumb]:rounded-full',
                            '[&::-webkit-slider-thumb]:bg-blue-600',
                            '[&::-webkit-slider-thumb]:dark:bg-blue-500',
                            '[&::-webkit-slider-thumb]:hover:scale-125',
                            '[&::-webkit-slider-thumb]:transition-transform',
                            '[&::-webkit-slider-thumb]:duration-100',
                        ]"
                    />
                </div>

                <button @click="deleteElement" :class="styleClass.deleteButton">Delete</button>
            </div>
        </div>
    </div>
</template>
