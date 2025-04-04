<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Your Activities
                </h1>

                <div class="w-full mt-5 mx-auto">
                    <div class="w-7/12 relative mx-auto overflow-x-auto rounded-lg p-5 bg-white dark:bg-dcontainer">
                        <!-- Scholarship Forms Section -->
                        <div class="mb-5 space-y-5">
                            <div class="flex flex-row justify-between border-b items-center pb-3">
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-dtext">Listed are your activities </h2>
                                <!-- <button @click="toggleNewForm" class="text-blue-600 text-sm hover:underline">Add New
                                    Form</button> -->
                            </div>

                                <!-- Content Area -->

                                    <div class="max-w-3xl mx-auto bg-white dark:bg-dsecondary py-5">
                                        <!-- <div v-if="filteredLogs.length > 0" class="space-y-6">
                                            <div v-for="(log, index) in filteredLogs" :key="index"> -->
                                        <div class="space-y-6">
                                            <div >
                                                <!-- Display Day Only Once -->
                                                <div class="text-gray-500 dark:text-gray-300 text-sm font-semibold mb-2">
                                                    <!-- {{ log.date }} -->feafaf
                                                </div>

                                                <!-- Log Entries -->
                                                <div class="space-y-3">
                                                    <div 
                                                        class="grid grid-cols-[100px_auto_50px] gap-4">
                                                        <!-- Time Column -->
                                                        <div class="text-gray-500 text-sm whitespace-nowrap self-start pt-1">
                                                            <!-- {{ entry.time }} -->feafaef
                                                        </div>

                                                        <!-- Activity Column -->
                                                        <div class="text-gray-700 dark:text-dtext break-words leading-relaxed">
                                                            <!-- <strong>{{ entry.user }}</strong> {{ entry.action }}
                                                            <span :class="entry.color">{{ entry.item }}</span>. -->feafeafa
                                                        </div>

                                                        <!-- Restore Button Column -->
                                                        <div class="flex justify-end self-start pt-1">
                                                            <button @click="removeLog(log, idx)" v-tooltip.right="'Remove'"
                                                                class="p-1 rounded-lg text-dprimary dark:text-dtext hover:bg-blue-200 transition">
                                                                <span class="material-symbols-rounded font-medium">remove</span>
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