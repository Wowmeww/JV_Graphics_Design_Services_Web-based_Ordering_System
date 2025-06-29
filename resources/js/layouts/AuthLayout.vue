<script setup>
import Confirm from '@/components/modal/Confirm.vue';
import { ref } from 'vue';
import Footer from '@/components/ui/footer/Footer.vue';
import AuthAside from '@/components/ui/navbar/AuthAside.vue';
import AuthNavbar from '@/components/ui/navbar/AuthNavbar.vue';

const asideExpanded = ref(false);

function toggleExpand() {
    asideExpanded.value = !asideExpanded.value;
}
</script>

<template>
    <div v-cloak>
        <AuthNavbar @toggle="toggleExpand" />

        <AuthAside :expanded="asideExpanded" @close="toggleExpand" />

        <div class="relative min-h-screen">
            <div class="mt-14 h-max rounded-lg lg:min-h-[98vh]">
                <slot />
            </div>
            <Confirm v-if="$page.props.confirm.show" />
        </div>
        <Footer />
    </div>
</template>
