<script setup>
    import { useForm } from '@inertiajs/vue3';
    import Layout from './partials/layout.vue';

    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import TextInputPrimary from '../../components/ui/TextInputPrimary.vue';
    import ButtonPrimary from '../../components/ui/buttons/ButtonPrimary.vue';
    import { ref, watch } from 'vue';

    defineOptions({
        layout: Layout
    });

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: ''
    });

    const passwordStrength = ref('');

    watch(() => form.password, (value) => {
        if (!value) {
            passwordStrength.value = '';
        } else if (value.length < 6) {
            passwordStrength.value = 'Weak';
        } else if (/[A-Z]/.test(value) && /[0-9]/.test(value) && /[^A-Za-z0-9]/.test(value)) {
            passwordStrength.value = 'Strong';
        } else {
            passwordStrength.value = 'Medium';
        }
    });

    function submit() {
        form.patch(route('password.update'), {
            onError: () => form.reset(),
            onSuccess: () => form.reset()
        });
    }
</script>

<template>


    <Head title="Password" />

    <ContainerPrimary class="max-w-3xl mx-auto">
        <main class="w-full py-1">
            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Password settings</h2>

            <p class="pl-6 text-base font-medium flex items-center gap-2">
                Update your current password to keep your account secure.
            </p>

            <form @submit.prevent="submit" class="mt-4 space-y-3">
                <TextInputPrimary label="Current Password" type="password" v-model="form.current_password"
                    :error="form.errors.current_password" placeholder="Enter your current password" />

                <TextInputPrimary label="New Password" type="password" v-model="form.password"
                    :error="form.errors.password" placeholder="Choose a strong new password" />

                <p v-if="passwordStrength" class="text-sm mt-1 ml-1" :class="{
                    'text-red-500': passwordStrength === 'Weak',
                    'text-yellow-500': passwordStrength === 'Medium',
                    'text-green-500': passwordStrength === 'Strong',
                }">
                    Password strength: {{ passwordStrength }}
                </p>
                <TextInputPrimary label="Confirm New Password" type="password" v-model="form.password_confirmation"
                    :error="form.errors.password_confirmation" placeholder="Re-enter your new password" />

                <div class="pt-6">
                    <ButtonPrimary type="submit" label="Update Password" :with-spinner="form.processing" :disable="!form.isDirty" />
                </div>
            </form>
        </main>
    </ContainerPrimary>
</template>