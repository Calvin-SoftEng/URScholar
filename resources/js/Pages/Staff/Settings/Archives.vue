<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Deleted Information
                </h1>

                <div class="w-full mt-5 h-full mx-auto">
                    <div class="w-10/12 h-full relative mx-auto overflow-x-auto rounded-lg p-5 bg-white dark:bg-dcontainer">
                        <!-- Scholarship Forms Section -->
                        <!-- <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
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
                        </div> -->

                        <!-- Content Area -->
                        <div class="bg-white relative overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-b-lg mt-4">

                            <div  class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <h3 class="text-lg font-bold p-4">Scholarship Eligibility Categories</h3>
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Condition
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
                                                Must be a Filipino Citizen
                                                                                        </th>
                                            <td class="px-6 py-4">
                                                Ms. Maam
                                            </td>
                                            <td class="px-6 py-4">
                                                Kahapon
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="space-x-2">
                                                    <button class="bg-primary text-white px-3 py-1 rounded-md text-sm hover:bg-green-600 transition">Restore</button>
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

const menuItems = [
    { name: "Scholarships", key: "scholarships" },
    { name: "Scholars", key: "scholars" },
    { name: "Sponsors", key: "sponsors" },
    { name: "Reports", key: "reports" },
    { name: "Payouts", key: "payouts" },
];

// Track the selected menu
const selectedMenu = ref("stakeholders");

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
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