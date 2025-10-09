<script setup>
import Layout from '@/components/ui/messages/Layout.vue';
import Message from '../../../components/ui/messages/Message.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useEcho } from '@laravel/echo-vue';
import { nextTick } from 'vue';

const props = defineProps({
    contacts: Array,
    receiver: Object,
    messages: Array,
    user: Object,
});

const tempMessages = ref([...props.messages]);

const form = useForm({
    content: null,
});

useEcho(`message-sent.${props.user.id}`, '.MessageSent', async ({ message }) => {
    if (message.receiver_id === props.user.id && message.sender_id === props.receiver?.id) {
        tempMessages.value.push(message);
        await nextTick(); // wait for DOM to update
        scrollToBottom();
    }
});

const messagesContainer = ref(null);

// Keep synced when prop changes
watch(
    () => props.messages,
    (newMessages) => {
        tempMessages.value = [...newMessages];
    },
);
// Also scroll on initial mount
onMounted(() => {
    scrollToBottom();
});
// Scroll function
function scrollToBottom() {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
}

function send() {
    form.post(route('message.store', { receiver: props.receiver.id }), {
        onSuccess() {
            form.reset('content');
            scrollToBottom();
        },
        preserveState: true,
        preserveScroll: true,
    });
}

const params = computed(() => route().params);
</script>

<template>
    <Layout :contacts="contacts" :selectedContact="receiver" :user="user">
        <div class="flex h-full max-h-[85vh] flex-col" v-if="receiver">
            <!-- Messages Area -->
            <div ref="messagesContainer" id="messages-container" class="max-h-9/12 flex-1 space-y-3 overflow-y-auto scroll-smooth p-4">
                <div class="text-center">
                    <Link
                        v-if="tempMessages?.length >= 20 && !params.all"
                        :href="route('message.store', { receiver: props.receiver.id, all: true })"
                        as="button"
                        class="inline-flex items-center text-sm font-medium text-blue-600 transition-colors duration-200 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                            />
                        </svg>
                        Load more messages
                    </Link>
                    <div v-else-if="tempMessages?.length >= 12" class="py-1 text-xs text-gray-500 dark:text-gray-400">
                        You've reached the beginning of the conversation
                    </div>
                </div>
                <Message
                    v-for="message in tempMessages"
                    :key="`message-${message.id}`"
                    :message="message"
                    :sender="!(receiver?.id === message.sender_id)"
                />
            </div>
            <!-- Message Input Area -->
            <div class="border-t border-gray-200 p-4 dark:border-gray-700">
                <form @submit.prevent="send" class="flex items-center gap-2">
                    <!-- <button
                        class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 " fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button> -->

                    <input
                        required
                        v-model="form.content"
                        type="text"
                        placeholder="Type a message..."
                        class="flex-1 rounded-full border border-gray-300 bg-white px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800"
                    />
                    <button
                        v-show="form.content"
                        :disabled="form.processing"
                        type="submit"
                        class="bg-primary-500 hover:bg-primary rounded-full p-2 text-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
                <small v-if="form.errors.content" class="form-control-error ps-4">{{ form.errors.content }}</small>
            </div>
        </div>
    </Layout>
</template>
