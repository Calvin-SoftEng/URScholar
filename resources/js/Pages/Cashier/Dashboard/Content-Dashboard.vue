<template>
    <div class="w-full h-full bg-dirtywhite dark:text-dprimary">
        <div class="grid grid-cols-4 gap-3 px-3 py-3 h-full">
            <!-- 75% Column -->
            <div class="col-span-3 h-full flex flex-col space-y-3">
                <!-- First Layer with 2 Column Cards -->
                <div class="grid grid-cols-2 gap-3 h-[35%]">
                    <div class="h-full w-full">
                        <Statistics />
                    </div>
                    <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer">
                        <ActivePayouts :sponsors="sponsors" :scholarships="scholarships" />
                    </div>
                </div>
                <!-- Second Layer with Single Card -->
                <div class="bg-white box-border rounded shadow-md h-[65%] dark:bg-dcontainer">
                    <ScholarPayouts :sponsors="sponsors" :scholarships="scholarships" />
                </div>
            </div>
            <!-- 25% Column -->
            <div class="w-full h-full flex flex-col space-y-3">
                <div class="h-full rounded-xl shadow-md bg-white dark:bg-dcontainer">
                    <Messages />
                </div>
                <div class="bg-white rounded shadow-md h-[65%] dark:bg-dcontainer">
                    <Calendar_Activities />
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Staff/Dashboard/ActiveScholarship.vue';
import ActivePayouts from '../../../Components/Cashier/Dashboard/ActivePayouts.vue';
import Messages from '@/Components/Cashier/Dashboard/Messages.vue';
import ScholarPayouts from '@/Components/Cashier/Dashboard/ScholarPayouts.vue';
import Calendar_Activities from '@/Components/Cashier/Dashboard/Calendar_Activities.vue';
import Statistics from '@/Components/Cashier/Dashboard/Statistics.vue';
// import Calendar from '../../../Components/Staf/Dashboard/Calendar.vue';

const components = {
    ActiveScholarship,
    ActivePayouts,
    Messages,
    ScholarPayouts,
    Calendar_Activities,
    Statistics,
    // Calendar,
};

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    }
});

</script>

<style>

</style>