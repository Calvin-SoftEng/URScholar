<template>
    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
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

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>Pending Scholars</span>
                        </h1>

                        <button
                            class="btn bg-blue-900 text-white dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary"
                            type="submit"
                            :disabled="isLoading">
                            <span v-if="!isLoading" class="material-symbols-rounded">
                                send
                            </span>
                            <svg v-else class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isLoading ? 'Processing...' : 'Forward' }}
                        </button>
                    </div>

                    <div class="w-full h-full space-y-5 mb-3">
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

                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                Subject</h3>
                            <textarea v-model="form.subject" id="message" rows="2"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Write Title to the message"></textarea>

                            <div class="flex flex-col gap-2">
                                <div class="h-full w-full flex flex-row gap-3">
                                    <div class="w-full">
                                        <div class="mb-3">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Add Messages</h3>
                                            <textarea v-model="form.content" id="message" rows="15"
                                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Write additional informations here..."></textarea>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col space-y-5">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Set Submission
                                                Timeline</h3>
                                            <div class="flex flex-row gap-3 w-full">
                                                <div class="relative w-full">
                                                    <DatePicker class="w-full dark:bg-dsecondary dark:text-white"
                                                        v-model="selectedStart" @update:model-value="handleDateStart"
                                                        placeholder="Submission Start" />
                                                </div>
                                                <div class="relative w-full">
                                                    <DatePicker class="w-full dark:bg-dsecondary dark:text-white"
                                                        v-model="selectedEnd" @update:model-value="handleDateEnd"
                                                        placeholder="Submission Deadline" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Requirements</h3>
                                            <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                                <div class="flex items-center mb-4 w-full">
                                                    <form @submit.prevent="addItem" class="flex items-center w-full">
                                                        <input v-model="newItem" type="text" placeholder="Enter an item"
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
                                                                <span class="material-symbols-rounded text-red-600">
                                                                    delete
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Loading Modal -->
        <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full text-center dark:bg-dsecondary">
                <div class="flex justify-center mb-4">
                    <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 dark:text-white">Processing Your Request</h3>
                <p class="text-gray-600 dark:text-gray-300">Please wait while we send notifications to scholars...</p>
            </div>
        </div>
        
        <!-- Success Modal -->
        <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full text-center dark:bg-dsecondary">
                <div class="flex justify-center mb-4">
                    <span class="material-symbols-rounded text-green-500 text-6xl">
                        check_circle
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 dark:text-white">Success!</h3>
                <p class="text-gray-600 mb-6 dark:text-gray-300">{{ successMessage }}</p>
                <button @click="closeSuccessModal" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    Continue
                </button>
            </div>
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
import { ref, onMounted, computed, watchEffect } from 'vue';
import { Head, useForm, Link, usePage, router } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import { useDateFormat, useNow } from '@vueuse/core'
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

const props = defineProps({
    scholarship: Object,
    scholars: Array
});

const directives = {
    Tooltip,
    DatePicker
};

const selectedStart = ref(null);
const selectedEnd = ref(null);
const formattedStart = ref('');
const formattedEnd = ref('');

const form = ref({
    subject: '',
    content: '',
    requirements: [],
    application: '',
    deadline: '',
});

// Loading and success modal states
const isLoading = ref(false);
const showSuccessModal = ref(false);
const successMessage = ref('');

const formatDateTime = (date) => {
    if (!date) return '';

    const d = new Date(date);
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
};

const handleDateStart = () => {
    formattedStart.value = formatDateTime(selectedStart.value);
    form.value.application = formattedStart.value;
};

const handleDateEnd = () => {
    formattedEnd.value = formatDateTime(selectedEnd.value);
    form.value.deadline = formattedEnd.value;
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
    selectedStart.value = null;
    selectedEnd.value = null;
    formattedStart.value = '';
    formattedEnd.value = '';
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    router.visit(`/scholarships/${props.scholarship.id}/requirements`);
};

const submitForm = async () => {
    if (items.value.length === 0) {
        alert('Please add at least one requirement item.');
        return;
    }
    
    if (!form.value.application || !form.value.deadline) {
        alert('Please set both submission start and deadline dates.');
        return;
    }
    
    isLoading.value = true;
    
    try {
        const response = await useForm(form.value).post(`/scholarships/${props.scholarship.id}/send-access/send`);
        
        // Wait a bit to show the loading state (simulating processing time)
        setTimeout(() => {
            isLoading.value = false;
            
            // Show success modal
            successMessage.value = 'Messages have been successfully sent to all scholars.';
            showSuccessModal.value = true;
        }, 2000);
        
        resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
        isLoading.value = false;
        alert('An error occurred while processing your request. Please try again.');
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