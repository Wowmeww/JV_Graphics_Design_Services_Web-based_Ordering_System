// resolveLayout.js
import AuthLayout from "../layouts/AuthLayout.vue";
import GuestLayout from "../layouts/GuestLayout.vue";

export default function resolveLayout(page) {
    return page.props?.auth?.user ? AuthLayout : GuestLayout;
}
