<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-10 gap-3 py-6 px-20 h-full">
            <!-- Left Column (30% width) -->
            <div class="sm:col-span-1 lg:col-span-3 flex flex-col gap-3">
                <!-- Small Card 1 -->
                <div class="h-full">
                    <ScholarsSupported />
                </div>

                <!-- Small Card 2 -->
                <div class="h-full">
                    <TotalScholarsSupported />
                </div>

                <!-- Disbursements Card -->
                <div class="bg-white shadow-md rounded-lg">
                    <Disbursements />
                </div>
            </div>

            <!-- Right Column (70% width) -->
            <div class="sm:col-span-2 lg:col-span-2 bg-white shadow-md p-6 rounded-lg flex flex-col justify-between">
                <ScholarsTable />
            </div>
        </div>


    </div>

</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ScholarsTable from '@/Components/Sponsor/Dashboard/ScholarsTable.vue';
import ScholarsSupported from '@/Components/Sponsor/Dashboard/ScholarsSupported.vue';
import TotalScholarsSupported from '@/Components/Sponsor/Dashboard/TotalScholarsSupported.vue';
import Disbursements from '@/Components/Sponsor/Dashboard/Disbursements.vue';

const components = {
    ScholarsTable,
    ScholarsSupported,
    TotalScholarsSupported,
    Disbursements,
    // ActiveScholarship,
    // QuickPost,
    // Messages,
    // Calendar,
};

// const props = defineProps({
//     scholarships: Array,
//     sponsors: Array,
//     scholars: Array,
// });

</script>
