<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import Dropdown from './Dropdown.vue';

const props = defineProps({
    element: { required: true, type: Object },
});

const emit = defineEmits(['update:element', 'delete:element']);

const text = ref({ ...props.element.text });
const image = ref({ ...props.element.image });
onMounted(function () {
    if (props.element.type === 'text') {
        text.value = { ...props.element.text };
    }
    if (props.element.type === 'image') {
        image.value = { ...props.element.image };
    }
});
watch(
    () => props.element,
    function (element) {
        if (element.type === 'text') {
            text.value = { ...props.element.text };
        }
        if (props.element.type === 'image') {
            image.value = { ...props.element.image };
        }
    },
    { deep: true },
);

// update
watch(
    () => text.value,
    function (newText) {
        emit('update:element', { value: newText, type: props.element.type });
    },
    { deep: true },
);
watch(
    () => image.value,
    function (image) {
        emit('update:element', { value: image, type: props.element.type });
    },
    { deep: true },
);

function deleteElement() {
    if (props.element.type === 'image') {
        image.value = null;
    }
    emit('delete:element', { value: text.value, type: props.element.type });
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
                    <label for="text-content" :class="styleClass.label">Text Content</label>
                    <input
                        v-model="text.text"
                        type="text"
                        placeholder="Text Content"
                        id="text-content"
                        :class="styleClass.input.base"
                        :style="{ fontFamily: text.font }"
                    />
                </div>
                <Dropdown @update:model-value="(e) => (text.font = e)" :value="text.font" />

                <div>
                    <label for="text-color" :class="styleClass.label">Text Color</label>
                    <input
                        v-model="text.color"
                        id="text-content"
                        type="color"
                        placeholder="Text Color"
                        :class="[styleClass.input.base, styleClass.input.color]"
                    />
                </div>

                <div>
                    <label for="text-size" :class="styleClass.sizeLabel">
                        <span>Font Size</span>
                        <span>{{ text.size }}</span>
                    </label>
                    <input
                        v-model="text.size"
                        type="number"
                        min="6"
                        id="text-size"
                        :class="styleClass.input.base"
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
                    <label for="image-width" :class="styleClass.sizeLabel">
                        <span>Width</span>
                    </label>
                    <input type="number" min="1" v-model="image.width" id="image-width" :class="styleClass.input.base" />
                </div>
                <div>
                    <label for="image-height" :class="styleClass.sizeLabel">
                        <span>Height</span>
                    </label>
                    <input type="number" min="1" v-model="image.height" id="image-height" :class="styleClass.input.base" />
                </div>

                <button @click="deleteElement" :class="styleClass.deleteButton">Delete</button>
            </div>
        </div>
    </div>
</template>
