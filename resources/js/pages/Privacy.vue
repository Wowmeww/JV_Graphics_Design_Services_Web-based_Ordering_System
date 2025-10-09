<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings);
</script>

<template>
    <Head title="Privacy" />
    <div class="min-h-screen bg-gradient-to-br  sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <div class="min-h-screen bg-gradient-to-br px-4 py-16">
                <div class="mx-auto max-w-4xl rounded-2xl border border-gray-200 bg-slate-200 p-10 text-gray-800 shadow-xl backdrop-blur-xl sm:p-12">
                    <header class="mb-12 text-center">
                        <h1 class="mb-2 text-4xl font-extrabold tracking-tight text-gray-900">Privacy & Cookie Policy</h1>
                    </header>

                    <div class="space-y-10 leading-relaxed">
                        <!-- Section 1 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">1.</span> Introduction
                            </h2>
                            <p>
                                Welcome to
                                <strong class="text-blue-700">{{ settings.app_name }}</strong
                                >. We respect your privacy and are committed to protecting your personal data. This Privacy & Cookie Policy explains
                                how we collect, use, and safeguard your information when you use our website or services.
                            </p>
                        </section>

                        <!-- Section 2 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">2.</span> Information We Collect
                            </h2>
                            <ul class="list-inside list-disc space-y-2">
                                <li>
                                    <strong>Personal Information:</strong> Such as your name, email, and contact details when you create an account or
                                    contact us.
                                </li>
                                <li>
                                    <strong>Usage Data:</strong> Information about how you use our site, such as pages visited, actions taken, and
                                    device information.
                                </li>
                                <li>
                                    <strong>Cookies:</strong> Small text files stored on your device that help us enhance your browsing experience.
                                </li>
                            </ul>
                        </section>

                        <!-- Section 3 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">3.</span> How We Use Your Information
                            </h2>
                            <p>We use the collected information to:</p>
                            <ul class="list-inside list-disc space-y-2">
                                <li>Provide, operate, and maintain our website and services.</li>
                                <li>Improve user experience and website functionality.</li>
                                <li>Communicate with you about updates, offers, or support.</li>
                                <li>Ensure security and prevent fraudulent activity.</li>
                            </ul>
                        </section>

                        <!-- Section 4 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">4.</span> Cookies Policy
                            </h2>
                            <p class="mb-3">
                                Our website uses cookies to store user preferences, analyze traffic, and improve performance. By using our site, you
                                consent to our use of cookies in accordance with this policy.
                            </p>
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">Managing Cookies</h3>
                            <p>
                                You can manage or disable cookies through your browser settings. Please note that disabling cookies may affect certain
                                functionalities of the website.
                            </p>
                        </section>

                        <!-- Section 5 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">5.</span> Data Security
                            </h2>
                            <p>
                                We implement appropriate technical and organizational measures to protect your personal data from unauthorized access,
                                disclosure, alteration, or destruction.
                            </p>
                        </section>

                        <!-- Section 6 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">6.</span> Your Rights
                            </h2>
                            <p>
                                Depending on the data, you may have rights to access, correct, or delete your personal data, and to object to or
                                restrict certain processing.
                            </p>
                        </section>

                        <!-- Section 7 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">7.</span> Changes to This Policy
                            </h2>
                            <p>
                                We may update this Privacy & Cookie Policy from time to time. Updates will be posted on this page with a new “Last
                                updated” date.
                            </p>
                        </section>

                        <!-- Section 8 -->
                        <section>
                            <h2 class="mb-3 flex items-center gap-2 text-2xl font-semibold text-gray-900">
                                <span class="text-blue-600">9.</span> Contact Us
                            </h2>
                            <p>If you have questions or concerns about this Privacy & Cookie Policy, please contact us at:</p>
                            <p class="mt-2">
                                📧
                                <a
                                    :href="`mailto:${settings.app_email}`"
                                    class="font-medium text-blue-600 transition hover:text-blue-700 hover:underline"
                                >
                                    {{ settings.app_email }}
                                </a>
                            </p>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
