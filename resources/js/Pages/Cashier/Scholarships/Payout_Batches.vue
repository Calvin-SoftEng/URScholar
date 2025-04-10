<template>
    <AuthenticatedLayout>
        <!-- <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] dark:bg-dprimary overflow-auto">
            <div class="w-full mx-auto p-3 rounded-xl text-white">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholarship Batches</span>
                        </li>
                    </ul>
                </div>

                <div
                    class="w-full flex flex-row justify-between dark:bg-dcontainer dark:border dark:border-gray-600 rounded-xl mb-3">
                    <div class="w-full flex justify-between ">
                        <div class="flex flex-col space-y-1">
                            <h1 class="text-4xl font-sora font-extrabold text-[darkblue] text-left dark:text-dtext">
                                <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span>
                            </h1>

                        </div>
                        <button @click="openScholarship"
                            class="text-primary bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 hover:border-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition duration-150 ease-in-out"
                            type="button">
                            Add Scholars
                        </button>


                    </div>
                </div>

                <div class="bg-white">

                    <ScholarList :scholarship="scholarship" :batches="batches"/>
                    
                </div>

            </div>
        </div> -->
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
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

                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>{{
                                scholarship.name }} </span>
                            <span> Payout List</span>
                        </h1>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-gray-200"></div>

                <div class="flex flex-row justify-between items-center">
                    <span>List of Batches </span>
                    <!-- {{ props.selectedSem }} {{ schoolyear.year }} -->

                    <div class="flex flex-row space-x-3 items-center">
                        <Link v-if="!payout_schedule" :href="`/cashier/scholarships/${scholarship.id}/schedule`">
                        <button
                            class="flex items-center gap-2 bg-white border border-blue-600 font-poppins text-primary px-4 py-2 rounded-lg hover:bg-blue-200 transition duration-200">
                            <font-awesome-icon :icon="['fas', 'bullhorn']" class="text-base" />
                            <span class="font-normal">Notify Payouts</span>
                        </button>
                        </Link>
                        <Link v-else>
                        <button disabled
                            class="flex items-center gap-2 bg-gray border border-gray-600 font-poppins text-primary px-4 py-2 rounded-lg hover:bg-gray-200 transition duration-200 'opacity-50 cursor-not-allowed'">
                            <font-awesome-icon :icon="['fas', 'bullhorn']" class="text-base" />
                            <span class="font-normal">Notify Payouts</span>
                        </button>
                        </Link>
                        <button @click="toggleSendBatch"
                            class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                            <span class="font-normal">Forward Completed Payouts</span>
                        </button>
                    </div>
                </div>


                <div v-if="!payout_schedule">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
                        <font-awesome-icon :icon="['fas', 'user-graduate']"
                            class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                        <p class="text-lg text-gray-700 dark:text-gray-300">
                            Need to set a schedule
                        </p>
                    </div>
                </div>
                <div v-else v-for="batch in batches" :key="batch.id"
                    class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
                    <div @click="() => openBatch(batch.id)"
                        class="flex flex-row justify-between items-center cursor-pointer">
                        <span>Batch {{ batch.batch_no }}</span>
                        <div class="grid grid-cols-3 gap-5">
                            <div class="flex flex-col justify-center items-center space-y-1">
                                <span class="text-gray-600 text-sm font-medium">Status</span>
                                <span
                                    class="bg-green-100 text-green-800 border border-green-400 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ batch.not_claimed_count === 0 ? 'Completed' : 'In Progress' }}
                                </span>
                            </div>

                            <div class="flex flex-col justify-center items-center space-y-1">
                                <span class="text-gray-600 text-sm font-medium">Claimed</span>
                                <span class="text-lg font-semibold text-gray-900">{{ batch.claimed_count }}</span>
                            </div>

                            <div class="flex flex-col justify-center items-center space-y-1">
                                <span class="text-gray-600 text-sm font-medium">Not Claimed</span>
                                <span class="text-lg font-semibold text-red-500">{{ batch.not_claimed_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simplified forwarding batch list modal -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Send Payroll
                            </h2>
                        </div>
                    </div>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <div class="py-4 px-8 flex flex-col gap-4 bg-white shadow-md rounded-lg border border-gray-200">
                    <label class="block text-lg font-semibold text-gray-700 dark:text-white">
                        Completed Payout Batches
                    </label>

                    <!-- Loading Indicator -->
                    <div v-if="isLoading" class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                    </div>

                    <!-- Batch List -->
                    <div v-if="ForwardBatchList"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
                        <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <div class="flex items-center gap-3">
                                    <!-- Icon -->
                                    <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                        class="text-blue-600 text-2xl flex-shrink-0" />

                                    <!-- Title and Description -->
                                    <div class="flex flex-col">
                                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                            Send Payroll
                                        </h2>
                                    </div>
                                </div>
                                <button type="button" @click="closeModal"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                class="py-4 px-8 flex flex-col gap-4 bg-white shadow-md rounded-lg border border-gray-200">
                                <label class="block text-lg font-semibold text-gray-700 dark:text-white">
                                    Completed Payout Batches
                                </label>

                                <!-- Loading Indicator -->
                                <div v-if="isLoading" class="flex justify-center items-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                                </div>

                                <!-- Batch List -->
                                <div v-else class="flex flex-col divide-y divide-gray-300">
                                    <div v-for="batch in batches" :key="batch.id"
                                        class="py-3 px-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">Batch {{
                                                batch.batch_no }}</p>
                                            <p class="text-sm text-gray-500">{{ batch.claimed_count }} Claimed,
                                                {{ batch.not_claimed_count }} Not Claimed</p>
                                        </div>
                                        <span
                                            :class="`text-sm font-medium px-3 py-1 rounded-full ${batch.not_claimed_count === 0 ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'}`">
                                            {{ batch.not_claimed_count === 0 ? 'Ready to Send' : 'Incomplete' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Forward Button -->
                                <div class="mt-4">
                                    <button v-if="!isDateMatched && canForward" :disabled="isSubmitting"
                                        @click="forwardPayout"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ isSubmitting ? 'Processing...' : 'Forward' }}
                                    </button>
                                    <button v-else-if="isDateMatched && !canForward" @click="forwardPayout"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Forward (Overdue {{ formatDate(payouts.date_end) }})
                                    </button>
                                    <button v-else v-tooltip.left="dateEndMessage" disabled
                                        class="w-full bg-gray-400 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Forward (Available on {{ formatDate(payouts.date_end) }})
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Forward Button -->
                    <!-- <div class="mt-4">
                        <button :disabled="isSubmitting" @click="forwardPayout"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ isSubmitting ? 'Processing...' : 'Forward' }}
                        </button>
                    </div> -->
                </div>

            </div>
        </div>

        <!-- Simplified forwarding batch list modal -->
        <div v-if="NotifyPayouts"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Set Claim Schedule
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Provide the Date and Time for the claim
                            </span>
                        </div>
                    </div>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <div class="py-4 px-8 flex flex-col gap-3">

                    <div class="flex flex-wrap w-full gap-4 items-center">
                        <!-- Date Picker -->
                        <div id="default-datepicker" class="relative max-w-sm w-full md:w-1/3">
                            <label for="default-datepicker"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select
                                time:</label>
                            <div class="absolute inset-y-0 top-6 left-0 flex items-center px-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input datepicker id="default-datepicker" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date" required>
                        </div>

                        <!-- Time Picker -->
                        <form class="max-w-[10rem] w-full mx-auto">
                            <label for="time"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Select
                                time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
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

                        <!-- message -->
                        <div class="w-full">
                            <label for="message"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Reminders</label>
                            <textarea id="message" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Write important reminders here"></textarea>
                        </div>

                    </div>

                    <!-- <div class="py-4 px-8 flex flex-col gap-3">
                        <label for="batchSelection" class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                            Select a Batch to Forward:
                        </label>


                        <div v-if="isLoading" class="flex justify-center items-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                        </div>


                        <div v-if="!isLoading" class="flex flex-col gap-2">
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
                    </div> -->

                    <!-- Forward Button -->
                    <div class="mt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Set Schedule
                        </button>
                    </div>
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
import { defineProps, ref, watchEffect, onBeforeMount, reactive, watch, onMounted, onUnmounted, computed } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Button } from '@/Components/ui/button'

