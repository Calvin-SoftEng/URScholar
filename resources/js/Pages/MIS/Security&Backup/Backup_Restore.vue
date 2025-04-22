<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout :branding="branding" :user="user" :auth="auth" :errors="errors">
        <div class="bg-dirtywhite dark:bg-dprimary p-6 h-full w-full space-y-2">
            <div>
                <h1 class="text-2xl font-bold mb-5 dark:text-dtext">Archives</h1>
            </div>
            <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                Here is the list of all the system activities. Listed are logs of each users.
            </p>
            <div class="w-full mt-5 space-y-5">

                <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-dprimary">
                    <a v-for="item in menuItems" :key="item.key" href="#" @click.prevent="selectMenu(item.key)" :class="[
                        'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                        selectedMenu === item.key
                            ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                            : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                    ]">
                        {{ item.name }}
                    </a>
                </div>

                <!-- Content Area -->
                <div class="w-full mx-auto  py-5 pr-2 overflow-y-auto" style="max-height: calc(100vh - 300px);">
                    <div v-if="selectedMenu === 'stakeholders'"
                        class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                                <tr v-for="stakeholder in stakeholders" :key="stakeholder.id"
                                    class="bg-white dark:bg-dcontainer border-b dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ stakeholder.first_name }} {{ stakeholder.middle_name }} {{
                                            stakeholder.last_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ stakeholder.campus.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatUserType(stakeholder.usertype) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button @click="restoreUser(stakeholder.id)"
                                                class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="selectedMenu === 'scholars'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <h3 class="text-lg font-bold p-4">Archived Scholars List</h3>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                                <tr v-for="scholar in scholars" :key="scholar.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ scholar.scholar_id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ scholar.first_name }} {{ scholar.middle_name }} {{ scholar.last_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ scholar.campus.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ scholar.year_level }} {{ scholar.course }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="space-x-2">
                                            <button @click="restoreUser(scholar.id)"
                                                class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


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
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watchEffect } from 'vue';
import { Tooltip } from 'primevue';
import { User } from 'lucide-vue-next';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

defineProps({
    stakeholders: Array,
    scholars: Array,
    branding: Object,
    user: Object,
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

const restoreUser = (userId) => {
    if (confirm('Are you sure you want to restore this user?')) {
        router.put(route('users.activateUser', userId));
    }
};

// Add this to your script setup section
const formatUserType = (usertype) => {
    const typeMapping = {
        'super_admin': 'Super Admin',
        'system_admin': 'System Admin',
        'head_cashier': 'Head Cashier',
        'cashier': 'Cashier',
        'coordinator': 'Coordinator',
        'student': 'Student',
        'sponsor': 'Sponsor'
    };

    return typeMapping[usertype] || usertype.charAt(0).toUpperCase() + usertype.slice(1);
};

// Track the selected menu
const selectedMenu = ref("stakeholders");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};

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
</script>