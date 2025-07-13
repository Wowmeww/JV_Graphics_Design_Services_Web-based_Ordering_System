<script setup>
    import { ref } from 'vue';
    import Dropdown from '../ui/input/Dropdown.vue';
    import TextInputPrimary from '../ui/TextInputPrimary.vue';

    const props = defineProps({
        status: String,
        errors: Object
    });
    const selected = ref(props.status);
    const message = ref(null);

    const emit = defineEmits(['close', 'updateStatus']);

    function updateStatus() {
        emit('updateStatus', { status: selected.value, message: message.value });
        emit('close');
    }
</script>

<template>
    {{ errors }}
    <!-- Order Status Update Modal -->
    <div class="fixed inset-0 bg-black/30 dark:bg-black/50 flex items-center justify-center p-4 z-50 ">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full transform transition-all duration-300 scale-95 ">
            <!-- animate-animated animate__bounceIn -->

            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Update Order Status
                    </h3>
                    <button @click="emit('close')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4">
                    <div>
                        <Dropdown label="Status" placeholder="Update order status"
                            :options="['pending', 'processing', 'completed', 'cancelled']" :value="selected"
                            @select="option => selected = option" />
                    </div>
                    <div>
                        <TextInputPrimary v-model="message" label="Message" type="textarea" :required="false"
                            placeholder="Message for customer(optional)" />
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6">
                    <button @click="emit('close')"
                        class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all btn">
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