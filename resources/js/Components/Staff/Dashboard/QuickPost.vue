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
                <!-- <div class="flex gap-2">
                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                        <span class="material-symbols-rounded text-[18px] me-2">school</span>
                        Share to University
                    </button>
                    
                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                        <span class="material-symbols-rounded text-[18px] me-2">groups</span>
                        Share to Group
                    </button>
                </div> -->

                <button @click="togglePost()" class="text-white font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Share a Post
                </button>
            </div>
        </div>
    </div>

    <!-- creating a sponsor --> 
    <div v-if="isCreating"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-6/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />
                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Share announcement
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Select where to post your message.
                            </span>
                        </div>
                    </div>

                    <!-- Close Button -->
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
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

const isCreating = ref(false);

const togglePost = () => {
    isCreating.value = !isCreating.value;
    if (isCreating.value) {
        resetForm();
    }
    initFlowbite(); // Initialize Flowbite first
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};
</script>