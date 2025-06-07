<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '../../components/ContainerPrimary.vue';
    import ButtonPrimary from '../../components/ui/buttons/ButtonPrimary.vue';
    import TextInputPrimary from '../../components/ui/TextInputPrimary.vue';

    const props = defineProps({
        status: String
    });

    const form = useForm({
        email: null
    });

    function submit() {
        form.post(route('password.email'));
    }

</script>

<template>

    <Head title="Forgot password" />
    <div class="h-screen mx-auto flex justify-center items-center">
        <ContainerPrimary class="md:min-w-2xl mb-40 animate__animated animate__slideInUp " title="Reset password"
            lead="Enter a valid email to receive instructions on how to reset your password.">
            <form class="pt-6 space-y-6" @submit.prevent="submit">
                <div>
                    <TextInputPrimary label="Email" :error="form.errors.email" type="email" v-model="form.email" />
                    <small v-if="props.status" class="text-xs font-semibold text-green-700 dark:text-green-600">{{ props.status }}</small>
                </div>
                <ButtonPrimary :disabled="form.processing" type="submit" label="Reset Password" as="submit" />
            </form>
        </ContainerPrimary>
    </div>
</template>