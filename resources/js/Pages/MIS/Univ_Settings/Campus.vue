<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5">Campus</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of the University's campuses. You can add and edit campuses here, and assign stakeholders.
            </p>
            <div class="w-full mt-5">
                <div v-if="!isTableVisible" class="bg-white relative overflow-x-auto border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between pb-4 bg-white dark:bg-gray-900 m-5">
                        <h1 class="text-xl font-semibold font-quicksand text-primary">
                            University of the Rizal System
                        </h1>
                        <button @click="toggleTable"
                            class="btn bg-primary text-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary"
                            >
                            Add Campus
                        </button>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Campus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Scholarship Coordinator
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Campus Cashier
                                </th>
                                <th scope="col" class="px-1 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Angono Campus
                                </th>
                                <td class="px-6 py-4">
                                    F5MQ+62W, Manila E Rd, Binangonan, 1940 Rizal
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4 flex flex-row gap-2">
                                    <button class="" v-tooltip.right="'Edit Campus'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                        edit
                                        </span>
                                    </button>
                                    <button @click="toggleAssign" v-tooltip.right="'Assign Focal Person'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-yellow-400 rounded-lg">
                                        assignment_ind
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Antipolo Campus
                                </th>
                                <td class="px-6 py-4">
                                    F5MQ+62W, Manila E Rd, Binangonan, 1940 Rizal
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4 flex flex-row gap-2">
                                    <button class="" v-tooltip.right="'Edit Campus'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                        edit
                                        </span>
                                    </button>
                                    <button @click="toggleAssign" v-tooltip.right="'Assign Focal Person'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-yellow-400 rounded-lg">
                                        assignment_ind
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Binangonan Campus
                                </th>
                                <td class="px-6 py-4">
                                    F5MQ+62W, Manila E Rd, Binangonan, 1940 Rizal
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4">
                                    Si ano
                                </td>
                                <td class="px-6 py-4 flex flex-row gap-2">
                                    <button class="" v-tooltip.right="'Edit Campus'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-blue-900 rounded-lg">
                                        edit
                                        </span>
                                    </button>
                                    <button @click="toggleAssign" v-tooltip.right="'Assign Focal Person'">
                                        <span class="material-symbols-rounded p-2 font-medium text-white dark:text-blue-500 bg-yellow-400 rounded-lg">
                                        assignment_ind
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="isTableVisible" class="relative overflow-x-auto border bg-white border-gray-200 rounded-lg p-4">
                    <div class="flex w-full items-end justify-end">
                        <button type="button" @click="toggleTable" class="text-primary bg-white border border-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Back
                        </button>
                        <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Save
                        </button>
                    </div>
                    <div class="flex flex-col w-full gap-4">
                        <h1 class="text-lg font-bold font-quicksand text-primary">
                            Enter the Campus Details
                        </h1>
                        <div>
                            <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Campus Name</label>
                            <input type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Binangonan, Campus" required />
                        </div>
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                            <input type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Street" required />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- adding focal person -->
        <div v-if="Assigning"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">Assign Focal Person for {call mo here}</h2>
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
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Coordinator</h3>
                        <select id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Coordinator List</option>
                            <option value="merit">Maam 1</option>
                            <option value="need">Maam 2</option>
                            <option value="athletic">Maam 3</option>
                            <option value="artistic">Maam 4</option>
                        </select>
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Campus Cashier</h3>
                        <select id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Cashier List</option>
                            <option value="merit">Maam 1</option>
                            <option value="need">Maam 2</option>
                            <option value="athletic">Maam 3</option>
                            <option value="artistic">Maam 4</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                           Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ContentDashboard from '@/Pages/Super_Admin/Dashboard/Content-Dashboard.vue';
import { Tooltip } from 'primevue';

const isTableVisible = ref(false);

const toggleTable = () => {
    isTableVisible.value = !isTableVisible.value;
};

const cancel = () => {
    isTableVisible.value = !isTableVisible.value;
};

const Assigning = ref(false);

const toggleAssign = () => {
    Assigning.value = !Assigning.value;
};

const closeModal = () => {
    Assigning.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = { id: null, name: '', description: '' };
};


</script>