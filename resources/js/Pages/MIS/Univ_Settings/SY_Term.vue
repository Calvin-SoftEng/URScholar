<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-10">
            <div>
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold dark:text-dtext">Academic School Year</h1>
                    <button 
                        @click="toggleSet" 
                        class="bg-blue-600 font-poppins text-white font-normal px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Add New School Year
                    </button>
                </div>


                <div v-if="scholar_year?.length">
                    <div v-for="year in [...scholar_year].reverse()" :key="year.id"
                        class="w-full h-full flex flex-col p-10 mt-5 gap-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 font-kanit text-white rounded-xl">
                        
                        <!-- Three dots button at the top right -->
                        

                        <div class="flex justify-between">
                            <span v-if="year.year" class="items-center justify-center font-bold text-3xl">
                                {{ year.year }}
                            </span>
                            <span v-else class="text-red-500 font-bold text-3xl">Year Not Available</span>
                            <div class="top-2 right-2">
                                <button @click="toggleSet" class="text-white hover:text-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12M6 6h12M6 18h12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div v-if="year.academic_year?.length">
                            <div v-for="academic in year.academic_year" :key="academic.id">
                                <h1 class="font-poppins font-normal text-sm">
                                    Semester: {{ academic.semester ?? 'No Semester Assigned' }}
                                </h1>
                            </div>
                        </div>
                        <div v-else>
                            <h1 class="font-normal text-sm text-yellow-400 font-poppins">No Academic Year Available</h1>
                        </div>

                        <h1 class="font-normal text-sm font-poppins">Current Academic Year</h1>
                    </div>
                </div>

                <div v-else class="text-center text-gray-400">
                    <h1 class="text-lg font-poppins">No School Years Available</h1>
                </div>

            </div>
        </div>

        <div v-if="SchoolYear"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Set School Year
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

                    <!-- School Year Input -->
                    <div class="w-full flex flex-col space-y-2 p-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">School Year</h3>
                    <select v-model="statusValue" id="scholarshipType"
                        class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                        <option value="" disabled selected>Select School Year</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2022-2023">2022-2023</option>
                    </select>
                    </div>

                    <!-- Semester Radio Buttons -->
                    <div class="mb-2">
                        <label class="text-sm text-gray-500">Semester:</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center text-sm text-gray-600 dark:text-gray-200">
                                <input type="radio" name="semester" value="First" v-model="semester"
                                    class="mr-2 text-blue-600 focus:ring-blue-600" />
                                First Semester
                            </label>
                            <label class="flex items-center text-sm text-gray-600 dark:text-gray-200">
                                <input type="radio" name="semester" value="Second" v-model="semester"
                                    class="mr-2 text-blue-600 focus:ring-blue-600" />
                                Second Semester
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90">
                            Set School Year
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import CalendarPicker from "@/Components/MIS/CalendarPicker.vue";

const selectedDate = ref({ month: "", day: "" });
const props = defineProps({
    scholar_year: Array,
});

const SchoolYear = ref(false);

const toggleSet = () => {
  SchoolYear.value = !SchoolYear.value;
};

const closeModal = () => {
  SchoolYear.value = false;
};

</script>