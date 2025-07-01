<script setup>
    import Confirm from '@/components/modal/Confirm.vue';
    import { computed, ref } from 'vue';
    import Footer from '@/components/ui/footer/Footer.vue';
    import AuthAside from '@/components/ui/navbar/AuthAside.vue';
    import AuthNavbar from '@/components/ui/navbar/AuthNavbar.vue';
    import Status from '../components/alert/Status.vue';
    import { usePage } from '@inertiajs/vue3';
    import ShopAside from '../components/ui/navbar/ShopAside.vue';

    const page = usePage();

    const status = computed(() => page.props.status);
    const isShopAsideOpen = computed(() => page.props.shopAside.isOpen)

    const asideExpanded = ref(false);

    function toggleExpand() {
        asideExpanded.value = !asideExpanded.value;
    }

    function toggleShopAside() {
        page.props.shopAside.isOpen = !page.props.shopAside.isOpen;
    }


</script>

<template>
    <div v-cloak>
        <AuthNavbar @toggle="toggleExpand" @openCart="toggleShopAside" />

        <AuthAside :expanded="asideExpanded" @close="toggleExpand" />

        <ShopAside :expanded="isShopAsideOpen" @close="toggleShopAside" />

        <div class="relative min-h-screen">
            <div class="mt-14 h-max rounded-lg lg:min-h-[98vh]">
                <slot />
            </div>
            <Confirm v-if="$page.props.confirm.show" />

            <Status :status="status" v-if="status" />
            <!-- <Status :status="{ statu: 'success', message: 'test', type: 'success' }" /> -->
        </div>
        <Footer />
    </div>
</template>
