<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout :branding="branding" :user="user" :auth="auth" :errors="errors">
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-10">
            <div>
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold dark:text-dtext">Academic School Year</h1>
                </div>

                <div v-if="scholar_year?.length">
                    <div v-for="year in [...scholar_year].reverse()" :key="year.id"
                        class="w-full h-full flex flex-col p-10 mt-5 gap-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 font-kanit text-white rounded-xl">

                        <div class="flex justify-between">
                            <div>
                                <span v-if="year.year" class="items-center justify-center font-bold text-3xl">
                                    {{ year.year }}
                                </span>
                                <span v-else class="text-red-500 font-bold text-3xl">Year Not Available</span>

                                <!-- Display selected status indicator -->
                                <span v-if="hasActiveAcademicYear(year)"
                                    class="ml-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs">
                                    Active
                                </span>
                            </div>

                            <!-- Hide toggle button if school year already has a 2nd semester -->
                            <div class="top-2 right-2" v-if="!hasSecondSemester(year)">
                                <button @click="toggleSet(year)"
                                    class="text-white hover:text-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 12h12M6 6h12M6 18h12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div v-if="year.academic_year?.length">
                            <div v-for="academic in year.academic_year" :key="academic.id">
                                <h1 class="font-poppins font-normal text-sm flex items-center">
                                    Semester: {{ formatSemester(academic.semester) || 'No Semester Assigned' }}
                                    <span v-if="academic.status === 'Active'"
                                        class="ml-2 text-green-400">(Current)</span>
                                </h1>
                            </div>
                        </div>
                        <div v-else>
                            <h1 class="font-normal text-sm text-yellow-400 font-poppins">No Academic Year Available</h1>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center text-gray-400">
                    <h1 class="text-lg font-poppins">No School Years Available</h1>
                </div>
            </div>
        </div>

        <div v-if="SchoolYear"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Set Academic Semester
                            </h2>
                        </div>
                    </div>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-6 max-w-lg mx-auto">
                    <!-- School Year Information -->
                    <div class="w-full flex flex-col space-y-4 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg">Selected School Year</h3>

                        <!-- School Year Display -->
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center">
                                <span class="text-gray-500 dark:text-gray-400 w-24">Year:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ selectedYear?.year || 'No Year Selected' }}</span>
                            </div>

                            <!-- Current Semesters -->
                            <div class="flex flex-col">
                                <span class="text-gray-500 dark:text-gray-400 mb-1">Current Semesters:</span>
                                <div v-if="selectedYear?.academic_year?.length" class="ml-4">
                                    <ul class="list-disc text-gray-700 dark:text-gray-300">
                                        <li v-for="academic in selectedYear.academic_year" :key="academic.id"
                                            class="flex items-center">
                                            {{ formatSemester(academic.semester) }}
                                            <span v-if="academic.status === 'Active'"
                                                class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                Active
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <p v-else class="text-yellow-600 dark:text-yellow-400 text-sm ml-4">
                                    No semesters created yet
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- New Semester Information -->
                    <div class="w-full flex flex-col space-y-4 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h3 class="font-semibold text-gray-900 dark:text-white">New Semester to Create</h3>

                        <div class="flex items-center">
                            <span class="text-gray-500 dark:text-gray-400 w-24">Semester:</span>
                            <span class="font-medium text-blue-600 dark:text-blue-400">
                                {{ getNextSemesterToCreate() }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                            The system will automatically determine which semester to create based on existing data.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90">
                            Create New Semester
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage} from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import CalendarPicker from "@/Components/MIS/CalendarPicker.vue";
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const selectedDate = ref({ month: "", day: "" });
const props = defineProps({
    scholar_year: Array,
    branding: Object,
});

const selectedYear = ref(null);
const SchoolYear = ref(false);
const statusValue = ref("");
const semester = ref("1st");

const toastVisible = ref(false);
const toastMessage = ref("");

watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        console.log("Showing toast with message:", flashMessage);
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            console.log("Hiding toast...");
            toastVisible.value = false;
        }, 3000);
    }
});

// Function to check if a school year already has a 2nd semester
const hasSecondSemester = (year) => {
    if (!year.academic_year || !year.academic_year.length) return false;
    return year.academic_year.some(academic => academic.semester === '2nd');
};

// Function to check if the school year has any active academic year
const hasActiveAcademicYear = (year) => {
    if (!year.academic_year || !year.academic_year.length) return false;
    return year.academic_year.some(academic => academic.status === 'Active');
};

// Format semester display
const formatSemester = (semester) => {
    if (semester === '1st') return 'First Semester';
    if (semester === '2nd') return 'Second Semester';
    return semester;
};

// Add this function to determine the next semester to create
const getNextSemesterToCreate = () => {
    if (!selectedYear.value?.academic_year || selectedYear.value.academic_year.length === 0) {
        return 'First Semester';
    }

    const hasFirstSemester = selectedYear.value.academic_year.some(academic => academic.semester === '1st');
    const hasSecondSemester = selectedYear.value.academic_year.some(academic => academic.semester === '2nd');

    if (hasFirstSemester && !hasSecondSemester) {
        return 'Second Semester';
    } else if (hasFirstSemester && hasSecondSemester) {
        return 'Both semesters already exist';
    } else {
        return 'First Semester';
    }
};

// Updated submitForm function
const submitForm = () => {
    // Check if both semesters already exist
    const hasFirstSemester = selectedYear.value?.academic_year?.some(academic => academic.semester === '1st');
    const hasSecondSemester = selectedYear.value?.academic_year?.some(academic => academic.semester === '2nd');

    if (hasFirstSemester && hasSecondSemester) {
        alert('Both semesters already exist for this school year');
        closeModal();
        return;
    }

    // Use router.post instead of axios
    router.post(route('sa.create-semester'), {
        schoolYearId: selectedYear.value?.id
    }, {
        onSuccess: (response) => {
        },
        onError: (errors) => {
            console.error('Error creating semester:', errors);
            alert('Failed to create semester. Please try again.');
        },
        onFinish: () => {
            closeModal();
        }
    });
};

// Updated toggleSet function
const toggleSet = (year) => {
    selectedYear.value = year;
    SchoolYear.value = true;
};

const closeModal = () => {
    SchoolYear.value = false;
};



</script>