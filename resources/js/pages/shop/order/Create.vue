<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import BackgroundImage from '../../../components/ui/BackgroundImage.vue';
import OrderCard from '../../../components/ui/card/OrderCard.vue';
import TextInputPrimary from '../../../components/ui/TextInputPrimary.vue';
import ButtonPrimary from '../../../components/ui/buttons/ButtonPrimary.vue';

const props = defineProps({
    items: Array,
    from: String,
    items_total_amount: Number,
});

const page = usePage();

const form = useForm({
    items: props.items,
});

function strictSanitize(input) {
    return input.replace(/[^a-zA-Z0-9 .,!?_-]/g, '');
}

function handleSubmit() {
    form.items = form.items.map((item) => {
        return {
            ...item,
            note: strictSanitize(item.note || ''),
        };
    });
    // alert('from');
    form.post(route('order.store', { from: props.from }));
}

function goBack() {
    // alert('tets');
    window.history.back();
}

//enhance the ui to perfection and remove the <style> </style> if its unesesary
const styleClass = {
    // Main form container
    formContainer: 'mx-auto max-w-6xl px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8',

    // Order items grid
    orderGrid: 'flex gap-3 lg:gap-4 flex-wrap justify-center',

    // Total amount container
    totalContainer:
        'flex items-center justify-between bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 border border-primary-200 dark:border-primary-700 rounded-xl p-4 sm:p-6',

    // Total amount label
    totalLabel: 'text-lg sm:text-xl font-bold text-primary-700 dark:text-primary-300',

    // Total amount value
    totalAmount: 'text-xl sm:text-2xl font-black text-primary-600 dark:text-primary-400',

    // Action buttons container
    actionsContainer: 'flex flex-col sm:flex-row justify-center gap-4 py-6 sm:py-8',

    // Button wrapper
    buttonWrapper: 'flex justify-center',

    // Submit button
    submitButton: 'w-full sm:w-auto min-w-48',

    // Back button
    backButton: 'w-full sm:w-auto min-w-48',
};
</script>

<template>
    <Head title="Place order" />
    <form @submit.prevent="handleSubmit" :class="styleClass.formContainer">
        <!-- Order Items Grid -->
        <div :class="styleClass.orderGrid">
            <OrderCard v-for="(item, i) in form.items" v-model="form.items[i].note" :key="i" :item="item" />
        </div>

        <!-- Total Amount Display -->
        <div :class="styleClass.totalContainer">
            <span :class="styleClass.totalLabel">Total Amount:</span>
            <span :class="styleClass.totalAmount">
                {{ items_total_amount.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
            </span>
        </div>

        <!-- Action Buttons -->
        <div :class="styleClass.actionsContainer">
            <div :class="styleClass.buttonWrapper">
                <ButtonPrimary :disable="form.processing" type="submit" label="Submit Order" :class="styleClass.submitButton" />
            </div>
            <div :class="styleClass.buttonWrapper">
                <ButtonPrimary @click="goBack" label="Back" variant="secondary" :class="styleClass.backButton" />
            </div>
        </div>
    </form>
</template>