import ScholarList from '../../../Components/Staff/ScholarsTabs/ScholarList.vue';
import Payout_Batches from '../../../Components/Cashier/Payouts/Payout_Batches_List.vue';
import { initFlowbite } from 'flowbite';
import { Schedule } from '@syncfusion/ej2-vue-schedule';

// components
const components = {
    Button,
};

const props = defineProps({
    scholarship: Object,
    payouts: Object,
    canForward: Object,
    batches: Array,
    grantees: Array,
    payout_schedule: Object,
});



const form = ref({
    scheduled_date: "",
    scheduled_time: "",

});

const ForwardBatchList = ref(false);
const NotifyPayouts = ref(false);
const selectedBatches = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const batchesWithScholars = ref([]);
const addVisible = ref(false);
const List = ref(true);

const loadBatchesData = async () => {
    isLoading.value = true;

    try {
        // Use the batch data directly from props since claimed/not claimed counts are already provided
        setTimeout(() => {
            batchesWithScholars.value = props.batches;

            // Automatically select all batches when data is loaded
            selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];

            isLoading.value = false;
        }, 300);
    } catch (error) {
        console.error('Error loading batch data:', error);
        toastMessage.value = 'Failed to load batch data';
        toastVisible.value = true;
        isLoading.value = false;
    }
};

