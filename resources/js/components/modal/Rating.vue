<script setup>
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        order: Object
    });

    const emit = defineEmits(['closeRatingModal']);

    const form = useForm({
        stars: props.order.rating ? props.order.rating.stars : 0,
        message: props.order.rating ? props.order.rating.message : ''
    });

    function submit() {
        form.post(route('rating.store', { order: props.order.id }), {
            onFinish: () => emit('closeRatingModal')
        });
    }
</script>

<template>

    <div class="bg-primary/30 absolute inset-0 grid place-content-center text-slate-800">
        <form @submit.prevent="submit"
            class="bg-white rounded-xl shadow-xl w-full min-w-xs p-6 space-y-4 -translate-y-20">
            <p class="text-xl font-bold text-gray-800 text-center">Rate the product</p>
            <!-- {{ order }} -->
            <div class="flex justify-center gap-2 text-yellow-400 text-2xl">
                <button type="button" v-for="i in 5" class="hover:scale-125 transition transform"
                    @click="form.stars = i">
                    <i v-if="i > form.stars" class="fa-regular fa-star"></i>
                    <i v-else class="fa-solid fa-star"></i>
                    <!-- <i v-else class="fa-solid fa-star"></i> -->
                </button>
            </div>

            <textarea
                class="w-full border border-gray-300 rounded-lg p-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="4" placeholder="Write a comment (optional)" v-model="form.message"></textarea>

            <div class="grid gap-3 px-2 grid-cols-2">
                <button type="button" @click="emit('closeRatingModal')"
                    class="font-semibold py-2 px-6 rounded-md shadow transition duration-300">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md shadow transition duration-300">
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>