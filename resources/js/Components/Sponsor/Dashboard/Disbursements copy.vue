<template>
    <div class="bg-white w-full min-h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
    
    <span class="font-poppins font-semibold text-xl dark:text-dtext">Disbursements</span>

    <!-- Scholarship List Container -->
    <div class="flex flex-col gap-3 flex-grow h-full relative">
        
        <!-- Show "No Active Scholarships" Card if Empty -->
        <!-- <div v-if="scholarships.length === 0" class="flex items-center justify-center h-40 bg-gray-100 dark:bg-gray-700 rounded-xl p-4">
            <span class="text-gray-500 dark:text-gray-300 text-sm">No active scholarships available.</span>
        </div> -->

            <!-- Scrollable Scholarships -->
            <!-- <div v-else class="max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded flex flex-col justify-start gap-2">
                <div v-for="scholarship in scholarships" :key="scholarship.id" class="space-y-1">
                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                {{ scholarship.name }}
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                    {{ getSponsorDetails(scholarship.sponsor_id).abbreviation }}
                                </span>

                                <span class="font-normal text-sm">
                                    {{ scholarship.scholarshipType }} Scholarship
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <span v-if="scholarship.status === 'Active'" 
                                class="w-3 h-3 bg-green-500 rounded-full shadow-md">
                            </span>

                            <span class="text-sm font-medium">
                                {{ scholarship.status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="max-h-64 h-full overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer scrollbar-thumb-rounded flex flex-col justify-start gap-2">
                <div class="space-y-1">
                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                Tulong DUnong
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                  What Campus
                                </span>

                                <span class="font-normal text-sm">
                                    90/90 Completed
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <!-- Check Icon -->
                            <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-500" />

                            <span class="text-sm font-medium">
                                Sept 19, 2023
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                Tulong DUnong
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                  What Campus
                                </span>

                                <span class="font-normal text-sm">
                                    90/90 Completed
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <!-- Check Icon -->
                            <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-500" />

                            <span class="text-sm font-medium">
                                Sept 19, 2023
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                Tulong DUnong
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                  What Campus
                                </span>

                                <span class="font-normal text-sm">
                                    90/90 Completed
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <!-- Check Icon -->
                            <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-500" />

                            <span class="text-sm font-medium">
                                Sept 19, 2023
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                Tulong DUnong
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                  What Campus
                                </span>

                                <span class="font-normal text-sm">
                                    90/90 Completed
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <!-- Check Icon -->
                            <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-500" />

                            <span class="text-sm font-medium">
                                Sept 19, 2023
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                        hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                        dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                    
                        <div class="flex flex-col">
                            <span class="font-semibold text-lg">
                                Tulong DUnong
                            </span>
                            <div class="flex flex-row gap-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2 py-0.5 rounded-sm 
                                    dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                  What Campus
                                </span>

                                <span class="font-normal text-sm">
                                    90/90 Completed
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-row items-center gap-2">
                            <!-- Check Icon -->
                            <font-awesome-icon :icon="['fas', 'circle-check']" class="text-green-500" />

                            <span class="text-sm font-medium">
                                Sept 19, 2023
                            </span>
                        </div>

                    </div>
                </div>
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