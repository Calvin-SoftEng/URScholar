<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Conditions that
                    applicants must meet
                </h1>

                <div class="w-full mt-5">
                    <div class="relative overflow-x-auto rounded-lg w-full p-5 bg-white dark:bg-dcontainer">
                        <!-- Eligibility Forms Section -->
                        <div class="mb-5 space-y-5">
                            <div class="flex flex-row justify-between border-b items-center pb-3">
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-dtext">Scholarship Eligibility Categories</h2>
                                <button @click="toggleNewEligibility" class="text-blue-600 text-sm hover:underline">Add
                                    New Category</button>
                            </div>

                            <!-- No eligibility message -->
                            <div v-if="eligibility.length === 0" class="text-center py-10 text-gray-500">
                                No eligibility categories found. Click "Add New Category" to create one.
                            </div>

                            <!-- Loop through eligibilities -->
                            <div v-for="item in eligibility" :key="item.id"
                                class="bg-white dark:bg-dsecondary border border-gray-100 shadow-sm w-full block rounded-lg mb-3">
                                <div class="flex justify-between items-center p-5 border-b border-b-blue-100 border-1">
                                    <div>
                                        <span class="font-semibold font-quicksand text-lg dark:text-dtext">{{ item.name }}</span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <button @click="toggleEditEligibility(item)" class="text-blue-600 text-sm hover:underline">
                                            Edit Category
                                        </button>
                                        
                                        <div class="h-4 border-l border-gray-400"></div> <!-- Vertical Line -->

                                        <button @click="toggleAddCondition(item.id)" class="text-blue-600 text-sm hover:underline">
                                            Add Condition
                                        </button>
                                        
                                        <div class="h-4 border-l border-gray-400"></div> <!-- Vertical Line -->

                                        <button @click="deleteEligibility(item.id)" class="text-red-600 text-sm hover:underline">
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <div class="w-full grid grid-cols-2 px-5 py-3 gap-2">
                                        <!-- No conditions message -->
                                        <div v-if="getConditions(item.id).length === 0"
                                            class="col-span-2 text-center py-3 text-gray-500">
                                            No conditions added yet. Click "Add Condition" to create one.
                                        </div>

                                        <!-- Loop through conditions for this eligibility -->
                                        <div v-for="cond in getConditions(item.id)" :key="cond.id"
                                            class="flex items-center gap-2 border rounded-md px-3 justify-between py-1 hover:bg-gray-100 dark:hover:bg-dprimary">
                                            <span class="text-gray-700 dark:text-dtext">{{ cond.name }}</span>
                                            <div class="ml-2 flex gap-1">
                                                <button @click="toggleEditCondition(cond)"
                                                    class="p-1 rounded hover:bg-gray-200">
                                                    <font-awesome-icon :icon="['fas', 'pen']" class="text-primary" />
                                                </button>
                                                <button @click="deleteCondition(cond.id)"
                                                    class="p-1 rounded hover:bg-gray-200">
                                                    <font-awesome-icon :icon="['fas', 'box-archive']"
                                                        class="text-primary" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Eligibility Modal -->
        <div v-if="isAddingEligibility || isEditingEligibility"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ isEditingEligibility ? 'Edit' : 'Add New' }} Eligibility Category
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitEligibilityData" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Category Name
                        </h3>
                        <input v-model="eligibilityData.name" type="text" id="name" placeholder="Enter category name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            {{ isEditingEligibility ? 'Update' : 'Add' }} Category
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add/Edit Condition Modal -->
        <div v-if="isAddingCondition || isEditingCondition"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ isEditingCondition ? 'Edit' : 'Add New' }} Condition
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitConditionData" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Condition Name
                        </h3>
                        <input v-model="conditionData.name" type="text" id="condition-name"
                            placeholder="Enter condition name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            {{ isEditingCondition ? 'Update' : 'Add' }} Condition
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <div v-if="toastVisible"
            class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg transition-opacity duration-300">
            {{ toastMessage }}
        </div>
    </SettingsLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    eligibility: Array,
    condition: Array,
});

// Function to filter conditions based on eligibility ID
const getConditions = (eligibilityId) => {
    return props.condition.filter(cond => cond.eligibility_id === eligibilityId);
};

// Eligibility modal state
const isAddingEligibility = ref(false);
const isEditingEligibility = ref(false);
const eligibilityData = ref({
    id: null,
    scholarship_id: 1, // Default scholarship ID - adjust as needed
    name: '',
});

// Condition modal state
const isAddingCondition = ref(false);
const isEditingCondition = ref(false);
const conditionData = ref({
    id: null,
    eligibility_id: null,
    name: '',
});

// Toggle functions for modals
const toggleNewEligibility = () => {
    eligibilityData.value = { id: null, scholarship_id: 1, name: '' };
    isAddingEligibility.value = true;
    isEditingEligibility.value = false;
};

const toggleEditEligibility = (eligibility) => {
    eligibilityData.value = {
        id: eligibility.id,
        scholarship_id: eligibility.scholarship_id,
        name: eligibility.name
    };
    isEditingEligibility.value = true;
    isAddingEligibility.value = false;
};

const toggleAddCondition = (eligibilityId) => {
    conditionData.value = { id: null, eligibility_id: eligibilityId, name: '' };
    isAddingCondition.value = true;
    isEditingCondition.value = false;
};

const toggleEditCondition = (condition) => {
    conditionData.value = {
        id: condition.id,
        eligibility_id: condition.eligibility_id,
        name: condition.name
    };
    isEditingCondition.value = true;
    isAddingCondition.value = false;
};

const closeModal = () => {
    isAddingEligibility.value = false;
    isEditingEligibility.value = false;
    isAddingCondition.value = false;
    isEditingCondition.value = false;
    eligibilityData.value = { id: null, scholarship_id: 1, name: '' };
    conditionData.value = { id: null, eligibility_id: null, name: '' };
};

// Form submission functions
const submitEligibilityData = () => {
    if (isEditingEligibility.value) {
        router.put(`/settings/eligibilities/${eligibilityData.value.id}`, eligibilityData.value, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Eligibility updated successfully');
                closeModal();
            },
        });
    } else {
        router.post('/settings/eligibilities', eligibilityData.value, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Eligibility added successfully');
                closeModal();
            },
        });
    }
};

const submitConditionData = () => {
    if (isEditingCondition.value) {
        router.put(`/settings/conditions/${conditionData.value.id}`, conditionData.value, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Condition updated successfully');
                closeModal();
            },
        });
    } else {
        router.post('/settings/conditions', conditionData.value, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Condition added successfully');
                closeModal();
            },
        });
    }
};

const deleteEligibility = (id) => {
    if (confirm('Are you sure you want to delete this eligibility category? All associated conditions will also be deleted.')) {
        router.delete(`/settings/eligibilities/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Eligibility category deleted successfully');
            },
        });
    }
};

const deleteCondition = (id) => {
    if (confirm('Are you sure you want to delete this condition?')) {
        router.delete(`/settings/conditions/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Condition deleted successfully');
            },
        });
    }
};

// Toast notification handling
const toastVisible = ref(false);
const toastMessage = ref("");

const showToast = (message) => {
    toastMessage.value = message;
    toastVisible.value = true;

    setTimeout(() => {
        toastVisible.value = false;
    }, 3000);
};

// Watch for flash messages
watch(() => usePage().props.flash?.success, (newMessage) => {
    if (newMessage) {
        showToast(newMessage);
    }
});
</script>