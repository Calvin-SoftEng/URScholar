<template>
   <!-- Content Container -->
<div class="bg-white w-full h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
    
    <!-- Card -->
    <div class="flex flex-col flex-grow gap-3">
        
        <!-- Statistics Grid (2x2) - Full Space Usage -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full flex-grow">

            <!-- Box 1 -->
            <div class="bg-gray-100 dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">120</p>
                <p class="text-gray-600 dark:text-gray-300">University Students</p>
            </div>

            <!-- Box 2 -->
            <div class="bg-gray-100 dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">45</p>
                <p class="text-gray-600 dark:text-gray-300">Total Scholars</p>
            </div>

            <!-- Box 3 -->
            <div class="bg-gray-100 dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">75</p>
                <p class="text-gray-600 dark:text-gray-300">Scholarship Sponsors</p>
            </div>

            <!-- Box 4 -->
            <div class="bg-gray-100 dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">15</p>
                <p class="text-gray-600 dark:text-gray-300">URScholar Users</p>
            </div>

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