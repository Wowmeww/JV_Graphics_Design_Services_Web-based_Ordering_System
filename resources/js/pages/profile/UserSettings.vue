<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch, toRaw } from 'vue';

import Layout from './partials/layout.vue';
import ContainerPrimary from '@/components/ContainerPrimary.vue';
import ButtonPrimary from '@/components/ui/buttons/ButtonPrimary.vue';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import TextInputPrimary from '@/components/ui/TextInputPrimary.vue';

defineOptions({ layout: Layout });

const props = defineProps({
    user: Object,
    mustVerifyEmail: Boolean,
});

const form = useForm({
    name: props.user.name,
    sex: props.user.sex,
    email: props.user.email,
    birth_date: props.user.birth_date,
    address: props.user.address,
    avatarAction: 'none',
    avatar: null,
    idCard: {
        type: null,
        selfie: null,
        front: null,
        back: null,
    },
    _method: 'PATCH',
});

function idCard(what, event) {
    const element = event.target;
    const file = element.files[0];

    if (file) {
        const sizeInMB = file.size / (1024 * 1024);
        if (sizeInMB > 1) {
            form.errors[`idCard.${what}`] = `The ${what} need to be lower than 1MB.`;
        } else {
            form.errors[`idCard.${what}`] = '';
            form.idCard[what] = file;
        }
    }
}

const isNotChanged = ref(true);
const avatarPreview = ref(getAvatarUrl(props.user.avatar_url));

function getAvatarUrl(path) {
    return path ? `/storage/${path}` : '/images/avatar-placeholder.webp';
}

function handleImageChange(e) {
    const image = e.target.files[0];
    if (!image) return;

    avatarPreview.value = URL.createObjectURL(image);
    form.avatar = image;
    form.avatarAction = 'upload';
}

function resetAvatarPreview() {
    avatarPreview.value = getAvatarUrl(props.user.avatar_url);
    form.avatar = null;
    form.avatarAction = 'none';
}

function deleteAvatar() {
    avatarPreview.value = '/images/avatar-placeholder.webp';
    form.avatar = null;
    form.avatarAction = 'delete';
}

// Watch form for changes to enable the submit button
watch(
    form,
    (newVal) => {
        isNotChanged.value = false;
    },
    { deep: true },
);

function submit() {
    form.post(route('profile.update'), {
        preserveScroll: true,
    });
}

function resetForm() {
    form.reset('name', 'email', 'sex', 'birth_date', 'address', 'idCard');
}

const styleClass = {
    input: {
        image: 'focus:ring-opacity-40 focus:border-primary-400 focus:ring-primary-300 dark:focus:border-primary-300 mt-2 block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 placeholder-gray-400/70 file:rounded-full file:border-none file:bg-gray-200 file:px-4 file:py-1 file:text-sm file:text-gray-700 focus:ring focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 dark:file:bg-gray-800 dark:file:text-gray-200',
    },
};
</script>