const selectAllBatches = () => {
    if (selectedBatches.value.includes('all')) {
        // If 'all' is selected, select all batch IDs
        selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];
    } else {
        // If 'all' is unselected, clear all selections
        selectedBatches.value = [];
    }
};

const toggleSendBatch = async () => {
    ForwardBatchList.value = true;

    //Load batches with scholar counts
    await loadBatchesData();
};

const toggleNotify = async () => {
    NotifyPayouts.value = true;
    initFlowbite();

    // Load batches with scholar counts
    // await loadBatchesData();
};

const isDateMatched = computed(() => {
    // Get current date in YYYY-MM-DD format, considering midnight UTC
    const today = new Date();
    const currentDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Compare with payout's date_end (assuming format is YYYY-MM-DD)
    return currentDate >= props.payouts.date_end;
});

const dateEndMessage = computed(() => {
    return `Button will be available on ${formatDate(props.payouts.date_end)}`;
});

const formatDate = (dateString) => {
    // Parse YYYY-MM-DD format
    const [year, month, day] = dateString.split('-');
    const date = new Date(year, month - 1, day); // month is 0-indexed in JavaScript
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

// Submit reason form
const forwardPayout = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('cashier.forward_payout', { scholarshipId: props.scholarship.id }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
};

const closeModal = () => {
    ForwardBatchList.value = false;
    NotifyPayouts.value = false;
};

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


const openBatch = (batchId) => {
    router.visit(`/cashier/scholarships/${props.scholarship.id}/batch/${batchId}`, {
        data: {
            scholarship: props.scholarship.id,
        },
        preserveState: true
    });
};

const selectedSem = ref("");

selectedSem.value = props.selectedSem;

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};




const formData = ref({
    file: null,
    // other form fields...
});

const updateFile = (file) => {
    formData.value.file = file;
};

const selected_scheduled = ref(''); // Stores the selected start date

onMounted(() => {
    initFlowbite(); // Initialize Flowbite globally

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

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
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