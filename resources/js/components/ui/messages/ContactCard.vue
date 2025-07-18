<script setup>
    import { useEcho } from '@laravel/echo-vue';
    import { computed, ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
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
        },
        user: Object
    });
    // const page = usePage();
    const sentMessages = computed(() => (props.contact.sent_messages));
    const lastMessage = ref(sentMessages.value.at(-1)?.content || '');
    const unreadMessages = ref(sentMessages.value.filter(msg => !msg.seen)?.length);

    const emit = defineEmits(['select', 'sentAMessage']);

    const selectContact = () => {
        emit('select');
        router.get(route('message.index', { receiver: props.contact.id }), {}, {
            preserveScroll: true,
        });
    };
    const avatar_url = computed(() => {
        let src = props.contact.avatar_url;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
    });

    // this echo is running 16 times why?
    useEcho(`message-sent.${props.user.id}`, '.MessageSent', function ({ message }) {
        if (message.sender_id === props.contact.id) {
            unreadMessages.value += 1;
            emit('sentAMessage', message);
            if (props.selected) {
                unreadMessages.value = 0;
            }
            lastMessage.value = message?.content;
        }
    });
    if (props.selected) {
        unreadMessages.value = 0;
    }
</script>

<template>
    <div as="button" @click="selectContact"
        class="flex w-full items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200 text-start relative group hover:shadow-xs"
        :class="{
            'bg-blue-100/90 hover:bg-blue-200 dark:bg-blue-900/70 dark:hover:bg-blue-800/90 shadow-inner': selected,
            'bg-white hover:bg-gray-50 dark:bg-gray-800/80 dark:hover:bg-gray-700/90': !selected
        }">
        <!-- Avatar with online indicator -->
        <div class="relative flex-shrink-0">
            <div
                class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 overflow-hidden flex items-center justify-center text-white font-semibold text-lg shadow-sm">
                <img v-if="avatar_url" :src="avatar_url" :alt="contact.name" class="w-full h-full object-cover">
                <span v-else class="uppercase">
                    {{ contact.name.charAt(0) }}
                </span>
            </div>
            <span v-if="contact.is_online"
                class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white dark:border-gray-800"></span>
        </div>

        <!-- Contact Info -->
        <div class="flex-1 min-w-0 overflow-hidden">
            <div class="flex justify-between items-baseline gap-2">
                <p class="font-semibold truncate text-gray-900 dark:text-white">
                    {{ contact.name }}
                </p>
            </div>
            <p v-if="lastMessage" class="text-sm truncate mt-0.5 text-gray-500 dark:text-gray-400">
                {{ lastMessage }}
            </p>
        </div>

        <!-- Unread Badge -->
        <span v-if="unreadMessages > 0"
            class="absolute right-3 flex items-center justify-center  min-w-5 h-5 text-xs font-medium px-1.5 py-0.5 bg-red-500/75 text-white rounded-full shadow-sm">
            {{ unreadMessages > 9 ? '9+' : unreadMessages }}
        </span>
    </div>
</template>