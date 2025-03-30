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
                                <!-- <span>{{ scholarship.name }}</span> -->
                            </li>
                            <li class="hover:text-gray-600">
                                <span>Batch 1</span>
                            </li>
                            <li>
                                <span class="text-blue-400 font-semibold">Sending Access</span>
                            </li>
                        </ul>
                    </div>

                    <div >
                        <div class="flex justify-between items-center mb-4">
                            <h1
                                class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                                <span
                                    class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>
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
                            <!-- partnership content -->
                            <div
                                class="w-full h-[30%] px-5 py-5 bg-[white] rounded-lg shadow-md space-y-5 dark:bg-dsecondary dark:border dark:border-gray-600">
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Recipients</h3>
                                    <div class="gap-2 bg-gray-50 w-full h-full border border-gray-300 rounded-lg p-2.5 dark:bg-dsecondary dark:border dark:border-gray-600">
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                            Batch No
                                        </span>
                                        
                                        <!-- Line Divider -->
                                        <div class="w-full h-[1px] bg-gray-300 my-2 dark:bg-gray-600"></div>

                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                            name 2
                                        </span>

                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                            name 1
                                        </span>
                                    </div>


                                
                                <div class="flex flex-col gap-2">
                                    <div class="h-full w-full grid grid-cols-1 gap-5">
                                        <!-- <div class="flex flex-col space-y-2"> -->
                                            <div class="flex flex-row w-full gap-4 items-center">
                                                <!-- Date Picker -->
                                                
                                                <div id="default-datepicker" class="relative max-w-sm w-full md:w-1/3">
                                                    <label for="default-datepicker" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select time:</label>
                                                    <div class="absolute inset-y-0 top-6 left-0 flex items-center px-3.5 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                        </svg>
                                                    </div>
                                                    <input datepicker id="default-datepicker" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date" required>
                                                </div>

                                                <!-- Time Picker -->
                                                <form class="w-full mx-auto">
                                                    <label for="time" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select time:</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                        <input v-model="selectedTime" type="time" id="time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="09:00" max="18:00" value="00:00" required>
                                                    </div>
                                                </form>

                                            </div>
                                            <!-- message -->
                                            <div class="w-full">
                                                <label for="message" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Reminders</label>
                                                <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write important reminders here"></textarea>
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
    scholars: Array,
    requirements: Array,
    errors: Object,
});

const directives = {
    Tooltip,
    DatePicker
};

const selectedStart = ref(""); // Stores the selected start date
const selectedEnd = ref("");   // Stores the selected end date
const formattedStart = ref('');
const formattedEnd = ref('');
const errors = ref({});

const form = ref({
    subject: "ninin",
    content: "ninnin",
    requirements: [],
    application: '',
    deadline: '',
    scheduled_date: "",
    scheduled_time: "",
});



// Computed property
const hasRequirements = computed(() => {
    return props.requirements && props.requirements.length > 0;
});

const formatDateTime = (date) => {
    if (!date) return '';

    const d = new Date(date);
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
};

const restoreScrollPosition = () => {
    window.scrollTo(0, scrollPosition.value);
};

// dynamic requirements
const newItem = ref('');
const items = ref([]);

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push(newItem.value.trim());
        form.value.requirements = items.value;
        newItem.value = '';
    }
};

const removeItem = (index) => {
    items.value = items.value.filter((_, i) => i !== index);
    form.value.requirements = items.value;
};


const resetForm = () => {
    form.value = {
        subject: '',
        content: '',
        requirements: [],
        application: '',
        deadline: '',
    };
    items.value = [];
    selectedStart.value = "";
    selectedEnd.value = "";
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

    fetchEmailPreview();

    watch(NotifyPayouts, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                initFlowbite(); // Initialize Flowbite when modal is accessed
                
                const startInput = document.getElementById("default-datepicker");
                if (startInput) {
                    startInput.value = selected_scheduled.value; // Keep the previous value
                    startInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.scheduled_date = date.toISOString().split("T")[0]; 
                        console.log("Application:", form.value.scheduled_date);
                        selected_scheduled.value = event.target.value; 
                    });
                } else {
                    console.warn("Start datepicker not found.");
                }
            }, 200); // Small delay to ensure modal is in the DOM
        }
    });
});


const validateForm = () => {
    errors.value = {};
    let isValid = true;

    if (!form.value.subject) {
        errors.value.subject = 'This field is required.';
        isValid = false;
    }

    if (!form.value.content) {
        errors.value.content = 'This field is required.';
        isValid = false;
    }

    if (!form.value.requirements || form.value.requirements.length === 0) {
        errors.value.requirements = 'At least one requirement is required.';
        isValid = false;
    }

    if (!form.value.application) {
        errors.value.application = 'Start date is required.';
        isValid = false;
    }

    if (!form.value.deadline) {
        errors.value.deadline = 'Deadline is required.';
        isValid = false;
    }

    return isValid;
};

const fetchEmailPreview = async () => {
    try {
        const response = await fetch("/scholarships/1/send-access", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                scholar: {
                    first_name: "John",
                    email: "john.doe@example.com"
                },
                password: "12345678",
                deadline: "March 31, 2025"
            })
        });

        if (!response.ok) throw new Error("Failed to fetch email preview");

        const data = await response.json();
        form.value.subject = data.subject;
        form.value.content = data.content;
    } catch (error) {
        console.error("Error fetching email preview:", error);
    }
};


const submitForm = async () => {
    // Client-side validation before submitting
    if (!validateForm()) {
        return;
    }

    try {
        const formData = useForm(form.value);
        await formData.post(`/scholarships/${props.scholarship.id}/send-access/send`, {
            onError: (e) => {
                errors.value = e;
            },
            onSuccess: () => {
                resetForm();
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