<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '../../components/ContainerPrimary.vue';
    import TextInputPrimary from '../../components/ui/TextInputPrimary.vue';
    import GuestLayout from '../../layouts/GuestLayout.vue';
    import ButtonPrimary from '../../components/ui/buttons/ButtonPrimary.vue';

    const props = defineProps({
        status: String
    });
    const form = useForm({
        email: null,
        password: null,
        remember: false
    });
    defineOptions({
        layout: GuestLayout,
    });
    function submit() {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    }
</script>

<template>

    <Head title="Sign in" />
    <ContainerPrimary class="mx-auto max-w-xl animate__animated animate__slideInUp" title="Login to account"
        lead="Enter your email & password to login">
        <div class="mt-8">
            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <small v-if="props.status" class="text-xs font-semibold text-green-700 dark:text-green-600">{{
                    props.status }}</small>
                <TextInputPrimary v-model="form.email" label="Email address" :error="form.errors.email" type="email"
                    placeholder="example@mail.com" />

                <TextInputPrimary v-model="form.password" label="Password" :error="form.errors.password" type="password"
                    placeholder="ke45heb5hre4l" />

                <div class="flex justify-between items-center">
                    <div class="checkbox-container flex gap-2 items-center">
                        <input v-model="form.remember" type="checkbox" id="remember" class="w-5 h-5 accent-primary" />
                        <label for="remember" class="text-sm font-normal cursor-pointer">Remember me</label>
                    </div>
                    <Link :href="route('password.request')" class="forgot-password-link text-sm text-link-primary">
                    Forgot password?</Link>
                </div>

                <div class="mt-2">
                    <ButtonPrimary :disabled="form.processing" type="submit" label="Login" />
                </div>
            </form>

            <div class="mt-8 text-center">
                <small class="mb-4 inline-block text-xs text-muted">Or continue with social
                    account</small>

                <div class="mb-7 flex flex-col justify-center gap-3.5 md:flex-row">
                    <ButtonPrimary as="Link" href="/auth/google/redirect">
                        <i class="fa-brands fa-google" /> Login with
                        Google
                    </ButtonPrimary>
                    <ButtonPrimary><i class="fa-brands fa-facebook" /> Login with
                        Facebook</ButtonPrimary>
                </div>

                <p class="text-sm leading-5 font-normal">
                    You don't have an account yet
                    <Link :href="route('register')" v-text="'Register Now'" class="text-link-primary inline-block" />
                </p>
            </div>
        </div>
    </ContainerPrimary>
</template>
