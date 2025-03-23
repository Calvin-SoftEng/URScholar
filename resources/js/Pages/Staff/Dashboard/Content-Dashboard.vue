<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-5 gap-3 px-4 py-4 h-full">
            <!-- Main Content (80%) -->
            <div class="col-span-4 h-full flex flex-col space-y-3 gap-3">
                <!-- Top Layer: 3 Cards -->
                <div class="grid grid-cols-3 gap-3 flex-none h-[40%]">
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer p-1 flex flex-col">
                        <QuickPost />
                    </div>
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer p-1 flex flex-col">
                        <ScholarsStatistics />
                    </div>
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer p-1 flex flex-col">
                        <ActiveScholarship :sponsors="sponsors" :scholarships="scholarships" />
                    </div>
                </div>

                <!-- Bottom Layer: Expanding Section -->
                <div class="bg-white box-border rounded shadow-md flex-grow dark:bg-dcontainer p-1 flex flex-col">
                    <ScholarshipActivities :sponsors="sponsors" :scholarships="scholarships" :scholars="scholars"/>
                </div>
            </div>

            <!-- Sidebar (20%) -->
            <div class="col-span-1 h-full flex flex-col space-y-4">
                <div class="h-[60%] rounded-xl shadow-md bg-white dark:bg-dcontainer p-1">
                    <!-- <Calendar /> -->
                    <Messages />
                </div>
                <div class="h-[40%] bg-white rounded-xl shadow-md dark:bg-dcontainer p-1">
                    <Calendar_Activities />

                </div>
            </div>
        </div>

    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Staff/Dashboard/ActiveScholarship.vue';
import QuickPost from '../../../Components/Staff/Dashboard/Statistics.vue';
import ScholarsStatistics from '@/Components/Staff/Dashboard/ScholarsStatistics.vue';
// import Calendar from '../../../Components/Staf/Dashboard/Calendar.vue';
import Messages from '@/Components/Staff/Dashboard/Messages.vue';
import ScholarshipActivities from '@/Components/Staff/Dashboard/ScholarshipActivities.vue';
import Calendar_Activities from '@/Components/Staff/Dashboard/Calendar_Activities.vue';

const components = {
    ActiveScholarship,
    QuickPost,
    Messages,
    ScholarsStatistics,
    Calendar_Activities
    // Calendar,
};

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
    scholars: Array,
});

</script>

<style></style>