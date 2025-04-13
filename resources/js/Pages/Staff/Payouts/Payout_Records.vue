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
                    <!-- <div class="grid grid-cols-2 shadow-sm rounded-lg border">
                        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-600 text-base" />
                                <p class="text-gray-500 text-sm">Completed Payouts</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit text-green-600">{{ completedPayoutCount }}</p>
                        </div>

                        <div class="flex flex-col items-start py-4 px-10">
                            <div class="flex flex-row space-x-3 items-center">
                                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                <p class="text-gray-500 text-sm">Pending Payouts</p>
                            </div>
                            <p class="text-4xl font-semibold font-kanit">{{ activePayoutCount }}</p>
                        </div>
                    </div> -->
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

            <!-- Main Content Section -->
            <div class="h-full">
                <!-- Empty State -->
                <div v-if="Object.keys(combinedPayoutData).length === 0"
                    class="bg-white dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
                    <font-awesome-icon :icon="['fas', 'user-graduate']"
                        class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                    <p class="text-lg text-gray-700 dark:text-gray-300">
                        No {{ selectedMenu === 'active' ? 'Active' : 'Completed' }} Payouts Available
                    </p>
                </div>

                <!-- Scholarships List -->
                <div v-else>
                    <div v-for="(scholarship, scholarshipId) in filteredPayoutData" :key="scholarshipId"
                        class="mb-8 bg-blue-50 dark:bg-gray-800 rounded-lg shadow-sm">
                        <!-- Scholarship Header -->
                        <div class="mb-4 p-4">
                            <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-400">{{ scholarship.name }}</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ scholarship.type }}</p>
                        </div>

                        <!-- Campus Sections -->
                        <div v-for="(campus, campusId) in scholarship.campuses" :key="campusId"
                            class="mb-6 mx-4 p-3 bg-white dark:bg-gray-700 rounded-lg shadow-sm">
                            <div class="mb-3">
                                <h3 class="text-xl font-semibold text-gray-700 dark:text-white">
                                    {{ campus.name }} Campus
                                </h3>
                            </div>

                            <!-- BatchPayouts for this campus -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-2">
                                <div v-for="batchPayout in campus.batchPayouts" :key="batchPayout.batchId"
                                    class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg shadow-md">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-md font-medium text-gray-800 dark:text-gray-200">
                                            Batch {{ batchPayout.batchNo }}
                                        </span>
                                        <span
                                            class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs px-2 py-1 rounded-full">
                                            {{ batchPayout.schoolYear }} - {{ batchPayout.semester }} Semester
                                        </span>
                                    </div>

                                    <!-- Active Payouts -->
                                    <div v-if="selectedMenu === 'active' && batchPayout.active.length > 0"
                                        class="space-y-3">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 border-b pb-1">
                                            Active Payouts
                                        </h4>
                                        <div v-for="payout in batchPayout.active" :key="payout.id"
                                            class="bg-white dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                            <!-- Status Badge -->
                                            <div class="flex justify-between items-center">
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ payout.dateRange }}
                                                </p>
                                                <span :class="[
                                                    'text-xs font-semibold px-2 py-1 rounded-full',
                                                    payout.status === 'Active'
                                                        ? 'bg-blue-500 text-white'
                                                        : 'bg-yellow-500 text-white'
                                                ]">
                                                    {{ payout.status }}
                                                </span>
                                            </div>

                                            <!-- Payout Details -->
                                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                <p>Schedule: {{ payout.schedule }}</p>
                                            </div>

                                            <!-- Claim Status -->
                                            <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                                                <div class="flex justify-between text-sm">
                                                    <div class="flex items-center">
                                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                        <span>Claimed: {{ payout.claimStats.claimed }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                                        <span>Not Claimed: {{ payout.claimStats.notClaimed }}</span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-2 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                                    <div class="bg-green-500 h-2.5 rounded-full"
                                                        :style="{ width: payout.claimStats.percentage + '%' }">
                                                    </div>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                                                    {{ payout.claimStats.percentage }}% claimed
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <button @click="openBatchPayroll(batchPayout.batchId)"
                                                    class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">
                                                    View Payroll
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Completed Payouts -->
                                    <div v-if="selectedMenu === 'history' && batchPayout.completed.length > 0"
                                        class="space-y-3">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 border-b pb-1">
                                            Completed Payouts
                                        </h4>
                                        <div v-for="payout in batchPayout.completed" :key="payout.id"
                                            class="bg-white dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                            <!-- Status Badge -->
                                            <div class="flex justify-between items-center">
                                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ payout.dateRange }}
                                                </p>
                                                <span
                                                    class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                                    Completed
                                                </span>
                                            </div>

                                            <!-- Payout Details -->
                                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                <p>Completed: {{ payout.completedDate }}</p>
                                            </div>

                                            <!-- Claim Status -->
                                            <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                                                <div class="flex justify-between text-sm">
                                                    <div class="flex items-center">
                                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                        <span>Claimed: {{ payout.claimStats.claimed }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                                        <span>Not Claimed: {{ payout.claimStats.notClaimed }}</span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-2 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                                    <div class="bg-green-500 h-2.5 rounded-full"
                                                        :style="{ width: payout.claimStats.percentage + '%' }">
                                                    </div>
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                                                    {{ payout.claimStats.percentage }}% claimed
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <button @click="openBatchPayroll(batchPayout.batchId)"
                                                    class="text-sm text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">
                                                    View Payroll
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Payouts Message -->
                                    <div v-if="(selectedMenu === 'active' && batchPayout.active.length === 0) ||
                                        (selectedMenu === 'history' && batchPayout.completed.length === 0)"
                                        class="text-sm text-gray-500 dark:text-gray-400 italic p-3">
                                        No {{ selectedMenu === 'active' ? 'active' : 'completed' }} payouts for this
                                        batch
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

// Menu items
const menuItems = [
    { name: "Active Payouts", key: "active" },
    { name: "Payout History", key: "history" }
];

// Selected menu state
const selectedMenu = ref('active');

// Method to select menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

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

// Combined data structure for both active and historical payouts
const combinedPayoutData = computed(() => {
    const result = {};

    // Process all scholarships
    props.scholarships.forEach(scholarship => {
        const scholarshipId = scholarship.id;

        // Initialize scholarship data structure
        result[scholarshipId] = {
            id: scholarshipId,
            name: scholarship.name || 'Unknown Scholarship',
            type: scholarship.scholarshipType || 'N/A',
            campuses: {},
            hasActivePayouts: false,
            hasInactivePayouts: false
        };

        // Get all batches for this scholarship
        const scholarshipBatches = props.batches.filter(batch =>
            batch.scholarship_id === scholarshipId
        );

        // Process campuses for this scholarship
        const uniqueCampusIds = [...new Set(scholarshipBatches.map(batch => batch.campus_id))];

        uniqueCampusIds.forEach(campusId => {
            // Initialize campus structure
            result[scholarshipId].campuses[campusId] = {
                id: campusId,
                name: getCampusName(campusId),
                batchPayouts: []
            };

            // Get batches for this campus
            const campusBatches = scholarshipBatches.filter(batch =>
                batch.campus_id === campusId
            );

            // Process each batch for this campus
            campusBatches.forEach(batch => {
                const batchId = batch.id;

                // Find all payouts related to this batch through disbursements
                const relatedPayouts = props.payouts.filter(payout => {
                    return props.disbursements.some(d =>
                        d.payout_id === payout.id &&
                        d.batch_id === batchId
                    );
                });

                // Skip if no payouts for this batch
                if (relatedPayouts.length === 0) return;

                // Create batch payout structure
                const batchPayout = {
                    batchId: batchId,
                    batchNo: batch.batch_no,
                    schoolYear: batch.school_year.year,
                    semester: batch.semester,
                    totalScholars: batch.total_scholars,
                    active: [], // Active payouts
                    completed: [] // Completed payouts
                };

                // Sort payouts into active and completed
                relatedPayouts.forEach(payout => {
                    const payoutData = {
                        id: payout.id,
                        dateRange: `${formatDate(payout.date_start)} - ${formatDate(payout.date_end)}`,
                        status: payout.status,
                        schedule: getPayoutSchedule(payout.id),
                        amount: formatCurrency(payout.sub_total),
                        claimStats: {
                            claimed: getClaimCount(payout.id, batchId, 'Claimed'),
                            notClaimed: getClaimCount(payout.id, batchId, 'Not Claimed'),
                            percentage: getClaimPercentage(payout.id, batchId)
                        },
                        completedDate: payout.status === 'Inactive' ?
                            formatDate(payout.updated_at) : null
                    };

                    // Add to appropriate array based on status
                    if (payout.status === 'Pending' || payout.status === 'Active') {
                        batchPayout.active.push(payoutData);
                        result[scholarshipId].hasActivePayouts = true;
                    } else if (payout.status === 'Inactive') {
                        batchPayout.completed.push(payoutData);
                        result[scholarshipId].hasInactivePayouts = true;
                    }
                });

                // Only add batch if it has payouts
                if (batchPayout.active.length > 0 || batchPayout.completed.length > 0) {
                    result[scholarshipId].campuses[campusId].batchPayouts.push(batchPayout);
                }
            });

            // Remove campus if no batch payouts
            if (result[scholarshipId].campuses[campusId].batchPayouts.length === 0) {
                delete result[scholarshipId].campuses[campusId];
            }
        });

        // Remove scholarship if no campuses
        if (Object.keys(result[scholarshipId].campuses).length === 0) {
            delete result[scholarshipId];
        }
    });

    return result;
});

// Filtered data based on the selected menu tab
const filteredPayoutData = computed(() => {
    const filtered = {};

    Object.entries(combinedPayoutData.value).forEach(([scholarshipId, scholarship]) => {
        // For 'active' tab, only include scholarships with active payouts
        // For 'history' tab, only include scholarships with inactive payouts
        if ((selectedMenu.value === 'active' && scholarship.hasActivePayouts) ||
            (selectedMenu.value === 'history' && scholarship.hasInactivePayouts)) {

            filtered[scholarshipId] = {
                ...scholarship,
                campuses: {}
            };

            // Filter campuses and batches
            Object.entries(scholarship.campuses).forEach(([campusId, campus]) => {
                filtered[scholarshipId].campuses[campusId] = {
                    ...campus,
                    batchPayouts: campus.batchPayouts.filter(batchPayout => {
                        // For active tab, only include batches with active payouts
                        // For history tab, only include batches with completed payouts
                        return (selectedMenu.value === 'active' && batchPayout.active.length > 0) ||
                            (selectedMenu.value === 'history' && batchPayout.completed.length > 0);
                    })
                };

                // Remove campus if no batch payouts after filtering
                if (filtered[scholarshipId].campuses[campusId].batchPayouts.length === 0) {
                    delete filtered[scholarshipId].campuses[campusId];
                }
            });

            // Remove scholarship if no campuses after filtering
            if (Object.keys(filtered[scholarshipId].campuses).length === 0) {
                delete filtered[scholarshipId];
            }
        }
    });

    return filtered;
});

// Active payouts count
const activePayoutCount = computed(() => {
    let count = 0;

    Object.values(combinedPayoutData.value).forEach(scholarship => {
        Object.values(scholarship.campuses).forEach(campus => {
            campus.batchPayouts.forEach(batchPayout => {
                count += batchPayout.active.length;
            });
        });
    });

    return count;
});

// Completed payouts count
const completedPayoutCount = computed(() => {
    let count = 0;

    Object.values(combinedPayoutData.value).forEach(scholarship => {
        Object.values(scholarship.campuses).forEach(campus => {
            campus.batchPayouts.forEach(batchPayout => {
                count += batchPayout.completed.length;
            });
        });
    });

    return count;
});
</script>


<style scoped>
/* Animation for empty state */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>