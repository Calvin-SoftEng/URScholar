<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-5 gap-3 px-4 py-4 h-full">
            <!-- Main Content (100%) -->
            <div class="col-span-5 h-full flex flex-col gap-3 overflow-hidden">

            <!-- Top Layer: 4 Cards -->
            <div class="grid grid-cols-5 gap-3 flex-none">
                <div class="col-span-3 flex flex-row gap-3">

                    <!-- Statistics -->
                    <div class="flex-1 h-full flex flex-col">
                    <Statistics :univ_students="univ_students" :scholarships="scholarships" :active_scholars="active_scholars" />
                    </div>

                    <!-- Active Scholarships -->
                    <div class="w-[40%] h-full rounded-xl shadow-md bg-white dark:bg-dcontainer flex flex-col min-h-0 flex-shrink-0">
                    <ActiveScholarship :sponsors="sponsors" :scholarships="scholarships" />
                    </div>
                </div>

                <!-- Date Data -->
                <div class="col-span-2 h-full rounded-xl shadow-md bg-white dark:bg-dcontainer flex flex-col min-h-0 flex-shrink-0">
                    <Date_Data :academic_year="academic_year" />
                </div>
                </div>

                <!-- Bottom Layer: Activities -->
                <div class="grid grid-cols-5 gap-3 flex-1 min-h-0">

                <!-- Scholarship Activities (scrollable) -->
                <div class="col-span-5 bg-white box-border rounded shadow-md dark:bg-dcontainer flex flex-col overflow-hidden">
                    <ScholarshipActivities :disbursements:="disbursements" :sponsors="sponsors" :scholarships="scholarships" :scholars="scholars" />
                </div>

                <!-- Uncomment if needed -->
                <!-- 
                <div class="col-span-1 bg-white rounded-xl shadow-md dark:bg-dcontainer flex flex-col overflow-hidden">
                    <Calendar_Activities :activity_logs="activity_logs" />
                </div>
                -->
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Cashier/Dashboard/ActiveScholarship.vue';
import Statistics from '../../../Components/Cashier/Dashboard/Statistics.vue';
// import ScholarsStatistics from '@/Components/Cashier/Dashboard/ScholarsStatistics.vue';
// import Calendar from '../../../Components/Staf/Dashboard/Calendar.vue';
import Messages from '@/Components/Cashier/Dashboard/Messages.vue';
import ScholarshipActivities from '@/Components/Cashier/Dashboard/ScholarshipActivities.vue';
import Calendar_Activities from '@/Components/Cashier/Dashboard/Calendar_Activities.vue';
import Date_Data from '@/Components/Cashier/Dashboard/Date_Data.vue';

const components = {
    ActiveScholarship,
    Messages,
    Statistics,
    Calendar_Activities,
    Date_Data
    // Calendar,
};

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
    active_scholars: Array,
    activity_logs: Array,
    academic_year: Object,
    univ_students: Object,
    disbursements: Array,
});

</script>

<style></style>