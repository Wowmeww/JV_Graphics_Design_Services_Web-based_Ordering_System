<script setup>
    import { computed, ref } from 'vue';

    const props = defineProps({
        announcement: Object,
    });

    const expanded = ref(false);

    function toggleExpand() {
        expanded.value = !expanded.value;
    }

    function formattedDate(date) {
        return new Date(date).toLocaleDateString('en-Us', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: 'numeric'
        });
    }

    const avatar_src = computed(() => {
        let src = props.announcement.user.avatar_url;
        if (src) {
            return src.includes('https')
                ? src
                : `/storage/${src}`;
        }
        return '/images/avatar-placeholder.webp';
    });

</script>

<template>

    <div class="max-w-2xl px-8 py-4 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border">
        <div class="flex items-center justify-between">
            <span class="text-sm font-light text-gray-600 dark:text-gray-400">
                {{ formattedDate(announcement.created_at) }}
            </span>
            <div v-if="$page.props?.auth?.user?.is_admin" class="space-x-2">
                <Link as="button" :href="route('announcement.edit', { announcement: announcement.id })"
                    class="hover:text-primary dark:hover:text-primary-300">
                <i class="bi bi-pencil-square"></i>
                </Link>
            </div>
        </div>
        <div class="mt-2">
            <a href="#"
                class="text-xl font-bold break-all text-wrap text-gray-700 dark:text-white hover:text-gray-600 dark:hover:text-gray-200 hover:underline"
                tabindex="0" role="link">{{ announcement.title }}</a>
            <p class="mt-2 text-gray-600 dark:text-gray-300 break-all text-wrap">
                {{ expanded ? announcement.content : announcement.content?.slice(0, 220) }}

                <span v-if="(announcement.content?.length >= 220) && !expanded">...</span>
            </p>
        </div>

        <div class="flex items-center justify-between mt-4">
            <button v-if="announcement.content?.length >= 220" @click="toggleExpand" href="#"
                class="text-blue-600 dark:text-blue-400 hover:underline" tabindex="0" role="link">
                {{ expanded ? 'Read less' : 'Read more' }}
            </button>
            <div class="flex items-center">
                <img class="hidden object-cover w-10 h-10 mx-4 rounded-full border border-primary dark:border-primary-200 sm:block"
                    :src="avatar_src" alt="avatar">
                <Link as="button" :href="route('message.index', { receiver: announcement.user.id })"
                    class="font-bold text-gray-700 cursor-pointer dark:text-gray-200" tabindex="0" role="link">
                {{ announcement.user.name }}
                </Link>
            </div>
        </div>

    </div>
</template>