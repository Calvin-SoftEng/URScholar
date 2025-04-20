<template>
    <div
        class="bg-white w-full min-h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        <span class="font-poppins font-semibold text-xl dark:text-dtext">Disbursements by Campus</span>

        <div class="flex flex-col gap-3 flex-grow h-full relative">
            <!-- Campuses List -->
            <div
                class="max-h-[calc(60vh-10px)] h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded flex flex-col justify-start gap-4">
                <!-- Display for each campus -->
                <div v-for="campus in campusList" :key="campus.id"
                    class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                    <!-- Campus Header -->
                    <div
                        class="flex justify-between items-center mb-3 border-b border-gray-200 dark:border-gray-600 pb-2">
                        <h3 class="font-semibold text-lg">{{ campus.name }}</h3>
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            {{ getCampusStats(campus.id).total }} Disbursements
                        </span>
                    </div>

                    <!-- Campus Disbursements List -->
                    <div class="space-y-2">
                        <div v-for="payout in getCampusPayouts(campus.id)" :key="payout.id"
                            class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg hover:shadow-xl transition-all duration-300 justify-between items-center text-white dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                            <div class="flex flex-col">
                                <span class="font-semibold text-lg">{{ payout.scholarship.name }}</span>
                                <div class="flex flex-row gap-1">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                        {{ payout.semester }}
                                    </span>
                                    <span class="font-normal text-sm">
                                        {{ payout.claimed_count }}/{{ payout.total_scholars }} Claimed
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-row items-center gap-2">
                                <span v-if="payout.status === 'Active'" class="text-green-300">
                                    <font-awesome-icon :icon="['fas', 'circle-check']" />
                                </span>
                                <span v-else-if="payout.status === 'Pending'" class="text-yellow-300">
                                    <font-awesome-icon :icon="['fas', 'clock']" />
                                </span>
                                <span class="text-sm font-medium">{{ formatDate(payout.date_end) }}</span>
                            </div>
                        </div>

                        <!-- Empty state for campus with no payouts -->
                        <div v-if="getCampusPayouts(campus.id).length === 0"
                            class="text-center py-4 text-gray-500 dark:text-gray-400">
                            No disbursements available for this campus
                        </div>
                    </div>

                    <!-- Summary Stats for this Campus -->
                    <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600 flex justify-between text-sm">
                        <div>
                            <span class="font-medium">Total Claimed: </span>
                            <span class="text-green-600 dark:text-green-400">{{ getCampusStats(campus.id).claimed
                                }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Pending: </span>
                            <span class="text-yellow-600 dark:text-yellow-400">{{ getCampusStats(campus.id).pending
                                }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Not Claimed: </span>
                            <span class="text-red-600 dark:text-red-400">{{ getCampusStats(campus.id).notClaimed
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    payouts: Array,
    campuses: Array,
    scholarships: Array
});

// Computed properties
const campusList = computed(() => {
    // Get unique campuses from payouts
    const uniqueCampuses = [];
    const campusIds = new Set();

    props.payouts.forEach(payout => {
        if (!campusIds.has(payout.campus.id)) {
            campusIds.add(payout.campus.id);
            uniqueCampuses.push(payout.campus);
        }
    });

    return uniqueCampuses;
});

// Methods
const getCampusPayouts = (campusId) => {
    return props.payouts.filter(payout => payout.campus_id === campusId);
};

const getCampusStats = (campusId) => {
    const campusPayouts = getCampusPayouts(campusId);

    let claimed = 0;
    let pending = 0;
    let notClaimed = 0;
    let total = 0;

    campusPayouts.forEach(payout => {
        claimed += payout.claimed_count || 0;
        pending += payout.pending_count || 0;
        notClaimed += payout.not_claimed_count || 0;
        total += payout.total_scholars || 0;
    });

    return { claimed, pending, notClaimed, total };
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};
</script>