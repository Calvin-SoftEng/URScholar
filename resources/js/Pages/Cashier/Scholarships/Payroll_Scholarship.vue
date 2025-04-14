<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">{{ scholarship.name }}</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <button @click="goBack"
                                class="mr-2 font-poppins font-extrabold text-blue-400 hover:text-blue-500">
                                < </button>
                                    <span>{{ scholarship.name }}</span>
                                    <span>{{ scholarship.scholarshipType }}</span>
                        </h1>
                        <span class="text-xl">SY {{ schoolyear.year }} - {{ selectedSem }} Semester</span>
                    </div>
                </div>

                <div>
                    <div class="w-full mt-5 rounded-xl space-y-5">
                        <!-- Stats Section -->
                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="grid grid-cols-5">
                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'receipt']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Payouts</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <div class="w-full flex flex-row justify-between items-end">
                                        <p class="text-4xl font-semibold font-kanit">{{ totalPayouts }}</p>
                                        <button v-if="forwardableBatchesCount > 0"
                                            class="h-5 px-3 py-1 bg-blue-400 text-white rounded-full text-sm inline-flex items-center justify-center">
                                            {{ forwardableBatchesCount }} forwardable batches
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Completed Payouts</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">{{ completedPayoutsCount }}</p>
                            </div>
                        </div>

                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <!-- Payouts List Section -->
                        <div class="flex flex-row justify-between items-center">
                            <!-- Dynamic Title -->
                            <h2 class="text-lg font-semibold text-gray-800 mt-4">
                                List of Payouts
                            </h2>

                            <div class="flex flex-row space-x-3 items-center">
                                <!-- Campus Filter -->
                                <span class="font-poppins text-sm">Filter Campus</span>
                                <select v-model="selectedCampus"
                                    class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="">All Campuses</option>
                                    <option v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                        {{ campus.name }}
                                    </option>
                                </select>

                                <!-- Forward to Cashier -->
                                <div v-if="hasForwardableBatches">
                                    <button @click="toggleSendBatch"
                                        class="flex items-center gap-2 bg-green-500 font-poppins text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward to <span
                                                class="font-semibold">Cashier</span></span>
                                    </button>
                                </div>
                                <div v-else>
                                    <button v-tooltip.left="'All batches must be inactive and complete'" disabled
                                        class="flex items-center gap-2 dark:text-dtext bg-blue-100 dark:bg-blue-800 
                                                border border-blue-300 dark:border-blue-500 hover:bg-blue-200 px-4 py-2 rounded-lg transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward to Cashier</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Payouts List by Campus -->
                        <div>
                            <!-- First show current user's campus payouts -->
                            <div v-if="filteredPayoutsByCurrentUserCampus.length > 0" class="mb-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">
                                    {{ currentUserCampus.name }} <span class="text-sm font-medium text-blue-500">(Your
                                        Campus)</span>
                                </h3>

                                <!-- Display payouts for current user's campus -->
                                <div>
                                    <div v-for="payout in filteredPayoutsByCurrentUserCampus" :key="payout.id"
                                        class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mb-3 payout-card"
                                        @click="openPayout(payout)">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg font-semibold text-gray-800">Payout #{{
                                                    payout.id }}</span>
                                                <span :class="{
                                                    'status-badge completed': payout.status === 'Completed',
                                                    'status-badge pending': payout.status === 'Pending',
                                                    'status-badge processing': payout.status === 'Processing'
                                                }">{{ payout.status }}</span>
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Total Scholars</span>
                                                    <span class="text-xl font-bold text-blue-600">{{
                                                        payout.total_scholars }}</span>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Date Created</span>
                                                    <span class="text-base text-gray-700">{{
                                                        formatDate(payout.created_at) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Then show other campuses' payouts -->
                            <div v-for="campus in filteredOtherCampuses" :key="campus.id" class="mb-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ campus.name }}</h3>

                                <!-- Display payouts for this campus -->
                                <div
                                    v-if="filteredPayoutsByCampus[campus.id] && filteredPayoutsByCampus[campus.id].length > 0">
                                    <div v-for="payout in filteredPayoutsByCampus[campus.id]" :key="payout.id"
                                        class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mb-3 payout-card"
                                        @click="viewPayoutDetails(payout)">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg font-semibold text-gray-800">Payout #{{
                                                    payout.id }}</span>
                                                <span :class="{
                                                    'status-badge completed': payout.status === 'Completed',
                                                    'status-badge pending': payout.status === 'Pending',
                                                    'status-badge processing': payout.status === 'Processing'
                                                }">{{ payout.status }}</span>
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Total Scholars</span>
                                                    <span class="text-xl font-bold text-blue-600">{{
                                                        payout.total_scholars }}</span>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Date Created</span>
                                                    <span class="text-base text-gray-700">{{
                                                        formatDate(payout.created_at) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 italic p-4 bg-gray-50 rounded-lg">
                                    No payouts available for this campus.
                                </div>
                            </div>

                            <!-- No payouts message -->
                            <div v-if="!hasAnyPayouts" class="text-center py-8">
                                <font-awesome-icon :icon="['far', 'folder-open']" class="text-gray-400 text-5xl mb-3" />
                                <p class="text-gray-500 text-lg">No payouts found for any campus.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forward batch list modal - KEPT AS IS -->
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
                                Forward the Batch List
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

                <!-- Form -->
                <form @submit.prevent="forwardBatches">
                    <div class="py-4 px-8 flex flex-col gap-3">
                        <div class="mb-4">
                            <label for="dateRangePicker"
                                class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                                Select a Date Range:
                            </label>

                            <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">
                                <!-- Application Start Date -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <InputError v-if="errors?.date_start" :message="errors.date_start"
                                            class="text-red-500" />
                                        <input id="datepicker-range-start" name="start" type="text" autocomplete="off"
                                            lang="en" v-model="dateRange.start"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Submission Start Date">
                                    </div>
                                </div>

                                <span class="text-gray-500">to</span>

                                <!-- Application Deadline -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <InputError v-if="errors?.date_end" :message="errors.date_end"
                                            class="text-red-500" />
                                        <input id="datepicker-range-end" name="end" type="text" autocomplete="off"
                                            lang="en" v-model="dateRange.end"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Submission End Date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div v-if="isLoading" class="flex justify-center items-center py-4">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                        </div>

                        <!-- Batches to be forwarded -->
                        <div v-else>
                            <label class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                                Batches to Forward:
                            </label>

                            <!-- If there are forwardable batches -->
                            <div v-if="props.forwardableBatches.length > 0" class="flex flex-col gap-2">
                                <!-- Batch Summary -->
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 mb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Batches</p>
                                            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{
                                                props.forwardableBatches.length }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Scholars</p>
                                            <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                                {{props.forwardableBatches.reduce((sum, batch) => sum +
                                                    batch.total_scholars, 0)}}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Batch List -->
                                <div class="max-h-64 overflow-y-auto rounded-lg border border-gray-200">
                                    <div v-for="batch in props.forwardableBatches" :key="batch.id"
                                        class="py-3 px-4 flex justify-between items-center border-b last:border-b-0">
                                        <div>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                Batch {{ batch.batch_no }}
                                                <span class="text-sm text-gray-500">({{ getCampusName(batch.campus_id)
                                                    }})</span>
                                            </p>
                                            <p class="text-sm text-gray-500">Scholars: {{ batch.total_scholars }}</p>
                                        </div>
                                        <span
                                            class="text-sm font-medium px-3 py-1 rounded-full text-green-700 bg-green-100">
                                            Ready
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- No payouts message -->
                            <div v-else class="py-6 text-center">
                                <font-awesome-icon :icon="['far', 'folder-open']" class="text-gray-400 text-4xl mb-3" />
                                <p class="text-gray-500 text-lg font-medium">No payouts available</p>
                                <p class="text-gray-400 text-sm">There are no batches ready to be forwarded at this
                                    time.</p>
                            </div>
                        </div>

                        <!-- Forward Button -->
                        <div class="mt-4">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200"
                                :disabled="props.forwardableBatches.length === 0 || isSubmitting"
                                :class="{ 'opacity-50 cursor-not-allowed': props.forwardableBatches.length === 0 || isSubmitting }">
                                <span v-if="isSubmitting" class="flex items-center justify-center">
                                    <div
                                        class="animate-spin h-4 w-4 border-2 border-white rounded-full border-t-transparent mr-2">
                                    </div>
                                    Processing...
                                </span>
                                <span v-else>Forward All Batches</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toastMessage.title }}</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage.description }}
                </ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>


