<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const settings = computed(() => usePage().props.settings);

const styleClass = {
    // Layout
    pageContainer: "min-h-screen bg-gradient-to-br from-primary-50 via-indigo-50 to-secondary-100 sm:px-6 lg:px-8 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900",
    contentContainer: "mx-auto max-w-4xl min-h-screen px-4 py-16",
    
    // Main Card
    mainCard: "hover:shadow-3xl mx-auto rounded-3xl border border-gray-300/60 bg-white/80 p-10 text-gray-800 shadow-2xl backdrop-blur-xl transition-all duration-500 sm:p-12 dark:border-gray-600/40 dark:bg-gray-800/80 dark:text-gray-200",
    
    // Header
    header: "mb-12 text-center",
    title: "mb-4 bg-gradient-to-r from-secondary-600 to-primary-600 bg-clip-text text-4xl font-extrabold tracking-tight text-transparent dark:from-primary-400 dark:to-secondary-400",
    
    // Sections
    section: "group rounded-2xl p-6 transition-all duration-300 hover:bg-primary-50/50 dark:hover:bg-primary-900/20",
    sectionTitle: "mb-4 flex items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white",
    sectionNumber: "flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-300",
    
    // Text
    paragraph: "text-gray-700 dark:text-gray-300",
    appName: "bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text font-bold text-transparent dark:from-primary-400 dark:to-secondary-400",
    strongText: "text-gray-900 dark:text-white",
    
    // Lists
    list: "list-inside space-y-3 text-gray-700 dark:text-gray-300",
    listItem: "flex items-start gap-3",
    bullet: "mt-1.5 h-2 w-2 flex-shrink-0 rounded-full bg-primary-500",
    
    // Sub Headings
    subHeading: "mb-3 text-lg font-semibold text-gray-800 dark:text-gray-200",
    
    // Contact Section
    contactBox: "mt-4 flex items-center gap-3 rounded-xl bg-primary-50/50 p-4 dark:bg-primary-900/20",
    contactLink: "font-semibold text-primary-600 transition-all duration-300 hover:text-blue-700 hover:underline dark:text-blue-400 dark:hover:text-blue-300"
};
</script>

<template>
    <Head title="Privacy" />
    <div :class="styleClass.pageContainer">
        <div :class="styleClass.contentContainer">
            <div :class="styleClass.mainCard">
                <header :class="styleClass.header">
                    <h1 :class="styleClass.title">
                        Privacy & Cookie Policy
                    </h1>
                </header>

                <div class="space-y-5 leading-relaxed">
                    <!-- Section 1 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">1</span>
                            Introduction
                        </h2>
                        <p :class="styleClass.paragraph">
                            Welcome to
                            <strong :class="styleClass.appName">{{ settings.app_name }}</strong>. We respect your privacy and are committed to protecting your personal data. This Privacy & Cookie Policy explains
                            how we collect, use, and safeguard your information when you use our website or services.
                        </p>
                    </section>

                    <!-- Section 2 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">2</span>
                            Information We Collect
                        </h2>
                        <ul :class="styleClass.list">
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>
                                    <strong :class="styleClass.strongText">Personal Information:</strong> Such as your name, email, and
                                    contact details when you create an account or contact us.
                                </span>
                            </li>
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>
                                    <strong :class="styleClass.strongText">Usage Data:</strong> Information about how you use our site,
                                    such as pages visited, actions taken, and device information.
                                </span>
                            </li>
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>
                                    <strong :class="styleClass.strongText">Cookies:</strong> Small text files stored on your device that
                                    help us enhance your browsing experience.
                                </span>
                            </li>
                        </ul>
                    </section>

                    <!-- Section 3 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">3</span>
                            How We Use Your Information
                        </h2>
                        <p :class="`${styleClass.paragraph} mb-4`">We use the collected information to:</p>
                        <ul :class="styleClass.list">
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>Provide, operate, and maintain our website and services.</span>
                            </li>
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>Improve user experience and website functionality.</span>
                            </li>
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>Communicate with you about updates, offers, or support.</span>
                            </li>
                            <li :class="styleClass.listItem">
                                <span :class="styleClass.bullet"></span>
                                <span>Ensure security and prevent fraudulent activity.</span>
                            </li>
                        </ul>
                    </section>

                    <!-- Section 4 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">4</span>
                            Cookies Policy
                        </h2>
                        <p :class="`${styleClass.paragraph} mb-4`">
                            Our website uses cookies to store user preferences, analyze traffic, and improve performance. By using our site, you
                            consent to our use of cookies in accordance with this policy.
                        </p>
                        <h3 :class="styleClass.subHeading">Managing Cookies</h3>
                        <p :class="styleClass.paragraph">
                            You can manage or disable cookies through your browser settings. Please note that disabling cookies may affect certain
                            functionalities of the website.
                        </p>
                    </section>

                    <!-- Section 5 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">5</span>
                            Data Security
                        </h2>
                        <p :class="styleClass.paragraph">
                            We implement appropriate technical and organizational measures to protect your personal data from unauthorized access,
                            disclosure, alteration, or destruction.
                        </p>
                    </section>

                    <!-- Section 6 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">6</span>
                            Your Rights
                        </h2>
                        <p :class="styleClass.paragraph">
                            Depending on the data, you may have rights to access, correct, or delete your personal data, and to object to or
                            restrict certain processing.
                        </p>
                    </section>

                    <!-- Section 7 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">7</span>
                            Changes to This Policy
                        </h2>
                        <p :class="styleClass.paragraph">
                            We may update this Privacy & Cookie Policy from time to time. Updates will be posted on this page with a new "Last
                            updated" date.
                        </p>
                    </section>

                    <!-- Section 8 -->
                    <section :class="styleClass.section">
                        <h2 :class="styleClass.sectionTitle">
                            <span :class="styleClass.sectionNumber">8</span>
                            Contact Us
                        </h2>
                        <p :class="styleClass.paragraph">
                            If you have questions or concerns about this Privacy & Cookie Policy, please contact us at:
                        </p>
                        <div :class="styleClass.contactBox">
                            <span class="text-2xl">📧</span>
                            <a
                                :href="`mailto:${settings.app_email}`"
                                :class="styleClass.contactLink"
                            >
                                {{ settings.app_email }}
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>