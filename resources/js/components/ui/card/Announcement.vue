<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    announcement: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});

const expanded = ref(false);

function toggleExpand() {
    expanded.value = !expanded.value;
}

function formattedDate(date) {
    if (!date) return 'No date';

    try {
        return new Date(date).toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
        });
    } catch (error) {
        console.error('Invalid date:', date);
        return 'Invalid date';
    }
}

const avatar_src = computed(() => {
    if (!props.announcement?.user) return '/images/avatar-placeholder.webp';

    let src = props.announcement.user.avatar_url;
    if (src) {
        return src.includes('https') ? src : `/storage/${src}`;
    }
    return '/images/avatar-placeholder.webp';
});

const announcement_image = computed(() => {
    return props.announcement?.image_url ? `/storage/${props.announcement?.image_url}` : '/images/img-placeholder.jpg';
});

const shouldShowReadMore = computed(() => {
    return props.announcement?.content?.length >= 220;
});

const displayContent = computed(() => {
    if (!props.announcement?.content) return '';
    return expanded.value ? props.announcement.content : props.announcement.content.slice(0, 220);
});

// enhance ui to perfect
const styleClass = {
    // Main container with enhanced visual hierarchy
    container:
        'group animate__animated animate__fadeInUp max-w-2xl px-6 py-6 rounded-2xl shadow-lg bg-gradient-to-br from-white to-gray-50/80 dark:from-gray-800 dark:to-gray-900/80 border border-gray-100/80 dark:border-gray-700/80 hover:shadow-2xl transition-all duration-500 hover:border-primary-300 dark:hover:border-primary-700 backdrop-blur-sm active:scale-[0.998]',

    // Header section with better spacing
    header: {
        container: 'flex flex-col gap-y-3 items-center justify-between mb-4',
        date: 'text-sm font-semibold text-gray-600 dark:text-gray-300 bg-white/80 dark:bg-gray-700/80 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-600 shadow-sm backdrop-blur-sm',
        image: 'w-full h-52 object-cover rounded-xl shadow-md hover:shadow-lg transition-all duration-500  cursor-zoom-in',
    },

    // Admin actions with better visual feedback
    admin: {
        container: 'flex items-center space-x-2',
        editButton:
            'text-gray-500 hover:text-primary-600 dark:hover:text-primary-400 active:scale-95 transition-all duration-300 p-2.5 rounded-full bg-white/90 dark:bg-gray-700/90 shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-600 backdrop-blur-sm',
    },

    // Content section with improved typography
    content: {
        container: 'space-y-5',
        title: 'text-3xl font-extrabold break-words text-gray-900 dark:text-white hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-300 cursor-pointer line-clamp-2 bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text hover:from-primary-700 hover:to-primary-500 dark:hover:from-primary-400 dark:hover:to-primary-200',
        body: 'text-gray-700 dark:text-gray-200 break-words leading-relaxed text-lg bg-white/60 dark:bg-gray-800/60 rounded-xl p-5 border border-gray-200/80 dark:border-gray-600/80 shadow-sm hover:shadow-md transition-all duration-300',
        ellipsis: 'font-black text-primary-600 dark:text-primary-400 text-xl',
    },

    // Footer section with enhanced interactions
    footer: {
        container: 'flex items-center justify-between mt-7 pt-5 border-t border-gray-300/50 dark:border-gray-500/50',
        expandButton:
            'group/btn inline-flex items-center gap-2 text-primary-700 dark:text-primary-300 hover:text-primary-800 dark:hover:text-primary-200 font-bold transition-all duration-300 hover:gap-3 px-5 py-2.5 rounded-full bg-primary-100/80 dark:bg-primary-900/30 hover:bg-primary-200 dark:hover:bg-primary-800/40 shadow-md hover:shadow-lg active:scale-95 border border-primary-200 dark:border-primary-700',
        user: {
            container: 'flex items-center gap-3',
            avatar: 'hidden sm:block w-14 h-14 rounded-full border-3 border-primary-300 dark:border-primary-600 object-cover shadow-lg  hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-300 cursor-pointer',
            name: 'font-bold text-gray-800 dark:text-gray-100 hover:text-primary-700 dark:hover:text-primary-300 transition-all duration-300 cursor-pointer bg-white/80 dark:bg-gray-700/80 px-5 py-2.5 rounded-full hover:shadow-lg border border-gray-300/50 dark:border-gray-500/50 hover:border-primary-300 dark:hover:border-primary-600 hover:scale-105 active:scale-95',
        },
    },
};
</script>

