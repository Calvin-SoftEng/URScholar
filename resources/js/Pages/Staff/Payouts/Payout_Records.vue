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
                <div class="space-y-8">
                    <div v-for="payout in recentPayouts" :key="payout.id"
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
                                <p class="text-sm text-gray-600">
                                    Total Amount: {{ payout.subTotal }}
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
                                            {{ batch.school_year }} - {{ batch.semester }}
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p>Scholars: {{ batch.total_scholars || 'N/A' }}</p>
                                        <p>Amount: {{ formatCurrency(batch.sub_total) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout History Section -->
            <div v-if="selectedMenu === 'history'" class="p-6 h-full">
                <div class="space-y-8">
                    <div v-for="historyItem in completedPayouts" :key="historyItem.id"
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

// Helper function to format date
const formatDate = (dateString) => {
    if (!dateString) return 'No Date';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
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

// Consolidated function to get payout details
const getPayoutDetails = (payout) => {
    // Find the associated scholarship
    const scholarship = props.scholarships.find(
        s => s.id === payout.scholarship_id
    ) || {};

    // Return a consolidated object with all relevant details
    return {
        // Payout details
        id: payout.id,
        dateStart: formatDate(payout.date_start),
        dateEnd: formatDate(payout.date_end),
        status: payout.status,
        subTotal: formatCurrency(payout.sub_total),
        totalScholars: payout.total_scholars || 0,

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
</script>

<style scoped>
/* Add any custom styles here */
</style>