<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">Home</li>
                        <li>
                            <span class="text-blue-400 font-semibold dark:text-gray-300">Scholarship Payouts</span>
                        </li>
                    </ul>
                </div>

                <!-- Header Section -->
                <div class="flex flex-col md:flex-row justify-between gap-4">
                    <div class="text-3xl font-semibold text-gray-700 flex flex-col gap-2">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>
                            <span>Scholarship Payouts</span>
                        </h1>
                    </div>

                    <!-- Stats Section -->
                    <div class="grid grid-cols-2 shadow-sm rounded-lg border">
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-600 text-base" />
                                <p class="text-gray-500 text-sm">Completed Payouts</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit text-green-600">{{ completedPayouts.length }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Pending Payouts</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ recentPayouts.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                <button v-for="item in menuItems" :key="item.key" @click="selectMenu(item.key)" :class="[
                    'relative inline-block text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                    selectedMenu === item.key
                        ? 'text-blue-700 dark:text-white'
                        : 'text-gray-900 dark:text-white hover:text-blue-700 dark:hover:bg-gray-700'
                ]">
                    {{ item.name }}
                    <span v-if="selectedMenu === item.key"
                        class="absolute left-0 bottom-0 w-full h-1 bg-blue-700 dark:bg-white"></span>
                </button>
            </div>

            <!-- Recent Payouts Section -->
            <div v-if="selectedMenu === 'recent'" class="p-6 h-full">
                <div v-for="(scholarshipData, scholarshipId) in groupedScholarshipData" :key="scholarshipId"
                    class="mb-8">
                    <!-- Scholarship Header -->
                    <div class="mb-4 bg-blue-50 p-3 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold text-blue-800">{{ scholarshipData.name }}</h2>
                        <p class="text-sm text-gray-600">{{ scholarshipData.type }}</p>
                    </div>

                    <!-- Campus Sections -->
                    <div v-for="(campusData, campusId) in scholarshipData.campuses" :key="campusId" class="mb-6 ml-4">
                        <div class="mb-3 bg-gray-50 p-2 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-700">Campus: {{ campusData.name }}</h3>
                        </div>

                        <!-- Batches for this campus -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-3">
                            <div v-for="batch in campusData.batches" :key="batch.id"
                                class="bg-white p-4 rounded-lg shadow-md">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-md font-medium">Batch {{ batch.batch_no }}</span>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ batch.school_year.year }} - {{ batch.semester }} Semester
                                    </span>
                                </div>

                                <!-- Payouts for this batch -->
                                <div v-if="batch.payouts.length > 0" class="space-y-3">
                                    <h4 class="text-sm font-medium text-gray-700 border-b pb-1">Payouts</h4>
                                    <div v-for="payout in batch.payouts" :key="payout.id"
                                        class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                        <!-- Status Badge -->
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ formatDate(payout.date_start) }} - {{ formatDate(payout.date_end) }}
                                            </p>
                                            <span :class="[
                                                'text-xs font-semibold px-2 py-1 rounded-full',
                                                payout.status === 'Completed' ? 'bg-green-500 text-white' :
                                                    payout.status === 'Active' ? 'bg-blue-500 text-white' :
                                                        'bg-yellow-500 text-white'
                                            ]">
                                                {{ payout.status }}
                                            </span>
                                        </div>

                                        <!-- Payout Details -->
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>Schedule: {{ getPayoutSchedule(payout.id) }}</p>
                                            <p>Total: {{ formatCurrency(payout.sub_total) }}</p>
                                        </div>

                                        <!-- Claim Status -->
                                        <div class="mt-3 pt-2 border-t border-gray-200">
                                            <div class="flex justify-between text-sm">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                    <span>Claimed: {{ getClaimCount(payout.id, batch.id, 'Claimed')
                                                    }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                                    <span>Not Claimed: {{ getClaimCount(payout.id, batch.id, 'Not Claimed') }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-500 h-2.5 rounded-full"
                                                    :style="{ width: getClaimPercentage(payout.id, batch.id) + '%' }">
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 text-right">
                                                {{ getClaimPercentage(payout.id, batch.id) }}% claimed
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button @click="openBatchPayroll(batch.id)"
                                                class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">
                                                View Payroll
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500 italic">
                                    No payouts for this batch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout History Section -->
            <div v-if="selectedMenu === 'history'" class="p-6 h-full">
                <div v-for="(scholarshipData, scholarshipId) in groupedCompletedScholarshipData" :key="scholarshipId"
                    class="mb-8">
                    <!-- Scholarship Header -->
                    <div class="mb-4 bg-green-50 p-3 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold text-green-800">{{ scholarshipData.name }}</h2>
                        <p class="text-sm text-gray-600">{{ scholarshipData.type }}</p>
                    </div>

                    <!-- Campus Sections -->
                    <div v-for="(campusData, campusId) in scholarshipData.campuses" :key="campusId" class="mb-6 ml-4">
                        <div class="mb-3 bg-gray-50 p-2 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-700">Campus: {{ campusData.name }}</h3>
                        </div>

                        <!-- Batches for this campus -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-3">
                            <div v-for="batch in campusData.batches" :key="batch.id"
                                class="bg-white p-4 rounded-lg shadow-md">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-md font-medium">Batch {{ batch.batch_no }}</span>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ batch.school_year.year }} - {{ batch.semester }} Semester
                                    </span>
                                </div>

                                <!-- Completed Payouts for this batch -->
                                <div v-if="batch.payouts.length > 0" class="space-y-3">
                                    <h4 class="text-sm font-medium text-gray-700 border-b pb-1">Completed Payouts</h4>
                                    <div v-for="payout in batch.payouts" :key="payout.id"
                                        class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                        <!-- Status Badge -->
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ formatDate(payout.date_start) }} - {{ formatDate(payout.date_end) }}
                                            </p>
                                            <span
                                                class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                                {{ payout.status }}
                                            </span>
                                        </div>

                                        <!-- Payout Details -->
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>Completed: {{ formatDate(payout.completed_date || payout.updated_at) }}
                                            </p>
                                            <p>Total: {{ formatCurrency(payout.sub_total) }}</p>
                                        </div>

                                        <!-- Claim Status -->
                                        <div class="mt-3 pt-2 border-t border-gray-200">
                                            <div class="flex justify-between text-sm">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                    <span>Claimed: {{ getClaimCount(payout.id, batch.id, 'Claimed')
                                                    }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                                    <span>Not Claimed: {{ getClaimCount(payout.id, batch.id, 'Not Claimed') }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-500 h-2.5 rounded-full"
                                                    :style="{ width: getClaimPercentage(payout.id, batch.id) + '%' }">
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 text-right">
                                                {{ getClaimPercentage(payout.id, batch.id) }}% claimed
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button @click="openBatchPayroll(batch.id)"
                                                class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">
                                                View Payroll
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500 italic">
                                    No completed payouts for this batch
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
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';

// Define props with proper type checking
const props = defineProps({
    scholarships: {
        type: Array,
        default: () => []
    },
    payouts: {
        type: Array,
        default: () => []
    },
    batches: {
        type: Array,
        default: () => []
    },
    disbursements: {
        type: Array,
        default: () => []
    },
    payout_schedule: {
        type: Array,
        default: () => []
    },
    academic_years: {
        type: Array,
        default: () => []
    },
    scholarship: {
        type: Object,
        default: () => ({})
    },
    schoolyear: {
        type: Object,
        default: () => ({})
    },
    selectedSem: {
        type: String,
        default: ''
    },
    campuses: {
        type: Array,
        default: () => []
    }
});

// Function to get campus name by ID
const getCampusName = (campusId) => {
    const campus = props.campuses.find(c => c.id === campusId);
    return campus ? campus.name : 'Unknown Campus';
};

// Function to open payroll for a specific batch
const openBatchPayroll = (batchId) => {
    // Find the scholarship ID associated with this batch
    const batch = props.batches.find(b => b.id === batchId);
    const scholarshipId = batch ? batch.scholarship_id : null;

    if (scholarshipId) {
        router.visit(`/payouts/${scholarshipId}/batch/${batchId}`, {
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

// Menu items
const menuItems = [
    { name: "Recent Payouts", key: "recent" },
    { name: "Payout History", key: "history" }
];

// Selected menu state
const selectedMenu = ref('recent');

// Method to select menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

// Get batches for a specific scholarship
const getBatchesForScholarship = (scholarshipId) => {
    return props.batches.filter(batch => batch.scholarship_id === scholarshipId);
};

// Get batches for a specific scholarship and campus
const getBatchesForScholarshipAndCampus = (scholarshipId, campusId) => {
    return props.batches.filter(batch =>
        batch.scholarship_id === scholarshipId &&
        batch.campus_id === campusId
    );
};

// Get count of claimed/not claimed disbursements for a specific payout and batch
const getClaimCount = (payoutId, batchId, status) => {
    return props.disbursements.filter(
        d => d.payout_id === payoutId &&
            d.batch_id === batchId &&
            d.status === status
    ).length;
};

// Get percentage of claimed disbursements
const getClaimPercentage = (payoutId, batchId) => {
    const totalDisbursements = props.disbursements.filter(
        d => d.payout_id === payoutId && d.batch_id === batchId
    ).length;

    if (totalDisbursements === 0) return 0;

    const claimedCount = getClaimCount(payoutId, batchId, 'Claimed');
    return Math.round((claimedCount / totalDisbursements) * 100);
};

// Helper function to format date
const formatDate = (dateString) => {
    if (!dateString) return 'No Date';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Helper function to format date and time
const formatDateTime = (date, time) => {
    if (!date || !time) return 'No Schedule';

    const dateObj = new Date(`${date}T${time}`);

    return dateObj.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) + ' ' +
        dateObj.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
};

// Helper function to format currency
const formatCurrency = (amount) => {
    if (!amount) return '₱0.00';
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
};

// Get payout schedule for a specific payout
const getPayoutSchedule = (payoutId) => {
    const schedule = props.payout_schedule.find(
        s => s.payout_id === payoutId
    );

    if (!schedule) return 'No Schedule';

    return formatDateTime(schedule.scheduled_date, schedule.scheduled_time);
};

// Get payouts for a specific batch
const getPayoutsForBatch = (batchId, status = null) => {
    let filteredPayouts = props.payouts.filter(payout => {
        // Find disbursements for this payout and batch combination
        const hasDisbursement = props.disbursements.some(
            d => d.payout_id === payout.id && d.batch_id === batchId
        );

        return hasDisbursement;
    });

    // Filter by status if specified
    if (status === 'active') {
        filteredPayouts = filteredPayouts.filter(p =>
            p.status === 'Pending' || p.status === 'Active'
        );
    } else if (status === 'completed') {
        filteredPayouts = filteredPayouts.filter(p =>
            p.status === 'Completed' || p.status === 'Inactive'
        );
    }

    return filteredPayouts;
};

// Modify the groupedScholarshipData computed property to skip campuses with only inactive payouts
const groupedScholarshipData = computed(() => {
    const result = {};

    // For each scholarship
    props.scholarships.forEach(scholarship => {
        const scholarshipId = scholarship.id;
        
        // Get all batches for this scholarship
        const scholarshipBatches = getBatchesForScholarship(scholarshipId);
        
        // If no batches, skip this scholarship
        if (scholarshipBatches.length === 0) return;

        // Initialize scholarship data
        result[scholarshipId] = {
            id: scholarshipId,
            name: scholarship.name || 'Unknown Scholarship',
            type: scholarship.scholarshipType || 'N/A',
            campuses: {}
        };

        // Group batches by campus
        scholarshipBatches.forEach(batch => {
            const campusId = batch.campus_id;
            
            // Get active payouts for this batch (only Pending or Active status)
            const batchPayouts = getPayoutsForBatch(batch.id, 'active');
            
            // Only process this batch if it has active payouts
            if (batchPayouts.length > 0) {
                // Initialize campus if not exists
                if (!result[scholarshipId].campuses[campusId]) {
                    result[scholarshipId].campuses[campusId] = {
                        id: campusId,
                        name: getCampusName(campusId),
                        batches: []
                    };
                }
                
                // Add batch with its payouts
                result[scholarshipId].campuses[campusId].batches.push({
                    ...batch,
                    payouts: batchPayouts
                });
            }
        });

        // Remove campuses with no batches
        Object.keys(result[scholarshipId].campuses).forEach(campusId => {
            if (result[scholarshipId].campuses[campusId].batches.length === 0) {
                delete result[scholarshipId].campuses[campusId];
            }
        });

        // Remove scholarships with no campuses
        if (Object.keys(result[scholarshipId].campuses).length === 0) {
            delete result[scholarshipId];
        }
    });

    return result;
});

// For the "Payout History" section, you need to modify how batches are filtered
// Update the groupedCompletedScholarshipData computed property

const groupedCompletedScholarshipData = computed(() => {
    const result = {};

    // For each scholarship
    props.scholarships.forEach(scholarship => {
        const scholarshipId = scholarship.id;
        
        // Get all batches for this scholarship
        const scholarshipBatches = getBatchesForScholarship(scholarshipId);
        
        // If no batches, skip this scholarship
        if (scholarshipBatches.length === 0) return;

        // Initialize scholarship data
        result[scholarshipId] = {
            id: scholarshipId,
            name: scholarship.name || 'Unknown Scholarship',
            type: scholarship.scholarshipType || 'N/A',
            campuses: {}
        };

        // Group batches by campus
        scholarshipBatches.forEach(batch => {
            const campusId = batch.campus_id;
            
            // Get completed payouts for this batch
            const batchPayouts = getPayoutsForBatch(batch.id, 'completed');
            
            // Only process this batch if it has completed payouts
            if (batchPayouts.length > 0) {
                // Initialize campus if not exists
                if (!result[scholarshipId].campuses[campusId]) {
                    result[scholarshipId].campuses[campusId] = {
                        id: campusId,
                        name: getCampusName(campusId),
                        batches: []
                    };
                }
                
                // Add batch with its payouts
                result[scholarshipId].campuses[campusId].batches.push({
                    ...batch,
                    payouts: batchPayouts
                });
            }
        });

        // Remove campuses with no batches
        Object.keys(result[scholarshipId].campuses).forEach(campusId => {
            if (result[scholarshipId].campuses[campusId].batches.length === 0) {
                delete result[scholarshipId].campuses[campusId];
            }
        });

        // Remove scholarships with no campuses
        if (Object.keys(result[scholarshipId].campuses).length === 0) {
            delete result[scholarshipId];
        }
    });

    return result;
});

// Computed properties for original structure (kept for compatibility)
const recentPayouts = computed(() =>
    props.payouts
        .filter(payout =>
            payout.status === 'Pending' || payout.status === 'Active'
        )
);

const completedPayouts = computed(() =>
    props.payouts
        .filter(payout =>
            payout.status === 'Completed' || payout.status === 'Inactive'
        )
);
</script>

<style scoped>
/* Add any custom styles here */
</style>