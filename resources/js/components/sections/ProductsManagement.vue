<script setup>
    import { ref, computed } from 'vue'
    import ContainerPrimary from "../ContainerPrimary.vue";
    import { route } from 'ziggy-js';
    import Search from '../ui/Search.vue';
    import PillPrimary from '../ui/buttons/PillPrimary.vue';
import ProductsTable from '../tables/ProductsTable.vue';
import Paginator from '../tables/Paginator.vue';
    const currentDate = ref();

    setInterval(() => {
        currentDate.value = new Date().toLocaleDateString('en-us', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }, 1000);

    const styleClass = {
        headerContainer: 'flex gap-4 justify-between items-center py-6 flex-col md:flex-row',
        title: 'font-bold text-4xl text-center md:text-left',
        refresh: 'font-semibold py-2 hidden lg:inline-block px-4 space-x-1 cursor-pointer group rounded transition hover:bg-primary-100/50 dark:hover:bg-primary-100/10',
        date: 'font-semibold border border-primary-800 w-full md:w-fit text-center dark:border-primary-200/60 py-2 px-12 rounded bg-primary-100/50 dark:bg-primary-100/10'
    };
</script>

<template>
    <ContainerPrimary :class="styleClass.headerContainer">
        <h2 :class="styleClass.title">Products Management</h2>

        <div class="flex gap-4 justify-center md:justify-between items-center w-full md:w-fit">
            <Link :href="route('dashboard')" :class="styleClass.refresh">
            <span>Data Refresh </span>
            <i class="fa-solid fa-rotate group-hover:animate-spin" />
            </Link>
            <p class="form-control px-14 font-semibold text-center">
                {{ currentDate }}
            </p>
        </div>
    </ContainerPrimary>


    <div class="flex justify-between md:items-center flex-col-reverse md:flex-row gap-3 my-6 items-stretch ">
        <PillPrimary label="Add new product" class="text-left" >
            <i class="bi bi-plus-circle"></i>
        </PillPrimary>

        <Search />
    </div>

    <!-- TABLE -->
     <ProductsTable />

     <!-- Paginator -->
      <Paginator />
</template>
