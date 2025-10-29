<script setup>
import { computed, reactive, ref, watch } from 'vue';
import Element from '@/components/designer/Element.vue';
import Tools from '@/components/designer/Tools.vue';
import initializeDragAndDrop from './dragDrop.js';
import { router, useForm, usePage } from '@inertiajs/vue3';
import html2canvas from 'html2canvas';
import { debounce } from 'lodash';

const props = defineProps({
    product: Object,
});
const page = usePage();

const processing = ref(false);

// Fixed images computed property - check if images exist before accessing
const images = computed(() => ({
    front: props.product.images[0] ? `/storage/${props.product.images[0].image_path}` : null,
    back: props.product.images[1] ? `/storage/${props.product.images[1].image_path}` : null,
    right: props.product.images[2] ? `/storage/${props.product.images[2].image_path}` : null,
    left: props.product.images[3] ? `/storage/${props.product.images[3].image_path}` : null,
}));

const selectedElement = reactive({
    from: 'front',
    text: null,
    image: null,
    design: null,
});

const elements = reactive({
    front: {
        texts: [],
        image: null,
        imagePreview: null,
        design: null,
    },
    back: {
        texts: [],
        image: null,
        imagePreview: null,
        design: null,
    },
    right: {
        texts: [],
        image: null,
        imagePreview: null,
        design: null,
    },
    left: {
        texts: [],
        image: null,
        imagePreview: null,
        design: null,
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
    debounce(async (newElements) => {
        if (processing.value) return; // prevent overlaps
        processing.value = true;

        try {
            const sides = ['front', 'back', 'right', 'left'];

            const canvasConfigs = sides.flatMap((side, i) => {
                const hasContent =
                    newElements[side].image?.file || (newElements[side].texts?.filter(Boolean).length ?? 0) || newElements[side].design;

                return [
                    {
                        selector: `#${side}-canvas`,
                        shouldCapture: hasContent,
                        shouldRemove: !hasContent,
                        label: `${side} uploaded design`,
                        filename: `${side}-design.png`,
                        index: i * 2,
                    },
                    {
                        selector: `#${side}-preview`,
                        shouldCapture: hasContent,
                        shouldRemove: !hasContent,
                        label: `${side} preview`,
                        filename: `${side}-preview.png`,
                        index: i * 2 + 1,
                    },
                ];
            });

            // ensure array has 8 slots
            if (!Array.isArray(form.images)) form.images = Array(8).fill(null);
            else if (form.images.length < 8) form.images.length = 8;

            // process one by one
            for (const config of canvasConfigs) {
                if (config.shouldRemove) {
                    form.images[config.index] = null;
                    continue;
                }
                // small delay to ensure DOM updates are done
                await new Promise((resolve) => setTimeout(resolve, 400));
                const el = document.querySelector(config.selector);
                if (!el) {
                    console.warn(`Element not found: ${config.selector}`);
                    continue;
                }

                try {
                    const canvas = await html2canvas(el, {
                        scale: 1,
                        logging: false,
                        useCORS: true,
                        backgroundColor: null,
                    });

                    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png', 0.9));

                    if (blob) {
                        form.images[config.index] = {
                            label: config.label,
                            file: new File([blob], config.filename, { type: 'image/png' }),
                        };
                    }
                } catch (error) {
                    console.error(`Failed to capture ${config.label}:`, error);
                }
            }

            // cleanup any nulls at the end (optional)
            form.images = form.images.filter(Boolean);
        } catch (error) {
            console.error('Canvas processing error:', error);
        } finally {
            processing.value = false;
        }
    }, 800),
    { deep: true },
);

async function addToCart() {
    if (processing.value) return;

    processing.value = true;

    try {
        // Remove duplicates more efficiently
        form.images = form.images.filter((image, index, self) => index === self.findIndex((img) => img.label === image.label));

        await form.post(
            route('cart.store', {
                product: props.product.id,
            }),
            {
                onFinish: () => {
                    processing.value = false;
                },
                onError: () => {
                    processing.value = false;
                },
            },
        );
    } catch (error) {
        console.error('Error adding to cart:', error);
        processing.value = false;
    }
}

const activeView = ref('front');

function updateElement(type, value) {
    const to = activeView.value;
    console.log(value.from);
    if (type === 'text' && to === value.from) {
        elements[to].texts[value.index] = value;
    } else if (type === 'image' && to === value.from) {
        elements[to].image = value;
    } else if (type === 'design') {
        console.log(value);
        elements[to].design = value;
    }
}

function deleteElement(type, value) {
    const to = activeView.value;
    console.log(value);
    if (type === 'text' && to === value.from) {
        elements[to].texts[value.text.index] = null;
    } else if (type === 'image' && to === value.from) {
        elements[to].image = null;
        elements[to].imagePreview = null;
    } else if (type === 'design' && to === value.from) {
        elements[to].design = null;
        console.log('update');
    }
    selectedElement.text = null;
    selectedElement.image = null;
    selectedElement.design = null;
    selectedElement.type = null;
}

const switchView = (view) => {
    activeView.value = view;
    selectedElement.from = view;
    selectedElement.text = null;
    selectedElement.image = null;
    selectedElement.design = null;
};

function addText(text) {
    const to = activeView.value;
    const newText = {
        text,
        font: 'Roboto',
        size: 24,
        color: '#000000',
        index: elements[to].texts.length,
        from: activeView.value,
        rotate: 0,
    };
    elements[to].texts.push(newText);
    selectElement('text', newText);
}

function addImage(imageFile) {
    const to = activeView.value;
    elements[to].imagePreview = URL.createObjectURL(imageFile);
    elements[to].image = {
        from: to,
        file: imageFile,
        rotate: 0,
    };
    selectElement('image', elements[to].image);
}

function selectElement(type, value, event = null) {
    const from = activeView.value;
    selectedElement.type = type;
    selectedElement.from = from;

    selectedElement.text = null;
    selectedElement.image = null;
    selectedElement.design = null;

    switch (type) {
        case 'text':
            selectedElement.text = value;
            break;

        case 'image':
            if (event && event.currentTarget) {
                elements[from].image.width = event.currentTarget.clientWidth;
                elements[from].image.height = event.currentTarget.clientHeight;
            }
            selectedElement.image = elements[from].image;
            break;

        case 'design':
            if (event && event.currentTarget) {
                elements[from].design.width = event.currentTarget.clientWidth;
                elements[from].design.height = event.currentTarget.clientHeight;
            }
            selectedElement.design = elements[from].design;
            break;
    }
}

// Computed property for total amount
const totalAmount = computed(() => {
    return (props.product.price * form.quantity).toLocaleString('en-PH', {
        style: 'currency',
        currency: 'PHP',
    });
});

const styleClasses = {
    container: 'mx-auto max-w-7xl px-4 py-8',
    layout: 'flex flex-col lg:gap-8 lg:flex-row',
    previewSection: 'container-secondary h-fit flex-1 space-y-6 p-6 rounded-2xl shadow-lg bg-white dark:bg-gray-800',
    headerContainer: 'flex flex-col gap-4 pb-4 border-b border-gray-200 dark:border-gray-700',
    title: 'text-2xl font-bold text-gray-900 dark:text-white',
    subtitle: 'text-sm text-gray-500 dark:text-gray-400 mt-1',
    viewToggleContainer:
        'mx-auto flex w-max divide-x divide-gray-300 overflow-hidden rounded-xl border border-gray-200 bg-white rtl:flex-row-reverse dark:divide-gray-700 dark:border-gray-700 dark:bg-gray-800 shadow-sm',
    viewToggleButton: 'lg:px-5 px-3 py-2 text-sm font-medium transition-all duration-300 sm:px-6 flex items-center',
    viewToggleActive: 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md',
    viewToggleInactive: 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700',
    viewToggleDisabled: 'cursor-not-allowed opacity-50',
    canvasContainer: 'grid place-content-center p-2 bg-gray-50 dark:bg-gray-900 rounded-xl mt-4',
    canvasImage: 'relative rounded-xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl',
    canvasOverlay: 'absolute inset-0',
    textElement:
        'cursor-pointer rounded-lg border-2 border-dashed border-transparent px-3 py-1.5 hover:border-indigo-500 hover:shadow-md transition-all duration-200 active:bg-white/80 backdrop-blur-sm',
    toolsPanel: 'flex flex-col gap-6 lg:w-96',
    uploadedImage: 'hover:drop-shadow-2xl p-0 transition-all duration-300 hover:scale-105 cursor-move',
    label: 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1',
    textInput:
        'mt-1 block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:ring-2 focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none transition-all duration-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:border-blue-300',
    buttonPrimary:
        'w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center',
    buttonDisabled: 'cursor-not-allowed opacity-50',
    processingIndicator: 'text-center text-sm text-blue-600 py-2 flex items-center justify-center',
    totalAmount: 'text-lg font-bold text-indigo-600 dark:text-indigo-400',
    sectionCard: 'container-secondary rounded-2xl p-6 shadow-md bg-white dark:bg-gray-800',
    sectionTitle: 'text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700',
};
</script>

<!-- enhance the style but dot touch the logics -->
<template>
    <Head title="Designer" />
    <div :class="styleClasses.container">
        <div :class="styleClasses.layout">
            <!-- T-Shirt Preview Section -->
            <div :class="styleClasses.previewSection">
                <div :class="styleClasses.headerContainer">
                    <div>
                        <Link :href="route('shop.show', { product: product.id })" class="text-link mb-2 flex items-center gap-3">
                            <i class="bi bi-arrow-left text-2xl"></i> <span>Back</span></Link
                        >
                        <h1 :class="styleClasses.title">T-Shirt Designer</h1>
                        <small :class="styleClasses.subtitle">For best results, we recommend uploading only high-quality images.</small>
                    </div>

                    <div :class="styleClasses.viewToggleContainer">
                        <button
                            @click="!processing && switchView('front')"
                            :disabled="processing"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'front' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                                processing ? styleClasses.viewToggleDisabled : '',
                            ]"
                        >
                            Front
                            <span v-if="processing && activeView === 'front'" class="ml-2">
                                <i class="fas fa-spinner fa-spin text-xs"></i>
                            </span>
                        </button>
                        <button
                            v-if="images.back"
                            @click="!processing && switchView('back')"
                            :disabled="processing"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'back' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                                processing ? styleClasses.viewToggleDisabled : '',
                            ]"
                        >
                            Back
                            <span v-if="processing && activeView === 'back'" class="ml-2">
                                <i class="fas fa-spinner fa-spin text-xs"></i>
                            </span>
                        </button>
                        <button
                            v-if="images.right"
                            @click="!processing && switchView('right')"
                            :disabled="processing"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'right' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                                processing ? styleClasses.viewToggleDisabled : '',
                            ]"
                        >
                            Right
                            <span v-if="processing && activeView === 'right'" class="ml-2">
                                <i class="fas fa-spinner fa-spin text-xs"></i>
                            </span>
                        </button>
                        <button
                            v-if="images.left"
                            @click="!processing && switchView('left')"
                            :disabled="processing"
                            :class="[
                                styleClasses.viewToggleButton,
                                activeView === 'left' ? styleClasses.viewToggleActive : styleClasses.viewToggleInactive,
                                processing ? styleClasses.viewToggleDisabled : '',
                            ]"
                        >
                            Left
                            <span v-if="processing && activeView === 'left'" class="ml-2">
                                <i class="fas fa-spinner fa-spin text-xs"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <!--------- Canvas ------------------------------------------------------------------------------------->
                <div :class="styleClasses.canvasContainer">
                    <!-------- FRONT ----------------------------------------------------------------------->
                    <div v-show="activeView === 'front'" :class="styleClasses.canvasImage" id="front-preview">
                        <img class="h-auto w-full" :src="images.front" alt="front" />

                        <div :class="styleClasses.canvasOverlay" id="front-canvas">
                            <template v-for="(text, i) in elements.front.texts" :key="`front-text-${i}`">
                                <span
                                    v-if="text"
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
                                    @touchstart.prevent="
                                        (e) => {
                                            initializeDragAndDrop(e);
                                            selectElement('text', { ...text, index: i });
                                        }
                                    "
                                    >{{ text.text }}</span
                                >
                            </template>

                            <img
                                id="front-image"
                                v-if="elements.front.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('image', null, e);
                                    }
                                "
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
                            <img
                                v-if="elements.front.design?.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('design', null, e);
                                    }
                                "
                                @click="(e) => selectElement('design', null, e)"
                                :src="`/storage/${elements.front.design.image}`"
                                alt="front-view-design"
                                :style="{
                                    position: 'absolute',
                                    zIndex: '0',
                                    width: '35%',
                                    height: 'auto',
                                    scale: (elements.front.design?.scale ?? 100) / 100,
                                    transform: `rotate(${elements.front.design?.rotate || 0}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>

                    <!-------- BACK ----------------------------------------------------------------------->
                    <div v-show="activeView === 'back'" :class="styleClasses.canvasImage" id="back-preview">
                        <img class="h-auto w-full" :src="images.back" alt="back" />

                        <div :class="styleClasses.canvasOverlay" id="back-canvas">
                            <template v-for="(text, i) in elements.back.texts" :key="`back-text-${i}`">
                                <span
                                    v-if="text"
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
                                    @touchstart.prevent="
                                        (e) => {
                                            initializeDragAndDrop(e);
                                            selectElement('text', { ...text, index: i });
                                        }
                                    "
                                    >{{ text.text }}</span
                                >
                            </template>

                            <img
                                id="back-image"
                                v-if="elements.back.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('image', null, e);
                                    }
                                "
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
                            <img
                                v-if="elements.back.design?.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('design', null, e);
                                    }
                                "
                                @click="(e) => selectElement('design', null, e)"
                                :src="`/storage/${elements.back.design.image}`"
                                alt="back-view-design"
                                :style="{
                                    position: 'absolute',
                                    zIndex: '0',
                                    width: '35%',
                                    height: 'auto',
                                    scale: (elements.back.design?.scale ?? 100) / 100,
                                    transform: `rotate(${elements.back.design?.rotate || 0}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>

                    <!-------- RIGHT SIDE ----------------------------------------------------------------------->
                    <div v-show="activeView === 'right'" :class="styleClasses.canvasImage" id="right-preview">
                        <img class="h-auto w-full" :src="images.right || images.front" alt="right side" />

                        <div :class="styleClasses.canvasOverlay" id="right-canvas">
                            <template v-for="(text, i) in elements.right.texts" :key="`right-text-${i}`">
                                <span
                                    v-if="text"
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
                                    @touchstart.prevent="
                                        (e) => {
                                            initializeDragAndDrop(e);
                                            selectElement('text', { ...text, index: i });
                                        }
                                    "
                                    >{{ text.text }}</span
                                >
                            </template>

                            <img
                                id="right-image"
                                v-if="elements.right.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('image', null, e);
                                    }
                                "
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.right.imagePreview"
                                alt="right-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.right?.image?.width ? elements.right?.image?.width + 'px' : '25%',
                                    height: elements.right?.image?.height ? elements.right?.image?.height + 'px' : 'auto',
                                    zIndex: '0',
                                    transform: `rotate(${elements.right.image.rotate}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                            <img
                                v-if="elements.right.design?.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('design', null, e);
                                    }
                                "
                                @click="(e) => selectElement('design', null, e)"
                                :src="`/storage/${elements.right.design.image}`"
                                alt="right-view-design"
                                :style="{
                                    position: 'absolute',
                                    zIndex: '0',
                                    width: '25%',
                                    height: 'auto',
                                    scale: (elements.right.design?.scale ?? 100) / 100,
                                    transform: `rotate(${elements.right.design?.rotate || 0}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>

                    <!-------- LEFT SIDE ----------------------------------------------------------------------->
                    <div v-show="activeView === 'left'" :class="styleClasses.canvasImage" id="left-preview">
                        <img class="h-auto w-full" :src="images.left || images.front" alt="left side" />

                        <div :class="styleClasses.canvasOverlay" id="left-canvas">
                            <template v-for="(text, i) in elements.left.texts" :key="`left-text-${i}`">
                                <span
                                    v-if="text"
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
                                    @touchstart.prevent="
                                        (e) => {
                                            initializeDragAndDrop(e);
                                            selectElement('text', { ...text, index: i });
                                        }
                                    "
                                    >{{ text.text }}</span
                                >
                            </template>

                            <img
                                id="left-image"
                                v-if="elements.left.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('image', null, e);
                                    }
                                "
                                @click="(e) => selectElement('image', null, e)"
                                :src="elements.left.imagePreview"
                                alt="left-view-uploaded-image"
                                :style="{
                                    position: 'absolute',
                                    width: elements.left?.image?.width ? elements.left?.image?.width + 'px' : '25%',
                                    height: elements.left?.image?.height ? elements.left?.image?.height + 'px' : 'auto',
                                    zIndex: '0',
                                    transform: `rotate(${elements.left.image.rotate}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                            <img
                                v-if="elements.left.design?.image"
                                @mousedown="(e) => initializeDragAndDrop(e)"
                                @touchstart.prevent="
                                    (e) => {
                                        initializeDragAndDrop(e);
                                        selectElement('design', null, e);
                                    }
                                "
                                @click="(e) => selectElement('design', null, e)"
                                :src="`/storage/${elements.left.design.image}`"
                                alt="left-view-design"
                                :style="{
                                    position: 'absolute',
                                    zIndex: '0',
                                    width: '25%',
                                    height: 'auto',
                                    scale: (elements.left.design?.scale ?? 100) / 100,
                                    transform: `rotate(${elements.left.design?.rotate || 0}deg)`,
                                }"
                                :class="styleClasses.uploadedImage"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div :class="styleClasses.toolsPanel">
                <!-------- SELECTED ELEMENT ----------------------------------------------------------------------->
                <Element
                    v-if="selectedElement.text || selectedElement.image || selectedElement.design"
                    :element="selectedElement"
                    @update:element="({ type, value }) => updateElement(type, value)"
                    @delete:element="({ type, value }) => deleteElement(type, value)"
                />

                <!-- Design Tools Panel -->
                <Tools
                    :designs="product.designs"
                    v-show="activeView === 'front'"
                    @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))"
                    @add-element:design="({ value }) => (elements.front.design = value)"
                />
                <Tools
                    :designs="product.designs"
                    v-show="activeView === 'back'"
                    @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))"
                    @add-element:design="({ value }) => (elements.back.design = value)"
                />
                <Tools
                    :designs="product.designs"
                    v-show="activeView === 'right'"
                    @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))"
                    @add-element:design="({ value }) => (elements.right.design = value)"
                />
                <Tools
                    :designs="product.designs"
                    v-show="activeView === 'left'"
                    @add-element="({ value, type }) => (type === 'text' ? addText(value) : addImage(value))"
                    @add-element:design="({ value }) => (elements.left.design = value)"
                />

                <form @submit.prevent="addToCart" :class="styleClasses.sectionCard">
                    <h2 :class="styleClasses.sectionTitle">Confirm Order</h2>

                    <div class="space-y-5">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label for="price" :class="styleClasses.label">Product Price</label>
                                <input
                                    id="price"
                                    type="text"
                                    disabled
                                    :class="styleClasses.textInput"
                                    :value="
                                        product.price.toLocaleString('en-PH', {
                                            style: 'currency',
                                            currency: 'PHP',
                                        })
                                    "
                                />
                            </div>
                            <div class="flex-1">
                                <label for="quantity" :class="styleClasses.label">Quantity</label>
                                <input v-model="form.quantity" id="quantity" min="12" max="24" type="number" :class="styleClasses.textInput" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-200 py-3 dark:border-gray-700">
                            <span class="font-semibold text-gray-700 dark:text-gray-300">Total Amount:</span>
                            <span :class="styleClasses.totalAmount">{{ totalAmount }}</span>
                        </div>

                        <!-- Processing Indicator -->
                        <div v-if="processing" :class="styleClasses.processingIndicator">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Processing design...
                        </div>

                        <div class="flex flex-col gap-2">
                            <button
                                :class="[styleClasses.buttonPrimary, processing ? styleClasses.buttonDisabled : '']"
                                type="submit"
                                :disabled="processing"
                            >
                                <span v-if="processing">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    Processing...
                                </span>
                                <span v-else>
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
