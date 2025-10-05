<script setup>
import PageTitleHeader from '@/components/ui/PageTitleHeader.vue';
import Paginator from '@/components/tables/Paginator.vue';
import { computed, reactive, ref, watch } from 'vue';
import Dropdown from '@/components/ui/input/Dropdown.vue';
import { router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { debounce } from 'lodash';
import { useEcho } from '@laravel/echo-vue';
import RoleDropdown from '../../../components/ui/input/RoleDropdown.vue';

const props = defineProps({
    users: Object,
});

const tempUsers = ref([...props.users.data]);

const params = route().params;

watch(
    () => props.users.data,
    () => (tempUsers.value = [...props.users.data]),
);
const form = reactive({
    search: params.search || null,
    role: params.role || null,
    sort: params.sort || 'Sort by date (new to old)',
});

useEcho('order-placed', '.OrderPlaced', () => {});

const applyFilters = () => {
    router.get(
        route('manage.users.index'),
        { ...form, page: 1 },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

watch(form, debounce(applyFilters, 400), { deep: true });

function clearFilter() {
    form.search = null;
    form.role = null;
    applyFilters();
}
function formatDate(date) {
    return new Date(date).toLocaleDateString('en-Us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

function avatarSrc(src) {
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
}

function clearSearch() {
    form.search = null;

    applyFilters();
}

function clearRole() {
    form.role = null;

    applyFilters();
}

const styleClass = computed(() => ({
    table: 'w-full divide-y divide-gray-200 dark:divide-gray-700 border rounded overflow-hidden border-gray-200 md:rounded-lg dark:border-gray-700',
    tableHead: 'bg-gray-50 dark:bg-gray-800',
    th: 'py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400',
    td: 'px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap capitalize',
    tBody: 'bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900',
    badges: {
        pending: 'inline-flex items-center px-3 py-1 text-yellow-600 bg-yellow-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        processing: 'inline-flex items-center px-3 py-1 text-blue-600 bg-blue-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        completed: 'inline-flex items-center px-3 py-1 rounded-full gap-x-2 text-emerald-500 bg-emerald-100/60 dark:bg-gray-800',
        cancelled: 'inline-flex items-center px-3 py-1 text-red-500 rounded-full gap-x-2 bg-red-100/60 dark:bg-gray-800',
        received: 'inline-flex items-center px-3 py-1 text-indigo-600 bg-indigo-100/60 dark:bg-gray-800 rounded-full gap-x-2',
        rated: 'inline-flex items-center px-3 py-1 text-purple-600 bg-purple-100/60 dark:bg-gray-800 rounded-full gap-x-2',
    },
    user: {
        avatar: 'object-cover w-8 h-8 rounded-full',
        name: 'text-sm font-medium text-gray-800 dark:text-white ',
        email: 'text-xs font-normal text-gray-600 dark:text-gray-400 lowercase',
    },
}));

const badges = {
    pending: { label: 'pending', icon: 'fa-regular fa-clock' },
    processing: { label: 'processing', icon: 'fa-solid fa-arrows-split-up-and-left' },
    completed: { label: 'completed', icon: 'fa-regular fa-circle-check' },
    cancelled: { label: 'cancelled', icon: 'fa-solid fa-xmark' },
    received: { label: 'received', icon: 'bi bi-download' },
    rated: { label: 'rated', icon: 'fa-solid fa-star-half-stroke' },
};

const sortOptionOpen = ref(false);
const sortOptions = ['Sort by date (new to old)', 'Sort by date (old to new)', 'Sort by name (A to Z)', 'Sort by name (Z to A)', 'Sort by role'];

function handleSort(sortOption) {
    form.sort = sortOption;
    sortOptionOpen.value = false;
}
</script>
<template>
    <Head title="Manage orders" />

    <!-- Overlay for mobile sort options -->
    <div v-if="sortOptionOpen" @click="sortOptionOpen = false" class="fixed inset-0 z-30 bg-black/10 backdrop-blur-sm"></div>

    <div class="px-2 py-3">
        <div class="mx-auto max-w-7xl py-6">
            <PageTitleHeader title="Users" />
            <div class="my-4 rounded-lg bg-white p-4 dark:bg-gray-800">
                <!-- FILTER -->
                <div class="flex flex-col items-center gap-x-5 gap-y-2 sm:flex-row">
                    <input
                        type="search"
                        v-model="form.search"
                        class="form-control form-control-primary flex-1 bg-white dark:bg-transparent"
                        placeholder="Search..."
                    />
                    <div class="w-full sm:w-40">
                        <Dropdown
                            :options="['customer', 'admin', 'suspended']"
                            :value="form.role"
                            @select="(option) => (form.role = option)"
                            placeholder="Filter role"
                        />
                    </div>

                    <button type="button" @click="clearFilter" class="btn btn-outline-primary w-full sm:w-fit">Clear filters</button>
                </div>
                <!-- APPLIED FILTERS -->

                <div class="flex gap-2 pt-2" v-if="form.search || form.role">
                    <span class="font-medium">Applied filters:</span>

                    <small class="badge badge-secondary" v-if="form.search">
                        Search: "{{ form.search }}"
                        <button @click="clearSearch" class="ml-2 text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </small>
                    <small class="badge badge-secondary" v-if="form.role">
                        Role: "{{ form.role }}"
                        <button @click="clearRole" class="ml-2 text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </small>
                </div>
            </div>
            <section class="mx-auto mt-4" v-if="tempUsers.length">
                <div class="relative py-2 overflow-x-scroll md:overflow-x-auto">
                    <table :class="styleClass.table">
                        <thead :class="styleClass.tableHead">
                            <tr>
                                <th scope="col" :class="styleClass.th">
                                    <div class="flex items-center gap-x-3">
                                        <span>Name</span>
                                        <div>
                                            <!-- Sort Options Dropdown (Shared for both mobile and desktop) -->
                                            <div
                                                v-if="sortOptionOpen"
                                                class="dark:bg-[#1e293b]' fixed z-40 mt-2 min-w-[180px] overflow-hidden rounded-lg border border-gray-200 bg-white text-left shadow-lg md:absolute md:right-auto dark:border-gray-600"
                                            >
                                                <span
                                                    v-for="option in sortOptions"
                                                    :class="{ 'bg-gray-100 font-semibold dark:bg-gray-700': form.sort === option }"
                                                    class="block cursor-pointer px-4 py-2 font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    @click="handleSort(option)"
                                                >
                                                    {{ option }}
                                                </span>
                                            </div>
                                            <button type="button" @click="sortOptionOpen = true">
                                                <i class="fa-solid fa-arrow-down-9-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col" :class="styleClass.th">Verified</th>
                                <th scope="col" :class="styleClass.th">Email</th>
                                <th scope="col" :class="styleClass.th">Age</th>
                                <th scope="col" :class="styleClass.th">Role</th>

                                <th scope="col" class="styleClass.th">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody :class="styleClass.tBody">
                            <tr v-for="user in tempUsers">
                                <td :class="styleClass.td">
                                    {{ user.name }}
                                </td>
                                <td :class="styleClass.td">
                                    <div class="group relative inline-flex">
                                        <!-- Verification Status Button -->
                                        <button
                                            class="text-xl transition-transform hover:scale-110 focus:outline-none"
                                            aria-label="Verification status"
                                        >
                                            <i
                                                v-if="user.verified_at"
                                                class="bi bi-patch-check-fill text-green-500 transition-colors hover:text-green-600 dark:text-green-400 dark:hover:text-green-300"
                                                title="Verified user"
                                            ></i>
                                            <i
                                                v-else
                                                class="bi bi-patch-exclamation-fill text-amber-500 transition-colors hover:text-amber-600 dark:text-amber-400 dark:hover:text-amber-300"
                                                title="Unverified user"
                                            ></i>
                                        </button>

                                        <!-- Tooltip -->
                                        <div
                                            class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 transform group-hover:block group-focus:block"
                                            role="tooltip"
                                        >
                                            <div
                                                class="relative rounded-lg bg-gray-900 px-3 py-2 text-xs font-medium text-white shadow-lg dark:bg-gray-700"
                                            >
                                                <span class="whitespace-nowrap">
                                                    {{ user.verified_at ? 'Verified user' : 'Unverified user' }}
                                                </span>
                                                <!-- Tooltip arrow -->
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 transform">
                                                    <svg class="h-2 w-4 text-gray-900 dark:text-gray-700" viewBox="0 0 255 255">
                                                        <polygon points="0,0 127.5,127.5 255,0" fill="currentColor" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td :class="[styleClass.td, 'space-x-2']">
                                    <div class="group relative inline-flex">
                                        <!-- Verification Status Button -->
                                        <button
                                            class="text-gray-600 transition-all duration-200 hover:scale-110 hover:text-blue-500 focus:outline-none dark:text-gray-200 dark:hover:text-blue-400"
                                            aria-label="Email verification status"
                                        >
                                            <i
                                                v-if="user.email_verified_at"
                                                class="fa-solid fa-circle-check text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300"
                                                title="Verified email"
                                            ></i>
                                            <i
                                                v-else
                                                class="fa-solid fa-circle-xmark text-amber-500 hover:text-amber-600 dark:text-amber-400 dark:hover:text-amber-300"
                                                title="Unverified email"
                                            ></i>
                                        </button>

                                        <!-- Tooltip -->
                                        <div
                                            class="absolute bottom-full left-1/2 z-10 mb-2 hidden -translate-x-1/2 transform group-hover:block group-focus:block"
                                            role="tooltip"
                                        >
                                            <div
                                                class="relative rounded-md bg-gray-800 px-3 py-2 text-xs font-medium text-white shadow-lg dark:bg-gray-700"
                                            >
                                                <span class="whitespace-nowrap">
                                                    {{ user.email_verified_at ? 'Email verified' : 'Email not verified' }}
                                                </span>
                                                <!-- Tooltip arrow -->
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 transform">
                                                    <svg class="h-2 w-4 text-gray-800 dark:text-gray-700" viewBox="0 0 255 255">
                                                        <polygon points="0,0 127.5,127.5 255,0" fill="currentColor" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="lowercase">
                                        {{ user.email }}
                                    </span>
                                </td>
                                <td :class="styleClass.td">
                                    <span>{{ user.age || '-' }}</span>
                                </td>
                                <td :class="[styleClass.td, 'z-50']">
                                    <span
                                        class="inline-flex items-center gap-2 rounded px-2.5 py-1 text-xs font-medium text-white uppercase shadow transition-all duration-150 focus:outline-none"
                                        :class="{
                                            'bg-green-500': user.role === 'customer',
                                            'bg-blue-500': user.role === 'admin',
                                            'bg-red-500': user.role === 'suspended',
                                        }"
                                    >
                                        {{ user.role }}</span
                                    >
                                </td>
                                <td :class="styleClass.td">
                                    <a :href="`mailto:${user.email}`" class="text-primary dark:text-primary-400 inline-block -translate-x-4">
                                        <i class="bi bi-envelope-at-fill text-lg"></i>
                                    </a>
                                    <Link as="button" :href="route('manage.users.edit', { user: user.id })">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-primary dark:text-primary-400"></i>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <!---------------- PAGINATOR ----------------------------------------------->

                    <Paginator :paginator="users" />
                </div>
            </section>

            <div v-else class="py-6 text-center text-gray-500">
                <p class="text-lg font-medium">No users found.</p>
                <p class="text-sm text-gray-400">When a new user registers, they will appear in this list.</p>
            </div>
        </div>
    </div>
</template>
