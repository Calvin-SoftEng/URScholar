<template>
   <!-- Content Container -->
<div class="w-full h-full justify-center items-center flex flex-col">
    
    <!-- Card -->
    <div class="flex flex-col flex-grow">
        
        <!-- Statistics Grid (2x2) - Full Space Usage -->
        <div class="flex flex-wrap gap-3 w-full flex-grow">

        <!-- Box 1: Sponsors (Icon Left) -->
        <div class="relative w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-3 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow overflow-hidden">
            <p class="text-5xl font-extrabold text-dprimary dark:text-dtext z-10">{{ scholarships.length }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300 tracking-wide uppercase z-10">Active Scholarship Sponsors</p>
            <img src="../../../../assets/images/sponsor_icon.png" alt="sponsor icon"
                class="absolute left-4 bottom-4 w-20 opacity-10 dark:opacity-20 pointer-events-none select-none" />
        </div>

        <!-- Box 2: Scholars (Icon Right) -->
        <div class="relative w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-3 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow overflow-hidden">
            <p class="text-5xl font-extrabold text-dprimary dark:text-dtext z-10">{{ active_scholars.length }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300 tracking-wide uppercase z-10">Active Scholars</p>
            <img src="../../../../assets/images/scholar_icon.png" alt="scholar icon"
                class="absolute right-4 bottom-4 w-20 opacity-10 dark:opacity-20 pointer-events-none select-none" />
        </div>

        <!-- Box 3: Students (Icon Left again) -->
        <div class="relative w-full bg-white dark:bg-dcontainer dark:border dark:border-gray-600 p-3 rounded-lg flex flex-col items-center justify-center shadow-md flex-grow overflow-hidden">
            <p class="text-5xl font-extrabold text-dprimary dark:text-dtext z-10">{{ univ_students }}</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300 tracking-wide uppercase z-10">University Students</p>
            <img src="../../../../assets/images/student_icon.png" alt="student icon"
                class="absolute left-4 bottom-4 w-20 opacity-10 dark:opacity-20 pointer-events-none select-none" />
        </div>

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
    },
    univ_students: Object,
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