<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ToastProvider, ToastRoot, ToastTitle, ToastDescription, ToastViewport } from 'radix-vue';
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';

const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    batches: Array,
    batchesByCampus: Object,
    hasForwardableBatches: Boolean,
    forwardableBatches: Array,
    campuses: Array,
    currentUser: Object,
    payouts: Array
});

// State variables
const ForwardBatchList = ref(false);
const selectedCampus = ref('');
const isLoading = ref(false);
const isSubmitting = ref(false);
const toastVisible = ref(false);
const toastMessage = ref({ title: '', description: '' });
const forwardableBatchesCount = computed(() => props.forwardableBatches?.length || 0);
const completedPayoutsCount = computed(() => props.payouts?.filter(p => p.status === 'Completed').length || 0);
const totalPayouts = computed(() => props.payouts?.length || 0);

// Date range for forwarding batches
const dateRange = ref({
    start: '',
    end: ''
});

// Form for errors
const form = useForm({
    date_start: '',
    date_end: '',
    batches: []
});

// Function to open payroll for a specific batch
const openPayroll = (batchId) => {
    // Find the scholarship ID associated with this batch
    const batch = props.batches.find(b => b.id === batchId);
    const scholarshipId = batch ? batch.scholarship_id : null;

    if (scholarshipId) {
        router.visit(`/cashier/payout/${scholarshipId}/batch/${batchId}`, {
            data: {
                scholarship: scholarshipId,
                selectedYear: props.schoolyear.id,
                selectedSem: props.selectedSem
            },
            preserveState: true
        });
    } else {
        console.error('Scholarship ID not found for batch:', batchId);
    }
};

