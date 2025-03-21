<template>
    <!-- Messages Container -->
    <div class="bg-white w-full min-h-full p-1 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        
        <!-- Messages Title -->
        <span class="font-poppins font-semibold text-xl dark:text-dtext pt-4 px-4">Activities</span>

        <!-- Scrollable Messages List -->
        <div class="flex flex-col flex-grow mt-3 overflow-hidden">
            <div class="h-fit overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded">
                    
                    <!-- People / Group Chats List -->
                    <ul class="w-full space-y-2 px-3">
                        <li v-for="(activity, index) in activities" :key="index" class="flex flex-col p-3 rounded-md bg-white shadow-sm border border-gray-200 dark:bg-dcontainer dark:border-gray-600">
                        <span class="text-primary font-quicksand font-semibold text-sm">{{ activity.title }}</span>
                        <span class="text-gray-500 text-xs">{{ activity.date }}</span>
                        </li>
                        
                    </ul>
            </div>
        </div>
    </div>




</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
});

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};

// Sample Activities Data
const activities = ref([
  { title: "Scholarship Application Deadline", date: "March 25, 2025" },
  { title: "Payout Release", date: "April 5, 2025" },
  { title: "Document Submission Deadline", date: "April 10, 2025" },
  { title: "Final Interview for Scholars", date: "April 15, 2025" },
]);
</script>