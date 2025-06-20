<script setup>

    const model = defineModel({
        type: [String, Number],
        default: ''
    });
    defineProps({
        label: { type: String, default: '' },
        type: { type: String, default: 'text' },
        error: { type: String, default: '' },
        placeholder: { type: String, default: '' },
        required: { type: Boolean, default: true },
        variant: { type: String, default: 'primary' },
        row: { type: Number, default: 4 },
        disabled: {type: Boolean,default: false}
    })
</script>

<template>
    <div class="form-group flex flex-col">
        <label class="input-label" :for="label">
            {{ label }}
            <span v-show="required" class="text-red-600 dark:text-red-500 font-black">*</span>
        </label>

        <textarea :disabled="disabled" v-if="type === 'textarea'" v-model="model" :type="type" :id="label" :rows="row"
            :class="[error ? `form-control-${variant} !border-red-500` : `form-control-${variant}`, 'pb-8']"
            :placeholder="placeholder" :required="required"></textarea>
        <input  :disabled="disabled" v-else v-model="model" :type="type" :id="label"
            :class="[error ? `form-control-${variant} !border-red-500` : `form-control-${variant}`]"
            :placeholder="placeholder" :required="required" />
        <small v-show="error" class="form-control-error">{{ error }}</small>
    </div>
</template>