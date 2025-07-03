<script setup>
    import { useForm } from '@inertiajs/vue3';
    import BackgroundImage from '../../../components/ui/BackgroundImage.vue';
    import OrderCard from '../../../components/ui/card/OrderCard.vue';
    import TextInputPrimary from '../../../components/ui/TextInputPrimary.vue';
    import ButtonPrimary from '../../../components/ui/buttons/ButtonPrimary.vue';

    const props = defineProps({
        items: Array,
        from: String
    });

    const form = useForm({
        items: props.items
    });

    function strictSanitize(input) {
        return input.replace(/[^a-zA-Z0-9 .,!?_-]/g, '');
    }

    function handleSubmit() {
        form.items = form.items.map(item => {
            return {
                ...item,
                note: strictSanitize(item.note || '')
            }
        })
        // alert('from');
        form.post(route('order.store', { from: props.from }));
    }

    function goBack() {
        // alert('tets');
        window.history.back();
    }
</script>

<template>

    <BackgroundImage path="/images/background/blob.jpg" class="h-full overflow-y-auto" />
    <form @submit.prevent="handleSubmit" class="mx-auto  max-w-6xl px-2  py-6">

        <div class="  grid md:grid-cols-2 gap-2 py-4">
            <OrderCard v-for="(item, i) in form.items" v-model="form.items[i].note" :key="i" :item="item" />
        </div>

        <div class="flex justify-center gap-3 py-4">
            <div>
                <ButtonPrimary :disable="form.processing" type="submit" label="Submit Order" />
            </div>
            <div>
                <ButtonPrimary @click="goBack" label="Back" variant="secondary" />
            </div>
        </div>
    </form>


</template>