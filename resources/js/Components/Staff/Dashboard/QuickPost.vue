<template>
    <!-- Content Container -->
    <div class="bg-white w-full h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        <span class="font-poppins font-semibold text-xl dark:text-dtext">Announce to Everyone</span>
        
        <!-- Card -->
        <div class="flex flex-col flex-grow gap-3">
            <!-- Message Container (Expands to fill space) -->
            <div class="flex-grow w-full">
                <textarea class="w-full h-full resize-none p-2 bg-transparent border-none outline-none focus:ring-0" 
                    placeholder="Write your announcement here..."></textarea>
            </div>



            <!-- Buttons Container (Stays at the Bottom) -->
            <div class="w-full flex flex-row justify-between items-center">
                <div class="flex gap-2">
                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                        <span class="material-symbols-rounded text-[18px] me-2">school</span>
                        Share to University
                    </button>
                    
                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                        <span class="material-symbols-rounded text-[18px] me-2">groups</span>
                        Share to Group
                    </button>
                </div>

                <button class="text-white font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Share a Post
                </button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

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