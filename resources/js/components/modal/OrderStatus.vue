<script setup>
import { ref, watch } from 'vue';
import Dropdown from '../ui/input/Dropdown.vue';
import TextInputPrimary from '../ui/TextInputPrimary.vue';

const props = defineProps({
    status: String,
    errors: Object,
});
const selected = ref(props.status);
const message = ref(null);

const emit = defineEmits(['close', 'updateStatus']);

function updateStatus() {
    emit('updateStatus', { status: selected.value, message: message.value });
    emit('close');
}

watch(
    () => selected.value,
    function (newStatus) {
        switch (newStatus) {
            case 'pending':
                message.value = 'Your order is now marked as *Pending*. We have received it and will begin processing shortly.';
                break;

            case 'processing':
                message.value = 'Your order is currently being processed. We are preparing your items.';
                break;

            case 'completed':
                message.value = 'Your order has been *Completed*. You can now pick up your order.';
                break;

            case 'cancelled':
                message.value = 'Your order has been *Cancelled*. If you have any questions, please contact support.';
                break;
        }
    },
);
</script>

<template>
    {{ errors }}
    <!-- Order Status Update Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4 dark:bg-black/50">
        <div class="w-full max-w-md scale-95 transform rounded-xl bg-white shadow-xl transition-all duration-300 dark:bg-gray-800">
            <!-- animate-animated animate__bounceIn -->

            <div class="p-6">
                <!-- Modal Header -->
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Update Order Status</h3>
                    <button @click="emit('close')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4">
                    <div>
                        <Dropdown
                            label="Status"
                            placeholder="Update order status"
                            :options="['pending', 'processing', 'completed', 'cancelled']"
                            :value="selected"
                            @select="(option) => (selected = option)"
                        />
                    </div>
                    <div>
                        <TextInputPrimary
                            v-model="message"
                            label="Message"
                            type="textarea"
                            :required="false"
                            placeholder="Message for customer(optional)"
                        />
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="emit('close')"
                        class="btn border border-gray-300 text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button :disabled="status == selected" @click="updateStatus" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
