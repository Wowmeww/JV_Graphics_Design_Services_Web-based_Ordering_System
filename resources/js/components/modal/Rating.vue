<script setup>
import { useForm } from '@inertiajs/vue3';
import TextInputPrimary from '../ui/TextInputPrimary.vue';

const props = defineProps({
    order: Object,
});

const emit = defineEmits(['closeRatingModal']);

const form = useForm({
    stars: props.order.rating ? props.order.rating.stars : 0,
    message: props.order.rating ? props.order.rating.message : '',
});

function submit() {
    if (props.order.rating) {
        form.patch(route('rating.update', { order: props.order.id }), {
            onFinish: () => emit('closeRatingModal'),
        });
    } else {
        form.post(route('rating.store', { order: props.order.id }), {
            onFinish: () => emit('closeRatingModal'),
        });
    }
}
const styleClass = {
    // Main overlay container
    overlay: 'bg-primary/30 backdrop-blur-sm absolute inset-0 grid place-content-center z-50',

    // Main form container
    formContainer:
        'max-w-full -translate-y-20 space-y-6 rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 dark:bg-gray-800 dark:border-gray-700',

    // Title
    title: 'text-center text-2xl font-bold text-gray-800 dark:text-white mb-2',

    // Stars container
    starsContainer: 'flex justify-center gap-3 text-3xl mb-2',
    starButton:
        'transform transition-all duration-300 hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 rounded-full p-1',
    starIcon: 'drop-shadow-sm',

    // Text input
    textInput: 'w-full',

    // Buttons container
    buttonsContainer: 'grid grid-cols-2 gap-4 px-1',
    cancelButton:
        'rounded-xl px-6 py-3 font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 shadow-sm transition-all duration-300 active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2',
    submitButton:
        'rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-300 hover:bg-blue-700 hover:shadow-xl active:scale-95 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',

    // Footer note
    footerNote: 'mt-2 block text-sm text-gray-500 dark:text-gray-400 italic text-center pt-2 border-t border-gray-100 dark:border-gray-700',
};
</script>

<template>
    <div :class="styleClass.overlay">
        <form @submit.prevent="submit" :class="styleClass.formContainer">
            <!-- Title -->
            <p v-if="!order.rating" :class="styleClass.title">Rate the Product</p>
            <p v-else :class="styleClass.title">Your Rating</p>

            <!-- Stars Rating -->
            <div :class="styleClass.starsContainer">
                <button type="button" v-for="i in 5" :key="i" :class="styleClass.starButton" @click="form.stars = i" :aria-label="`Rate ${i} stars`">
                    <i v-if="i > form.stars" class="fa-regular fa-star text-yellow-400/60 hover:text-yellow-400" :class="styleClass.starIcon"></i>
                    <i v-else class="fa-solid fa-star text-yellow-400" :class="styleClass.starIcon"></i>
                </button>
            </div>

            <!-- Comment Input -->
            <TextInputPrimary
                :label="''"
                :required="false"
                placeholder="Write a comment (optional)"
                v-model="form.message"
                type="textarea"
                :class="styleClass.textInput"
            />

            <!-- Action Buttons -->
            <div :class="styleClass.buttonsContainer">
                <button type="button" @click="emit('closeRatingModal')" :class="styleClass.cancelButton">Cancel</button>
                <button type="submit" :class="styleClass.submitButton" :disabled="form.processing">
                    {{ form.processing ? 'Submitting...' : 'Submit' }}
                </button>
            </div>

            <!-- Footer Note -->
            <small :class="styleClass.footerNote"> After 24 hours, rating updates will no longer be permitted. </small>
        </form>
    </div>
</template>

<style scoped>
/* Smooth transitions for all interactive elements */
button,
input,
textarea {
    transition: all 0.2s ease-in-out;
}

/* Enhanced focus states for accessibility */
button:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    button,
    input,
    textarea {
        transition: none;
    }

    .starButton {
        transform: none !important;
    }
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
    .formContainer {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .formContainer {
        margin: 1rem;
        padding: 1.5rem;
    }

    .starsContainer {
        gap: 2;
    }

    .buttonsContainer {
        grid-template-columns: 1fr;
        gap: 3;
    }
}
</style>
