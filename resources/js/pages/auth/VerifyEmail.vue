<script setup>
    import { router, useForm, usePage } from '@inertiajs/vue3'
    import { watch } from 'vue';

    defineProps({
        message: String
    });

    const page = usePage();

    const form = useForm();

    function resendVerification() {
        form.post(route('verification.send'));
    }
    function logout() {
        page.props.confirm = {
            show: true,
            message: 'Do you want to logout?',
            confirmed: false,
            action: {
                logout: true
            },
        }
    }
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4 -translate-y-12">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg w-full max-w-md p-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Verify Your Email</h1>

            <p class="text-gray-600 dark:text-gray-300">
                Please check your email for a verification link to continue.
            </p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-4">
                Didn't get the email? You can request another below.
            </p>

            <div v-if="form.recentlySuccessful || message" class="text-green-600 dark:text-green-400 text-sm mb-4">
                A new verification link has been sent to your email address.
            </div>

            <form @submit.prevent="resendVerification" class="space-y-4">
                <button type="submit" :disabled="form.processing"
                    class="w-full py-2 px-4 disabled:cursor-wait disabled:opacity-40 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    Resend Verification Email
                </button>
            </form>

            <form @submit.prevent="logout" class="mt-4">
                <button type="submit" class="w-full text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</template>
