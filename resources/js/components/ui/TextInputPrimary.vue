<script setup>
    import { ref } from 'vue';


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
        disabled: { type: Boolean, default: false },
        customStyle: { type: String, default: '' }
    })

    const showPassword = ref(false);
</script>

<template>
    <div class="form-group flex flex-col">
        <label class="input-label" :for="label">
            {{ label }}
            <span v-show="required" class="text-red-600 dark:text-red-500 font-black">*</span>
        </label>

        <div class="w-fill relative">
            <textarea :disabled="disabled" v-if="type === 'textarea'" v-model="model" :type="type" :id="label"
                :rows="row"
                :class="[error ? `form-control form-control-${variant} !border-red-500` : `${customStyle} form-control form-control-${variant}`, 'pb-8']"
                :placeholder="placeholder" :required="required"></textarea>
            <input :disabled="disabled" v-else v-model="model" :type="showPassword ? 'text' : type" :id="label"
                :class="[error ? `form-control form-control-${variant} !border-red-500` : `${customStyle} form-control form-control-${variant}`]"
                :placeholder="placeholder" :required="required" />

            <div v-if="type === 'password'" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <button type="button" @click="showPassword = !showPassword"
                    :class="`text-lg text-${variant} hover:underline`">
                    <i v-if="showPassword" class="bi bi-eye"></i>
                    <i v-else class="bi bi-eye-slash"></i>
                </button>
            </div>
        </div>
        <small v-show="error" class="form-control-error">{{ error }}</small>
    </div>
</template>