<template>
    <article 
        :class="styleClass.container"
        role="article"
        aria-labelledby="announcement-title"
    >
        <!-- Header Section -->
        <header :class="styleClass.header.container">
            <img
                v-if="announcement.image_url"
                :src="announcement_image"
                :alt="`${announcement?.title || 'Announcement'} image`"
                :class="styleClass.header.image"
                loading="lazy"
                @click="handleImageClick"
                @keyup.enter="handleImageClick"
                @keyup.space="handleImageClick"
                tabindex="0"
                role="button"
                :aria-label="`View larger image for ${announcement?.title}`"
            />
            <div class="flex gap-x-4 items-center w-full justify-between">
                <time 
                    :datetime="announcement?.created_at" 
                    :class="styleClass.header.date"
                    aria-label="Publication date"
                >
                    <i class="bi bi-calendar-event mr-2" aria-hidden="true"></i>
                    {{ formattedDate(announcement?.created_at) }}
                </time>
                <div 
                    v-if="$page.props?.auth?.user?.is_admin" 
                    :class="styleClass.admin.container"
                    aria-label="Administrator actions"
                >
                    <Link
                        as="button"
                        :href="route('announcement.edit', { announcement: announcement?.id })"
                        :class="styleClass.admin.editButton"
                        :title="`Edit ${announcement?.title}`"
                        :aria-label="`Edit ${announcement?.title}`"
                    >
                        <i class="bi bi-pencil-square text-lg" aria-hidden="true"></i>
                    </Link>
                </div>
            </div>
        </header>

        <!-- Content Section -->
        <section :class="styleClass.content.container">
            <h2 
                :id="'announcement-title-' + announcement?.id"
                :class="styleClass.content.title"
                tabindex="0"
                role="heading"
                aria-level="2"
            >
                {{ announcement?.title || 'Untitled Announcement' }}
            </h2>
            <div 
                :class="styleClass.content.body"
                aria-live="polite"
                aria-atomic="true"
            >
                <p>
                    {{ displayContent }}
                    <span 
                        v-if="shouldShowReadMore && !expanded" 
                        :class="styleClass.content.ellipsis"
                        aria-hidden="true"
                    > 
                        ... 
                    </span>
                </p>
            </div>
        </section>

        <!-- Footer Section -->
        <footer :class="styleClass.footer.container">
            <button
                v-if="shouldShowReadMore"
                @click="toggleExpand"
                @keyup.enter="toggleExpand"
                @keyup.space="toggleExpand"
                :class="styleClass.footer.expandButton"
                tabindex="0"
                role="button"
                :aria-expanded="expanded"
                :aria-controls="'announcement-content-' + announcement?.id"
                :aria-label="expanded ? 'Read less content' : 'Read more content'"
            >
                <span>{{ expanded ? 'Read less' : 'Read more' }}</span>
                <i
                    :class="expanded ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"
                    class="transition-transform duration-300 group-hover/btn:scale-110 group-hover/btn:translate-y-0.5"
                    aria-hidden="true"
                ></i>
            </button>

            <!-- Spacer to push user info to right when read more is visible -->
            <div v-else class="flex-1" aria-hidden="true"></div>

            <div 
                :class="styleClass.footer.user.container"
                aria-label="Announcement author"
            >
                <img
                    :class="styleClass.footer.user.avatar"
                    :src="avatar_src"
                    :alt="`${announcement?.user?.name || 'User'}'s avatar`"
                    loading="lazy"
                    tabindex="0"
                    role="img"
                    :aria-label="`Avatar of ${announcement?.user?.name || 'User'}`"
                />
                <Link
                    v-if="announcement?.user"
                    as="button"
                    :href="route('message.index', { receiver: announcement.user.id })"
                    :class="styleClass.footer.user.name"
                    tabindex="0"
                    role="link"
                    :aria-label="`Send message to ${announcement.user.name}`"
                    :title="`Contact ${announcement.user.name}`"
                >
                    <i class="bi bi-person-circle mr-2" aria-hidden="true"></i>
                    {{ announcement.user.name }}
                </Link>
                <span 
                    v-else 
                    :class="styleClass.footer.user.name"
                    aria-label="Unknown author"
                >
                    <i class="bi bi-person-circle mr-2" aria-hidden="true"></i>
                    Unknown User
                </span>
            </div>
        </footer>
    </article>
</template>

<style scoped>
/* Enhanced line clamping */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth focus states for accessibility */
button:focus-visible,
a:focus-visible,
img:focus-visible {
    outline: 3px solid #3b82f6;
    outline-offset: 3px;
    border-radius: 12px;
}

/* Enhanced selection styles */
::selection {
    background-color: #3b82f6;
    color: white;
}

/* Smooth scrolling for better UX */
html {
    scroll-behavior: smooth;
}

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .container,
    .header img,
    .admin button,
    .content h2,
    .footer button,
    .footer img {
        transition: none !important;
        animation: none !important;
    }
}

/* Dark mode optimizations */
@media (prefers-color-scheme: dark) {
    .container {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    }
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .line-clamp-2 {
        -webkit-line-clamp: 3;
    }
    
    .footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .footer .user-container {
        justify-content: center;
    }
}
</style>

