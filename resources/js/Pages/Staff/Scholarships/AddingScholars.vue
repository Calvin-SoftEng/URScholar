<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                                Home
                        </li>
                        <li class="hover:text-gray-600">
                                <span>Scholarships</span>
                        </li>
                        <li class="hover:text-gray-600">
                                <span>Scholarship Batches</span>
                        </li>
                        <li>
                                <span class="text-blue-400 font-semibold">Adding Scholars</span>
                        </li>
                    </ul>
                </div>
                
                <div class="w-full flex flex-row justify-between dark:bg-dcontainer dark:border dark:border-gray-600 rounded-xl mb-3">
                    <div class="w-full flex justify-between ">
                        <div class="flex flex-col space-y-1">
                            <h1 class="text-4xl font-sora font-extrabold text-[darkblue] text-left dark:text-dtext">
                                <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span>
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="w-full h-full">
                    <Adding :scholarship="scholarship" :scholars="scholars" :schoolyear="schoolyear" :selectedSem="selectedSem" :batch="batch"
                    :campuses="campuses" :course="course" :errors="errors"/>
                </div>
                

                

                
                <!-- <div class="w-full mt-5">
                    <ul class="text-primary flex space-x-3 flex-grow justify-left">
                        <button @click="toggleList"><li class="px-4 py-2 border-b-2 cursor-pointer" :class="List ? 'border-blue-400 bg-white rounded-t-lg shadow-sm' : 'border-transparent'">Scholar List</li></button>
                        <button @click="toggleAdd" class="px-5 py-2 border-b-2 cursor-pointer " :class="addVisible ? 'border-blue-400 bg-white rounded-t-lg shadow-sm' : 'border-transparent'">Adding</button>
                    </ul>
                </div>

                <div v-if="List" class="w-full">
                    <ScholarList :scholarship="scholarship" :scholars="scholars"/>
                </div>
                <div v-if="addVisible" class="w-full h-full">
                    <Adding :scholarship="scholarship" :scholars="scholars"/>
                </div> -->


            </div>
        </div>

        <ToastProvider>
            <ToastRoot 
                v-if="toastVisible" 
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full"
            >
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
import { useForm, Link, usePage } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue,} from '@/Components/ui/select'

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
    scholars: Array,
    schoolyear: Object,
    selectedSem: Object,
    batch: Array,
    campuses: Array,
    course: Array,
    errors: Object,
});

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