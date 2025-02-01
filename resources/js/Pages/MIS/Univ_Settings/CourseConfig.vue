<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full">
            <div class="relative">
                <h1 class="text-2xl font-bold mb-5">Course List</h1>
                <Link :href="route('mis.course')">
                <span class="absolute top-1 right-[-50px] flex flex-col items-center space-y-1">
                    <button>
                        <span class="material-symbols-rounded px-2 py-1 rounded-full border-2 border-gray-300 dark:border-gray-600 
                            hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200 text-gray-600 dark:text-gray-300 text-lg">
                        arrow_back
                        </span>
                    </button>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Go Back</p>
                </span>
                </Link>
            </div>

            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's courses. You can add and edit courses for each campus.
            </p>

            <div class="w-full h-full flex flex-col px-5 mt-5">
                <div class="bg-white w-full">
                    <div class="flex justify-between items-center p-5 border-b border-b-blue-100 border-1">
                        <div>
                            <img src="" alt="">
                            <span class="font-semibold font-sora text-lg">University of Rizal System, Antipolo Campus</span>
                        </div>
                        <div class="flex gap-1">
                            <button @click="toggleModal"
                                class="bg-primary px-3 py-1 rounded-md text-white hover:border-primary hover:border hover:bg-white hover:text-primary dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary"
                                >
                                Add
                            </button>
                            <button
                                class="bg-white px-3 py-1 rounded-md border-gray-100 text-primary border hover:bg-primary hover:text-dtext dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary"
                                >
                                Save
                            </button>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="relative overflow-x-autorounded-b-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                            <thead class="text-xs text-gray-700 uppercase bg-white dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Courses
                                    </th>
                                    <th scope="col" class="py-3">
                                        <span class="sr-only">Action</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                    <td class="px-6 py-4">
                                        Commisioner Chuchu (CHED)
                                    </td>
                                    <td class="px-6 py-4 w-full flex flex-row gap-2 justify-end">
                                        <button class="" v-tooltip.right="'Edit Course'">
                                            <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                            edit
                                            </span>
                                        </button>
                                        <button @click="toggleArchive" v-tooltip.right="'Archive'">
                                            <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-red-500 rounded-lg">
                                            inventory_2
                                            </span>
                                        </button>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- adding course -->
        <div v-if="isCoursesVisible"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">Call mo campus here</h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                        <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                           Add this Course</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- archiving -->
        <div 
            v-if="Archive"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity ease-in duration-300"
        >
            <div class="bg-white dark:bg-gray-900 dark:border-gray-600 rounded-lg shadow-xl w-4/12">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-300 dark:border-gray-600">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Archive Course</h2>
                    <button 
                        type="button" 
                        @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    >
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7L1 13" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 text-center">
                    <span class="material-symbols-rounded mx-auto mb-4 text-6xl text-red-600 dark:text-red-500">
                    inventory_2
                    </span>
                    <p class="text-lg text-gray-700 dark:text-gray-300">
                        Archive this course and still retain the data in the Archives Menu <br>
                        <span class="font-semibold text-red-600 dark:text-red-400">This action cannot be undone.</span>
                    </p>
                </div>

                <!-- Modal Footer (Buttons) -->
                <div class="flex justify-end gap-3 p-4 border-t border-gray-300 dark:border-gray-600">
                    <button 
                        @click="closeModal"
                        class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="confirmDelete"
                        class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-600 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-all"
                    >
                        Archive
                    </button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Super_Admin/Dashboard/Content-Dashboard.vue';
import { ref } from 'vue';


const isCoursesVisible = ref(false);

const toggleModal = () => {
    isCoursesVisible.value = !isCoursesVisible.value;
};

const cancel = () => {
    isCoursesVisible.value = !isCoursesVisible.value;
};

const Archive = ref(false);

const toggleArchive = () => {
    Archive.value = !Archive.value;
};

const closeModal = () => {
    isCoursesVisible.value = false;
    Archive.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};
</script>