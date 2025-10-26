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
    form.post(route('rating.store', { order: props.order.id }), {
        onFinish: () => emit('closeRatingModal'),
    });
}
</script>

<template>
    <div class="bg-primary/30 absolute inset-0 grid place-content-center text-slate-800">
        <form @submit.prevent="submit" class="w-full min-w-xs -translate-y-20 space-y-4 rounded-xl bg-white p-6 shadow-xl">
            <p v-if="!order.rating" class="text-center text-xl font-bold text-gray-800">Rate the product</p>
            <p v-else class="text-center text-xl font-bold text-gray-800">Your rating</p>
            <!-- {{ order }} -->
            <div class="flex justify-center gap-2 text-2xl text-yellow-400">
                <button type="button" v-for="i in 5" class="transform transition hover:scale-125" @click="form.stars = i">
                    <i v-if="i > form.stars" class="fa-regular fa-star"></i>
                    <i v-else class="fa-solid fa-star"></i>
                    <!-- <i v-else class="fa-solid fa-star"></i> -->
                </button>
            </div>

            <TextInputPrimary label="" :required="false" placeholder="Write a comment (optional)" v-model="form.message" type="textarea" />

            <div class="grid grid-cols-2 gap-3 px-2">
                <button type="button" @click="emit('closeRatingModal')" class="rounded-md px-6 py-2 font-semibold shadow transition duration-300">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-6 py-2 font-semibold text-white shadow transition duration-300 hover:bg-blue-700"
                >
                    Submit
                </button>
            </div>

            <small class="mt-1 block text-sm text-gray-500 italic"> After 24 hours, rating updates will no longer be permitted. </small>
        </form>
    </div>
</template>
