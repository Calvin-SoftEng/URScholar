<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Pending Payouts</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        
                        <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>Isko Name</span> <span>Payout List</span>
                        </h1>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-5">
                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Scholarship Batches</p>
                        </div>
                        <div class="w-full flex flex-row justify-between space-x-3 items-end">
                            <p class="text-4xl font-semibold font-kanit">55</p>
                            <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new Batch</button>
                        </div>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Total Verified Scholars</p>
                        </div>
                        <div class="w-full flex flex-row justify-between space-x-3 items-end">
                            <p class="text-4xl font-semibold font-kanit">55</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Unverified Scholars</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">1</p>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Submitted Requirements</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">2</p>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Completed Scholars</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">2</p>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-gray-200"></div>

                <div class="flex flex-row justify-between items-center">
                    <span>List of Batches</span>

                    <button @click="toggleSendBatch" class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                        <span class="font-normal">Forward Completed Scholars</span>
                    </button>
                </div>
                

                <Link :href="(route('cashier.payouts'))">
                    <div
                    class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue mt-4">
                    <div class="flex flex-row justify-between items-center cursor-pointer">
                        <span>Batch 1</span>
                        <div class="grid grid-cols-3 gap-5 items-center">
                            <div class="flex flex-col justify-center items-center">
                                <span>Status</span>
                                <span class="bg-green-100 text-green-800 border border-green-400 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <span>Claimed</span>
                                <span>200</span>
                            </div>
                            <div class="flex flex-col justify-center items-center">
                                <span>Assigned</span>
                                <span>200</span>
                            </div>
                        </div>
                    </div>
                    </div>
                </Link>

                
            </div>
        </div>

        <!-- forwarding batch list -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                        Forwarding Batch List
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- body -->
                <!-- body -->
                <div class="py-4 px-8 flex flex-col gap-3">
                    <label for="batchSelection" class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                        Select a Batch to Forward:
                    </label>

                    <!-- Loading indicator -->
                    <div class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                    </div>

                    <!-- Checkbox List -->
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="all" v-model="selectedBatches" @change="selectAllBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Send All Batch List</span>
                        </label>

                        <label v-for="batch in batchesWithScholars" :key="batch.id" class="flex items-center space-x-2">
                            <input type="checkbox" :value="batch.id" v-model="selectedBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Batch {{ batch.batch_no }}</span>
                            <span class="text-sm text-gray-500">({{ batch.scholar_count }} scholars)</span>
                        </label>
                    </div>

                    <!-- Forward Button -->
                    <!-- <div class="mt-4">
                        <button :disabled="isSubmitting || selectedBatches.length === 0" @click="forwardBatches"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ isSubmitting ? 'Processing...' : 'Forward' }}
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
        

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

import Adding from '../../../Components/Staff/ScholarsTabs/Adding.vue';

import ScholarList from '../../../Components/Staff/ScholarsTabs/ScholarList.vue';
import Payout_Batches from '../../../Components/Cashier/Payouts/Payout_Batches.vue';

// components

const components = {
    Button,
    Papa,
};

const addVisible = ref(false);
const List = ref(true);

const toggleAdd = () => {
    addVisible.value = true;
    List.value = false;
};

const toggleList = () => {
    List.value = true;
    addVisible.value = false;
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
    schoolyear: Object,
    selectedSem: Object,
    batches: Array,
});

const selectedSem = ref("");

selectedSem.value = props.selectedSem;

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};


const ForwardBatchList = ref(false);

const toggleSendBatch = () => {
  ForwardBatchList.value = !ForwardBatchList.value;
}

const closeModal = () => {
    ForwardBatchList.value = false;
    resetForm();
};




const formData = ref({
    file: null,
    // other form fields...
});

const updateFile = (file) => {
    formData.value.file = file;
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