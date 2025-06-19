import { usePage } from "@inertiajs/vue3";
import AuthLayout from "../layouts/AuthLayout.vue";
import GuestLayout from "../layouts/GuestLayout.vue";

const page = usePage();
const user = page.props

const layout = user ? AuthLayout : GuestLayout;

export default layout;
