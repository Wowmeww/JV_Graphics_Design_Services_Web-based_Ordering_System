<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        message: Object,
        sender: Boolean
    });

    const getRelativeTime = (date) => {
        const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });
        const now = new Date();
        const diff = (now - new Date(date)) / 1000; // in seconds

        if (diff < 60) return 'Just now';
        if (diff < 3600) return rtf.format(-Math.floor(diff / 60), 'minute');
        if (diff < 86400) return rtf.format(-Math.floor(diff / 3600), 'hour');
        if (diff < 604800) return rtf.format(-Math.floor(diff / 86400), 'day');
        return new Date(date).toLocaleDateString(); // fallback
    };
    const avatarUrl = computed(() => {
        const url = props.message?.sender?.avatar_url;
        if (!url) return null;
        return url.includes('https')
            ? url
            : `/storage/${url}`;
    });

</script>

<template>
    <!-- Incoming message example -->
    <div :class="{
        'flex items-start gap-2': !sender,
        'flex items-start gap-2 flex-row-reverse': sender,
    }">
        <div :class="{
            'w-8 h-8 rounded-full bg-gradient-to-br overflow-hidden from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold text-lg shadow-sm': !sender,
            'hidden': sender,
        }">
            <img v-if="avatarUrl" :src="avatarUrl" alt="">
            <span v-else class="font-bold dark:text-white">N</span>
        </div>
        <div class="max-w-[75%]">
            <div :class="{
                'bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-sm text-wrap break-words break-all': !sender,
                'bg-primary-700 text-white rounded-lg p-3 text-sm text-wrap break-words break-all': sender,
            }">
                <p>{{ message.content }}</p>
                <p :class="{
                    'text-xs text-gray-500 dark:text-gray-400 mt-1 text-right': !sender,
                    'text-xs text-blue-100 mt-1 text-right': sender,
                }">
                    {{ getRelativeTime(message.created_at) }}
                </p>
            </div>
        </div>
    </div>
</template>