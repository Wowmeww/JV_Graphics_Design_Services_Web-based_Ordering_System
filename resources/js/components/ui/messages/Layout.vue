<script setup>
import { computed, ref, watch } from 'vue';
import ContactCard from './ContactCard.vue';
import { useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
const params = route().params;
const props = defineProps({
    contacts: {
        type: Array,
        default: () => [],
    },
    selectedContact: {
        type: Object,
        default: null,
    },
    user: Object,
});

const form = useForm({
    search: params.search,
});

const tempContacts = ref([...props.contacts]);

watch(
    () => form.search,
    debounce((value) => {
        value = value.trim();

        form.get(route('message.index', { receiver: props.selectedContact?.id }), {
            preserveScroll: true,
        });
    }, 1000),
);

const isDarkMode = ref(false);
const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const avatarUrl = computed(() => {
    if (!props.selectedContact?.avatar_url) return null;
    return props.selectedContact.avatar_url.includes('https') ? props.selectedContact.avatar_url : `/storage/${props.selectedContact.avatar_url}`;
});

const contactStatus = computed(() => {
    if (!props.selectedContact) return 'Offline';
    return props.selectedContact.is_online ? 'Online' : 'Offline';
});

function handleUnshift(i, contact) {
    if (i !== 0) {
        tempContacts.value.splice(i, 1);
        tempContacts.value.unshift(contact);
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-100 text-gray-900 transition-colors duration-300 dark:bg-gray-900 dark:text-white">
        <div class="relative mx-auto flex h-screen max-w-6xl flex-col md:flex-row">
            <!-- Mobile Toggle Button -->
            <button
                @click="toggleSidebar"
                class="fixed top-4 left-4 z-20 rounded-full bg-white p-2 text-gray-800 shadow-lg md:hidden dark:bg-gray-700 dark:text-white"
                aria-label="Toggle sidebar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Sidebar Area -->
            <aside
                :class="[
                    'w-full flex-shrink-0 border-r p-4 shadow-md transition-transform duration-300 ease-in-out md:w-80',
                    'fixed z-10 h-full border-gray-200 bg-white md:relative dark:border-gray-700 dark:bg-gray-800',
                    isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
                ]"
            >
                <!-- Close button for mobile -->
                <button
                    @click="toggleSidebar"
                    class="absolute top-4 right-4 rounded-full border border-transparent p-1 text-gray-500 hover:border-slate-400 hover:text-gray-700 md:hidden dark:text-gray-300 dark:hover:text-white"
                    aria-label="Close sidebar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Sidebar Content -->
                <slot name="sidebar">
                    <div class="space-y-2 pb-4">
                        <h2 class="text-xl font-semibold">Messages</h2>
                        <!-- SEARCH INPUT -->
                        <input
                            v-model="form.search"
                            type="search"
                            placeholder="Search. . ."
                            class="w-full flex-1 rounded-full border border-gray-300 bg-white px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800"
                        />
                    </div>
                    <div class="max-h-[calc(100vh-120px)] space-y-2 overflow-y-auto">
                        <ContactCard
                            :user="user"
                            v-for="(contact, i) in tempContacts"
                            @sentAMessage="handleUnshift(i, contact)"
                            :key="`contact-${contact.id}`"
                            :contact="contact"
                            :selected="contact.id === selectedContact?.id"
                        />
                    </div>
                </slot>
            </aside>

            <!-- Overlay for mobile -->
            <div v-show="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-0 bg-black/25 md:hidden" />

            <!-- Main Content Area -->
            <main class="flex flex-1 flex-col bg-white shadow-inner dark:bg-gray-800">
                <!-- Header showing contact name and status -->
                <header v-if="selectedContact" class="border-b border-gray-200 p-4 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <!-- Mobile sidebar toggle -->
                        <button
                            @click="toggleSidebar"
                            class="inline-flex items-center justify-center rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100 md:hidden dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- Contact Avatar -->
                        <div class="relative">
                            <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-blue-500">
                                <img v-if="avatarUrl" :src="avatarUrl" :alt="selectedContact.name" class="h-full w-full object-cover" />
                                <span v-else class="font-bold text-white">
                                    {{ selectedContact?.name?.charAt(0)?.toUpperCase() }}
                                </span>
                            </div>
                            <!-- <span v-if="selectedContact?.is_online"
                                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white dark:border-gray-800" /> -->
                        </div>

                        <!-- Contact Info -->
                        <div class="min-w-0 flex-1">
                            <h2 class="truncate font-medium text-gray-900 dark:text-white">
                                {{ selectedContact?.name }}
                            </h2>
                            <!-- <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                <span :class="[
                                    'inline-block w-2 h-2 rounded-full mr-1.5',
                                    selectedContact?.is_online ? 'bg-green-500' : 'bg-gray-400'
                                ]" />
                                {{ contactStatus }}
                            </p> -->
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="flex-1 overflow-y-auto">
                    <div class="h-full p-4">
                        <slot name="content">
                            <!-- Default empty state -->
                            <div
                                v-if="!selectedContact"
                                class="flex h-full flex-col items-center justify-center py-12 text-center text-gray-400 dark:text-gray-500"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mb-4 h-16 w-16 opacity-70"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    />
                                </svg>
                                <h3 class="mb-2 text-xl font-medium">No conversation selected</h3>
                                <p class="mb-4">Choose a contact to start chatting</p>
                                <button
                                    @click="toggleSidebar"
                                    class="inline-flex items-center rounded-lg bg-blue-500 px-4 py-2 text-white transition-colors hover:bg-blue-600 md:hidden"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="mr-2 h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                    View Contacts
                                </button>
                            </div>
                        </slot>

                        <!-- Default slot for messages and input -->
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
