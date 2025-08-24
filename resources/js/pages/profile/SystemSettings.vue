<script setup>
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import Layout from './partials/layout.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import { router, useForm } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';
import axios from 'axios';

defineOptions({
    layout: Layout,
});

const props = defineProps({
    systemSettings: Array,
});

const tempSettings = computed(function () {
    let temp = {};
    props.systemSettings.forEach((element) => (temp[element.key] = element.value));

    return temp;
});

const form = useForm({
    app_name: tempSettings.value.app_name,
    app_name_short: tempSettings.value.app_name_short,
    app_logo: tempSettings.value.app_logo || 'none',
    _method: 'PATCH',
});

const logoPreview = reactive({
    src: '',
    isDirty: false,
});

function submit() {
    form.post(route('system.settings'), {
        onFinish: () => (logoPreview.isDirty = false),
    });
}
resetLogoPreview();
function resetLogoPreview() {
    axios(route('page.settings', { what: 'app_logo' })).then(function (res) {
        form.errors.app_logo = '';
        form.app_logo = tempSettings.value.app_logo || 'none';
        logoPreview.src = res.data;
    });
    logoPreview.isDirty = false;
}

function handleLogoChange(event, action) {
    const file = event?.target.files[0];

    if (file && action === 'change') {
        const maxSize = 1; // 1 mb
        if (file.size > 1024 * (1024 * maxSize)) {
            form.errors.app_logo = `The image need to be smaller than ${maxSize}MB.`;
            return;
        }

        logoPreview.isDirty = true;
        logoPreview.src = URL.createObjectURL(file);
        form.app_logo = file;
    } else if (!file && action === 'delete') {
        logoPreview.src = '/favicon.png';
        form.app_logo = 'delete';
        logoPreview.isDirty = true;
    }
}
</script>

<template>
    <Head title="System settings" />
    <ContainerPrimary class="mx-auto max-w-3xl">
        <main class="w-full py-1">
            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Advance System settings</h2>
            <p class="flex items-center gap-2 pl-6 text-base font-medium">Update the pages settings.</p>

            <form @submit.prevent="submit" class="mt-4 space-y-3">
                <TextInputPrimary v-model="form.app_name" :error="form.errors.app_name" label="Website name" placeholder="Enter your desired name." />
                <TextInputPrimary
                    v-model="form.app_name_short"
                    :error="form.errors.app_name_short"
                    label="Website name (in mobile devices)"
                    placeholder="Enter your desired name."
                />

                <label class="input-label">Logo</label>
                <div class="mt-4 flex items-center gap-3">
                    <!-- Logo Preview with Reset Button -->
                    <div class="group relative">
                        <div class="relative h-20 w-20 md:h-30 md:w-30">
                            <img
                                class="h-full w-full rounded-full object-cover p-1 ring-4 ring-indigo-400/20 transition-all duration-300 group-hover:ring-indigo-500/30 dark:ring-indigo-500/30 dark:group-hover:ring-indigo-400/40"
                                :src="logoPreview.src"
                                alt="page logo"
                            />
                            <button
                                v-if="logoPreview.isDirty"
                                @click="resetLogoPreview"
                                type="button"
                                class="absolute -top-2 -right-2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 shadow-md transition-all hover:scale-110 hover:bg-white dark:bg-gray-700/90"
                                title="Reset app_logo"
                            >
                                <i class="fa-solid fa-rotate-right text-sm text-indigo-600 dark:text-indigo-300"></i>
                            </button>
                        </div>
                        <small v-if="form.errors.app_logo" class="mt-2 block max-w-44 text-center text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.app_logo }}
                            <span v-if="form.errors.app_logo?.includes('1024 kilobytes')"> or 1mb.</span>
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-1 flex-col gap-2">
                        <div>
                            <label
                                for="app_logo"
                                class="flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:bg-indigo-700 dark:hover:bg-indigo-600"
                            >
                                <i class="fa-solid fa-camera text-sm"></i>
                                Change
                            </label>
                            <input id="app_logo" type="file" accept="image/*" hidden @change="(e) => handleLogoChange(e, 'change')" />
                        </div>

                        <button
                            v-if="tempSettings.app_logo"
                            type="button"
                            @click="handleLogoChange(null, 'delete')"
                            class="flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-3.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        >
                            <i class="fa-solid fa-trash-can text-sm"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <div class="pt-6">
                    <ButtonPrimary type="submit" label="Save changes" :with-spinner="form.processing" :disable="!form.isDirty" />
                </div>
            </form>
        </main>
    </ContainerPrimary>
</template>
