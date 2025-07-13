<script setup>
    import { useForm } from '@inertiajs/vue3';
    import ContainerPrimary from '../../components/ContainerPrimary.vue';
    import TextInputPrimary from '../../components/ui/TextInputPrimary.vue';

    const props = defineProps({
        email: String
    });

    const form = useForm({
        email: props.email,
        password: null
    });


    function submit() {
        form.post(route('password.confirm'), {
            onError: () => form.reset(),
            onSuccess: () => form.reset()
        });
    }
</script>


<template>

    <Head title="Confirm Password" />

    <div class="h-screen grid place-content-center px-2">
        <ContainerPrimary class="-translate-y-20 md:w-md">
            <form @submit.prevent="submit">
                <h2 class="text-lg font-semibold">
                    Confirm Your Password
                </h2>
                <p>
                    Please enter your password to continue.
                </p>

                <div class="py-6">
                    <TextInputPrimary label="Password" v-model="form.password" :error="form.errors.password"
                        type="password" placeholder="Confirm password" />
                </div>

                <div class="flex md:justify-end">
                    <button :disabled="form.processing" class="btn btn-secondary w-full md:w-fit"
                        type="submit">Confirm</button>
                </div>
            </form>
        </ContainerPrimary>
    </div>

</template>