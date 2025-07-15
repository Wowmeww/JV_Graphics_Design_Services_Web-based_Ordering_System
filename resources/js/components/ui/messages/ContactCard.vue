<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        contact: {
            type: Object,
            required: true,
            default: () => ({
                id: 0,
                name: '',
                lastMessage: '',
                unread: 0,
                online: false,
                lastMessageTime: ''
            })
        },
        selected: {
            type: Boolean,
            default: false
        }
    });

    const emit = defineEmits(['select']);

    const selectContact = () => {
        emit('select', props.contact);
    };
    const avatar_url = computed(() => {
        let src = props.contact.avatar_url;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
    });
</script>

<template>
    <Link :href="route('message.index', { receiver: contact.id })" as="button" @click="selectContact" preserve-state
        class="flex w-full items-center space-x-3 p-3 rounded-lg cursor-pointer transition-all duration-200 text-start relative"
        :class="{
            'bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/80 dark:hover:bg-blue-900': selected,
            'bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/80': !selected
        }">
    <!-- Avatar with online indicator -->
    <div class="relative">
        <div
            class="w-11 h-11 rounded-full bg-gradient-to-br overflow-hidden from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-lg shadow-sm">
            <img :src="avatar_url" v-if="avatar_url" alt="" />
            <span v-else>{{ contact.name.charAt(0) }}</span>
        </div>
        <span v-if="false"
            class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white dark:border-gray-800"></span>
    </div>

    <!-- Contact Info -->
    <div class="flex-1 min-w-0">
        <div class="flex justify-between items-baseline">
            <p class="font-semibold truncate text-gray-900 dark:text-white">
                {{ contact.name }}
            </p>
        </div>
        <p v-if="'contact.lastMessage'" class="text-sm truncate mt-0.5 text-gray-500 dark:text-gray-400">
            {{ 'contact.lastMessage' }}
        </p>
    </div>

    <!-- Unread Badge -->
    <span v-if="'contact.unread > 0'"
        class="flex items-center justify-center min-w-5 h-5 absolute right-3 z-40 text-xs font-medium px-1.5 py-0.5 bg-red-500 text-white rounded-full">
        <!-- {{ 'contact.unread > 9 ? 9+ : contact.unread' }} -->
        2
    </span>
    </Link>
</template>