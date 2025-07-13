<script setup>
    import { ref } from 'vue';
    import ContainerPrimary from '../ContainerPrimary.vue';


    defineProps({
        title: String,
    });

    const currentDate = ref(new Date().toLocaleDateString('en-us', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }));

    setInterval(() => {
        currentDate.value = new Date().toLocaleDateString('en-us', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }, 30000);

    function refreshPage() {
        location.reload();
    }

    const styleClass = {
        headerContainer: 'flex gap-4 justify-between items-center py-6 flex-col md:flex-row',
        title: 'font-bold text-2xl md:text-4xl text-center md:text-left',
        refresh: 'font-semibold py-2 hidden lg:inline-block px-4 space-x-1 cursor-pointer group rounded transition hover:bg-primary-100/50 dark:hover:bg-primary-100/10 min-w-fit',
        date: 'font-semibold border border-primary-800 w-full md:w-fit text-center dark:border-primary-200/60 py-2 px-12 rounded bg-primary-100/50 dark:bg-primary-100/10'
    };
</script>

<template>
    <ContainerPrimary :class="styleClass.headerContainer">
        <h2 :class="styleClass.title">{{ title }}</h2>

        <div class="flex gap-4 justify-center md:justify-between items-center w-full md:w-fit">
            <button @click="refreshPage" :class="styleClass.refresh">
                <span>Data Refresh </span>
                <i class="fa-solid fa-rotate group-hover:animate-spin" />
            </button>
            <p class="form-control px-14 font-semibold text-center">
                {{ currentDate ? currentDate : 'Date' }}
            </p>
        </div>
    </ContainerPrimary>
</template>