<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Deleted Information
                </h1>

                <div class="w-full mt-5 mx-auto">
                    <div class="w-7/12 relative mx-auto overflow-x-auto rounded-lg p-5 bg-white dark:bg-dcontainer">
                        <!-- Scholarship Forms Section -->
                        <div class="mb-5 space-y-5">
                            <div class="flex flex-row justify-between border-b items-center pb-3">
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-dtext">Listed are what has been deleted from your account</h2>
                                <!-- <button @click="toggleNewForm" class="text-blue-600 text-sm hover:underline">Add New
                                    Form</button> -->
                            </div>

                                <!-- Content Area -->
                                <div class=" relative overflow-x-auto mt-4">
                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">
                                                        Time
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        Item
                                                    </th>
                                                    <th scope="col" class="px-6 py-3">
                                                        <span class="sr-only">Actions</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        10:20 AM
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        Verification Choice
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
                </div>
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