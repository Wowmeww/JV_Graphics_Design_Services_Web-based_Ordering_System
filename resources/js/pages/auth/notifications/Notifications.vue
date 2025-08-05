<script setup>
import Notification from '@/components/ui/card/Notification.vue';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import { computed, ref } from 'vue';
const props = defineProps({
    notifications: Array,
});
const page = usePage();

const user = computed(() => page.props.auth?.user);

const tempNotifications = ref([...props.notifications]);

useEcho(`new-order-notification.${user.value?.id}`, '.NewOrderNotification', async ({ notification }) => {
    tempNotifications.value?.unshift(notification);
});
</script>

<template>
    <Head title="Notifications" />

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            <Link
                as="button"
                :href="route('notification.mark.all.read')"
                class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
            >
                Mark all as read
            </Link>
        </div>

        <!-- Notification List -->
        <div>
            <div v-if="notifications.length" class="space-y-4">
                <Notification v-for="notification in tempNotifications" :notification="notification" :key="`notification-card-${notification.id}`" />
            </div>
            <!-- Empty State -->
            <div v-else class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center dark:border-gray-600 dark:bg-gray-700/50">
                <i class="fa-regular fa-bell-slash mb-3 text-4xl text-gray-400 dark:text-gray-500"></i>
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No notifications</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You'll see notifications here when you have them</p>
            </div>
        </div>
    </div>
</template>