<template>
    <Head title="Profile" />

    <ContainerPrimary class="mx-auto max-w-3xl !py-0">
        <main class="w-full py-1">
            <form @submit.prevent="submit" class="p-2 md:p-4">
                <div class="mt-8 w-full pb-8 sm:rounded-lg md:px-6">
                    <div class="flex justify-between">
                        <div>
                            <h2 class="pl-6 text-2xl font-bold sm:text-xl">Public Profile</h2>
                            <small class="pl-6">Click the save button after making any changes</small>
                        </div>

                        <div>
                            <div class="group relative inline-flex">
                                <!-- Verification Status Indicator -->
                                <span class="inline-flex text-xl transition-transform hover:scale-110" aria-label="Verification status">
                                    <i
                                        v-if="user.verified_at"
                                        class="bi bi-patch-check-fill text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300"
                                        title="Verified user"
                                    ></i>
                                    <i
                                        v-else
                                        class="bi bi-patch-exclamation-fill text-amber-500 hover:text-amber-600 dark:text-amber-400 dark:hover:text-amber-300"
                                        title="Unverified user"
                                    ></i>
                                </span>

                                <!-- Tooltip -->
                                <div class="absolute top-1/4 right-full z-10 mr-3 hidden -translate-y-1/2 transform group-hover:block" role="tooltip">
                                    <div class="relative rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white shadow-lg dark:bg-gray-700">
                                        <span class="whitespace-nowrap">
                                            {{ user.verified_at ? 'Verified' : 'You are unverified' }}
                                        </span>
                                        <!-- Tooltip arrow -->
                                        <svg
                                            class="absolute top-1/2 left-full -ml-2 h-3 w-3 -translate-y-1/2 rotate-180 transform text-gray-900 dark:text-gray-700"
                                            viewBox="0 0 255 255"
                                        >
                                            <polygon points="0,127.5 127.5,0 127.5,255" fill="currentColor" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mx-auto mt-8 grid">
                        <!-- Enhanced Avatar Section -->
                        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">
                            <!-- Avatar Preview with Reset Button -->
                            <div class="group relative">
                                <div class="relative h-44 w-44">
                                    <img
                                        class="h-full w-full rounded-full object-cover p-1 ring-4 ring-indigo-400/20 transition-all duration-300 group-hover:ring-indigo-500/30 dark:ring-indigo-500/30 dark:group-hover:ring-indigo-400/40"
                                        :src="avatarPreview"
                                        alt="User avatar"
                                    />
                                    <button
                                        v-if="form.avatarAction == 'delete' || (avatarPreview !== `storage/${props.avatar_url}` && props.avatar_url)"
                                        @click="resetAvatarPreview"
                                        type="button"
                                        class="absolute -top-2 -right-2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 shadow-md transition-all hover:scale-110 hover:bg-white dark:bg-gray-700/90"
                                        title="Reset avatar"
                                    >
                                        <i class="fa-solid fa-rotate-right text-sm text-indigo-600 dark:text-indigo-300"></i>
                                    </button>
                                </div>
                                <small v-if="form.errors.avatar" class="mt-2 block max-w-44 text-center text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.avatar }}
                                    <span v-if="form.errors.avatar?.includes('1024 kilobytes')"> or 1mb.</span>
                                </small>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-4 sm:ml-6">
                                <div>
                                    <label
                                        for="avatar"
                                        class="flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:bg-indigo-700 dark:hover:bg-indigo-600"
                                    >
                                        <i class="fa-solid fa-camera text-sm"></i>
                                        Change picture
                                    </label>
                                    <input id="avatar" type="file" accept="image/*" hidden @change="handleImageChange" />
                                </div>

                                <button
                                    type="button"
                                    @click="deleteAvatar"
                                    class="flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-3.5 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                                >
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                    Delete picture
                                </button>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="mt-8 items-center space-y-4 text-[#202142] sm:mt-14 dark:text-white">
                            <div class="grid gap-4 md:grid-cols-2">
                                <TextInputPrimary v-model="form.name" label="Full name" placeholder="Enter full name" />
                                <Dropdown
                                    label="Sex"
                                    :value="form.sex"
                                    :options="['male', 'female']"
                                    :error="form.errors.sex"
                                    @select="(opt) => (form.sex = opt)"
                                />
                            </div>

                            <TextInputPrimary v-model="form.email" label="Your email" :error="form.errors.email" placeholder="your.email@mail.com" />

                            <TextInputPrimary
                                v-model="form.birth_date"
                                label="Birth date"
                                type="date"
                                :error="form.errors.birth_date"
                                :required="false"
                                placeholder="Enter your birth date"
                            />

                            <TextInputPrimary
                                v-model="form.address"
                                label="Address (street, baranggay, municipality, city)"
                                :error="form.errors.address"
                                :required="false"
                                placeholder="Enter address"
                            />

                            <!-- Verification info---------------------------------------------------------- -->
                            <!-- Enhanced Verification Info Banner -->
                            <div v-if="user.id_card && !user.verified_at" class="w-full bg-yellow-500/90 dark:bg-yellow-600/90">
                                <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                                    <div class="flex flex-wrap items-center justify-between">
                                        <div class="flex w-0 flex-1 items-center">
                                            <span class="flex rounded-lg bg-yellow-600/80 p-2 dark:bg-yellow-700/80">
                                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                            <p class="ml-3 font-medium text-white">
                                                Verification under review Your verification documents have been received and are currently being
                                                reviewed. This process typically takes 1-3 business days.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div v-if="!user.id_card && !user.verified_at" class="w-full bg-yellow-500/90 dark:bg-yellow-600/90">
                                <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                                    <div class="flex flex-wrap items-center justify-between">
                                        <div class="flex w-0 flex-1 items-center">
                                            <span class="flex rounded-lg bg-yellow-600/80 p-2 dark:bg-yellow-700/80">
                                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                            <p class="ml-3 font-medium text-white">
                                                To be able to make order you need to be verified so please provide needed documents (selfie, id) pictures and fill-up profile info. This process typically takes 1-3 business days.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div>
                                    <label for="selfie" class="input-label">Selfie</label>
                                    <input
                                        id="selfie"
                                        @change="(e) => idCard('selfie', e)"
                                        type="file"
                                        accept="image/*"
                                        :class="[styleClass.input.image, form.errors[`idCard.selfie`] && 'ring ring-red-500 focus:ring-red-500']"
                                    />
                                    <small class="form-control-error" v-if="form.errors[`idCard.selfie`]">
                                        {{ form.errors[`idCard.selfie`] }}
                                    </small>
                                </div>
                                <Dropdown
                                    allowCustomValue
                                    label="ID type"
                                    :value="form.idCard.type"
                                    :options="['Drivers licens', 'National ID']"
                                    :error="form.errors[`idCard.type`]"
                                    @select="(opt) => (form.idCard.type = opt)"
                                />
                                <div class="flex flex-col gap-3 md:flex-row">
                                    <div>
                                        <label for="id-front" class="input-label">Front ID picture</label>
                                        <input
                                            id="id-front"
                                            @change="(e) => idCard('front', e)"
                                            type="file"
                                            accept="image/*"
                                            :class="[styleClass.input.image, form.errors[`idCard.front`] && 'ring ring-red-500 focus:ring-red-500']"
                                        />
                                        <small class="form-control-error" v-if="form.errors[`idCard.front`]">
                                            {{ form.errors[`idCard.front`] }}
                                        </small>
                                    </div>
                                    <div>
                                        <label for="id-back" class="input-label">Back ID picture</label>
                                        <input
                                            id="id-back"
                                            @change="(e) => idCard('back', e)"
                                            type="file"
                                            accept="image/*"
                                            :class="[styleClass.input.image, form.errors[`idCard.back`] && 'ring ring-red-500 focus:ring-red-500']"
                                        />
                                        <small class="form-control-error" v-if="form.errors[`idCard.back`]">
                                            {{ form.errors[`idCard.back`] }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 pt-4 md:flex-row">
                                <ButtonPrimary
                                    variant="outline-secondary"
                                    type="button"
                                    label="Reset Changes"
                                    :disable="form.processing || !form.isDirty"
                                    :withSpinner="false"
                                    @click="resetForm"
                                />
                                <ButtonPrimary
                                    :disable="form.processing || !form.isDirty"
                                    :withSpinner="form.processing"
                                    type="submit"
                                    label="Save Changes"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </ContainerPrimary>
</template>
