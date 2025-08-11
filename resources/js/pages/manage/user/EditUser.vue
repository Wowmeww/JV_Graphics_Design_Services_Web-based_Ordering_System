<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import TextInputPrimary from '../../../components/ui/TextInputPrimary.vue';
import Dropdown from '../../../components/ui/input/Dropdown.vue';
import UserID from '../../../components/sections/UserID.vue';

const page = usePage();
// Props from Laravel Controller (user data)
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Form state
const form = useForm({
    role: props.user.role, // 'active' or 'inactive'
    verified_at: props.user.verified_at, // true or false
});

// Handle form submit
function updateUser() {
    if (form.verified_at) {
        form.verified_at = new Date().toISOString();
    }
    form.patch(route('manage.users.update', { user: props.user.id }));
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('en-us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

function avatarSrc() {
    const src = props.user.avatar_url;

    if (!src) {
        return '/images/avatar-placeholder.webp';
    } else if (src.includes('https')) {
        return src;
    }

    return `/storage/${src}`;
}

function suspendUser() {
    page.props.confirm = {
        show: true,
        message: 'Are you sure you want to suspend this user?',
        confirmed: false,
        action: {
            suspendUser: true,
        },
    };
}

function unSuspendUser() {
    form.role = 'customer';
    updateUser();
}

watch(
    () => page.props.confirm,
    (confirm) => {
        if (confirm.confirmed && confirm.action.suspendUser) {
            form.role = 'suspended';
            updateUser();
        }
    },
    {
        deep: true,
    },
);

// put all the tailwind calsses
const styleClasses = {
    pageWrapper: 'px-2 py-3',
    pageContainer: 'mx-auto max-w-5xl space-y-4 px-2 py-5 md:space-y-6',
    headerWrapper: 'flex flex-col-reverse items-start justify-between gap-2 sm:flex-row sm:items-center',
    headerTitle: 'text-2xl font-bold md:text-3xl',
    cardContainer: 'container-secondary overflow-hidden p-0',
    cardHeader: 'from-secondary-200 to-primary-500 dark:from-secondary flex items-center justify-between bg-gradient-to-r p-5',
    avatarImage: 'size-[4rem] rounded-full object-cover object-center md:size-[6rem]',
    userInfoWrapper: 'text-light flex flex-col font-medium',
    userName: 'text-lg font-bold sm:text-2xl',
    roleBadge:
        'inline-flex items-center gap-2 rounded px-2.5 py-1 text-xs font-medium text-white capitalize shadow transition-all duration-150 focus:outline-none md:text-base',
    gridLayout: 'grid gap-6 p-5 lg:grid-cols-2',
    sectionTitle: 'border-b pb-2 text-lg capitalize',
    fieldWrapper: 'flex w-full flex-col',
    fieldLabel: 'input-label',
    fieldValue: 'form-control form-control-secondary flex items-center',
};
</script>

<template>
    <Head :title="`User - ${props.user.name}`" />
    <div :class="styleClasses.pageWrapper">
        <div :class="styleClasses.pageContainer">
            <div :class="styleClasses.headerWrapper">
                <div>
                    <h1 :class="styleClasses.headerTitle">User Management</h1>
                    <p>Edit user and manage permissions</p>
                </div>

                <Link as="button" :href="route('manage.users.index')" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left-long"></i>
                    Back to users page
                </Link>
            </div>

            <form @submit.prevent="updateUser" :class="styleClasses.cardContainer">
                <div :class="styleClasses.cardHeader">
                    <div class="inline-flex items-center gap-5">
                        <div class="flex flex-col items-center gap-2">
                            <img :class="styleClasses.avatarImage" :src="avatarSrc()" />
                            <span
                                :class="[
                                    styleClasses.roleBadge,
                                    'sm:hidden',
                                    {
                                        'bg-green-500': user.role === 'customer',
                                        'bg-blue-500': user.role === 'admin',
                                        'bg-red-500': user.role === 'suspended',
                                    },
                                ]"
                            >
                                {{ user.role }}</span
                            >
                        </div>

                        <div :class="styleClasses.userInfoWrapper">
                            <span :class="styleClasses.userName">
                                {{ user.name }}

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
                                    <div
                                        class="absolute top-1/2 right-full z-10 mr-3 hidden -translate-y-1/2 transform group-hover:block"
                                        role="tooltip"
                                    >
                                        <div
                                            class="relative rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white shadow-lg dark:bg-gray-700"
                                        >
                                            <span class="whitespace-nowrap">
                                                {{ user.verified_at ? 'Verified user' : 'Unverified user' }}
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
                            </span>
                            <span>
                                {{ user.email }}
                            </span>
                            <p>
                                Registered since
                                <span>
                                    {{ formatDate(user.created_at) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <span
                        :class="[
                            styleClasses.roleBadge,
                            '!hidden sm:!inline-block',
                            {
                                'bg-green-500': user.role === 'customer',
                                'bg-blue-500': user.role === 'admin',
                                'bg-red-500': user.role === 'suspended',
                            },
                        ]"
                    >
                        {{ user.role }}</span
                    >
                </div>

                <div :class="styleClasses.gridLayout">
                    <div class="space-y-3">
                        <h3 :class="styleClasses.sectionTitle">Personal information</h3>

                        <div :class="styleClasses.fieldWrapper">
                            <span :class="styleClasses.fieldLabel">Name</span>
                            <div :class="styleClasses.fieldValue">
                                {{ user.name }}
                            </div>
                        </div>
                        <div :class="styleClasses.fieldWrapper">
                            <span :class="styleClasses.fieldLabel">Sex</span>
                            <div :class="styleClasses.fieldValue">
                                {{ user.sex }}
                            </div>
                        </div>
                        <div :class="styleClasses.fieldWrapper">
                            <span :class="styleClasses.fieldLabel"
                                >Email
                                <div class="group relative inline-block">
                                    <!-- Status Icon -->
                                    <span
                                        class="cursor-pointer text-gray-600 transition-colors duration-200 hover:text-blue-500 dark:text-gray-200 dark:hover:text-blue-400"
                                    >
                                        <i v-if="user.email_verified_at" class="fa-solid fa-circle-check text-green-400"></i>
                                        <i v-else class="fa-solid fa-circle-xmark text-red-400"></i>
                                    </span>

                                    <!-- Tooltip -->
                                    <div
                                        class="absolute -top-9 left-1/2 hidden w-max max-w-xs -translate-x-1/2 items-center rounded-lg bg-gray-800 p-2 text-xs text-gray-200 shadow-lg group-hover:flex dark:bg-white dark:text-gray-700"
                                    >
                                        <span class="truncate">
                                            {{ user.email_verified_at ? 'Email is verified' : 'Email not verified' }}
                                        </span>

                                        <!-- Tooltip Arrow -->
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="absolute -bottom-1 left-1/2 h-3 w-3 -translate-x-1/2 rotate-45 text-gray-800 dark:text-white"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M20 3H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </span>
                            <div :class="styleClasses.fieldValue">
                                {{ user.email }}
                            </div>
                        </div>
                        <div v-if="user.birth_date" class="flex gap-2">
                            <div :class="[styleClasses.fieldWrapper, 'flex-5/6']">
                                <span :class="styleClasses.fieldLabel">Birth date</span>
                                <div :class="styleClasses.fieldValue">
                                    {{ formatDate(user.birth_date) }}
                                </div>
                            </div>
                            <div :class="[styleClasses.fieldWrapper, 'flex-1/6']">
                                <span :class="styleClasses.fieldLabel">Age</span>
                                <div :class="styleClasses.fieldValue">
                                    {{ user.age }}
                                </div>
                            </div>
                        </div>
                        <div :class="styleClasses.fieldWrapper">
                            <span :class="styleClasses.fieldLabel">Address</span>
                            <div :class="styleClasses.fieldValue">
                                {{ user.address }}
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <h3 :class="styleClasses.sectionTitle">Account Settings</h3>

                        <Dropdown :value="form.role" @select="(option) => (form.role = option)" :options="['customer', 'admin']" label="User Role" />
                        <div :class="styleClasses.fieldWrapper">
                            <label :class="styleClasses.fieldLabel">Identity Verification</label>
                            <div class="flex items-center gap-3">
                                <!-- Enhanced Toggle Switch -->
                                <button
                                    type="button"
                                    role="switch"
                                    aria-checked="form.verified_at"
                                    :aria-label="form.verified_at ? 'Verified' : 'Unverified'"
                                    class="focus:ring-primary-500 relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:ring-2 focus:ring-offset-2 focus:outline-none"
                                    :class="{
                                        'bg-green-500': form.verified_at,
                                        'bg-gray-300 dark:bg-gray-600': !form.verified_at,
                                    }"
                                    @click="form.verified_at = !form.verified_at"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                        :class="{
                                            'translate-x-5': form.verified_at,
                                            'translate-x-0': !form.verified_at,
                                        }"
                                    >
                                        <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                                            <svg v-if="form.verified_at" class="h-3 w-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <svg v-else class="h-3 w-3 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </span>
                                    </span>
                                </button>

                                <span
                                    class="text-sm font-medium"
                                    :class="{
                                        'text-green-600 dark:text-green-400': form.verified_at,
                                        'text-gray-600 dark:text-gray-400': !form.verified_at,
                                    }"
                                >
                                    {{ form.verified_at ? 'Verified' : 'Unverified' }}
                                </span>
                            </div>
                        </div>

                        <UserID v-if="user.id_card" :idCard="user.id_card" />
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <div class="flex flex-col gap-2 border-t p-2 pt-4 sm:flex-row sm:justify-end">
                        <button type="button" @click="() => form.reset('role', 'verified_at')" class="btn btn-outline-primary">Reset Changes</button>
                        <button type="submit" class="btn btn-primary" :disabled="!form.isDirty">Save Changes</button>
                    </div>
                </div>
            </form>

            <div
                v-if="user.role == 'customer' || user.role == 'suspended'"
                class="container-secondary relative overflow-hidden rounded-xl border-2 border-red-500/80 bg-gradient-to-br from-red-900/50 to-red-800/40 p-6 shadow-lg transition-all duration-300 hover:shadow-red-500/10 dark:border-red-600/70 dark:from-red-900/20 dark:to-red-900/30"
            >
                <!-- Pulsing warning effect -->
                <div class="absolute -top-10 -right-10 h-20 w-20 rounded-full bg-red-500/10"></div>

                <div class="relative z-10">
                    <!-- Header with warning icon -->
                    <div class="mb-3 flex items-center gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                        <h3 class="text-xl font-bold text-white">Danger Zone</h3>
                    </div>

                    <!-- Content with animated underline -->
                    <p class="relative inline-block font-medium text-white">
                        This action can be undone. But please proceed with extreme caution.
                        <span
                            class="absolute bottom-0 left-0 h-0.5 w-full origin-left scale-x-0 bg-red-400 transition-transform duration-500 group-hover:scale-x-100"
                        ></span>
                    </p>

                    <div class="pt-5">
                        <button
                            @click="suspendUser"
                            v-if="user.role == 'customer'"
                            class="btn border border-red-500 bg-red-500 text-white hover:bg-transparent dark:border-red-700 dark:bg-red-700"
                        >
                            Suspend Account
                        </button>
                        <button
                            @click="unSuspendUser"
                            v-if="user.role == 'suspended'"
                            class="btn border border-green-500 bg-green-500 text-white hover:bg-transparent dark:border-green-700 dark:bg-green-700"
                        >
                            Unsuspend Account
                        </button>
                    </div>

                    <!-- Optional: Add a subtle glow effect on hover -->
                    <div class="absolute inset-0 -z-10 rounded-xl bg-red-500/10 opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                </div>
            </div>
        </div>
    </div>
</template>
