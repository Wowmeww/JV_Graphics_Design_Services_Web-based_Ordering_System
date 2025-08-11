<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const emit = defineEmits(['update-role']);
const props = defineProps({
    role: String,
});

const roles = ['customer', 'suspended', 'admin'];
const isOpen = ref(false);
const dropdown = ref(null);

function updateRole(role) {
    emit('update-role', { role });
    isOpen.value = false;
}

function handleClickOutside(e) {
    if (dropdown.value && !dropdown.value.contains(e.target)) {
        isOpen.value = false;
    }
}

function handleKeydown(e) {
    if (e.key === 'Escape') {
        isOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <div class="relative inline-flex w-fit text-xs uppercase" ref="dropdown">
        <button
            @click="isOpen = !isOpen"
            class="inline-flex items-center uppercase gap-2 rounded px-2 py-1 text-white shadow transition-all duration-150 focus:outline-none"
            :class="{
                'bg-green-500': role === 'customer',
                'bg-blue-500': role === 'admin',
                'bg-red-500': role === 'suspended',
            }"
            aria-haspopup="listbox"
            :aria-expanded="isOpen.toString()"
        >
            <span>{{ role }}</span>
            <i class="fa-solid fa-chevron-down text-[10px]"></i>
        </button>

        <transition name="fade">
            <div
                v-if="isOpen"
                class="absolute top-full right-0 z-50 mt-2 w-full min-w-[120px] rounded-md border bg-white py-1 text-gray-700 shadow-md dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                role="listbox"
            >
                <p
                    v-for="r in roles"
                    :key="r"
                    @click="updateRole(r)"
                    class="cursor-pointer px-3 py-1.5 text-sm hover:bg-slate-200 hover:dark:bg-slate-600"
                    role="option"
                >
                    {{ r }}
                </p>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
