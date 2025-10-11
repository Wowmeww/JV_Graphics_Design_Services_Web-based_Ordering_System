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
        hour: 'numeric',
    });
}

const avatar_src = computed(() => {
    let src = props.announcement.user.avatar_url;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
});

const styleClass = {
    // Main container
    container:
        'group animate__animated animate__fadeInUp max-w-2xl px-6 py-5 rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:border-primary-200 dark:hover:border-primary-800 backdrop-blur-sm',

    // Header section
    header: {
        container: 'flex items-center justify-between mb-3',
        date: 'text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full',
    },

    // Admin actions
    admin: {
        container: 'flex items-center space-x-3',
        editButton:
            'text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-white dark:bg-gray-700 shadow-sm hover:shadow-md',
    },

    // Content section
    content: {
        container: 'space-y-4',
        title: 'text-2xl font-bold break-words text-gray-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 cursor-pointer line-clamp-2',
        body: 'text-gray-600 dark:text-gray-300 break-words leading-relaxed text-lg bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600',
        ellipsis: 'font-black text-primary-500',
    },

    // Footer section
    footer: {
        container: 'flex items-center justify-between mt-6 pt-4 border-t border-gray-200 dark:border-gray-600',
        expandButton:
            'group/btn inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold transition-all duration-200 hover:gap-3 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/30',
        user: {
            container: 'flex items-center gap-3',
            avatar: 'hidden sm:block w-12 h-12 rounded-full border-2 border-primary-200 dark:border-primary-700 object-cover shadow-md hover:scale-105 transition-transform duration-200',
            name: 'font-semibold text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 cursor-pointer bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-full hover:shadow-sm',
        },
    },
};
</script>

<template>
    <div :class="styleClass.container">
        <div :class="styleClass.header.container">
            <span :class="styleClass.header.date">
                <i class="bi bi-calendar-event mr-2"></i>
                {{ formattedDate(announcement.created_at) }}
            </span>
            <div v-if="$page.props?.auth?.user?.is_admin" :class="styleClass.admin.container">
                <Link
                    as="button"
                    :href="route('announcement.edit', { announcement: announcement.id })"
                    :class="styleClass.admin.editButton"
                    title="Edit announcement"
                >
                    <i class="bi bi-pencil-square text-lg"></i>
                </Link>
            </div>
        </div>

        <div :class="styleClass.content.container">
            <a href="#" :class="styleClass.content.title" tabindex="0" role="link">
                {{ announcement.title }}
            </a>
            <p :class="styleClass.content.body">
                {{ expanded ? announcement.content : announcement.content?.slice(0, 220) }}
                <span v-if="announcement.content?.length >= 220 && !expanded" :class="styleClass.content.ellipsis"> ... </span>
            </p>
        </div>

        <div :class="styleClass.footer.container">
            <button
                v-if="announcement.content?.length >= 220"
                @click="toggleExpand"
                :class="styleClass.footer.expandButton"
                tabindex="0"
                role="button"
            >
                <span>{{ expanded ? 'Read less' : 'Read more' }}</span>
                <i
                    :class="expanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"
                    class="transition-transform duration-200 group-hover/btn:scale-110"
                ></i>
            </button>

            <div :class="styleClass.footer.user.container">
                <img :class="styleClass.footer.user.avatar" :src="avatar_src" :alt="`${announcement.user.name}'s avatar`" />
                <Link
                    as="button"
                    :href="route('message.index', { receiver: announcement.user.id })"
                    :class="styleClass.footer.user.name"
                    tabindex="0"
                    role="link"
                >
                    <i class="bi bi-person-circle mr-2"></i>
                    {{ announcement.user.name }}
                </Link>
            </div>
        </div>
    </div>
</template>
