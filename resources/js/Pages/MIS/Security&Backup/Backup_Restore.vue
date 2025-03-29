<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5">Archives</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of all the system activities. Listed are logs of each users.
            </p>
            <div class="w-full mt-5 space-y-5">
                
                <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                    <a v-for="item in menuItems" :key="item.key"
                        href="#"
                        @click.prevent="selectMenu(item.key)"
                        :class="[
                            'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                            selectedMenu === item.key
                                ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                                : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                        ]"
                    >
                        {{ item.name }}
                    </a>
                </div>

                <!-- Content Area -->
                <div class="bg-white relative overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg border-t-0 mt-4">
                    <div v-if="selectedMenu === 'stakeholders'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Archived System Stakeholders</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Campus
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Si ano
                                    </th>
                                    <td class="px-6 py-4">
                                        Binangonan
                                    </td>
                                    <td class="px-6 py-4">
                                        Coordinator
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Si ano
                                    </th>
                                    <td class="px-6 py-4">
                                        Binangonan
                                    </td>
                                    <td class="px-6 py-4">
                                        Cashier
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'scholars'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Archived Scholars List</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Scholar Id
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Full Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Campus
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Year and Course
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        URS-0000
                                    </th>
                                    <td class="px-6 py-4">
                                        Si ano
                                    </td>
                                    <td class="px-6 py-4">
                                        Binangonan
                                    </td>
                                    <td class="px-6 py-4">
                                        4th Yr BSIt
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'scholarships'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Available Scholarships</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Updated an Account
                                    </th>
                                    <td class="px-6 py-4">
                                        Ms. Maam
                                    </td>
                                    <td class="px-6 py-4">
                                        Kahapon
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'sponsors'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Sponsors</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Updated an Account
                                    </th>
                                    <td class="px-6 py-4">
                                        Ms. Maam
                                    </td>
                                    <td class="px-6 py-4">
                                        Kahapon
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'reports'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Reports List</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Updated an Account
                                    </th>
                                    <td class="px-6 py-4">
                                        Ms. Maam
                                    </td>
                                    <td class="px-6 py-4">
                                        Kahapon
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'payouts'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Payouts List</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Activity
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Updated an Account
                                    </th>
                                    <td class="px-6 py-4">
                                        Ms. Maam
                                    </td>
                                    <td class="px-6 py-4">
                                        Kahapon
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'guest'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Guest Section</h3>
                        <p class="p-4">Guest users can browse public scholarship opportunities.</p>
                    </div>
                </div>

                <!-- adding roles -->
                <div v-if="isCreating || isEditing"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
                    <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <span class="text-xl font-semibold text-gray-900 dark:text-white">
                                <h2 class="text-2xl font-bold">
                                    {{ isEditing ? 'Edit Role' : 'Add New Role'}}
                                </h2>
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
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Role Name</h3>
                                <input v-model="form.name" type="text" id="name"
                                    placeholder="Enter Scholarship Name"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                            </div>
                            <div class="mt-2">
                                <button type="submit"
                                    class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                                    {{ isEditing ? 'Update Role' : 'Add Role' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';

defineProps({
    campuses: Array,
    coor: Array,
    cashier: Array,
});


const isCreating = ref(false);
const isEditing = ref(false);


const toggleAddRole = () => {
    isCreating.value = !isCreating.value;
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const menuItems = [
    { name: "System Stakeholders", key: "stakeholders" },
    { name: "Scholars", key: "scholars" },
    { name: "Scholarships", key: "scholarships" },
    { name: "Sponsors", key: "sponsors" },
    { name: "Reports", key: "reports" },
    { name: "Payouts", key: "payouts" },
    { name: "Guest", key: "guest" },
];

// Track the selected menu
const selectedMenu = ref("stakeholders");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

</script>