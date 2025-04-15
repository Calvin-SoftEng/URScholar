<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <!-- Breadcrumbs and Header -->
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">Home</li>
                        <li class="hover:text-gray-600"><span>Scholarships</span></li>
                        <li><span class="text-blue-400 font-semibold">{{ scholarship.name }}</span></li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <button @click="goBack"
                                class="mr-2 font-poppins font-extrabold text-blue-400 hover:text-blue-500">
                                &lt;
                            </button>
                            <span>{{ scholarship.name }} {{ scholarship.scholarshipType }}</span>
                        </h1>
                        <span class="text-xl">SY {{ schoolyear.year }} - {{ selectedSem }} Semester</span>
                    </div>
                </div>

                <div>
                    <div class="w-full mt-5 rounded-xl space-y-5">
                        <!-- Stats Section -->
                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="grid grid-cols-4">
                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Batches</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <p class="text-4xl font-semibold font-kanit">{{ batches.length }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Grantees</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">{{ totalScholars }}</p>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Payouts</p>
                                </div>
                                <div class="grid grid-cols-3 items-center gap-3">
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ totalClaimed }}
                                    </p>
                                    <div class="h-5 w-[2px] bg-gray-400 mx-auto"></div>
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ totalPending }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Completed Batches</p>
                                </div>
                                <div class="grid grid-cols-3 items-center gap-3">
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ completedBatchesCount }}
                                    </p>
                                    <div class="h-5 w-[2px] bg-gray-400 mx-auto"></div>
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ batches.length }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <!-- Campus Filter -->
                        <div class="flex flex-row justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-800 mt-4">
                                List of Batches by Campus
                            </h2>

                            <div class="flex flex-row space-x-3 items-center">
                                <span class="font-poppins text-sm">Filter Campus</span>
                                <select v-model="selectedCampus"
                                    class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="">All Campuses</option>
                                    <option v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                        {{ campus.name }}
                                    </option>
                                </select>

                                <!-- Forward to Cashier Button -->
                                <button @click="toggleSendBatch" :class="[
                                    hasForwardableBatches
                                        ? 'bg-green-500 hover:bg-green-700 text-white'
                                        : 'bg-blue-100 dark:bg-blue-800 border border-blue-300 dark:border-blue-500 hover:bg-blue-200 dark:text-dtext',
                                    'flex items-center gap-2 font-poppins px-4 py-2 rounded-lg transition duration-200'
                                ]" :disabled="!hasForwardableBatches"
                                    v-tooltip.left="!hasForwardableBatches ? 'All batches must be inactive and complete' : ''">
                                    <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                    <span class="font-normal">Forward to <span
                                            class="font-semibold">Cashiers</span></span>
                                </button>
                            </div>
                        </div>

                        <!-- Batches by Campus -->
                        <div>
                            <!-- Loop through each campus -->
                            <div v-for="campus in campusesWithBatches" :key="campus.id" class="mb-8">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ campus.name }} Campus</h3>

                                <!-- List of batches for this campus -->
                                <div v-for="batch in getBatchesForCampus(campus.id)" :key="batch.id"
                                    class="bg-gradient-to-r from-[#F0F4FF] to-[#E6E9FD] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mb-3"
                                    @click="viewBatchDetails(batch)">
                                    <div class="flex flex-col space-y-3">
                                        <!-- Batch header -->
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-lg font-semibold text-gray-800">Batch #{{
                                                    batch.batch_no }}</span>
                                                <span :class="{
                                                    'status-badge completed': batch.status === 'Completed',
                                                    'status-badge pending': batch.status === 'Pending',
                                                    'status-badge processing': batch.status === 'Processing'
                                                }">{{ batch.status || 'Pending' }}</span>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ formatDate(batch.created_at)
                                                }}</span>
                                        </div>

                                        <!-- Batch details -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-8">
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-500">School Year</span>
                                                    <span class="text-sm font-medium">{{ schoolyear.year }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-500">Semester</span>
                                                    <span class="text-sm font-medium">{{ selectedSem }}</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-6">
                                                <div class="flex flex-col items-center">
                                                    <div class="flex items-center">
                                                        <font-awesome-icon :icon="['fas', 'users']"
                                                            class="text-primary text-sm mr-1" />
                                                        <span class="text-xs text-gray-500">No. of Students</span>
                                                    </div>
                                                    <span class="text-sm font-semibold text-blue-600">{{
                                                        batch.sub_total}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- No batches message -->
                                <div v-if="getBatchesForCampus(campus.id).length === 0"
                                    class="text-center py-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-500">No batches available for this campus</p>
                                </div>
                            </div>

                            <!-- No campuses message when filtering returns nothing -->
                            <div v-if="campusesWithBatches.length === 0" class="text-center py-8">
                                <font-awesome-icon :icon="['far', 'folder-open']" class="text-gray-400 text-5xl mb-3" />
                                <p class="text-gray-500 text-lg">No batches found matching your criteria.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forward batch list modal - Keep as is -->
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
                            <div v-if="batches.length > 0" class="flex flex-col gap-2">
                                <!-- Batch Summary -->
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 mb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Batches</p>
                                            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{
                                                batches.length }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Scholars</p>
                                            <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                                {{batches.reduce((sum, batch) => sum +
                                                    (batch.claimed_count + batch.not_claimed_count), 0)}}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Batch List -->
                                <div class="max-h-64 overflow-y-auto rounded-lg border border-gray-200">
                                    <div v-for="batch in batches" :key="batch.id"
                                        class="py-3 px-4 flex justify-between items-center border-b last:border-b-0">
                                        <div>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                                Batch {{ batch.batch_no }}
                                                <span class="text-sm text-gray-500">({{ getCampusName(batch.campus_id)
                                                }})</span>
                                            </p>
                                            <p class="text-sm text-gray-500">Scholars: {{ batch.sub_total }}</p>
                                        </div>
                                        <span
                                            class="text-sm font-medium px-3 py-1 rounded-full text-green-700 bg-green-100">
                                            Ready
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- No batches message -->
                            <div v-else class="py-6 text-center">
                                <font-awesome-icon :icon="['far', 'folder-open']" class="text-gray-400 text-4xl mb-3" />
                                <p class="text-gray-500 text-lg font-medium">No batches available</p>
                                <p class="text-gray-400 text-sm">There are no batches ready to be forwarded at this
                                    time.</p>
                            </div>
                        </div>

                        <!-- Forward Button -->
                        <div class="mt-4">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200">
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

        <!-- Toast notifications - Keep as is -->
        <ToastProvider>
            <!-- Toast content - Keep as is -->
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ToastProvider, ToastRoot, ToastTitle, ToastDescription, ToastViewport } from 'radix-vue';
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';

// Props and initial setup
const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    batches: Array,
    campuses: Array,
    currentUser: Object,
    payouts: Array,
    payoutsByCampus: Array,
    // Other props
});

