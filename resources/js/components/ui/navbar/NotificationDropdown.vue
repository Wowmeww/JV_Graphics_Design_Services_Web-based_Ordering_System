<script setup>
import { computed, ref, watch } from 'vue';
import NotificationDropdownCard from '../card/NotificationDropdownCard.vue';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';

const isOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user);
const tempNotifications = ref([...user.value.notifications.reverse().slice(0, 4)]);

watch(
    () => page.props.auth.user.notifications,
    (notifications) => {
        tempNotifications.value = [...notifications.reverse().slice(0, 4)];
    },
);

useEcho(`new-order-notification.${user.value?.id}`, '.NewOrderNotification', async ({ notification }) => {
    tempNotifications.value?.unshift(notification);
    tempNotifications.value.pop();
});
</script>

<template>
    <div class="relative">
        <!-- Dropdown toggle button -->
        <button
            @click="isOpen = !isOpen"
            class="focus:ring-opacity-40 dark:focus:ring-opacity-40 relative z-10 block rounded-md border border-transparent bg-white p-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-300 focus:outline-none dark:bg-gray-800 dark:text-white dark:focus:ring-blue-400"
        >
            <!-- make a better icon -->
            <svg class="h-5 w-5 text-gray-800 dark:text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Bell body -->
                <path
                    d="M12 2C10.8954 2 10 2.89543 10 4V5C6.68629 5 4 7.68629 4 11V16L3 17V18H21V17L20 16V11C20 7.68629 17.3137 5 14 5V4C14 2.89543 13.1046 2 12 2Z"
                    fill="currentColor"
                />

                <circle
                    v-if="tempNotifications?.filter((notification) => !notification.seen).length"
                    cx="18"
                    cy="6"
                    r="3"
                    fill="#EF4444"
                    stroke="white"
                    stroke-width="1.5"
                />

                <!-- Bell clapper -->
                <path d="M10 19C10 20.1046 10.8954 21 12 21C13.1046 21 14 20.1046 14 19H10Z" fill="currentColor" />
            </svg>
        </button>

        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="opacity-0 scale-90"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-90"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 z-20 mt-2 w-64 origin-top-right overflow-hidden rounded-md bg-white shadow-lg sm:w-80 dark:bg-gray-800"
            >
                <NotificationDropdownCard
                    v-for="notification in tempNotifications"
                    :notification="notification"
                    :key="`notification-${notification.id}`"
                />
                <Link
                    class="block w-full bg-gray-800 py-2 text-center font-bold text-white hover:underline dark:bg-gray-700"
                    as="button"
                    :href="route('notification.index')"
                    @click="isOpen = false"
                >
                    See all notifications
                </Link>
            </div>
        </Transition>
    </div>
</template>
