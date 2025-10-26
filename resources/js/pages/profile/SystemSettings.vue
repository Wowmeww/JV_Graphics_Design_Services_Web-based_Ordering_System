<script setup>
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import Layout from './partials/layout.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import axios from 'axios';

defineOptions({
    layout: Layout,
});

const props = defineProps({
    settings: Object,
});

const settings = computed(() => props.settings);

const form = useForm({
    app_name: settings.value.app_name,
    app_name_short: settings.value.app_name_short,
    app_logo: settings.value.app_logo || 'none',
    app_email: settings.value.app_email,
    app_phone: settings.value.app_phone,
    landing_page_title: settings.value.landing_page_title,
    landing_page_subtitle: settings.value.landing_page_subtitle,
    app_about: JSON.stringify(settings.value.app_about),
    _method: 'PATCH',
});

const appAbout = ref([
    ...settings.value.app_about,
    {
        header: null,
        content: null,
    },
]);

const logoPreview = reactive({
    src: '',
    isDirty: false,
});

function submit() {
    form.post(route('system.settings'), {
        onFinish: () => (logoPreview.isDirty = false),
    });
}
function resetLogoPreview() {
    form.errors.app_logo = '';
    form.app_logo = settings.value.app_logo || 'none';
    logoPreview.src = settings.value.app_logo;
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
        logoPreview.src = '/favicon.jpg';
        form.app_logo = 'delete';
        logoPreview.isDirty = true;
    }
}

watch(appAbout.value, (about) => {
    about = about.filter((abt) => abt.header || abt.content);
    const tail = appAbout.value.at(-1);

    if (tail.header || tail.content) {
        appAbout.value.push({
            header: null,
            content: null,
        });
    }

    form.app_about = JSON.stringify(about);
});
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
                        <div class="relative h-20 w-20 md:h-54 md:w-54">
                            <img
                                class="h-full w-full rounded-full object-cover p-1 ring-4 ring-indigo-400/20 transition-all duration-300 group-hover:ring-indigo-500/30 dark:ring-indigo-500/30 dark:group-hover:ring-indigo-400/40"
                                :src="logoPreview.src || settings.app_logo"
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
                            v-if="settings.app_logo !== form.app_logo"
                            type="button"
                            @click="handleLogoChange(null, 'delete')"
                            class="flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-3.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                        >
                            <i class="fa-solid fa-trash-can text-sm"></i>
                            Restore default
                        </button>
                    </div>
                </div>

                <TextInputPrimary
                    v-model="form.landing_page_title"
                    :error="form.errors.landing_page_title"
                    label="Landing page header"
                    placeholder="Enter your desired header."
                />
                <TextInputPrimary
                    v-model="form.landing_page_subtitle"
                    :required="false"
                    :error="form.errors.landing_page_subtitle"
                    label="Landing page subheader"
                    placeholder="Enter your desired subheader."
                />
                <TextInputPrimary
                    v-model="form.app_email"
                    :required="false"
                    :error="form.errors.app_email"
                    label="Email address"
                    type="email"
                    placeholder="Enter your desired email."
                />
                <TextInputPrimary
                    v-model="form.app_phone"
                    :required="false"
                    :error="form.errors.app_phone"
                    label="Phone number"
                    placeholder="Enter your desired number."
                />

                <div class="flex flex-col">
                    <label class="input-label">About</label>
                    <small v-if="form.errors.app_about" class="form-control-error">
                        {{ form.errors.app_about }}
                    </small>
                </div>
                <div class="h-96 space-y-1.5 overflow-y-scroll pb-6 pl-4 lg:pl-6">
                    <div class="space-y-2" v-for="(item, i) in appAbout" :key="`about-item-${i}`">
                        <TextInputPrimary
                            :required="false"
                            v-model="item.header"
                            :label="`Header ${i + 1}`"
                            placeholder="Enter your desired header."
                        />
                        <TextInputPrimary
                            :required="false"
                            v-model="item.content"
                            :label="`Content ${i + 1}`"
                            type="textarea"
                            placeholder="Enter your desired content."
                            :row="2"
                        />
                    </div>
                </div>

                <div class="pt-6">
                    <ButtonPrimary type="submit" label="Save changes" :with-spinner="form.processing" :disable="!form.isDirty" />
                </div>
            </form>
        </main>
    </ContainerPrimary>
</template>
