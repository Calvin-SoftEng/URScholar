<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Archived Information
                </h1>

                <div class="w-full mt-5 h-full mx-auto">
                    <div class="w-10/12 h-full relative mx-auto overflow-x-auto rounded-lg p-5 bg-white dark:bg-dcontainer">
                        <!-- Tab Navigation -->
                        <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                            <a href="#" 
                               @click.prevent="activeTab = 'conditions'"
                               :class="[
                                   'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                                   activeTab === 'conditions'
                                       ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                                       : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                               ]">
                                Archived Conditions
                            </a>
                            <a href="#" 
                               @click.prevent="activeTab = 'eligibilities'"
                               :class="[
                                   'flex-1 text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                                   activeTab === 'eligibilities'
                                       ? 'text-blue-700 border-b-4 border-blue-700 dark:text-white dark:border-white'
                                       : 'text-gray-900 border-transparent hover:border-gray-200 hover:text-blue-700 dark:text-white dark:hover:bg-gray-700'
                               ]">
                                Archived Eligibilities
                            </a>
                        </div>

                        <!-- Content Area -->
                        <div class="bg-white relative overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg mt-4">
                            <!-- Conditions Table -->
                            <div v-if="activeTab === 'conditions'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <h3 class="text-lg font-bold p-4">Deleted Scholarship Conditions</h3>
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Condition</th>
                                            <th scope="col" class="px-6 py-3">User</th>
                                            <th scope="col" class="px-6 py-3">Date</th>
                                            <th scope="col" class="px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="conditions.length === 0" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                            <td colspan="4" class="px-6 py-4 text-center">No deleted conditions found</td>
                                        </tr>
                                        <tr v-for="condition in conditions" :key="condition.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ condition.name }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ condition.user.first_name }} {{ condition.user.last_name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ formatDate(condition.deleted_at || condition.updated_at) }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="space-x-2">
                                                    <button @click="restoreCondition(condition.id)" class="bg-primary text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Eligibilities Table -->
                            <div v-if="activeTab === 'eligibilities'" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <h3 class="text-lg font-bold p-4">Deleted Scholarship Eligibility Categories</h3>
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Eligibility</th>
                                            <th scope="col" class="px-6 py-3">User</th>
                                            <th scope="col" class="px-6 py-3">Date</th>
                                            <th scope="col" class="px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="eligibilities.length === 0" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                            <td colspan="4" class="px-6 py-4 text-center">No deleted eligibilities found</td>
                                        </tr>
                                        <tr v-for="eligibility in eligibilities" :key="eligibility.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ eligibility.name }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ eligibility.user.first_name }} {{ eligibility.user.last_name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ formatDate(eligibility.deleted_at || eligibility.updated_at) }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="space-x-2">
                                                    <button @click="restoreEligibility(eligibility.id)" class="bg-primary text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SettingsLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    eligibility: Array,
    condition: Array,
});

// Create reactive references from props
const eligibilities = computed(() => props.eligibility || []);
const conditions = computed(() => props.condition || []);
const activeTab = ref('conditions');

// Format date function
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Restore functions
const restoreCondition = (id) => {
    if (confirm('Are you sure you want to restore this condition?')) {
        router.put(`/settings/conditions/${id}/restore`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Success message if needed
            },
        });
    }
};

const restoreEligibility = (id) => {
    if (confirm('Are you sure you want to restore this eligibility?')) {
        router.put(`/settings/eligibilities/${id}/restore`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                // Success message if needed
            },
        });
    }
};

// Toast notification handling
const toastVisible = ref(false);
const toastMessage = ref("");

// Watch for flash messages (commented out as in your original code)
// watch(() => usePage().props.flash?.success, (newMessage) => {
//     if (newMessage) {
//         toastMessage.value = newMessage;
//         toastVisible.value = true;
//         setTimeout(() => {
//             toastVisible.value = false;
//         }, 3000);
//     }
// });
</script>