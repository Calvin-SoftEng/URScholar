<template>
    <AuthenticatedLayout>
        <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] dark:bg-dprimary overflow-x-auto">
            <div class="w-full mx-auto p-3 rounded-xl text-white overflow-x-auto">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                                Home
                        </li>
                        <li class="hover:text-gray-600">
                                <span>Scholarships</span>
                        </li>
                        <li class="hover:text-gray-600">
                                <!-- <span>{{ scholarship.name }} Dashboard</span> -->
                        </li>
                        <li>
                                <span class="text-blue-400 font-semibold">Submitted Documents</span>
                        </li>
                    </ul>
                </div>

                <div class="w-full text-black">
                    <div class="px-20">
                        <div class="flex flex-col items-end justify-end text-black mx-20">
                            <span class="material-symbols-rounded text-gray-600 dark:text-dtext">
                                cancel
                            </span>
                            <h1 class="text-gray-600 dark:text-dtext">ESC</h1>
                        </div>
                        <div class="w-full flex flex-row gap-10 justify-center">
                            <div class="rounded-full p-20 border">

                            </div>
                            <div class="flex flex-col space-y-3">
                                <span class="uppercase font-sora text-3xl text-primary font-extrabold dark:text-dtext">
                                    Juanito Abrakadabra</span>
                                <div class="gap-1 flex flex-wrap w-9/12 font-sora dark:text-dtext">
                                    <span class="px-5 py-1 border rounded-full">Active</span>
                                    <span class="px-5 py-1 border rounded-full">URS Ngobinon</span>
                                    <span class="px-5 py-1 border rounded-full">Scholar-ID</span>
                                    <span class="px-5 py-1 border rounded-full">Course</span>
                                    <span class="px-5 py-1 border rounded-full">Scholarship</span>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <div class="w-full h-full bg-white ">
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-t-lg">
                                    <thead class="text-xs text-dtext uppercase bg-primary dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Requirements
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                File Submitted
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Date Submitted
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                <span class="sr-only">Expand</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white dark:bg-gray-800">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                Scholarship Application
                                            </th>
                                            <td class="px-6 py-4">
                                                Form.pdf
                                            </td>
                                            <td class="px-6 py-4">
                                                Jan 11, 2022
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="bg-purple-100 text-purple-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-purple-900 dark:text-purple-300">Approved</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" @click="toggleCheck" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- creating a sponsor -->
                        <div v-if="Checking" class="fixed inset-0 flex flex-col bg-black bg-opacity-50 transition-opacity-ease-in duration-300 dark:bg-primary dark:bg-opacity-50 z-50">
                            <!-- Header with close button -->
                            <div class="flex justify-end bg-white">
                                <button type="button" @click="closeModal"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-10 h-10 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Document Viewer -->
                            <div class="flex-grow flex items-center justify-center pt-5 overflow-auto">
                                <div class="w-full h-full max-w-6xl bg-white dark:bg-gray-900 rounded-lg shadow-xl p-4 overflow-auto">
                                    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-4">Viewing Document</h2>
                                    <iframe :src="documentUrl" class="w-full h-[80vh] border-0"></iframe>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, onBeforeMount, reactive } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';

import ScholarsTab from '../../../Components/Admin/ScholarsTabs/ScholarsTab.vue';
import ScholarReqs from '../../../Components/Admin/ScholarsTabs/ScholarReqs.vue';

import SlidingAddScholars from '../../../Components/Admin/ScholarsTabs/SlidingAddScholars.vue';
import Test from '@/Components/Admin/ScholarsTabs/ScholarsTab 2.vue';

// components

const components = {
    DataTable,
    Column,
    Button,
    FileUpload, 
    SlidingAddScholars,
};

const Checking = ref(false);

const toggleCheck = () => {
    Checking.value = !Checking.value;
    if (Checking.value) {
        resetForm();
    }
};

const closeModal = () => {
    Checking.value = false;
    resetForm();
};

// Reactive variables to track which tab is open
const activeTab = ref("scholars"); // Default to Scholars

// Functions to toggle the active tab
const toggleScholars = () => {
    activeTab.value = "scholars";
};

const toggleReqs = () => {
    activeTab.value = "requirements";
};

const toggleMonitoring = () => {
    activeTab.value = "monitoring";
};

const props = defineProps({
    scholarship: Object,
    scholars: Array,
    requirements: Object,
    submitRequirements: Array,
});

const formData = ref({
  file: null,
  // other form fields...
});

const updateFile = (file) => {
  formData.value.file = file;
};

</script>

<style>
/* override the prime vue componentss */

.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

/* Fade transition for backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>