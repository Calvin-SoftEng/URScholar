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
                            <li class="hover:text-gray-600">
                                <span>Batch 1</span>
                            </li>
                            <li>
                                <span class="text-blue-400 font-semibold">Sending Access</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h1
                                class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                                <button @click="goBack"
                                class="mr-2 font-poppins font-extrabold text-blue-400 hover:text-blue-500">
                                < </button><span>
                                    Student Email Notifications</span>
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
                                <div
                                    class="flex flex-wrap gap-2 bg-gray-50 w-full h-full border border-gray-300 rounded-lg p-2.5 dark:bg-dsecondary dark:border dark:border-gray-600">
                                    <span v-for="(scholar, index) in scholars.filter(s => s.email)" :key="scholar.id"
                                        class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-dprimary dark:text-blue-400 border border-blue-400 dark:border-gray-400">
                                        {{ scholar.email }}
                                    </span>
                                </div>


                                <div class="flex flex-col gap-2">
                                    <div class="h-full w-full grid grid-cols-1 gap-5">
                                        <!-- <div class="flex flex-col space-y-2"> -->
                                        <div class="relative">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                Message
                                            </h3>
                                            <div
                                                class="block p-2.5 w-full text-base text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <p>
                                                    This email message is for the distribution of accounts for students,
                                                    reminding them of their scholarship opportunities. We encourage all
                                                    students to check their accounts for updates, and we are happy to
                                                    provide further assistance with the application process if needed.
                                                </p>
                                            </div>

                                        </div>
                                        <!-- <div class="w-full">
                                                <div class="mb-3">
                                                    <h3 class="font-semibold text-gray-900 dark:text-white">Add Messages
                                                    </h3>
                                                    <textarea v-model="form.content" id="content" rows="15"
                                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Write additional informations here..."></textarea>
                                                    <InputError v-if="errors.content" :message="errors.content"
                                                        class="mt-1" />
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="w-full flex flex-col space-y-7">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Set Submission
                                                    Timeline</h3>
                                                <div id="date-range-picker" date-rangepicker
                                                    class="flex items-center gap-3 w-full">
                                                    <!-- Application Start Date -->
                                                    <div class="flex flex-col w-full">
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                                </svg>
                                                            </div>
                                                            <input :value="selectedStart"
                                                                @input="selectedStart = $event.target.value"
                                                                id="datepicker-range-start" name="start" type="text"
                                                                autocomplete="off"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                placeholder="Submission Start Date">
                                                        </div>
                                                        <InputError v-if="errors.application"
                                                            :message="errors.application" class="mt-1" />
                                                    </div>

                                                    <span class="text-gray-500">to</span>

                                                    <!-- Application Deadline -->
                                                    <div class="flex flex-col w-full">
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                    aria-hidden="true"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                                </svg>
                                                            </div>
                                                            <input :value="selectedEnd"
                                                                @input="selectedEnd = $event.target.value"
                                                                id="datepicker-range-end" name="end" type="text"
                                                                autocomplete="off"
                                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                placeholder="Submission Deadline">
                                                        </div>
                                                        <InputError v-if="errors.deadline" :message="errors.deadline"
                                                            class="mt-1" />
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" @click="toggleRequirements"
                                                class="text-blue-600 hover:text-blue-800 text-start transition-all max-w-fit"
                                                v-tooltip.right="'Set Requirements'">
                                                {{ requestRequirements ? 'Cancel Requirements' : 'Request Requirements'
                                                }}
                                            </button>


                                            <div v-show="requestRequirements" class="w-full mb-5">
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Requirements
                                                </h3>
                                                <InputError v-if="errors.requirements" :message="errors.requirements"
                                                    class="mt-1" />

                                                <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                                    <div class="flex items-center mb-4 w-full">
                                                        <form @submit.prevent="addItem"
                                                            class="flex items-center w-full">
                                                            <input v-model="newItem" type="text"
                                                                placeholder="Enter an item"
                                                                class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                                            <button type="submit"
                                                                class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                                Add
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <form @submit.prevent="removeItem">
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div v-for="(item, index) in items" :key="index"
                                                                class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                                                <span>{{ item }}</span>
                                                                <button @click="removeItem(index)"
                                                                    class="flex items-center text-red-500 hover:text-red-700">
                                                                    <span
                                                                        class="material-symbols-rounded text-red-600">delete</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </ul>
                                            </div>

                                            <button type="button" @click="toggletemplates"
                                                class="text-blue-600 hover:text-blue-800 text-start transition-all max-w-fit"
                                                v-tooltip.right="'Upload Application Files Templates'">
                                                {{ requirementemplates ? 'Cancel Upload' : 'Upload Templates' }}
                                            </button>

                                            <!-- Update the file input section to properly handle file uploads -->
                                            <div v-show="requirementemplates">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                                    for="multiple_files">
                                                    Upload multiple files
                                                </label>
                                                <input ref="fileInput"
                                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                    dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                    id="multiple_files" type="file" multiple @change="handleFileChange"
                                                    name="templates[]" />

                                                <!-- Preview Selected Files -->
                                                <div v-if="selectedFiles.length"
                                                    class="mt-3 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                                        Selected Files:</p>
                                                    <ul class="space-y-2">
                                                        <li v-for="(file, index) in selectedFiles" :key="index"
                                                            class="flex items-center justify-between text-sm bg-white dark:bg-gray-700 p-2 rounded-lg">
                                                            <span
                                                                class="text-gray-700 dark:text-gray-300 truncate max-w-xs">{{
                                                                    file.name }}</span>
                                                            <button type="button" @click="removeFile(index)"
                                                                class="text-red-500 hover:text-red-700 text-xs">
                                                                ✖ Remove
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

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
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed, watchEffect, nextTick, watch } from 'vue';
import { Head, useForm, Link, usePage, router } from '@inertiajs/vue3';
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
    semester: Object,
    school_year: Object,
});

