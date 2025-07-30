<script setup>
import { computed, reactive, ref } from 'vue';
import Colors from '@/components/designer/Colors.vue';
import Element from '@/components/designer/Element.vue';
import Tools from '@/components/designer/Tools.vue';
import initializeDragAndDrop from './dragDrop.js';
import { useForm } from '@inertiajs/vue3';
import html2canvas from 'html2canvas';

const props = defineProps({
    product: Object,
});

const images = computed(() => ({
    front: props.product.images[0] ? `/storage/${props.product.images[0].image_path}` : null,
    back: props.product.images[1] ? `/storage/${props.product.images[1].image_path}` : null,
}));

const selectedElement = reactive({
    from: 'front',
    text: null,
    image: null,
});

const elements = reactive({
    front: {
        texts: [],
        image: null,
        imagePreview: '',
    },
    back: {
        texts: [],
        image: null,
        imagePreview: '',
    },
});

// ------ FORM --------------------------------------------------------------------------------------------
const form = useForm({
    product_id: props.product.id,
    option_id: null,
    quantity: 12,
    total_amount: props.product.price * 12,
    type: 'custom',
    images: [],
});

async function addToCart() {
    // Start with real uploaded images
    form.images = [
        elements.front.image?.file ? { label: 'front image', file: elements.front.image.file } : null,
        elements.back.image?.file ? { label: 'back image', file: elements.back.image.file } : null,
    ].filter(Boolean);

    if (form.images[0] || form.images[1]) {
        const canvasElements = [
            { el: document.querySelector('#front-canvas'), label: 'uploaded front', filename: 'front-canvas.png' },
            { el: document.querySelector('#back-canvas'), label: 'uploaded back', filename: 'back-canvas.png' },
        ];

        for (const { el, label, filename } of canvasElements) {
            if (!el) continue;

            try {
                const canvas = await html2canvas(el);
                const dataUrl = canvas.toDataURL('image/jpeg', 0.9); // 90% quality

                const file = dataURLtoFile(dataUrl, filename);

                if (file) {
                    form.images.push({ label, file });
                }
            } catch (err) {
                console.error(`Failed to render canvas for ${label}`, err);
            }
        }
    }

    // Now post the form
    form.post(route('cart.store', { product: props.product.id }));
}

function dataURLtoFile(dataurl, filename) {
    const arr = dataurl.split(',');
    const mimeMatch = arr[0].match(/:(.*?);/);
    const mime = mimeMatch ? mimeMatch[1] : 'image/png';
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    return new File([u8arr], filename, { type: mime });
}

const activeView = ref('front'); // 'front' or 'back'

function updateElement(type, value) {
    const to = activeView.value;
    if (type === 'text' && to === value.from) {
        elements[to].texts[value.index] = value;
    } else if (type === 'image' && to === value.from) {
        elements[to].image = value;
    }
}
function deleteElement(type, value) {
    const to = activeView.value;
    if (type === 'text' && to === value.from) {
        elements[to].texts.splice(value.index, 1);

        selectedElement.text = null;
    } else {
        elements[to].image = null;
        elements[to].imagePreview = null;
        selectedElement.image = null;
    }
    selectedElement.type = null;
    selectedElement.from = null;
}
const switchView = (view) => {
    activeView.value = view;
    selectedElement.from = view;

    selectedElement.text = null;
    selectedElement.image = null;
};

function addText(text) {
    const to = activeView.value;
    elements[to].texts.push({
        text,
        font: 'Roboto',
        size: 24,
        color: '#000000',
        index: 0,
        from: activeView.value,
    });
}
function addImage(imageFile) {
    const to = activeView.value;
    elements[to].imagePreview = URL.createObjectURL(imageFile);
    elements[to].image = {
        from: activeView.value,
        file: imageFile,
    };
}

function selectElement(type, value, event) {
    const from = activeView.value;
    selectedElement.type = type;
    if (type === 'text') {
        selectedElement.text = value;
    } else {
        elements[from].image.width = event.currentTarget.clientWidth;
        elements[from].image.height = event.currentTarget.clientHeight;
        selectedElement.text = null;
        selectedElement.image = elements[from].image;
    }
}

const styleClasses = {
    container: 'mx-auto max-w-7xl px-4 py-6',
    layout: 'flex flex-col lg:gap-6 lg:flex-row',
    previewSection: 'container-secondary h-fit flex-1 space-y-4 p-4',
    headerContainer: 'flex flex-wrap items-center justify-between',
    title: 'text-xl font-bold text-gray-900 dark:text-white',
    viewToggleContainer:
        'flex divide-x divide-gray-300 overflow-hidden rounded-lg border border-gray-200 bg-white rtl:flex-row-reverse dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800',
    viewToggleButton: 'px-4 py-2 text-sm font-medium transition-colors duration-200 sm:px-6',
    viewToggleActive: 'bg-blue-500 text-white',
    viewToggleInactive: 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700',
    canvasContainer: 'grid place-content-center',
    canvasImage: 'relative rounded-xl overflow-hidden',
    canvasOverlay: 'absolute inset-0',
    textElement: 'cursor-pointer rounded border border-transparent px-3 py-0.5 hover:border-indigo-600',
    toolsPanel: 'flex flex-col gap-5 lg:w-sm',
    uploadedImage: 'hover:drop-shadow-primary-700 dark:hover:drop-shadow-primary hover:drop-shadow-xl p-0',
    label: 'block text-sm text-gray-500 dark:text-gray-300',
    textInput:
        'mt-2 block w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:border-blue-300',
};
</script>

