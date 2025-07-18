<script setup>
    import { ref, watch } from 'vue';
    import Announcement from '../../../components/ui/card/Announcement.vue';
    import Search from '../../../components/ui/Search.vue';
    import { debounce } from 'lodash';
    import { router } from '@inertiajs/vue3';
    const props = defineProps({
        announcements: Object,
        search: String
    });

    const search = ref(props.search);

    watch(() => search.value, debounce(function (value) {
        value = value.trim();
        router.get(route('announcement.index'), {
            search: search.value
        }, {
            preserveState: true
        });
    }, 400));

    function addAnnouncement() {
        router.get(route('announcement.create'));
    }
</script>

<template>

    <Head title="Announcements" />

    <div class="mx-auto px-2 max-w-6xl py-6 h-full space-y-4">

        <div
            class="container-secondary flex flex-col items-center gap-2 sm:justify-between sm:flex-row sm:items-center">
            <h1 class="text-xl md:text-2xl font-semibold">Announcements</h1>

            <div>
                <button @click="addAnnouncement" class="btn btn-secondary ">
                    <i class="fa-solid fa-plus"></i>
                    <span> New Announcements</span>
                </button>
            </div>
        </div>

        <div
            class="container-secondary flex flex-col items-center gap-y-2 sm:px-20 gap-x-4 sm:justify-between sm:flex-row sm:items-center">
            <div class="flex-1 w-full">
                <Search v-model="search" variant="secondary" type="search" :required="false"
                    placeholder="Search. . ." />
            </div>
            <button @click="search = ''" class="btn btn-outline-secondary w-full sm:w-auto">Clear filter</button>
        </div>

        <div class="container-secondary h-screen">
            <div
                class="grid md:grid-cols-2 items-start gap-3 grid-rows-none h-full overflow-y-scroll">

                <Announcement v-for="announcement in announcements" :announcement="announcement" with-controls class="" />

                <div v-if="!announcements.length"
                    class="col-span-full flex flex-col items-center justify-center h-full py-12">
                    <div class="text-center max-w-md p-6 rounded-xl">
                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                            <i class="fa-regular fa-bell text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                        <div v-if="!search">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No announcements yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                                When you create announcements, they'll appear here
                            </p>
                            <button @click="addAnnouncement" class="btn btn-secondary mx-auto">
                                <i class="fa-solid fa-plus"></i>
                                <span>Create announcement</span>
                            </button>
                        </div>
                        <p v-else class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                            No announcement found that match your search
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>