const errors = computed(() => form.errors);

// Campus related computed properties
const currentUserCampus = computed(() => {
    return props.campuses.find(campus => campus.id === props.currentUser.campus_id) || {};
});

const filteredPayoutsByCurrentUserCampus = computed(() => {
    if (!selectedCampus.value || selectedCampus.value === currentUserCampus.value.id.toString()) {
        return props.payouts.filter(payout => payout.campus_id === currentUserCampus.value.id);
    }
    return [];
});

const filteredPayoutsByCampus = computed(() => {
    const result = {};

    props.campuses.forEach(campus => {
        if (!selectedCampus.value || selectedCampus.value === campus.id.toString()) {
            result[campus.id] = props.payouts.filter(payout => payout.campus_id === campus.id);
        } else {
            result[campus.id] = [];
        }
    });

    return result;
});

const filteredOtherCampuses = computed(() => {
    return props.campuses.filter(campus => {
        return campus.id !== currentUserCampus.value.id &&
            (!selectedCampus.value || selectedCampus.value === campus.id.toString()) &&
            filteredPayoutsByCampus.value[campus.id]?.length > 0;
    });
});

const hasAnyPayouts = computed(() => {
    if (filteredPayoutsByCurrentUserCampus.value.length > 0) return true;

    return filteredOtherCampuses.value.some(campus =>
        filteredPayoutsByCampus.value[campus.id] &&
        filteredPayoutsByCampus.value[campus.id].length > 0
    );
});

// Methods
const toggleSendBatch = () => {
    ForwardBatchList.value = !ForwardBatchList.value;
};

const closeModal = () => {
    ForwardBatchList.value = false;
};

const viewPayoutDetails = (payout) => {
    window.location.href = route('view.scholarship.batch', {
        scholarship: props.scholarship.id,
        batch: payout.id
    });
};

const goBack = () => {
    window.history.back();
};

const getCampusName = (campusId) => {
    const campus = props.campuses.find(c => c.id === campusId);
    return campus ? campus.name : 'Unknown Campus';
};

const formatDate = (dateString) => {
    if (!dateString) return '';

    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }).format(date);
};

