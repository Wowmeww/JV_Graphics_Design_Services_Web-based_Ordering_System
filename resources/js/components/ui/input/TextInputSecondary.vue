<script setup>
    const props = defineProps({
        label: String,
        required: { type: Boolean, default: false },
        type: { type: String, default: 'text' },
        placeholder: String,
        rows: { type: Number, default: 4 },
    });

    const modelValue = defineModel();
</script>

<template>
    <div class="w-full">
        <!-- Label -->
        <label :for="label" class="block mb-2 text-sm font-medium text-indigo-900 dark:text-white">
            {{ label }}
            <span v-if="required" class="text-red-600 dark:text-red-400 font-semibold">*</span>
        </label>

        <!-- Textarea -->
        <textarea v-if="type === 'textarea'" v-model="modelValue" :id="label" :name="label" :rows="rows"
            :required="required" :placeholder="placeholder"
            class="block p-2.5 px-3 w-full text-sm text-indigo-900 bg-indigo-50 rounded-lg border focus:outline-0 border-indigo-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>

        <!-- Default Input -->
        <input v-else :type="type" v-model="modelValue" :id="label" :name="label" :required="required"
            :placeholder="placeholder"
            class="bg-indigo-50 border focus:outline-0 border-indigo-300 text-indigo-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 px-3 " />
    </div>
</template>
