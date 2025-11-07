<script setup>
import { computed } from 'vue';

const props = defineProps({
    notification: { type: Object, required: true },
});

function getInitials() {
    const fullName = props.notification.from.name;
    console.log(fullName);
    if (!props) return '';

    const names = fullName.trim().split(' ');

    if (names.length === 0) return '';
    if (names.length === 1) return names[0].charAt(0).toUpperCase();

    return `${names[0].charAt(0)}${names[names.length - 1].charAt(0)}`.toUpperCase();
}

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
    const url = props.notification.from.avatar_url;
    if (!url) return null;
    return url.includes('https') ? url : `/storage/${url}`;
});
</script>

<template>
    <div class="group relative py-1.5">
        <a href="#" class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 hover:bg-gray-50/80 dark:hover:bg-gray-700/50">
            <!-- Notification indicator dot (only for unread) -->
            <div
                v-if="!notification.seen"
                class="bg-secondary-500 dark:bg-secondary-300 absolute top-1/2 left-3 h-2 w-2 -translate-y-1/2 rounded-full transition-opacity"
            ></div>

            <!-- Avatar placeholder (replace with actual img tag) -->
            <div
                class="to-secondary border-secondary dark:border-secondary-500 flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full border bg-gradient-to-br from-blue-400 text-xs font-medium text-white"
            >
                <span v-if="!avatarUrl">
                    {{ getInitials() }}
                </span>

                <img v-else :src="avatarUrl" alt="avatar" class="h-full w-full object-cover" />
            </div>

            <!-- Notification content -->
            <div class="flex-1">
                <p class="flex flex-col  text-sm text-gray-700 dark:text-gray-200">
                    <span class="text-gray-900 dark:text-white">
                        {{ notification.from.name }}
                    </span>
                    <span class="font-semibold">
                        {{ notification.header }}
                    </span>
                </p>
                <div class="mt-1 flex items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ getRelativeTime(notification.created_at) }}
                    </span>
                </div>
            </div>
        </a>
    </div>
</template>
