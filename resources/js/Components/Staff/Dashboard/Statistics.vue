<template>
   <!-- Content Container -->
<div class="w-full h-full justify-center items-center flex flex-col">
    
    <!-- Card -->
    <div class="flex flex-col flex-grow">
        
        <!-- Statistics Grid (2x2) - Full Space Usage -->
        <div class="flex flex-wrap gap-3 w-full flex-grow">

            <!-- Box 1 -->
            <div class="w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-4xl font-bold dark:text-lprimary">{{scholarships.length}}</p>
                <p class="text-gray-600 dark:text-gray-300">Active Scholarship Sponsors</p>
                
            </div>

            <!-- Box 2 -->
            <div class="w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-4xl font-bold dark:text-lprimary">{{active_scholars.length}}</p>
                <p class="text-gray-600 dark:text-gray-300">Active Scholars</p>
            </div>
            

            <!-- Box 3 -->
            <div class="w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-4xl font-bold dark:text-lprimary">12</p>
                <p class="text-gray-600 dark:text-gray-300">University Students</p>
            </div>

            <!-- Box 4 -->

        </div>

        <!-- Buttons Container (Stays at the Bottom) -->
        <div class="w-full flex flex-row justify-between items-center">
            <!-- Buttons or actions go here -->
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
    },
    active_scholars: {
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