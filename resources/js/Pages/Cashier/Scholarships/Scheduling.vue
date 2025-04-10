<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <form @submit.prevent="submitForm">
                <div class="w-full mx-auto space-y-3">
                    <div class="breadcrumbs text-sm text-gray-400 mb-2">
                        <ul>
                            <li class="hover:text-gray-600">
                                Home
                            </li>
                            <li class="hover:text-gray-600">
                                <span>Scholarships</span>
                            </li>
                            <li class="hover:text-gray-600">
                                <span>{{ scholarship.name }}</span>
                            </li>
                            <li>
                                <span class="text-blue-400 font-semibold">Scheduling Payouts</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1
                                class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                                <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>
                                    Scheduling Payouts
                                </span>
                            </h1>

                            <button
                                class="btn bg-blue-900 text-white dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary"
                                type="submit">
                                <span class="material-symbols-rounded">
                                    send
                                </span>
                                Forward
                            </button>
                        </div>

                        <div class="w-6/12 mx-auto h-full space-y-5 mb-3">
                            <!-- Recipients section for all batches -->
                            <div
                                class="w-full px-5 py-5 bg-[white] rounded-lg shadow-md space-y-5 dark:bg-dsecondary dark:border dark:border-gray-600">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Recipients</h3>

                                <!-- Display all batches -->
                                <div v-for="batch in batches" :key="batch.id" class="mb-5">
                                    <div
                                        class="gap-2 bg-gray-50 w-full h-full border border-gray-300 rounded-lg p-2.5 dark:bg-dsecondary dark:border dark:border-gray-600">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                            Batch {{ batch.batch_no }}
                                        </span>

                                        <!-- Line Divider -->
                                        <div class="w-full h-[1px] bg-gray-300 my-2 dark:bg-gray-600"></div>

                                        <!-- List of scholars for this batch -->
                                        <div class="space-y-1 max-h-40 overflow-y-auto">
                                            <div v-for="disbursement in getDisbursementsForBatch(batch.id)"
                                                :key="disbursement.id" class="flex items-center justify-between">
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                                    {{ disbursement.scholar.email }}
                                                    <!-- Access email directly from scholar -->
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ disbursement.status }}
                                                </span>
                                            </div>


                                            <!-- Message when no scholars are found -->
                                            <div v-if="getDisbursementsForBatch(batch.id).length === 0"
                                                class="text-center text-gray-500 py-2">
                                                No recipients found for this batch
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scheduling section -->
                                <div class="flex flex-col gap-2">
                                    <div class="h-full w-full grid grid-cols-1 gap-5">
                                        <div class="flex flex-row w-full gap-4 items-center">
                                            <!-- Date Picker -->
                                            <div class="relative max-w-sm w-full md:w-1/3">
                                                <label for="default-datepicker"
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select
                                                    date:</label>
                                                <div
                                                    class="absolute inset-y-0 top-6 left-0 flex items-center px-3.5 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input v-model="selected_scheduled" name="end" type="text" datepicker
                                                    id="default-datepicker"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Select date" required>
                                            </div>

                                            <!-- Time Picker -->
                                            <form class="w-full mx-auto">
                                                <label for="time"
                                                    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select
                                                    time:</label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd"
                                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <input v-model="selectedTime" type="time" id="time"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        min="09:00" max="18:00" value="00:00" required>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Reminders textarea -->
                                        <div class="w-full">
                                            <label for="message"
                                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Reminders</label>
                                            <textarea id="message" rows="4"
                                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Write important reminders here"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Sent Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>
            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed, watchEffect, nextTick, watch } from 'vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import { useDateFormat, useNow } from '@vueuse/core'
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
import { initFlowbite } from 'flowbite';
import { Datepicker } from "flowbite";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    scholarship: Object,
    batches: Array,
    disbursements: Array,
    payout: Object,
});

const directives = {
    Tooltip,
    DatePicker
};

const selected_scheduled = ref(""); // Stores the selected start date
const selectedTime = ref(""); // Stores the selected time
const errors = ref({});

// Form data with email field added
const form = ref({
    scheduled_date: '',
    scheduled_time: '',
    reminders: '',
    payout_id: props.payout.id,
});

// Helper function to get disbursements for a specific batch
const getDisbursementsForBatch = (batchId) => {
    return props.disbursements.filter(disbursement => {
        return disbursement.scholar && disbursement.scholar.grantees && disbursement.scholar.grantees.some(grantee => grantee.batch_id === batchId);
    });
};


const formatDateTime = (date) => {
    if (!date) return '';

    const d = new Date(date);
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
};

const resetForm = () => {
    form.value = {
        scheduled_date: '',
        scheduled_time: '',
        reminders: ''
    };
    selected_scheduled.value = '';
    selectedTime.value = '';
};

// Watch for errors from the page props
watch(() => usePage().props.errors, (newErrors) => {
    errors.value = newErrors;
}, { immediate: true, deep: true });

onMounted(() => {
    // Initialize errors from page props if they exist
    if (usePage().props.errors) {
        errors.value = usePage().props.errors;
    }

    initFlowbite(); // Initialize Flowbite first

    // 🎯 Start Date Picker
    const startInput = document.getElementById("default-datepicker");
    if (startInput) {
        startInput.value = selected_scheduled.value; // Keep the previous value
        startInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value); // ✅ Get selected date
            const offset = date.getTimezoneOffset() * 60000; // Get timezone offset in milliseconds
            const localDate = new Date(date.getTime() - offset); // Adjust to local time

            form.value.scheduled_date = localDate.toISOString().split("T")[0]; // Still outputs YYYY-MM-DD correctly
            selected_scheduled.value = event.target.value;
        });
    }

    // 🎯 Time Picker
    const timeInput = document.getElementById("time");
    if (timeInput) {
        timeInput.value = selectedTime.value; // Keep the previous value
        timeInput.addEventListener("change", (event) => {
            const time = event.target.value; // Get selected time
            form.value.scheduled_time = time;
            selectedTime.value = time;
        });
    }
});

// Ensure selected values persist
watch(selected_scheduled, (newVal) => {
    const element = document.getElementById("default-datepicker");
    if (element) element.value = newVal;
});

watch(selectedTime, (newVal) => {
    const element = document.getElementById("time");
    if (element) element.value = newVal;
});

const validateForm = () => {
    errors.value = {};
    let isValid = true;

    if (!form.value.scheduled_date) {
        errors.value.scheduled_date = 'Date is required.';
        isValid = false;
    }

    if (!form.value.scheduled_time) {
        errors.value.scheduled_time = 'Time is required.';
        isValid = false;
    }

    return isValid;
};


// Submit function
const submitForm = async () => {

    // Client-side validation before submitting
    if (!validateForm()) {
        return;
    }

    try {
        // Get reminder text
        form.value.reminders = document.getElementById("message").value;

        const formData = useForm(form.value);

        await formData.post(`/cashier/scholarships/${props.scholarship.id}/notify`, {
            onError: (e) => {
                errors.value = e;
            },
            onSuccess: () => {
                resetForm();
                toastMessage.value = `Payout scheduled successfully for ${form.value.scholar_email}`;
                toastVisible.value = true;
            }
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const toastVisible = ref(false);
const toastMessage = ref("");

watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        console.log("Showing toast with message:", flashMessage);
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            console.log("Hiding toast...");
            toastVisible.value = false;
        }, 3000);
    }
});
</script>

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}

.dark .p-datepicker {
    background-color: #333;
    color: white;
}

.dark .p-inputtext {
    background-color: #444;
    color: white;
}

.dark .p-inputtext:focus {
    background-color: #555;
}
</style>