<script setup>
defineProps({
    title: { type: String, default: '' },
    lead: { type: String, default: '' },
});
</script>
<template>
    <div class="container-primary rounded-2xl bg-white md:p-8 p-3.5 dark:bg-[#1e293b]">
        <div v-if="title">
            <h1 v-show="title" class="page-header">{{ title }}</h1>
            <p v-show="lead" class="lead">{{ lead }}</p>
        </div>
        <slot />
    </div>
</template>