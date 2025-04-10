<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-5 gap-3 px-4 py-4 h-full">
            <!-- Main Content (100%) -->
            <div class="col-span-5 h-full flex flex-col gap-3">

                <!-- Top Layer: 4 Cards -->
                <div class="grid grid-cols-4 gap-3 flex-none h-[40%]">
                <div class="h-full flex flex-col">
                    <Statistics :scholarships="scholarships" :active_scholars="active_scholars" />
                </div>
                <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer flex flex-col min-h-0 flex-shrink-0">
                    <ScholarsStatistics />
                </div>
                <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer flex flex-col min-h-0 flex-shrink-0">
                    <ActiveScholarship :sponsors="sponsors" :scholarships="scholarships" />
                </div>
                <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer flex flex-col min-h-0 flex-shrink-0">
                    <Date_Data />
                </div>
                </div>

                <!-- Bottom Layer: 80/20 split -->
                <div class="grid grid-cols-4 gap-3 flex-none h-[60%] pb-2">
                <!-- 80% content -->
                <div class="col-span-3 bg-white box-border rounded shadow-md dark:bg-dcontainer flex flex-col flex-grow overflow-hidden">
                    <ScholarshipActivities :sponsors="sponsors" :scholarships="scholarships" :scholars="scholars" />
                </div>

                <!-- 20% content -->
                <div class="col-span-1 bg-white rounded-xl shadow-md dark:bg-dcontainer flex flex-col">
                    <Calendar_Activities />
                </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Staff/Dashboard/ActiveScholarship.vue';
import Statistics from '../../../Components/Staff/Dashboard/Statistics.vue';
import ScholarsStatistics from '@/Components/Staff/Dashboard/ScholarsStatistics.vue';
// import Calendar from '../../../Components/Staf/Dashboard/Calendar.vue';
import Messages from '@/Components/Staff/Dashboard/Messages.vue';
import ScholarshipActivities from '@/Components/Staff/Dashboard/ScholarshipActivities.vue';
import Calendar_Activities from '@/Components/Staff/Dashboard/Calendar_Activities.vue';
import Date_Data from '@/Components/Staff/Dashboard/Date_Data.vue';

const components = {
    ActiveScholarship,
    Statistics,
    Messages,
    ScholarsStatistics,
    Calendar_Activities,
    Date_Data
    // Calendar,
};

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
    active_scholars: Array,
});

</script>

<style></style>