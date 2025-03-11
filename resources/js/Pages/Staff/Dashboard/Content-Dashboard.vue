<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-4 gap-3 px-3 py-3 h-full">
            <!-- 75% Column -->
            <div class="col-span-3 h-full flex flex-col space-y-3">
                <!-- First Layer with 2 Column Cards -->
                <div class="grid grid-cols-2 gap-3 h-[35%]">
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer">
                        <QuickPost />
                    </div>
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer">
                        <ActiveScholarship :sponsors="sponsors" :scholarships="scholarships" />
                    </div>
                </div>
                <!-- Second Layer with Single Card -->
                <div class="bg-white box-border rounded shadow-md h-[65%] dark:bg-dcontainer">
                    <!-- <ContentComponent /> -->
                    <ScholarshipActivities />
                </div>
            </div>
            <!-- 25% Column -->
            <div class="w-full h-full flex flex-col space-y-3">
                <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer">
                    <!-- <Calendar /> -->
                    <Messages />
                </div>
                <div class="bg-white rounded shadow-md h-[65%] dark:bg-dcontainer">

                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Staff/Dashboard/ActiveScholarship.vue';
import QuickPost from '../../../Components/Staff/Dashboard/QuickPost.vue';
// import Calendar from '../../../Components/Staf/Dashboard/Calendar.vue';
import Messages from '@/Components/Staff/Dashboard/Messages.vue';
import ScholarshipActivities from '@/Components/Staff/Dashboard/ScholarshipActivities.vue';

const components = {
    ActiveScholarship,
    QuickPost,
    Messages,
    // Calendar,
};

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
});

</script>

<style></style>