<template>
    <Head title="Designer" />
    <div :class="styleClasses.container">
        <div :class="styleClasses.layout">
            <!-- T-Shirt Preview Section -->
            <div :class="styleClasses.previewSection">
                <div :class="styleClasses.headerContainer">
                    <h1 :class="styleClasses.title">T-Shirt Designer</h1>
                    <div :class="styleClasses.viewToggleContainer">
                        <button
                            @click="switchView('front')"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'front' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                            ]"
                        >
                            Front
                        </button>
                        <button
                            v-if="images.back"
                            @click="switchView('back')"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'back' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                            ]"
                        >
                            Back
                        </button>
                    </div>
                </div>

                <!-- Canvas -->
                <div :class="styleClasses.canvasContainer">
                    <div v-show="activeView === 'front'" :class="styleClasses.canvasImage">
                        <img class="" :src="images.front" alt="front" />

                        <div :class="styleClasses.canvasOverlay" id="front-canvas">
                            <span
                                v-for="(text, i) in elements.front.texts"
                                :key="`front-text-${i}`"
                                @click="selectElement('text', { ...text, index: i })"
                                :class="styleClasses.textElement"
                                :style="{
                                    fontSize: `${text.size}px`,
                                    fontFamily: text.font,
                                    color: text.color,
                                    zIndex: i + 1,
                                    position: 'absolute',
                                }"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                >{{ text.text }}</span
                            >

                            <img
                                v-if="elements.front.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.front.imagePreview"
                                alt="front-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.front.image.width + 'px', // Customize width as needed
                                    height: elements.front.image.height + 'px', // Customize height as needed
                                    zIndex: '0',
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>
                    <div v-show="activeView === 'back'" :class="styleClasses.canvasImage">
                        <img class="" :src="images.back" alt="back" />

                        <div :class="styleClasses.canvasOverlay" id="back-canvas">
                            <span
                                v-for="(text, i) in elements.back.texts"
                                :key="`back-text-${i}`"
                                @click="selectElement('text', { ...text, index: i })"
                                :class="styleClasses.textElement"
                                :style="{
                                    fontSize: `${text.size}px`,
                                    fontFamily: text.font,
                                    color: text.color,
                                    zIndex: i + 1,
                                    position: 'absolute',
                                    top: 4,
                                    left: 4,
                                }"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                >{{ text.text }}</span
                            >

                            <img
                                v-if="elements.back.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.back.imagePreview"
                                alt="back-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.back.image.width + 'px', // Customize width as needed
                                    height: elements.back.image.height + 'px', // Customize height as needed
                                    zIndex: '0',
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Design Tools Panel -->
            <div :class="styleClasses.toolsPanel">
                <Element
                    v-show="selectedElement.text || selectedElement.image"
                    :element="selectedElement"
                    @update:element="({ type, value }) => updateElement(type, value)"
                    @delete:element="({ type, value }) => deleteElement(type, value)"
                />

                <Tools v-show="activeView === 'front'" @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))" />
                <Tools v-show="activeView === 'back'" @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))" />

                <form @submit.prevent="addToCart" class="container-secondary">
                    <h2 class="text-lg font-semibold">Confirm</h2>

                    <div class="space-y-4 pt-3">
                        <div>
                            <label for="price" :class="styleClasses.label">Product price</label>
                            <input
                                id="price"
                                type="text"
                                disabled
                                placeholder="Text Content"
                                :class="styleClasses.textInput"
                                :value="
                                    product.price.toLocaleString('en-PH', {
                                        style: 'currency',
                                        currency: 'PHP',
                                    })
                                "
                            />
                        </div>
                        <div>
                            <label for="quantity" :class="styleClasses.label">Quantity</label>
                            <input
                                v-model="form.quantity"
                                id="quantity"
                                min="12"
                                max="24"
                                type="number"
                                placeholder="Text Content"
                                :class="styleClasses.textInput"
                            />
                        </div>

                        <div class="flex justify-end gap-2 text-xs font-semibold">
                            <span>Total amount: </span>
                            <span>{{ (product.price * form.quantity).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</span>
                        </div>

                        <div class="gap-2 md:flex md:justify-end">
                            <button class="btn btn-secondary" @click="canvases" type="button">Place order</button>
                            <button class="btn btn-primary" type="submit">Add to cart</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
