<template>
    <!-- Messages Container -->
    <div class="bg-white w-full min-h-full p-1 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        
        <!-- Messages Title -->
        <span class="font-poppins font-semibold text-xl dark:text-dtext pt-4 px-4">Messages</span>

        <!-- Search Bar (Fixed at the Top) -->
        <form class="w-full mt-3 px-2">
            <label for="chat-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-400" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="chat-search" placeholder="Search group chats and scholars"
                    class="w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-dprimary dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            </div>
        </form>

        <!-- Scrollable Messages List -->
        <div class="flex flex-col flex-grow mt-3 overflow-hidden">
            <div class="h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded">
                    
                    <!-- People / Group Chats List -->
                    <ul class="w-full space-y-2 text-primary dark:text-dtext">
                        <li class="flex items-center space-x-3 p-3 rounded-md hover:bg-gray-100 dark:hover:bg-dprimary transition-all cursor-pointer">
                            <img src="https://placehold.co/50" alt="Person" class="h-8 w-8 rounded-full border border-gray-300" />
                            <span class="text-dprimary dark:text-dtext !important font-quicksand font-semibold text-sm">John Doe Dimacatacutan</span>
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


</script>