// State
const selectedCampus = ref('');
const ForwardBatchList = ref(false);
const isLoading = ref(false);
const isSubmitting = ref(false);
const dateRange = ref({ start: '', end: '' });
const errors = ref({});
const toastVisible = ref(false);
const toastMessage = ref({ title: '', description: '' });
const form = ref({
    // Form fields
    payoutStartInput: '',
    payoutEndInput: '',
});

// Computed properties
const campusesWithBatches = computed(() => {
    // Filter campuses that have batches (or all if no campus filter is applied)
    return props.campuses.filter(campus => {
        const hasBatches = props.batches.some(batch =>
            batch.campus_id === campus.id &&
            (!selectedCampus.value || selectedCampus.value === '' ||
                selectedCampus.value === campus.id.toString())
        );
        return hasBatches;
    }).sort((a, b) => a.name.localeCompare(b.name)); // Sort alphabetically
});

const getBatchesForCampus = (campusId) => {
    return props.batches.filter(batch =>
        batch.campus_id === campusId &&
        (!selectedCampus.value || selectedCampus.value === '' ||
            selectedCampus.value === campusId.toString())
    ).sort((a, b) => a.batch_no - b.batch_no); // Sort by batch number
};

// Stats calculations
const totalScholars = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + batch.claimed_count + batch.not_claimed_count;
    }, 0);
});

const totalClaimed = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + batch.claimed_count;
    }, 0);
});

const totalPending = computed(() => {
    return props.batches.reduce((total, batch) => {
        return total + batch.not_claimed_count;
    }, 0);
});

const completedBatchesCount = computed(() => {
    return props.batches.filter(batch => batch.status === 'Completed').length;
});

const forwardableBatches = computed(() => {
    return props.batches.filter(batch =>
        batch.status === 'Accomplished' && batch.not_claimed_count === 0
    );
});

const hasForwardableBatches = computed(() => forwardableBatches.value.length > 0);

// Functions
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    }).format(date);
};

const getCampusName = (campusId) => {
    const campus = props.campuses.find(c => c.id === campusId);
    return campus ? campus.name : 'Unknown Campus';
};

const viewBatchDetails = (batch) => {
    router.visit(route('cashier.pending_payouts', {
        scholarshipId: props.scholarship.id,
        batchId: batch.id,
        selectedSem: props.selectedSem,
        selectedYear: props.schoolyear.id,
    }));
};

const toggleSendBatch = () => {
    ForwardBatchList.value = true;
};

const closeModal = () => {
    ForwardBatchList.value = false;
    dateRange.value = { start: '', end: '' };
    errors.value = {};
};

const forwardBatches = async () => {
    isSubmitting.value = true;
    errors.value = {};

    try {
        const batchIds = forwardableBatches.value.map(batch => batch.id);

        await router.post(route('cashier.forward'), {
            scholarship_id: props.scholarship.id,
            batch_ids: batchIds,
            date_start: form.value.payoutStartInput,
            date_end: form.value.payoutEndInput,
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        }, {
            onSuccess: () => {
                toastMessage.value = {
                    title: 'Success!',
                    description: 'Batches have been forwarded to the cashier.'
                };
                toastVisible.value = true;
                closeModal();
                setTimeout(() => {
                    toastVisible.value = false;
                }, 3000);
            },
            onError: (err) => {
                errors.value = err;
            }
        });
    } catch (error) {
        console.error('Error forwarding batches:', error);
    } finally {
        isSubmitting.value = false;
    }
};

const goBack = () => {
    window.history.back();
};

// Lifecycle hooks
onMounted(() => {
    initFlowbite();
    // Additional initialization code as needed
});

watch(ForwardBatchList, (newValue) => {
    if (newValue) {
        setTimeout(() => {
            initFlowbite();
            // Initialize datepickers and other modal components
        }, 200);
    }
});
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

/* Additional styles */
.payout-card {
    transition: transform 0.2s ease-in-out;
}

.payout-card:hover {
    transform: translateY(-2px);
}
</style>