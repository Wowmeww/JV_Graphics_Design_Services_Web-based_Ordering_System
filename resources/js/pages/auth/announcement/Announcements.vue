<script setup>
import { ref, watch } from 'vue';
import Announcement from '../../../components/ui/card/Announcement.vue';
import Search from '../../../components/ui/Search.vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
const props = defineProps({
    announcements: Object,
    search: String,
});

const search = ref(props.search);

watch(
    () => search.value,
    debounce(function (value) {
        value = value.trim();
        router.get(
            route('announcement.index'),
            {
                search: search.value,
            },
            {
                preserveState: true,
            },
        );
    }, 400),
);

function addAnnouncement() {
    router.get(route('announcement.create'));
}
</script>

<template>
    <Head title="Announcements" />

    <div class="mx-auto h-full max-w-6xl space-y-4 px-2 py-6">
        <div class="container-secondary flex flex-col items-center gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-semibold md:text-2xl">Announcements</h1>

            <div v-if="$page.props.auth?.user.is_admin">
                <button @click="addAnnouncement" class="btn btn-secondary">
                    <i class="fa-solid fa-plus"></i>
                    <span> New Announcements</span>
                </button>
            </div>
        </div>

        <div class="container-secondary flex flex-col items-center gap-x-4 gap-y-2 sm:flex-row sm:items-center sm:justify-between sm:px-20">
            <div class="w-full flex-1">
                <Search v-model="search" variant="secondary" type="search" :required="false" placeholder="Search. . ." />
            </div>
            <button @click="search = ''" class="btn btn-outline-secondary w-full sm:w-auto">Clear filter</button>
        </div>

        <div class="container-secondary h-screen">
            <div class="grid h-full grid-rows-none items-start gap-3 overflow-y-auto md:grid-cols-2">
                <Announcement v-for="announcement in announcements" :announcement="announcement" class="" />

                <div v-if="!announcements?.length" class="col-span-full flex h-full flex-col items-center justify-center py-12">
                    <div class="max-w-md rounded-xl p-6 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                            <i class="fa-regular fa-bell text-xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <div v-if="!search">
                            <h3 class="mb-1 text-lg font-medium text-gray-900 dark:text-white">No announcements yet</h3>

                            <template v-if="$page.props.auth?.user.is_admin">
                                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">When you create announcements, they'll appear here</p>
                                <button @click="addAnnouncement" class="btn btn-secondary mx-auto">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Create announcement</span>
                                </button>
                            </template>
                        </div>
                        <p v-else class="mb-4 text-sm text-gray-500 dark:text-gray-400">No announcement found that match your search</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
