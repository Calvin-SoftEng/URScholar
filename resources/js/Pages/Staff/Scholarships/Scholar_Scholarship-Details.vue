<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li class="hover:text-gray-600">
                            <span>{{ scholarship.name }}</span>
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Batch 1</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholar Details</span>
                        </li>
                    </ul>
                </div>

                <div class="w-full h-full flex justify-center items-center dark:text-dprimary relative">
                    <!-- Close Button -->
                    <button class="absolute top-4 right-10">
                        <span
                            class="material-symbols-rounded p-2 rounded-full bg-white dark:bg-dcontainer text-blue-900 dark:text-dprimary shadow-md hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            arrow_back
                        </span>
                    </button>

                    <div class="h-full grid grid-cols-2 gap-3 py-3 w-9/12">
                        <!-- 25% Column -->
                        <div class="col-span-2 w-full h-full flex flex-col">
                            <div
                                class="h-full rounded-xl p-3 shadow-md bg-white dark:bg-dcontainer flex flex-col">
                                <div class="flex flex-row gap-3">
                                    <div class="bg-black rounded-lg w-3/12 h-full aspect-square">

                                    </div>
                                    <div class="w-full">
                                        <div class="flex flex-col p-2 space-y-3">
                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Scholar Name</span>
                                                <span class="text-xl font-sora text-primary">
                                                    {{ scholar.last_name}},
                                                {{ scholar.first_name }}
                                                {{scholar.middle_name ? scholar.middle_name.split(' ').map(word =>
                                                    word.charAt(0).toUpperCase()).join('.') + '.' : ''}}</span>
                                            </div>

                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Campus</span>
                                                <span class="text-base text-primary">{{ scholar.campus }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col p-2 space-y-2">
                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Program</span>
                                                <span class="text-base text-primary">{{ scholar.course }}</span>
                                            </div>

                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Campus</span>
                                                <span class="text-base text-primary">{{ scholar.campus }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col p-2 space-y-2">
                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Contact
                                                    No.</span>
                                                <span class="text-base text-primary">BSIT</span>
                                            </div>

                                            <div class="flex flex-col text-black">
                                                <span class="font-semibold uppercase text-xs text-gray-500">Email
                                                    Address</span>
                                                <span class="text-base text-primary">{{ scholar.email }}</span>
                                            </div>
                                        </div>
                                        <!-- Ensure button stays at the bottom -->
                                        <div class="mt-auto w-full flex justify-end">
                                            <button class="w-full rounded-md py-1 bg-primary text-white">View more Details</button>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                        </div>

                        <!-- 75% Column -->
                        <div class="col-span-2 h-full flex flex-col space-y-3">
                            <!-- Second Layer with Single Card -->
                            <div
                                class="bg-white p-6 box-border rounded shadow-md h-[50%] dark:bg-dcontainer flex flex-col space-y-3">
                                <h1 class="text-black font-normal text-xl font-poppins">Requirements Checking</h1>
                                <div
                                    class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-gray-100 dark:scrollbar-track-gray-900">

                                    <!-- Requirement List -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Requirement Item -->
                                        <div v-for="req in submittedRequirements" :key="req.id"
                                            class="bg-gray-100 w-full rounded-lg p-3 flex justify-between items-center font-quicksand text-primary">
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ req.requirement }}</span>
                                                <span>{{ req.submitted_requirements }}</span>
                                            </div>

                                            <div class="flex flex-row gap-5 items-center justify-center">
                                                <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                                                    <span class="material-symbols-rounded text-lg">assignment_turned_in</span>
                                                    <span class="font-medium">Jan 1, 2023</span>
                                                </div>
                                                <div>
                                                    <span :class="statusClass(req.status)"
                                                        class="text-sm font-medium px-2.5 py-0.5 rounded border">
                                                        {{ req.status }}
                                                    </span>
                                                </div>
                                                <button @click="toggleCheck(req)"
                                                    class="flex items-center gap-2 px-3 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all">
                                                    <span class="material-symbols-rounded text-base">open_in_full</span>
                                                    <span class="font-medium text-sm">View</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div
                                class="bg-white p-6 box-border rounded shadow-md flex-1 dark:bg-dcontainer flex flex-col space-y-3">
                                <h1 class="text-black font-normal text-lg font-poppins">Monitoring</h1>
                                <div
                                    class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-gray-100 dark:scrollbar-track-gray-900">

                                    <div
                                        class="bg-gray-100 w-full rounded-lg p-3 flex justify-between items-center font-quicksand text-primary mb-2">
                                        <div class="flex flex-col">
                                            <span>Document.pdf</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                                            <span class="font-medium">First Semester - @nd Year</span>
                                        </div>
                                        <div>
                                            <button @click="toggleCheck(req)"
                                                class="flex items-center gap-2 px-3 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all">
                                                <span class="material-symbols-rounded text-base">open_in_full</span>
                                                <span class="font-medium text-sm">View</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="Checking"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
                    <div
                        class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-10/12 max-h-[95vh] overflow-y-auto">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white pl-2">{{
                                selectedRequirement?.requirement }}</h2>
                            <div class="flex items-center justify-between gap-10">
                                <a :href="`/storage/${selectedRequirement?.path}`" target="_blank"
                                    class="flex items-center gap-2 text-gray-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm px-3 py-1.5 dark:hover:bg-gray-600 dark:hover:text-white transition">
                                    <span class="material-symbols-rounded text-lg">open_in_new</span>
                                    <span class="font-medium">Open in New Tab</span>
                                </a>
                                <button type="button" @click="closeModal"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-4 flex flex-col gap-3">
                            <div class="w-full flex justify-center p-5">
                                <!-- Display Image -->
                                <img v-if="selectedRequirement?.submitted_requirements.match(/\.(jpg|jpeg|png|gif)$/i)"
                                    :src="`/storage/${selectedRequirement.path}`"
                                    class="rounded-lg border shadow-sm max-w-full h-auto" alt="Submitted File">

                                <!-- Display PDF -->
                                <iframe v-else-if="selectedRequirement?.submitted_requirements.match(/\.(pdf)$/i)"
                                    :src="`/storage/${selectedRequirement.path}#toolbar=0`"
                                    class="w-full h-[600px] border rounded-lg"></iframe>

                                <!-- Display Message for Other File Types -->
                                <p v-else class="text-gray-600">
                                    Cannot preview this file type. <a :href="`/storage/${selectedRequirement.path}`"
                                        class="text-blue-600 underline" target="_blank">Download here</a>.
                                </p>
                            </div>

                            <!-- Returning Requirement Message -->
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">*If Returning Requirement</h3>
                                <textarea id="return-requirement" placeholder="Add a message in returning"
                                    v-model="returnMessage"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-6/12 h-32 resize-none text-left dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"></textarea>
                            </div>

                            <!-- Close Button -->
                            <div class="mt-2 flex flex-row justify-between">
                                <button type="button" @click="updateRequirementStatus('Returned')"
                                    class="text-white font-sans w-full bg-gradient-to-r from-red-700 via-red-800 to-red-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                    Return
                                </button>
                                <button type="button" @click="updateRequirementStatus('Approved')"
                                    class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                    Approve
                                </button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive, computed } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'


