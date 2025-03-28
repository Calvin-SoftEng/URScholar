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

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Payout Records
                    </h1>
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
            <div v-if="selectedMenu === 'recent'" class="grid grid-cols-[15%_85%] gap-6 p-6 h-full justify-center">
                <div class="w-full">
                    <span class="text-gray-700 font-medium">Date</span>
                </div>

                <div class="relative block">
                    <div v-for="payout in recentPayouts" :key="payout.id"
                        class="bg-white p-5 rounded-lg shadow-md relative mb-4">
                        <span
                            class="absolute -top-3 right-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">
                            Pending
                        </span>
                        <p class="text-lg font-semibold text-red-500">
                            {{ payout.scholarshipName }}
                        </p>
                        <p class="text-lg font-semibold text-red-500">
                            {{ payout.batchNumber }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Expected on:
                            <span v-if="payout.dateEnd">
                                {{ formatDate(payout.dateEnd) }}
                            </span>
                            <span v-else>No Deadline</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payout History Section -->
            <div v-if="selectedMenu === 'history'" class="grid grid-cols-[15%_85%] gap-6 p-6 h-full justify-center">
                <div class="w-full">
                    <span class="text-gray-700 font-medium">Date</span>
                </div>

                <div class="relative block">
                    <div v-for="historyItem in completedPayouts" :key="historyItem.id"
                        class="bg-white p-5 rounded-lg shadow-md relative mb-4">
                        <span
                            class="absolute -top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            Accomplished
                        </span>
                        <p class="text-lg font-semibold text-red-500">
                            {{ scholarshipName(historyItem.scholarship_id) }}
                        </p>
                        <p class="text-lg font-semibold text-red-500">
                            {{ batchNumber(historyItem.scholarship_id) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ formatDate(historyItem.date_end) }}
                        </p>
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

// Helper method to get scholarship name
const scholarshipName = (scholarshipId) => {
    const scholarship = props.scholarships.find(s => s.id === scholarshipId);
    return scholarship ? scholarship.name : 'Unknown Scholarship';
};

// Helper method to get batch number
const batchNumber = (scholarshipId) => {
    const scholarship = props.scholarships.find(s => s.id === scholarshipId);
    const batch = props.batches.find(b => b.id === scholarship?.batch_id);
    return batch ? batch.batch_no : 'Unknown Batch';
};


// Consolidated function to get payout details
const getPayoutDetails = (payout) => {
    // Find the associated scholarship
    const scholarship = props.scholarships.find(
        s => s.id === payout.scholarship_id
    ) || {};

    // Find the associated batch
    const batch = props.batches.find(
        b => b.id === scholarship.batch_id
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
        scholarshipName: scholarship.name || 'Unknown Scholarship',
        scholarshipType: scholarship.scholarshipType || 'N/A',

        // Batch details
        batchNumber: batch.batch_no || 'Unknown Batch',

        // Derived display properties
        displayName: `${scholarship.name || 'Scholarship'} - ${batch.batch_no || 'Batch'}`,
        isRecent: payout.status === 'Pending' || payout.status === 'Active',
        isCompleted: payout.status === 'Completed' || payout.status === 'Inactive'
    };
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
    return amount
        ? new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(amount)
        : '₱0.00';
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
/* Existing styles remain the same */
</style>