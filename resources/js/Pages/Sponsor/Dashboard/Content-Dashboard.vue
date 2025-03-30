<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 py-6 px-20 h-full">
            <!-- Small Card 1 (1 Col) -->
            <div class="bg-white shadow-md p-6 rounded-lg flex flex-col justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Small Card 1</h2>
                <p class="text-sm text-gray-500">This card takes 1 column.</p>
            </div>

            <!-- Small Card 2 (1 Col) -->
            <div class="bg-white shadow-md p-6 rounded-lg flex flex-col justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Small Card 2</h2>
                <p class="text-sm text-gray-500">This card also takes 1 column.</p>
            </div>

            <!-- Tall Card (3rd Col, Spanning 2 Rows) -->
            <div class="bg-white shadow-md p-6 rounded-lg flex flex-col justify-between lg:row-span-2">
                <h2 class="text-lg font-semibold text-gray-800">Tall Card</h2>
                <p class="text-sm text-gray-500">This card spans 2 rows.</p>
            </div>

            <!-- Wide Card (Bottom, 2 Cols Wide) -->
            <div class="bg-white shadow-md p-6 rounded-lg flex flex-col justify-between sm:col-span-2">
                <ScholarsTable />
            </div>
            
        </div>

    </div>

</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ScholarsTable from '@/Components/Sponsor/Dashboard/ScholarsTable.vue';

const components = {
    ScholarsTable
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