// components

const props = defineProps({
    scholar: Object,
    scholarship: Object,
    batch: Object,
    submittedRequirements: Array,
});

const components = {
    Button,
    Papa,
};

const statusClass = (status) => {
    switch (status) {
        case 'Pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-gray-700 dark:text-yellow-400 border-yellow-400';
        case 'Returned':
            return 'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border-red-400';
        case 'Approved':
            return 'bg-green-100 text-green-800 dark:bg-gray-700 dark:text-green-400 border-green-400';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400 border-gray-400';
    }
};

// const parsedRequirements = computed(() => {
//     try {
//         if (Array.isArray(props.requirements.requirements)) {
//             return props.requirements.requirements;
//         }
//         return JSON.parse(props.requirements.requirements);
//     } catch (error) {
//         console.error("Error parsing requirements JSON:", error);
//         return [];
//     }
// });

const Checking = ref(false);

const selectedRequirement = ref(null);

const toggleCheck = (req) => {
    selectedRequirement.value = req;
    Checking.value = true;
};

const closeModal = () => {
    Checking.value = false;
    selectedRequirement.value = null;
    resetForm();
};

const returnMessage = ref('');

const updateRequirementStatus = (status) => {

    if (selectedRequirement.value) {

        // Send an update request to the backend
        router.post('/scholarships/scholar/update-requirements', {
            submittedReq: selectedRequirement.value.id,
            status: status,
            message: status === 'Returned' ? returnMessage.value : null
        }, {
            onSuccess: () => {
                closeModal();
                toastMessage.value = `Requirement ${status.toLowerCase()} successfully!`;
                toastVisible.value = true;


                setTimeout(() => {
                    toastVisible.value = false;
                }, 3000);
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    }
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

<style>
/* override the prime vue componentss */

.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

/* Fade transition for backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>