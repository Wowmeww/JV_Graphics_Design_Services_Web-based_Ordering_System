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

    const user = page.props.user;

    const status = computed(() => page.props.status);
    const isShopAsideOpen = computed(() => page.props.shopAside.isOpen)

    const asideExpanded = ref(false);

    function toggleExpand() {
        asideExpanded.value = !asideExpanded.value;
    }

    function toggleShopAside() {
        page.props.shopAside.isOpen = !page.props.shopAside.isOpen;
    }
    function openCart() {
        page.props.shopAside.isOpen = true;
        page.props.shopAside.isCartOpen = true;
        page.props.shopAside.isWishlistOpen = false;
    }
    function openWishlist() {
        page.props.shopAside.isOpen = true;
        page.props.shopAside.isCartOpen = false;
        page.props.shopAside.isWishlistOpen = true;
    }

</script>

<template>
    <div v-cloak class="overflow-y-scroll">
        <AuthNavbar @toggle="toggleExpand" @openCart="openCart" @openWishlist="openWishlist" />

        <AuthAside :expanded="asideExpanded" @close="toggleExpand" />

        <ShopAside v-if="!user?.is_admin" :expanded="isShopAsideOpen" @close="toggleShopAside" />

        <div class="relative">
            <div class="mt-14 rounded-lg min-h-screen">
                <slot />
            </div>
            <Confirm v-if="$page.props.confirm.show" />

            <Status :status="status" v-if="status" />
            <!-- <Status :status="{ statu: 'success', message: 'test', type: 'success' }" /> -->
        </div>
        <Footer />
    </div>
</template>
