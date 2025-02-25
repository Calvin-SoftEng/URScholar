<template>
    <div class="w-full h-full dark:text-dprimary">
        <div class="grid grid-cols-4 gap-3 px-3 py-3 h-full">
            <!-- 75% Column -->
            <div class="col-span-3 h-full flex flex-col space-y-3">
                <!-- First Layer with 2 Column Cards -->
                <div class="grid grid-cols-2 gap-3 h-[35%]">
                <div class="h-full rounded-xl shadow-md dark:bg-dcontainer">
                    <QuickPost />
                </div>
                <div class="h-full rounded-xl shadow-md dark:bg-dcontainer">
                    <ActiveScholarship :sponsors="sponsors" :scholarships="scholarships" />
                </div>
                </div>
                <!-- Second Layer with Single Card -->
                <div class="bg-black box-border rounded h-[65%] dark:bg-dcontainer">
                <!-- <ContentComponent /> -->
                </div>
            </div>
            <!-- 25% Column -->
            <div class="w-full h-full flex flex-col space-y-3">
                <div class="h-full rounded-xl shadow-md dark:bg-dcontainer">
                    <Calendar />
                </div>
                <div class="bg-black rounded h-[65%] dark:bg-dcontainer"></div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

import ActiveScholarship from '../../../Components/Admin/Dashboard/ActiveScholarship.vue';
import QuickPost from '../../../Components/Admin/Dashboard/QuickPost.vue';
import Calendar from '../../../Components/Admin/Dashboard/Calendar.vue';

const components = {
    ActiveScholarship,
    QuickPost,
    Calendar,
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