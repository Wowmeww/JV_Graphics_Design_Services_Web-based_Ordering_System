<script setup>
import { useForm } from '@inertiajs/vue3';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import PasswordStrengthIndicator from '@/components/PasswordStrengthIndicator.vue';

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
});
defineOptions({
    layout: GuestLayout,
});
function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Sign up" />
    <ContainerPrimary
        class="animate__animated animate__slideInUp mx-auto mt-10 mb-6 max-w-xl"
        title="Create your account"
        lead="Enter your personal details to create account"
    >
        <div class="mt-8">
            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <TextInputPrimary v-model="form.name" label="Name" :error="form.errors.name" placeholder="John Doe" />
                <TextInputPrimary v-model="form.email" label="Email address" :error="form.errors.email" type="email" placeholder="example@mail.com" />
                <div>
                    <TextInputPrimary
                        v-model="form.password"
                        label="Password"
                        :error="form.errors.password"
                        type="password"
                        placeholder="ke45heb5hre4l"
                    />
                    <PasswordStrengthIndicator :password="form.password" />
                </div>
                <TextInputPrimary
                    v-model="form.password_confirmation"
                    label="Confirm password"
                    :error="form.errors.password_confirmation"
                    type="password"
                    placeholder="ke45heb5hre4l"
                />

                <div class="mt-2">
                    <ButtonPrimary :disabled="form.processing" type="submit" label="Register" />
                </div>
            </form>

            <div class="mt-8 text-center">
                <small class="text-muted mb-4 inline-block text-xs">Or continue with social account</small>

                <div class="mb-7 flex flex-col justify-center gap-3.5 md:flex-row">
                    <a :href="route('google.login')" class="btn btn-outline-primary" target="_blank"> <i class="fa-brands fa-google" /> Login with Google </a>
                    <!-- <a :href="route('facebook.login')" class="btn btn-outline-primary"><i class="fa-brands fa-facebook" /> Login with Facebook</a> -->
                    <!-- <button disabled class="btn btn-outline-primary">
                        <i class="fa-brands fa-facebook" type="button" />
                        <span>Login with Facebook</span>
                    </button> -->
                </div>

                <p class="text-sm leading-5 font-normal">
                    Already have account?
                    <Link :href="route('login')" v-text="'Login Now'" class="text-link-primary inline-block" />
                </p>
            </div>
        </div>
    </ContainerPrimary>
</template>
