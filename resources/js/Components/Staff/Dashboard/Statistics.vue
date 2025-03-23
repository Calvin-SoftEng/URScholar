<template>
   <!-- Content Container -->
<div class="w-full h-full justify-center items-center flex flex-col">
    
    <!-- Card -->
    <div class="flex flex-col flex-grow">
        
        <!-- Statistics Grid (2x2) - Full Space Usage -->
        <div class="flex flex-wrap gap-3 w-full flex-grow">

            <!-- Box 1 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">120</p>
                <p class="text-gray-600 dark:text-gray-300">University Students</p>
            </div>

            <!-- Box 2 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">45</p>
                <p class="text-gray-600 dark:text-gray-300">Total Scholars</p>
            </div>

            <!-- Box 3 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
                <p class="text-2xl font-bold text-primary">75</p>
                <p class="text-gray-600 dark:text-gray-300">Scholarship Sponsors</p>
            </div>

            <!-- Box 4 -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow">
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