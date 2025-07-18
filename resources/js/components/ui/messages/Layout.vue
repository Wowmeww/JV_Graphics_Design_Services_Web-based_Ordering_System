<script setup>
    import { computed, ref, watch } from 'vue';
    import ContactCard from './ContactCard.vue';
    import { useForm } from '@inertiajs/vue3';
    import { debounce } from 'lodash';

    const props = defineProps({
        contacts: {
            type: Array,
            default: () => []
        },
        selectedContact: {
            type: Object,
            default: null
        },
        user: Object
    });

    const form = useForm({
        search: null
    });

    const tempContacts = ref([...props.contacts]);

    watch(() => form.search, debounce((value) => {
        value = value.trim();

        form.get(route('message.index', { receiver: props.selectedContact?.id }), {
            preserveState: true,
            preserveScroll: true
        });
    }, 500));

    const isDarkMode = ref(false);
    const isSidebarOpen = ref(false);

    const toggleSidebar = () => {
        isSidebarOpen.value = !isSidebarOpen.value;
    };

    const avatarUrl = computed(() => {
        if (!props.selectedContact?.avatar_url) return null;
        return props.selectedContact.avatar_url.includes('https')
            ? props.selectedContact.avatar_url
            : `/storage/${props.selectedContact.avatar_url}`;
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
    <div class="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <div class="flex flex-col md:flex-row h-screen max-w-6xl mx-auto relative">
            <!-- Mobile Toggle Button -->
            <button @click="toggleSidebar"
                class="fixed z-20 left-4 top-4 p-2 rounded-full shadow-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white md:hidden"
                aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Sidebar Area -->
            <aside :class="[
                'w-full md:w-80 flex-shrink-0 border-r p-4 shadow-md transition-transform duration-300 ease-in-out ',
                'fixed md:relative z-10 h-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700',
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
            ]">
                <!-- Close button for mobile -->
                <button @click="toggleSidebar"
                    class="absolute right-4 top-4 p-1 rounded-full text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white md:hidden border border-transparent hover:border-slate-400"
                    aria-label="Close sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Sidebar Content -->
                <slot name="sidebar">
                    <div class="pb-4 space-y-2">
                        <h2 class="text-xl font-semibold">Messages</h2>
                        <!-- SEARCH INPUT -->
                        <input v-model="form.search" type="search" placeholder="Search. . ."
                            class="flex-1 w-full border border-gray-300 dark:border-gray-600 rounded-full py-2 px-4 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="space-y-2 overflow-y-auto max-h-[calc(100vh-120px)]">
                        <ContactCard :user="user" v-for="(contact, i) in tempContacts"
                            @sentAMessage="handleUnshift(i, contact)"
                            :key="`contact-${contact.id}`" :contact="contact"
                            :selected="contact.id === selectedContact?.id" />
                    </div>
                </slot>
            </aside>

            <!-- Overlay for mobile -->
            <div v-show="isSidebarOpen" @click="isSidebarOpen = false"
                class="fixed inset-0 bg-black/25 z-0 md:hidden" />

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col bg-white dark:bg-gray-800 shadow-inner">
                <!-- Header showing contact name and status -->
                <header v-if="selectedContact"
                    class="p-4 border-b dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <!-- Mobile sidebar toggle -->
                        <button @click="toggleSidebar"
                            class="inline-flex md:hidden items-center justify-center p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Contact Avatar -->
                        <div class="relative">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center overflow-hidden">
                                <img v-if="avatarUrl" :src="avatarUrl" :alt="selectedContact.name"
                                    class="w-full h-full object-cover" />
                                <span v-else class="text-white font-bold">
                                    {{ selectedContact?.name?.charAt(0)?.toUpperCase() }}
                                </span>
                            </div>
                            <span v-if="selectedContact?.is_online"
                                class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white dark:border-gray-800" />
                        </div>

                        <!-- Contact Info -->
                        <div class="flex-1 min-w-0">
                            <h2 class="font-medium text-gray-900 dark:text-white truncate">
                                {{ selectedContact?.name }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                <span :class="[
                                    'inline-block w-2 h-2 rounded-full mr-1.5',
                                    selectedContact?.is_online ? 'bg-green-500' : 'bg-gray-400'
                                ]" />
                                {{ contactStatus }}
                            </p>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <div class="flex-1 overflow-y-auto ">
                    <div class="p-4 h-full">
                        <slot name="content">
                            <!-- Default empty state -->
                            <div v-if="!selectedContact"
                                class="h-full flex flex-col items-center justify-center text-center py-12 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 opacity-70" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="text-xl font-medium mb-2">No conversation selected</h3>
                                <p class="mb-4">Choose a contact to start chatting</p>
                                <button @click="toggleSidebar"
                                    class="md:hidden inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
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