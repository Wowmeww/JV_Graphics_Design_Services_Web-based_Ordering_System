<script setup>
    import { router, useForm, usePage } from '@inertiajs/vue3';
    import { watch } from 'vue';

    const page = usePage();
    const props = defineProps({
        announcement: Object
    });

    const form = useForm({
        title: props.announcement.title,
        content: props.announcement.content
    });

    function submit() {
        form.patch(route('announcement.update', [props.announcement]));
    }

    function handelDelete() {
        const confirm = page.props.confirm;
        confirm.action = { deleteAnnouncement: true }
        confirm.show = true;
        confirm.message = 'Are you sure to delete this announcement?';
    }

    watch(() => page.props.confirm, function (confirm) {
       
        if (confirm.confirmed && confirm.action.deleteAnnouncement) {
            router.delete(route('announcement.destroy', [props.announcement.id]));
        }
    }, { deep: true })
</script>

<template>

    <Head title="Edit Announcement" />

    <div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <!-- Gradient Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-t-xl shadow-lg">
            <div class="max-w-3xl mx-auto px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white">Edit Announcement</h2>
                        <p class="mt-1 text-blue-100">
                            Share important updates with your community
                        </p>
                    </div>
                    <div class="bg-white/10 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625 1.234 9.366 3.87a1 1 0 01-.127 1.454A3.995 3.995 0 0117 13h-1.832c-2.127 0-4.113.676-5.73 1.832a4.002 4.002 0 00-2.002.626z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-b-xl overflow-hidden">
            <form @submit.prevent="submit" class="p-8 space-y-8">
                <!-- Title Field -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <label for="title" class="block text-lg font-semibold text-gray-800 dark:text-white">
                            Announcement Title
                        </label>
                        <span class="text-red-500 text-xl">*</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Make it clear and attention-grabbing
                    </p>
                    <input id="title" v-model="form.title" type="text"
                        class="block w-full form-control form-control-secondary text-xl"
                        placeholder="e.g., System Maintenance This Weekend"
                        :class="{ 'border-red-400': form.errors.title }">
                    <p v-if="form.errors.title" class="mt-1 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Content Field -->
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <label for="content" class="block text-lg font-semibold text-gray-800 dark:text-white">
                            Announcement Details
                        </label>
                        <span class="text-red-500 text-xl">*</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Include all relevant information for your audience
                    </p>
                    <textarea id="content" v-model="form.content" rows="10"
                        class="block w-full form-control form-control-secondary text-xl"
                        placeholder="Write your detailed announcement here..."
                        :class="{ 'border-red-400': form.errors.content }"></textarea>
                    <p v-if="form.errors.content" class="mt-1 text-sm text-red-500 font-medium flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.content }}
                    </p>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-8 border-t border-gray-100 dark:border-gray-700">
                    <Link :href="route('announcement.index')"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 text-base font-medium rounded-xl border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                    </Link>
                    <button type="button" @click="handelDelete"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 text-base font-medium text-white bg-gradient-to-r bg-red-500 hover:bg-red-600 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">Delete</button>
                    <button type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 text-base font-medium text-white bg-gradient-to-r from-secondary-500 to-primary hover:from-secondary hover:to-primary-700 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300"
                        :disabled="form.processing" :class="{ 'opacity-80 cursor-not-allowed': form.processing }">
                        <template v-if="!form.processing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Update Announcement
                        </template>
                        <template v-else>
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Updating...
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>