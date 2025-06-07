<script setup>
    defineProps({
        path: {
            type: String,
            default: 'images/background/homeBI.jpg'
        },
        blur: {
            type: Boolean,
            default: true
        }
    });
</script>

<template>
    <div v-cloak :style="`background-image: url(${path}); background-position: center; background-repeat: repeat-x; background-size: cover;`"
        class="min-h-screen absolute top-0  inset-x-0 -z-10" >
        <div v-if="blur"
            style="background: rgba(59, 18, 72, 0.29); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(6.6px); -webkit-backdrop-filter: blur(6.6px);"
            class="absolute inset-0">
        </div>
    </div>
</template>