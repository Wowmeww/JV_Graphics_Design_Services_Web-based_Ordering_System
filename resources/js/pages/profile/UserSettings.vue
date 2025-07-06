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
        mustVerifyEmail: Boolean
    });

    const originalData = {
        name: props.user.name,
        sex: props.user.sex,
        email: props.user.email,
        birth_date: props.user.birth_date,
        address: props.user.address,
        avatarAction: 'none',
        avatar: null,
        _method: 'PATCH'
    };

    const form = useForm({ ...originalData });

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
    watch(form, (newVal) => {
        const raw = toRaw(form);
        const fieldsToCompare = ['name', 'sex', 'email', 'birth_date', 'address', 'avatarAction'];
        isNotChanged.value = fieldsToCompare.every(key => raw[key] === originalData[key]) && !form.avatar;
    }, { deep: true });

    function submit() {
        form.post(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                // Save the new data as original for future changes
                Object.assign(originalData, { ...toRaw(form), avatar: null });
                form.reset();
                isNotChanged.value = true;
            }
        });
    }
</script>

<template>

    <Head title="Profile" />

    <ContainerPrimary class="max-w-3xl mx-auto !py-0">
        <main class="w-full py-1">
            <form @submit.prevent="submit" class="p-2 md:p-4">
                <div class="w-full md:px-6 pb-8 mt-8 sm:rounded-lg">
                    <h2 class="pl-6 text-2xl font-bold sm:text-xl">Public Profile</h2>
                    <small class="pl-6">Click the save button after making any changes</small>

                    <div class="grid mx-auto mt-8">
                        <!-- Avatar Section -->
                        <div class="flex flex-col items-center space-y-5 sm:flex-row sm:space-y-0">
                            <div class="relative">
                                <img class="object-cover w-40 h-40 p-1 rounded-full ring-2 ring-indigo-300 dark:ring-indigo-500"
                                    :src="avatarPreview" alt="User avatar" />
                                <button
                                    v-if="(avatarPreview != `/storage/${user.avatar_url}`) || form.avatarAction == 'delete'"
                                    @click="resetAvatarPreview" type="button"
                                    class="absolute top-0 right-0 p-2.5 rounded-full bg-slate-300/80 hover:bg-slate-300/90">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                                <small v-if="form.errors.avatar"
                                    class="pt-2 block text-center text-red-600 dark:text-red-400 max-w-40 text-wrap">
                                    {{ form.errors.avatar }}
                                    <span v-if="form.errors.avatar?.includes('1024 kilobytes')"> or 1mb.</span>
                                </small>
                            </div>

                            <div class="flex flex-col space-y-5 sm:ml-8">
                                <label for="avatar"
                                    class="py-3.5 px-7 text-base font-medium text-indigo-100 bg-[#202142] rounded-lg border border-indigo-200 hover:bg-indigo-900 focus:outline-none focus:ring-4 focus:ring-indigo-200 cursor-pointer">
                                    Change picture
                                </label>
                                <input id="avatar" type="file" hidden @change="handleImageChange" />
                                <button type="button" @click="deleteAvatar"
                                    class="py-3.5 px-7 text-base font-medium text-indigo-900 bg-white rounded-lg border border-indigo-200 hover:bg-indigo-100 hover:text-[#202142] focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                    Delete picture
                                </button>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="items-center mt-8 sm:mt-14 text-[#202142] dark:text-white space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <TextInputPrimary v-model="form.name" label="Full name" placeholder="Enter full name" />
                                <Dropdown label="Sex" :value="form.sex" :options="['male', 'female']"
                                    :error="form.errors.sex" @select="opt => (form.sex = opt)" />
                            </div>

                            <TextInputPrimary v-model="form.email" label="Your email" :error="form.errors.email"
                                placeholder="your.email@mail.com" />

                            <TextInputPrimary v-model="form.birth_date" label="Birth date" type="date"
                                :error="form.errors.birth_date" :required="false" placeholder="Enter your birth date" />

                            <TextInputPrimary v-model="form.address"
                                label="Address (street, baranggay, municipality, city)" :error="form.errors.address"
                                :required="false" placeholder="Enter address" />

                            <div class="md:flex md:justify-end pt-4">
                                <ButtonPrimary :disable="form.processing || isNotChanged" :withSpinner="!isNotChanged"
                                    type="submit" label="Save" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </ContainerPrimary>
</template>
