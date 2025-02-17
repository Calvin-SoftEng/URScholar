<template>
    <AuthenticatedLayout>
        <!-- <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] dark:bg-dprimary overflow-auto">
            <div class="w-full mx-auto p-3 rounded-xl text-white">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholarship Batches</span>
                        </li>
                    </ul>
                </div>

                <div
                    class="w-full flex flex-row justify-between dark:bg-dcontainer dark:border dark:border-gray-600 rounded-xl mb-3">
                    <div class="w-full flex justify-between ">
                        <div class="flex flex-col space-y-1">
                            <h1 class="text-4xl font-sora font-extrabold text-[darkblue] text-left dark:text-dtext">
                                <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span>
                            </h1>

                        </div>
                        <button @click="openScholarship"
                            class="text-primary bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 hover:border-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition duration-150 ease-in-out"
                            type="button">
                            Add Scholars
                        </button>


                    </div>
                </div>

                <div class="bg-white">

                    <ScholarList :scholarship="scholarship" :batches="batches"/>
                    
                </div>

            </div>
        </div> -->
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholarship Batches</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <!-- <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span> -->
                        <span>{{ scholarship.name }}</span> <span>scholarship type</span>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="`/scholarships/${props.scholarship.id}/send-access`">
                            <button class="px-4 py-2 text-sm text-primary bg-dirtywhite border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                                <span><font-awesome-icon :icon="['fas', 'user-plus']" class="mr-2 text-sm"/>Import Scholars</span>
                            </button>
                        </Link>
                    <button @click="importScholars" class="px-4 py-2 text-sm text-primary bg-dirtywhite border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                        <span><font-awesome-icon :icon="['far', 'envelope']" class="mr-2 text-sm"/>Send Email</span>
                    </button>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-gray-200"></div>

                <!-- Stats Section -->
                <div class="grid grid-cols-5">
                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Scholarship Batches</p>
                        </div>
                        <div class="w-full flex flex-row justify-between space-x-3 items-end">
                            <p class="text-4xl font-semibold font-kanit">55</p>
                            <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new Batch</button>
                        </div>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Total Verified Scholars</p>
                        </div>
                        <div class="w-full flex flex-row justify-between space-x-3 items-end">
                            <p class="text-4xl font-semibold font-kanit">55</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Unverified Scholars</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">1</p>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Submitted Requirements</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">2</p>
                    </div>

                    <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                        <div class="flex flex-row space-x-3 items-center">
                            <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base"/>
                            <p class="text-gray-500 text-sm">Completed Scholars</p>
                        </div>
                        <p class="text-4xl font-semibold font-kanit">2</p>
                    </div>
                </div>

                <div class="w-full h-[1px] bg-gray-200"></div>
                
                <!-- <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] dark:bg-dprimary overflow-auto">
                    <div class="w-full mx-auto p-3 rounded-xl text-white"

                            <ScholarList :scholarship="scholarship" :batches="batches" />
                            
                    </div>
                </div> -->
                <ScholarList :scholarship="scholarship" :batches="batches" />
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

// components

const components = {
    Button,
    Papa,
};

const addVisible = ref(false);
const List = ref(true);

const toggleAdd = () => {
    addVisible.value = true;
    List.value = false;
};

const toggleList = () => {
    List.value = true;
    addVisible.value = false;
};
// Reactive variables to track which tab is open
const activeTab = ref("scholars"); // Default to Scholars

// Functions to toggle the active tab
const toggleScholars = () => {
    activeTab.value = "scholars";
};

const toggleReqs = () => {
    activeTab.value = "requirements";
};

const toggleMonitoring = () => {
    activeTab.value = "monitoring";
};

const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: Object,
    batches: Array,
});

const selectedSem = ref("");

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};




const formData = ref({
    file: null,
    // other form fields...
});

const updateFile = (file) => {
    formData.value.file = file;
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