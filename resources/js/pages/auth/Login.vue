<script setup>
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});
const form = useForm({
    email: null,
    password: null,
    remember: false,
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
    <ContainerPrimary
        class="animate__animated animate__slideInUp mx-auto mt-10 max-w-xl"
        title="Login to account"
        lead="Enter your email & password to login"
    >
        <div class="mt-8">
            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <small v-if="props?.status" class="text-xs font-semibold text-green-700 dark:text-green-600">{{ props.status }}</small>
                <TextInputPrimary v-model="form.email" label="Email address" :error="form.errors.email" type="email" placeholder="example@mail.com" />

                <TextInputPrimary
                    v-model="form.password"
                    label="Password"
                    :error="form.errors.password"
                    type="password"
                    placeholder="ke45heb5hre4l"
                />

                <div class="flex items-center justify-between">
                    <div class="checkbox-container flex items-center gap-2">
                        <input v-model="form.remember" type="checkbox" id="remember" class="accent-primary h-5 w-5" />
                        <label for="remember" class="cursor-pointer text-sm font-normal">Remember me</label>
                    </div>
                    <Link :href="route('password.request')" class="forgot-password-link text-link-primary text-sm"> Forgot password?</Link>
                </div>

                <div class="mt-2">
                    <ButtonPrimary :disabled="form.processing" type="submit" label="Login" />
                </div>
            </form>

            <div class="mt-8 text-center">
                <small class="text-muted mb-4 inline-block text-xs">Or continue with social account</small>

                <div class="mb-7 flex flex-col justify-center gap-3.5 md:flex-row">
                    <a :href="route('google.login')" class="btn btn-outline-primary"> <i class="fa-brands fa-google" /> Login with Google </a>
                    <!-- <a :href="route('facebook.login')" class="btn btn-outline-primary">  <i class="fa-brands fa-facebook" type="button" />
                        <span>Login with Facebook</span> </a> -->
                    <button disabled class="btn btn-outline-primary">
                        <i class="fa-brands fa-facebook" type="button" />
                        <span>Login with Facebook</span>
                    </button>
                </div>

                <p class="text-sm leading-5 font-normal">
                    You don't have an account yet
                    <Link :href="route('register')" v-text="'Register Now'" class="text-link-primary inline-block" />
                </p>
            </div>
        </div>
    </ContainerPrimary>
</template>