const forwardBatches = () => {
    if (props.forwardableBatches.length === 0) return;

    isSubmitting.value = true;

    form.date_start = dateRange.value.start;
    form.date_end = dateRange.value.end;
    form.batches = props.forwardableBatches.map(batch => batch.id);

    form.post(route('forward.batches', { scholarship: props.scholarship.id }), {
        onSuccess: () => {
            isSubmitting.value = false;
            closeModal();
            showToast('Success!', 'Batches have been forwarded to Cashier.');

            // Refresh the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        },
        onError: () => {
            isSubmitting.value = false;
            showToast('Error', 'Failed to forward batches. Please try again.');
        }
    });
};

const showToast = (title, description) => {
    toastMessage.value = { title, description };
    toastVisible.value = true;

    setTimeout(() => {
        toastVisible.value = false;
    }, 5000);
};

// Initialize datepicker on mount
// onMounted(() => {
//     // Integration with Flowbite Datepicker or other library would go here
//     // Example: if using Flowbite
//     // Initialize Flowbite Datepicker
//     const dateInput = document.getElementById("datepicker-autohide");
//     if (dateInput) {
//         const datepicker = new Datepicker(dateInput, {
//             autohide: true,
//             format: "yyyy-mm-dd", // Adjust format as needed
//         });

//         dateInput.addEventListener("changeDate", (event) => {
//             form.value.birthdate = event.target.value;
//         });
//     }

//     const startInput = document.getElementById("datepicker-range-start");
//     if (startInput) {
//         startInput.value = selectedStart.value; // Keep the previous value
//         startInput.addEventListener("changeDate", (event) => {
//             const date = new Date(event.target.value);

//             // Correct for time zone issues
//             date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

//             form.value.application = date.toISOString().split("T")[0]; // Keeps the correct local date
//             console.log("Application:", form.value.application);
//             selectedStart.value = event.target.value;
//         });
//     }

//     const endInput = document.getElementById("datepicker-range-end");
//     if (endInput) {
//         endInput.value = selectedEnd.value; // Keep the previous value
//         endInput.addEventListener("changeDate", (event) => {
//             const date = new Date(event.target.value);

//             // Correct for time zone issues
//             date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

//             form.value.deadline = date.toISOString().split("T")[0]; // Keeps the correct local date
//             selectedEnd.value = event.target.value;
//         });
//     }

//     watch(ForwardBatchList, (newValue) => {
//         if (newValue) {
//             setTimeout(() => {
//                 initFlowbite(); // Initialize Flowbite when modal is accessed

//                 const startInput = document.getElementById("datepicker-range-start");
//                 if (startInput) {
//                     startInput.value = StartPayout.value; // Keep the previous value
//                     startInput.addEventListener("changeDate", (event) => {
//                         const date = new Date(event.target.value); // ✅ Get selected date
//                         form.value.payoutStartInput = date.toISOString().split("T")[0];
//                         console.log("Application:", form.value.payoutStartInput);
//                         StartPayout.value = event.target.value;
//                     });
//                 } else {
//                     console.warn("Start datepicker not found.");
//                 }

//                 const endInput = document.getElementById("datepicker-range-end");
//                 if (endInput) {
//                     endInput.value = EndPayout.value; // Keep the previous value
//                     endInput.addEventListener("changeDate", (event) => {
//                         const date = new Date(event.target.value); // ✅ Get selected date
//                         form.value.payoutEndInput = date.toISOString().split("T")[0];
//                         EndPayout.value = event.target.value;
//                     });
//                 } else {
//                     console.warn("End datepicker not found.");
//                 }


//             }, 200); // Small delay to ensure modal is in the DOM
//         }
//     });
// });
</script>

<style scoped>
.breadcrumbs ul {
    display: flex;
    align-items: center;
}

.breadcrumbs li:not(:last-child)::after {
    content: '/';
    margin: 0 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.completed {
    background-color: #DEF7EC;
    color: #03543E;
}

.status-badge.pending {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-badge.processing {
    background-color: #DBEAFE;
    color: #1E40AF;
}

.payout-card {
    transition: transform 0.2s ease-in-out;
}

.payout-card:hover {
    transform: translateY(-2px);
}
</style>

<style scoped>
/* override the prime vue componentss */
:root {
    --p-tooltip-background: #D97706 !important;
    /* Yellow warning color */
}

.p-tooltip-text {
    font-size: 12px !important;
    color: white !important;
}

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

/* Custom styles for payout cards */
.payout-card {
    transition: all 0.2s ease;
}

.payout-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Status indicators */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.completed {
    background-color: #DEF7EC;
    color: #03543E;
}

.status-badge.pending {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-badge.processing {
    background-color: #E0F2FE;
    color: #0369A1;
}
</style>