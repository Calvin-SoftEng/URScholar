<template>
    <!-- Messages Container -->
    <div class="bg-white w-full h-full p-1 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        <!-- Messages Title -->
        <span class="font-poppins font-semibold text-xl dark:text-dtext pt-4 px-4">Activities</span>

        <!-- Scrollable Messages List -->
        <div class="flex flex-col flex-grow mt-3 overflow-hidden">
        <!-- Scrollable container -->
        <div
            class="flex-1 max-h-[27rem] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded dark:border-gray-600 rounded-lg px-3 py-2 space-y-2">
            
            <!-- Activity List -->
            <ul class="w-full space-y-1 ">
            <li
                v-for="(activity, index) in activity_logs"
                :key="index"
                class="flex flex-col p-3 rounded-md bg-white shadow-sm border border-gray-200 dark:bg-dcontainer dark:border-gray-600">
                <span class="text-dprimary font-semibold text-sm dark:text-dtext">
                {{ activity.activity }}
                </span>
                <span class="text-dprimary font-quicksand font-semibold text-sm dark:text-dtext">
                You {{ activity.description }}
                </span>
                <span class="text-gray-500 text-xs">
                {{ new Date(activity.created_at).toLocaleString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }) }}
                </span>
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
    activity_logs: Array,
});

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};


</script>