<script setup>

    const model = defineModel({
        type: Number,
        default: 12
    });

    function increment() {
        model.value = Number(model.value) + 1;
        if (model.value >= 24) model.value = 24;
    }
    function decrement() {
        model.value = Number(model.value) - 1;
        if (model.value <= 12) model.value = 12;
    }
</script>

<template>
    <div class="border border-secondary dark:border-white rounded-md flex overflow-hidden">
        <button @click="decrement" class="px-1.5 inline-flex items-center bg-secondary-500 text-white" type="button">
            <i class="fa-solid fa-minus"></i>
        </button>
        <input disabled type="text" v-model="model" class="focus:outline-0 text-center  w-14 " />
        <button @click="increment" class="px-1.5 inline-flex items-center bg-secondary-500 text-white" type="button">
            <i class="fa-solid fa-plus"></i>
        </button>

    </div>
</template>