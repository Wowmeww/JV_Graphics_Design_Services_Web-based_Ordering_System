<script setup>

    const model = defineModel(String);
    defineProps({
        label: { type: String, default: '' },
        type: { type: String, default: 'text' },
        error: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: true },
    })
</script>

<template>
    <div class="form-group flex flex-col">
        <label class="input-label" for="name">
            {{ label }}
            <span v-show="required" class="text-red-600 dark:text-red-500 font-black">*</span>
        </label>
        <input v-model="model" :type="type" :id="label" class="form-control" :class="{ '!border-red-500': error }"
            :placeholder="placeholder" :required="required" />
        <small v-show="error" class="form-control-error">{{ error }}</small>
    </div>
</template>