<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5 dark:text-dtext">Archives</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of all the system activities. Listed are logs of each users.
            </p>
            <div class="w-full mt-5 space-y-5">
                
                <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-dprimary">
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
                <div
                        class="w-full mx-auto  py-5 pr-2 overflow-y-auto"
                        style="max-height: calc(100vh - 300px);">
                    <div v-if="selectedMenu === 'stakeholders'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4 dark:text-dtext">Archived System Stakeholders</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dnavy dark:text-gray-400">
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
                                <tr class="bg-white dark:bg-dcontainer border-b dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        User 1
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
                                        User 2
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
];

// Track the selected menu
const selectedMenu = ref("stakeholders");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

</script>