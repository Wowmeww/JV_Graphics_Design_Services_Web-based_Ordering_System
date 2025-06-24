<script setup>    ;
    import Navbar from '../components/ui/navbar/Navbar.vue';
    import Footer from '../components/ui/footer/Footer.vue';
    import AuthNavbar from '../components/ui/navbar/AuthNavbar.vue';
    import AuthAside from '../components/ui/navbar/AuthAside.vue';
    import { ref } from 'vue';
    import Confirm from '@/components/modal/Confirm.vue';

    const asideExpanded = ref(false);



    function toggleExpand() {
        asideExpanded.value = !asideExpanded.value;
    }

</script>

<template>
    <div v-cloak>

        <AuthNavbar @toggle="toggleExpand" />

        <AuthAside :expanded="asideExpanded" @close="toggleExpand" />

        <div class="p-2 pt-8 sm:px-4 md:p-8 min-h-[98vh]">
            <div class="rounded-lg h-max lg:min-h-[98vh] mt-14 md:px-4">
                <slot />
            </div>
            <Confirm v-if="$page.props.confirm.show" />
        </div>
        <Footer />
    </div>
</template>
