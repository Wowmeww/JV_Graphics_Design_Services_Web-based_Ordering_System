<script setup>
import { onMounted, reactive, ref } from 'vue';

defineProps({
    designs: Array,
});

const tabs = ['text', 'upload', 'designs'];
const activeTab = ref(tabs[0]);
const emit = defineEmits(['addElement', 'addElement:design']);

const temps = reactive({
    text: null,
    image: null,
    error: {
        image: '',
    },
    element_id: null,
});
function addElement(type) {
    emit('addElement', {
        type,
        value: temps[type],
    });

    if (type === 'text') {
        temps.text = null;
    }
}
function addDesign(design) {
    emit('addElement:design', {
        type: 'design',
        value: {
            ...design,
            rotate: 0,
        },
    });
}

function handleImageChange(event) {
    const file = event.target.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes

    if (file.size > maxSize) {
        temps.error.image = 'The image should not be more than 2MB';
        return;
    }
    temps.error.image = '';
    temps.image = file;
    addElement('image');
}

const styleClass = {
    container: 'container-secondary m-0 h-fit',
    title: 'text-lg font-semibold',
    tabContainer: 'grid grid-cols-3 gap-1 rounded bg-gray-300 p-1 font-medium dark:bg-gray-600',
    tabButton: 'rounded px-4 py-1 hover:bg-white dark:hover:bg-slate-900',
    activeTab: 'bg-white dark:bg-slate-900',
    label: 'block text-sm text-gray-500 dark:text-gray-300',
    textInput:
        'mt-2 block w-full rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-gray-700 placeholder-gray-400/70 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:focus:border-blue-300',
    fileInput:
        'mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 placeholder-gray-400/70 file:rounded-full file:border-none file:bg-gray-200 file:px-4 file:py-1 file:text-sm file:text-gray-700 focus:border-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:file:bg-gray-800 dark:file:text-gray-200 dark:focus:border-blue-300',
    addButton: 'btn btn-secondary mt-3 w-full',
    errorText: 'form-control-error',
};

onMounted(() => (temps.element_id = Math.ceil(Math.random() * 9000000000)));
</script>

<template>
    <div :class="styleClass.container">
        <h1 :class="styleClass.title">Design Tools</h1>

        <!-- Tab Buttons -->
        <div class="py-3">
            <div :class="styleClass.tabContainer">
                <button
                    v-for="tab in tabs"
                    :key="`tab-${tab}`"
                    :class="[styleClass.tabButton, { [styleClass.activeTab]: tab === activeTab }]"
                    @click="() => (activeTab = tab)"
                >
                    {{ tab[0].toUpperCase() + tab.substring(1) }}
                </button>
            </div>
        </div>

        <!-- Tab Content with Transitions -->

        <div v-show="activeTab === 'text'" :key="activeTab" class="animate__animated animate__fadeIn">
            <div>
                <div>
                    <label :for="`text-content-${temps.element_id}`" :class="styleClass.label">Text Content</label>
                    <input
                        v-model="temps.text"
                        :id="`text-content-${temps.element_id}`"
                        type="text"
                        placeholder="Text Content"
                        :class="styleClass.textInput"
                    />
                </div>
            </div>
            <div>
                <button :disabled="!temps.text" @click="addElement('text')" :class="styleClass.addButton">Add Text</button>
            </div>
        </div>
        <div v-show="activeTab === 'upload'" :key="activeTab" class="animate__animated animate__fadeIn">
            <div>
                <label for="image" :class="styleClass.label">Image</label>
                <input type="file" id="image" accept="image/*" @change="handleImageChange" :class="styleClass.fileInput" />
                <small v-if="temps.error.image" :class="styleClass.errorText">{{ temps.error.image }}</small>
            </div>
        </div>
        <div v-show="activeTab === 'designs'" :key="activeTab" class="animate__animated animate__fadeIn flex flex-wrap gap-2">
            <!-- make the style class look perfect -->
            <img
                v-for="design in designs"
                :key="`design-${design.id}`"
                :src="`/storage/${design.image}`"
                class="size-16 cursor-pointer overflow-hidden rounded-2xl border border-slate-300 object-cover transition-all hover:border-slate-400 dark:border-slate-600 dark:hover:border-slate-400"
                @click="addDesign(design)"
            />
        </div>
    </div>
</template>
