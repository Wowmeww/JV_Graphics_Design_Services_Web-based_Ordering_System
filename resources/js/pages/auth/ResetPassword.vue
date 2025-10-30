<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
    import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';

    const props = defineProps({
        token: String,
        status: String,
        email: String
    });

    const form = useForm({
        password: null,
        password_confirmation: null,
        token: props.token,
        email: props.email
    });

    function submit() {
        form.post(route('password.reset.update'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }

</script>

<template>
    <Head title="Reset password" />
    <div class="h-screen mx-auto flex justify-center items-center">
        <ContainerPrimary class="md:min-w-2xl mb-40 animate__animated animate__slideInUp " title="Reset your password"
            lead="Create a new password, then login to your account.">
            <form class="pt-6 space-y-6" @submit.prevent="submit">

                <TextInputPrimary label="Password" :error="form.errors.password" v-model="form.password" type="password" />
                <TextInputPrimary label="Confirm Password" v-model="form.password_confirmation" type="password" />

                <ButtonPrimary type="submit" label="Reset Password" as="submit" :disable="form.processing" />
            </form>
        </ContainerPrimary>
    </div>
</template>