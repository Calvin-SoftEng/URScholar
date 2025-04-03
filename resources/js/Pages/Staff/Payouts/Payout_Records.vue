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
                <div v-for="(payouts, academicYear) in groupedRecentPayouts" :key="academicYear" class="mb-8">
                    <!-- Academic Year Header -->
                    <div class="mb-4 bg-blue-50 p-3 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold text-blue-800">{{ academicYear }}</h2>
                    </div>

                    <div class="space-y-8">
                        <div v-for="payout in payouts" :key="payout.id"
                            class="bg-white p-5 rounded-lg shadow-md relative">
                            <!-- Status Badge -->
                            <span
                                class="absolute -top-3 right-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">
                                {{ payout.status }}
                            </span>

                            <!-- Scholarship & Payout Details -->
                            <div class="flex flex-col md:flex-row justify-between">
                                <div class="mb-4 md:mb-0">
                                    <p class="text-xl font-semibold text-gray-800">
                                        {{ payout.scholarshipName }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Payment Period: {{ payout.dateStart }} - {{ payout.dateEnd || 'No Deadline' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium">Scheduled:</span> {{ payout.payoutSchedule }}
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                        {{ payout.scholarshipType }}
                                    </span>
                                </div>
                            </div>

                            <!-- Batch Information -->
                            <div class="mt-6">
                                <h3 class="text-md font-semibold text-gray-700 mb-3">Associated Batches:</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="batch in getBatchesForScholarship(payout.scholarshipId)" :key="batch.id"
                                        class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-800 font-medium">Batch {{ batch.batch_no }}</span>
                                            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full">
                                                {{ batch.school_year.year }} - {{ batch.semester }} Semester
                                            </span>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>Scholars: {{ batch.total_scholars || 'N/A' }}</p>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout History Section -->
            <div v-if="selectedMenu === 'history'" class="p-6 h-full">
                <div v-for="(payouts, academicYear) in groupedCompletedPayouts" :key="academicYear" class="mb-8">
                    <!-- Academic Year Header -->
                    <div class="mb-4 bg-green-50 p-3 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold text-green-800">{{ academicYear }}</h2>
                    </div>

                    <div class="space-y-8">
                        <div v-for="historyItem in payouts" :key="historyItem.id"
                            class="bg-white p-5 rounded-lg shadow-md relative">
                            <!-- Status Badge -->
                            <span
                                class="absolute -top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                {{ historyItem.status }}
                            </span>

                            <!-- Scholarship & Payout Details -->
                            <div class="flex flex-col md:flex-row justify-between">
                                <div class="mb-4 md:mb-0">
                                    <p class="text-xl font-semibold text-gray-800">
                                        {{ historyItem.scholarshipName }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Payment Period: {{ historyItem.dateStart }} - {{ historyItem.dateEnd || 'No Deadline' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="font-medium">Scheduled:</span> {{ historyItem.payoutSchedule }}
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                        {{ historyItem.scholarshipType }}
                                    </span>
                                </div>
                            </div>

                            <!-- Batch Information -->
                            <div class="mt-6">
                                <h3 class="text-md font-semibold text-gray-700 mb-3">Associated Batches:</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div v-for="batch in getBatchesForScholarship(historyItem.scholarshipId)"
                                        :key="batch.id" class="bg-gray-50 p-3 rounded-md border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-800 font-medium">Batch {{ batch.batch_no }}</span>
                                            <span class="text-xs bg-gray-200 px-2 py-1 rounded-full">
                                                {{ batch.school_year.year }} - {{ batch.semester }} Semester
                                            </span>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>Scholars: {{ batch.total_scholars || 'N/A' }}</p>
                                        </div>

                                        <!-- Claim Status -->
                                        <div class="mt-3 pt-2 border-t border-gray-200">
                                            <div class="flex justify-between text-sm">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                    <span>Claimed: {{ getClaimCount(historyItem.id, batch.id, 'Claimed')
                                                        }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                                    <span>Not Claimed: {{ getClaimCount(historyItem.id, batch.id, 'Not Claimed') }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-green-500 h-2.5 rounded-full"
                                                    :style="{ width: getClaimPercentage(historyItem.id, batch.id) + '%' }">
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 text-right">
                                                {{ getClaimPercentage(historyItem.id, batch.id) }}% claimed
                                            </div>
                                        </div>
                                    </div>
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
import { Head } from '@inertiajs/vue3';

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
    }
});

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

// Get academic year for a batch
const getAcademicYear = (batchId) => {
    const batch = props.batches.find(b => b.id === batchId);
    if (!batch || !batch.school_year) return 'Unknown Academic Year';

    return `${batch.school_year.year} - ${batch.semester} Semester`;
};

// Consolidated function to get payout details
const getPayoutDetails = (payout) => {
    // Find the associated scholarship
    const scholarship = props.scholarships.find(
        s => s.id === payout.scholarship_id
    ) || {};

    // Find associated batch to determine academic year
    const batches = getBatchesForScholarship(scholarship.id);
    const primaryBatch = batches.length > 0 ? batches[0] : null;
    const academicYear = primaryBatch ?
        `${primaryBatch.school_year.year} - ${primaryBatch.semester} Semester` :
        'Unknown Academic Year';

    // Return a consolidated object with all relevant details
    return {
        // Payout details
        id: payout.id,
        dateStart: formatDate(payout.date_start),
        dateEnd: formatDate(payout.date_end),
        status: payout.status,
        subTotal: formatCurrency(payout.sub_total),
        totalScholars: payout.total_scholars || 0,
        payoutSchedule: getPayoutSchedule(payout.id),
        academicYear: academicYear,

        // Scholarship details
        scholarshipId: scholarship.id,
        scholarshipName: scholarship.name || 'Unknown Scholarship',
        scholarshipType: scholarship.scholarshipType || 'N/A',
    };
};

// Computed properties for easy access
const recentPayouts = computed(() =>
    props.payouts
        .filter(payout =>
            payout.status === 'Pending' || payout.status === 'Active'
        )
        .map(getPayoutDetails)
);

const completedPayouts = computed(() =>
    props.payouts
        .filter(payout =>
            payout.status === 'Completed' || payout.status === 'Inactive'
        )
        .map(getPayoutDetails)
);

// Group payouts by academic year
const groupedRecentPayouts = computed(() => {
    const grouped = {};

    recentPayouts.value.forEach(payout => {
        if (!grouped[payout.academicYear]) {
            grouped[payout.academicYear] = [];
        }
        grouped[payout.academicYear].push(payout);
    });

    return grouped;
});

const groupedCompletedPayouts = computed(() => {
    const grouped = {};

    completedPayouts.value.forEach(payout => {
        if (!grouped[payout.academicYear]) {
            grouped[payout.academicYear] = [];
        }
        grouped[payout.academicYear].push(payout);
    });

    return grouped;
});
</script>

<style scoped>
/* Add any custom styles here */
</style>