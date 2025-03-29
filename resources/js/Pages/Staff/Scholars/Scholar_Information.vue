<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholars</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholar Information</span>
                        </li>
                    </ul>
                </div>

                <div class="w-full h-full flex justify-center items-center dark:text-dprimary relative">
                    <!-- Close Button -->
                    <button class="absolute top-4 right-20">
                        <span
                            class="material-symbols-rounded p-2 rounded-full bg-white dark:bg-dcontainer text-blue-900 dark:text-dprimary shadow-md hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            arrow_back
                        </span>
                    </button>

            
                        <!-- 25% Column -->
                        <div class="h-full flex flex-col w-7/12">
                            <div class="h-full rounded-xl p-3 shadow-md bg-white dark:bg-dcontainer flex flex-col space-y-5">
                            <div class="flex-1 flex flex-col space-y-5">
                                
                                <!-- Profile Image & Name -->
                                <div class="flex flex-row items-center justify-center p-2">
                                    <div class="w-32 h-32 flex items-center justify-center text-gray-500 text-xl">
                                        <img src="../../../../assets/images/no_userpic.png" alt="" class="rounded-lg">
                                    </div>
                                    <div class="flex flex-col items-center justify-center p-2">
                                    
                                    <span class="text-lg font-semibold mt-2">John Michael Doe</span>
                                    <div>
                                        <div class="flex space-x-2">
                                            <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded">URS-20200</span>
                                            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded">URS Binangonan</span>
                                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Active</span>
                                            <span class="px-2 py-1 bg-purple-200 text-purple-800 rounded">BSIT</span>
                                        </div>

                                    </div>
                                    </div>
                                </div>

                                <div class="w-full h-0.5 bg-gray-100"></div>

                                <div class="space-y-2">
                                    <div v-for="(section, index) in sections" :key="index" class="border-b border-gray-300">
                                        <button
                                            class="w-full text-left p-4 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                                            @click="toggleSection(index)"
                                        >
                                            <span class="font-semibold">{{ section.title }}</span>
                                            <span>{{ section.isOpen ? '▲' : '▼' }}</span>
                                        </button>

                                        <!-- Personal Information -->
                                        <div v-if="section.isOpen && section.title === 'Personal Information'" class="p-4 bg-white border-t border-gray-200">
                                            <div class="flex flex-col p-2 space-y-2">
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">First Name</span>
                                                    <span class="text-base text-primary">Carl Vincent</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Last Name</span>
                                                    <span class="text-base text-primary">Catahimican</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Middle Name</span>
                                                    <span class="text-base text-primary">Soriano</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Suffix</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Birthdate</span>
                                                    <span class="text-base text-primary">May 20, 2001</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Place of Birth</span>
                                                    <span class="text-base text-primary">Manila, Philippines</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Age</span>
                                                    <span class="text-base text-primary">23</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Sex</span>
                                                    <span class="text-base text-primary">Male</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Civil Status</span>
                                                    <span class="text-base text-primary">Single</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Religion</span>
                                                    <span class="text-base text-primary">Roman Catholic</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Guardian Name</span>
                                                    <span class="text-base text-primary">Guardian Name</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Relationship to Guardian</span>
                                                    <span class="text-base text-primary">Father</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Education -->
                                        <div v-if="section.isOpen && section.title === 'Education'" class="p-4 bg-white border-t border-gray-200">
                                            <div class="flex flex-col p-2 space-y-2">
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Elementary School</span>
                                                    <span class="text-base text-primary">Name of School</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">2016-2020</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Junior High School</span>
                                                    <span class="text-base text-primary">Name of School</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">2016-2020</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Senior High School</span>
                                                    <span class="text-base text-primary">Name of School</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">2016-2020</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">College</span>
                                                    <span class="text-base text-primary">Name of School</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">2016-2020</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Vocational</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Post Graduate</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Years Attended</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Honors/Awards Received</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Information -->
                                        <div v-if="section.isOpen && section.title === 'Contact Information'" class="p-4 bg-white border-t border-gray-200">
                                            <div class="flex flex-col p-2 space-y-2">
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Email</span>
                                                    <span class="text-base text-primary">Dummy@gmail,.com</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Phone Number</span>
                                                    <span class="text-base text-primary">09123456789</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Family Details -->
                                        <div v-if="section.isOpen && section.title === 'Family Details'" class="p-4 bg-white border-t border-gray-200">
                                            <div class="flex flex-col p-2 space-y-2">
                                                <h3 class="font-bold text-lg">Mother</h3>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">First Name</span>
                                                    <span class="text-base text-primary">Maria</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Last Name</span>
                                                    <span class="text-base text-primary">Dela Cruz</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Middle Name</span>
                                                    <span class="text-base text-primary">Santos</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Age</span>
                                                    <span class="text-base text-primary">45</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Citizenship</span>
                                                    <span class="text-base text-primary">Filipino</span>
                                                </div>
                                                <h3 class="font-bold text-lg">Father</h3>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">First Name</span>
                                                    <span class="text-base text-primary">Juan</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Last Name</span>
                                                    <span class="text-base text-primary">Dela Cruz</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Middle Name</span>
                                                    <span class="text-base text-primary">Santos</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Age</span>
                                                    <span class="text-base text-primary">50</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Citizenship</span>
                                                    <span class="text-base text-primary">Filipino</span>
                                                </div>
                                                <h3 class="font-bold text-lg">Siblings</h3>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Name</span>
                                                    <span class="text-base text-primary">Jose Dela Cruz</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Age</span>
                                                    <span class="text-base text-primary">18</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Occupation</span>
                                                    <span class="text-base text-primary">Student</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Extracurricular -->
                                        <div v-if="section.isOpen && section.title === 'Extracurricular'" class="p-4 bg-white border-t border-gray-200">
                                            <div class="flex flex-col p-2 space-y-2">
                                                <h3 class="font-bold text-lg">Organizational Membership</h3>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Name of Organization</span>
                                                    <span class="text-base text-primary">Ex. USSG</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Inclusive Dates of Membership</span>
                                                    <span class="text-base text-primary">Ex. 2022 - Present</span>
                                                </div>
                                                <div class="flex flex-col text-black">
                                                    <span class="font-semibold uppercase text-xs text-gray-500">Position Held</span>
                                                    <span class="text-base text-primary">N/A</span>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                </div>
                            </div>

                            </div>
                        </div>


                </div>

                <!-- viewing docs -->
                <div v-if="Checking"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
                    <div
                        class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-10/12 max-h-[95vh] overflow-y-auto">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Call Document Name Here</h2>
                            <button type="button"
                                class="flex items-center gap-2 text-gray-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm px-3 py-1.5 dark:hover:bg-gray-600 dark:hover:text-white transition"
                                data-modal-hide="default-modal">
                                <span class="material-symbols-rounded text-lg">open_in_new</span>
                                <span class="font-medium">Open in New Tab</span>
                            </button>
                        </div>


                        <div class="p-4 flex flex-col gap-3">
                            <div class="w-full flex flex-col p-10">
                                <img src="../../../../assets/images/psa_sample.png" alt="">
                            </div>

                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">*If Returning Requirement</h3>
                                <textarea id="return-requirement" placeholder="Add a message in returning"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full h-32 resize-none text-left dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"></textarea>
                            </div>


                            <!-- Close Button -->
                            <div class="mt-2 flex flex-row justify-end">
                                <button type="button" @click="closeModal"
                                    class="text-white font-sans w-4/12 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                    Save
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
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

import Adding from '../../../Components/Staff/ScholarsTabs/Adding.vue';

import ScholarList from '../../../Components/Staff/ScholarsTabs/ScholarList.vue';
import { Check } from 'lucide-vue-next';

// components

const props = defineProps({
    scholar: Object,
    scholarship: Object,
});

const components = {
    Button,
    Papa,
};


const sections = ref([
  { title: 'Personal Information', isOpen: false },
  { title: 'Education', isOpen: false },
  { title: 'Contact Information', isOpen: false },
  { title: 'Family Details', isOpen: false },
  { title: 'Education History', isOpen: false },
  { title: 'Extracurricular', isOpen: false }
]);

const toggleSection = (index) => {
  sections.value[index].isOpen = !sections.value[index].isOpen;
};
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