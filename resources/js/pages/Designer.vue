<script setup>
import { computed, reactive, ref, watch } from 'vue';
import Colors from '@/components/designer/Colors.vue';
import Element from '@/components/designer/Element.vue';
import Tools from '@/components/designer/Tools.vue';
import initializeDragAndDrop from './dragDrop.js';
import { router, useForm, usePage } from '@inertiajs/vue3';
import html2canvas from 'html2canvas';
import { transform } from 'lodash';

const props = defineProps({
    product: Object,
});
const page = usePage();

const processing = ref(false);

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
    option_id: '',
    quantity: 12,
    total_amount: 0,
    type: 'custom',
    images: [],
    from: '',
});

watch(
    () => elements,
    async (newElements) => {
        try {
            processing.value = true;
            // 1. Define canvas configurations
            const canvasConfigs = [
                {
                    selector: '#front-canvas',
                    shouldCapture: newElements.front.image?.file || newElements.front.texts?.length > 0,
                    label: 'front uploaded design',
                    filename: 'front-design.png',
                },
                {
                    selector: '#front-preview',
                    shouldCapture: newElements.front.image?.file || newElements.front.texts?.length > 0,
                    label: 'front preview',
                    filename: 'front-preview.png',
                },
                {
                    selector: '#back-canvas',
                    shouldCapture: newElements.back.image?.file || newElements.back.texts?.length > 0,
                    label: 'back uploaded design',
                    filename: 'back-design.png',
                },
                {
                    selector: '#back-preview',
                    shouldCapture: newElements.back.image?.file || newElements.back.texts?.length > 0,
                    label: 'back preview',
                    filename: 'back-preview.png',
                },
            ];

            // 2. Process captures in parallel with error handling
            canvasConfigs.map(async (config) => {
                if (!config.shouldCapture) return null;

                const el = document.querySelector(config.selector);
                if (!el) {
                    console.warn(`Element not found: ${config.selector}`);
                    return null;
                }

                try {
                    const canvas = await html2canvas(el, {
                        scale: 2,
                        logging: false,
                        useCORS: true,
                        backgroundColor: null,
                        allowTaint: true,
                    });

                    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png', 0.9));

                    form.images.push(blob ? { label: config.label, file: new File([blob], config.filename, { type: 'image/png' }) } : null);

                    form.images = form.images.filter(Boolean);
                    processing.value = false;
                } catch (error) {
                    console.error(`Failed to capture ${config.label}:`, error);
                    return null;
                }
            });
        } catch (error) {
            console.error('Canvas processing error:', error);
            toast.error('Failed to process design images');
        } finally {
            // processing.value = false;
        }
    },
    { deep: true },
);

async function addToCart() {
    processing.value = true;

    form.images = form.images
        .reduceRight((acc, current) => {
            if (!acc.some((item) => item.label === current.label)) {
                acc.push(current);
            }
            return acc;
        }, [])
        .reverse();
    form.post(
        route('cart.store', {
            product: props.product.id,
        }),
        {
            onFinish: () => {
                processing.value = false;
            },
        },
    );
}
async function orderNow() {
    processing.value = true;

    form.images = form.images
        .reduceRight((acc, current) => {
            if (!acc.some((item) => item.label === current.label)) {
                acc.push(current);
            }
            return acc;
        }, [])
        .reverse();

    processing.value = false;
    page.props.custom_order_resource = form.images;
    console.log(page.props.custom_order_resource);

    router.get(
        route('order.create', {
            item: {
                product_id: form.product_id,
                option_id: form.option_id,
                quantity: form.quantity,
                total_amount: form.quantity * props.product.price,
                type: form.type,
                images: form.images,
                from: form.from,
            },
        }),
        {},
    );
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
        rotate: 0,
    });
}
function addImage(imageFile) {
    const to = activeView.value;
    const canvas = document.querySelector(`#${to}-canvas`);
    let imageElement = null;
    elements[to].imagePreview = URL.createObjectURL(imageFile);
    elements[to].image = {
        from: activeView.value,
        file: imageFile,
        rotate: 0,
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

function initialSizer(size = 0, dimension, from) {
    let temp = 0;
    dimension = dimension.toLowerCase();
    const canvas = document.querySelector(`#${from}-canvas`);
    console.dir(size);
    switch (`#${from}-canvas`) {
        case 'w' || 'width':
            temp = size;
            break;

        case 'h' || 'height':
            temp = size;
            break;
    }

    return 600 + 'px';
}
</script>

<template>
    <Head title="Designer" />
    <div :class="styleClasses.container">
        <div :class="styleClasses.layout">
            <!-- T-Shirt Preview Section -->
            <div :class="styleClasses.previewSection">
                <div :class="styleClasses.headerContainer">
                    <div>
                        <h1 :class="styleClasses.title">T-Shirt Designer</h1>
                        <small>For best results, we recommend uploading only image.</small>
                    </div>

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
                    <div v-show="activeView === 'front'" :class="styleClasses.canvasImage" id="front-preview">
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
                                    transform: `rotate(${text.rotate}deg)`,
                                }"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                >{{ text.text }}</span
                            >
                            <img
                                id="front-image"
                                v-if="elements.front.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.front.imagePreview"
                                alt="front-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.front?.image?.width ? elements.front?.image?.width + 'px' : '35%',
                                    height: elements.front?.image?.height ? elements.front?.image?.height + 'px' : 'auto',
                                    zIndex: '0',
                                    transform: `rotate(${elements.front.image.rotate}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                            {{}}
                        </div>
                    </div>
                    <div v-show="activeView === 'back'" :class="styleClasses.canvasImage" id="back-preview">
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
                                    transform: `rotate(${text.rotate}deg)`,
                                }"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                >{{ text.text }}</span
                            >
                            <img
                                id="back-image"
                                v-if="elements.back.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.back.imagePreview"
                                alt="back-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.back?.image?.width ? elements.back?.image?.width + 'px' : '35%',
                                    height: elements.back?.image?.height ? elements.back?.image?.height + 'px' : 'auto',
                                    zIndex: '0',
                                    transform: `rotate(${elements.back.image.rotate}deg)`,
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

                        <div class="flex flex-col gap-2">
                            <!-- <button class="btn btn-secondary" @click="orderNow" type="button" :disabled="processing">Place order</button> -->
                            <button class="btn btn-primary" type="submit" :disabled="processing">Add to cart</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
