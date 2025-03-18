<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Scholarship Forms
                </h1>

                <div class="w-full mt-5">
                    <div class="relative overflow-x-auto rounded-lg w-full p-5 bg-white">
                        <!-- Scholarship Forms Section -->
                        <div class="mb-5">
                            <div class="flex flex-row justify-between border-b items-center pb-3">
                                <h2 class="text-lg font-semibold text-gray-700">Scholarship Criteria</h2>
                                <button @click="toggleNewForm" class="text-blue-600 text-sm hover:underline">Add New
                                    Form</button>
                            </div>
                            <div class="mt-3 space-y-5">
                                <!-- Loop through scholarship forms -->
                                <div v-for="form in scholarship_form" :key="form.id"
                                    class="bg-white border border-gray-100 shadow-sm w-full block rounded-lg mb-3">
                                    <div
                                        class="flex justify-between items-center p-5 border-b border-b-blue-100 border-1">
                                        <div>
                                            <span class="font-semibold font-quicksand text-lg">{{ form.name }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="toggleEditForm(form)"
                                                class="text-blue-600 text-sm hover:underline">Edit Form</button>
                                            <button @click="toggleAddCriteria(form.id)"
                                                class="text-blue-600 text-sm hover:underline">Add Criteria</button>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="w-full grid grid-cols-2 px-5 py-3 gap-2">
                                            <!-- Loop through scholarship form data -->
                                            <div v-for="data in getFormData(form.id)" :key="data.id"
                                                class="flex items-center gap-2 border rounded-md px-3 justify-between py-1 hover:bg-gray-100">
                                                <span class="text-gray-700">{{ data.name }}</span>
                                                <div class="ml-2 flex gap-1">
                                                    <button @click="toggleEditCriteria(data)"
                                                        class="p-1 rounded hover:bg-gray-200">
                                                        <font-awesome-icon :icon="['fas', 'pen']"
                                                            class="text-primary" />
                                                    </button>
                                                    <button @click="deleteCriteria(data.id)"
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
        </div>

        <!-- Add/Edit Scholarship Form Modal -->
        <div v-if="isAddingForm || isEditingForm"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ isEditingForm ? 'Edit' : 'Add New' }} Scholarship Form
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

                <form @submit.prevent="submitFormData" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Form Name
                        </h3>
                        <input v-model="formData.name" type="text" id="name" placeholder="Enter form name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            {{ isEditingForm ? 'Update' : 'Add' }} Form
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add/Edit Criteria Modal -->
        <div v-if="isAddingCriteria || isEditingCriteria"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            {{ isEditingCriteria ? 'Edit' : 'Add New' }} Criteria
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

                <form @submit.prevent="submitCriteriaData" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            Criteria Name
                        </h3>
                        <input v-model="criteriaData.name" type="text" id="criteria-name"
                            placeholder="Enter criteria name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            {{ isEditingCriteria ? 'Update' : 'Add' }} Criteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SettingsLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    scholarship_form: Array,
    scholarship_form_data: Array,
});

// Function to filter form data based on form ID
const getFormData = (formId) => {
    return props.scholarship_form_data.filter(data => data.scholarship_form_id === formId);
};

// Scholarship Form modal state
const isAddingForm = ref(false);
const isEditingForm = ref(false);
const formData = ref({
    id: null,
    name: '',
});

// Criteria modal state
const isAddingCriteria = ref(false);
const isEditingCriteria = ref(false);
const criteriaData = ref({
    id: null,
    scholarship_form_id: null,
    name: '',
});

// Toggle functions for modals
const toggleNewForm = () => {
    formData.value = { id: null, name: '' };
    isAddingForm.value = true;
    isEditingForm.value = false;
};

const toggleEditForm = (form) => {
    formData.value = { id: form.id, name: form.name };
    isEditingForm.value = true;
    isAddingForm.value = false;
};

const toggleAddCriteria = (formId) => {
    criteriaData.value = { id: null, scholarship_form_id: formId, name: '' };
    isAddingCriteria.value = true;
    isEditingCriteria.value = false;
};

const toggleEditCriteria = (criteria) => {
    criteriaData.value = {
        id: criteria.id,
        scholarship_form_id: criteria.scholarship_form_id,
        name: criteria.name
    };
    isEditingCriteria.value = true;
    isAddingCriteria.value = false;
};

const closeModal = () => {
    isAddingForm.value = false;
    isEditingForm.value = false;
    isAddingCriteria.value = false;
    isEditingCriteria.value = false;
    formData.value = { id: null, name: '' };
    criteriaData.value = { id: null, scholarship_form_id: null, name: '' };
};

// Form submission functions
const submitFormData = () => {
    if (isEditingForm.value) {
        router.put(`/settings/scholarship-forms${formData.value.id}`, formData.value, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        router.post('/settings/scholarship-forms', formData.value, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

const submitCriteriaData = () => {
    if (isEditingCriteria.value) {
        router.put(`/settings/scholarship-forms/data${criteriaData.value.id}`, criteriaData.value, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    } else {
        router.post('/settings/scholarship-forms/data', criteriaData.value, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
        });
    }
};

const deleteCriteria = (id) => {
    if (confirm('Are you sure you want to delete this criteria?')) {
        router.delete(`/settings/scholarship-forms${id}`, {
            preserveScroll: true,
        });
    }
};

// Toast notification handling
const toastVisible = ref(false);
const toastMessage = ref("");

// Watch for flash messages
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