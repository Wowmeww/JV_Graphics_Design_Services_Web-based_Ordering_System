<script setup>
    import ContainerPrimary from '@/components/ContainerPrimary.vue';
    import Layout from './partials/layout.vue';

    defineOptions({
        layout: Layout
    });

    import { useAppearance } from '@/composables/useAppearance';
    const { appearance, updateAppearance } = useAppearance();

    const themes = [
        { label: 'light', icon: 'w-5 h-5 pt-1 fa-solid fa-sun ' },
        { label: 'dark', icon: 'w-5 h-5 pt-1 fa-solid fa-moon fa-sun' },
        { label: 'system', icon: 'w-5 pt-1 h-5 fa-solid fa-moon fa-desktop' },
    ];

</script>

<template>

    <Head title="Appearance" />

    <ContainerPrimary class="max-w-3xl mx-auto">
        <main class="w-full py-1">
            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Appearance and Themes settings</h2>


            <div class="mt-4">
                <p class="text-base font-medium flex items-center gap-2">
                    <span>
                        Theme
                    </span>
                    <span>
                        <small v-for="theme in themes" :key="theme.label" class="text-xl">
                            <i :class="theme.icon" class="!hidden sm:!inline" v-if="appearance === theme.label"></i>
                            <span v-if="appearance === theme.label" class="text-base capitalize sm:hidden"> - {{
                                theme.label
                            }}</span>
                        </small>
                    </span>
                </p>

                <div
                    class="flex overflow-hidden bg-white text-xs border divide-x rounded-lg rtl:flex-row-reverse dark:bg-gray-900 dark:border-gray-700 dark:divide-gray-700 w-fit text-gray-800 dark:text-white">
                    <button v-for="theme in themes" :key="theme.label" @click="updateAppearance(theme.label)"
                        class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors duration-200 sm:text-base sm:px-6 dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100 capitalize flex gap-2"
                        :class="{ 'dark:bg-gray-800 bg-gray-100': appearance === theme.label }">
                        <i :class="theme.icon" class="text-lg"></i>
                        <span class="hidden sm:inline">
                            {{ theme.label }}
                        </span>
                    </button>
                </div>
            </div>

        </main>
    </ContainerPrimary>
</template>