const directives = {
    Tooltip,
    DatePicker
};

const selectedStart = ref(""); // Stores the selected start date
const selectedEnd = ref("");   // Stores the selected end date
const requestRequirements = ref(false);
const requirementemplates = ref(false);
const formattedStart = ref('');
const formattedEnd = ref('');
const errors = ref({});

const form = ref({
    subject: "ninin",
    content: "ninnin",
    requirements: [],
    application: '',
    deadline: '',
    semester: props.semester,
    school_year: props.school_year,
});

const goBack = () => {
    window.history.back();
};


const toggleRequirements = () => {
    requestRequirements.value = !requestRequirements.value;
};

const toggletemplates = () => {
    requirementemplates.value = !requirementemplates.value;
};


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

const selectedFiles = ref([]);
const fileInput = ref(null);

const handleFileChange = (event) => {
    selectedFiles.value = Array.from(event.target.files);
};

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1);

    // Reset the file input by clearing and re-adding remaining files
    const dataTransfer = new DataTransfer();
    selectedFiles.value.forEach(file => dataTransfer.items.add(file));

    if (fileInput.value) {
        fileInput.value.files = dataTransfer.files;
    }
};

if (fileInput.value) {
    fileInput.value.files = dataTransfer.files;
}


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

    // 🎯 Start Date Picker
    const startInput = document.getElementById("datepicker-range-start");
    if (startInput) {
        startInput.value = selectedStart.value; // Keep the previous value
        startInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value); // ✅ Get selected date
            const offset = date.getTimezoneOffset() * 60000; // Get timezone offset in milliseconds
            const localDate = new Date(date.getTime() - offset); // Adjust to local time

            form.value.application = localDate.toISOString().split("T")[0]; // Still outputs YYYY-MM-DD correctly
            // console.log("Application:", form.value.application);
            selectedStart.value = event.target.value;
        });
    }

    // 🎯 End Date Picker
    const endInput = document.getElementById("datepicker-range-end");
    if (endInput) {
        endInput.value = selectedEnd.value; // Keep the previous value
        endInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value); // ✅ Get selected date
            const offset = date.getTimezoneOffset() * 60000; // Get timezone offset in milliseconds
            const localDate = new Date(date.getTime() - offset); // Adjust to local time

            form.value.deadline = localDate.toISOString().split("T")[0]; // Still outputs YYYY-MM-DD correctly
            selectedEnd.value = event.target.value;
        });
    }
});

// Ensure selected values persist
watch(selectedStart, (newVal) => {
    document.getElementById("datepicker-range-start").value = newVal;
});

watch(selectedEnd, (newVal) => {
    document.getElementById("datepicker-range-end").value = newVal;
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
        // Create FormData object to handle file uploads
        const formData = new FormData();

        // Add regular form fields
        formData.append('subject', form.value.subject);
        formData.append('content', form.value.content);
        formData.append('application', form.value.application);
        formData.append('deadline', form.value.deadline);
        formData.append('semester', form.value.semester);
        formData.append('school_year', form.value.school_year);

        // Add requirements as JSON to preserve array structure
        formData.append('requirements', JSON.stringify(form.value.requirements));

        // Add files
        if (selectedFiles.value.length > 0) {
            selectedFiles.value.forEach((file, index) => {
                formData.append(`templates[]`, file);
            });
        }

        // Use Inertia's post method with FormData
        await router.post(`/scholarships/${props.scholarship.id}/send-access/send`, formData, {
            onError: (errors) => {
                console.error('Form submission errors:', errors);
                errors.value = errors;
            },
            onSuccess: () => {
                resetForm();
                selectedFiles.value = [];
                if (fileInput.value) {
                    fileInput.value.value = '';
                }
            },
            forceFormData: true
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