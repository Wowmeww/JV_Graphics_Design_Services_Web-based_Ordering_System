<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    notification: { type: Object, required: true },
});

const tempSeen = ref(props.notification.seen);

function toggleSeen() {
    router.patch(
        route('notification.update', { notification: props.notification.id, seen: !tempSeen.value }),
        {},
        {
            onFinish: () => (tempSeen.value = !tempSeen.value),
        },
    );
}

function getInitials() {
    const fullName = props.notification.from.name;

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
    // console.log(props.notification.from);
    if (!url) return null;
    return url.includes('https') ? url : `/storage/${url}`;
});
</script>

<template>
    <div
        :class="[
            'relative rounded-xl border border-gray-200 p-5 shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-gray-800',
            !tempSeen ? 'border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-gray-800',
        ]"
    >
        <div class="flex items-start gap-4">
            <div
                class="relative flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
            >
                <div
                    v-if="!tempSeen"
                    class="bg-primary-300 text-primary dark:text-primary-400 absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full dark:bg-blue-900/30"
                >
                    <i class="fa-solid fa-bell text-xs"></i>
                </div>

                <div
                    class="to-secondary border-secondary dark:border-secondary-500 flex h-full w-full shrink-0 items-center justify-center overflow-hidden rounded-full border bg-gradient-to-br from-blue-400 text-xs font-medium text-white"
                >
                    <span v-if="!avatarUrl">
                        {{ getInitials() }}
                    </span>

                    <img v-else :src="avatarUrl" alt="avatar" class="h-full w-full object-cover" />
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ notification.from.name }}
                </p>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ notification.header }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ notification.content }}
                </p>
                <div class="mt-2 flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                    <span><i class="fa-regular fa-clock mr-1"></i>{{ getRelativeTime(notification.created_at) }}</span>
                    <button @click="toggleSeen" class="cursor-pointer font-medium text-blue-600 hover:underline dark:text-blue-400">
                        {{ tempSeen ? 'Mark as unread' : 'Mark as read' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
