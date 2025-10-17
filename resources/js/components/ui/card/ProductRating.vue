<script setup>
import { computed } from 'vue';

const props = defineProps({
    rating: Object,
});

const avatar_url = props.rating.user.avatar_url ? `/storage/${props.rating.user.avatar_url}` : '/images/avatar-placeholder.webp';

const starIcons = computed(() => {
    const rating = props.rating.stars ?? 0;
    const fullStars = rating;
    const emptyStars = 5 - fullStars;

    return {
        full: fullStars,
        empty: emptyStars,
    };
});
</script>

<template>
    <div class="flex gap-3">
        <img alt="" :src="avatar_url" class="border-opacity-10 h-12 w-12 rounded-full border object-cover" />

        <div class="flex-1 space-y-3">
            <div>
                <p class="font-semibold">
                    {{ rating.user.name }}
                </p>

                <div class="flex items-center gap-4">
                    <div class="text-secondary dark:text-secondary-300">
                        <i v-for="i in starIcons.full" :key="'full-' + i" class="fa-solid fa-star"></i>
                        <i v-for="i in starIcons.empty" :key="'empty-' + i" class="fa-regular fa-star"></i>
                    </div>
                    <p class="text-xs font-medium">
                        {{ new Date(rating.updated_at).toLocaleDateString('en-GB') }}
                    </p>
                </div>
            </div>
            <p>
                {{ rating.message }}
            </p>
        </div>
    </div>
</template>
