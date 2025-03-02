<template>
    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold dark:text-gray-300">View Sponsors</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Partnered Sponsors
                    </h1>

                </div>

                <!-- List of Scholarships -->
                <div v-if="!Showcase">
                    <div class="py-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div v-for="sponsor in sponsors" :key="sponsor.id"
                                class="relative bg-white border border-gray-300 rounded-lg shadow-lg hover:shadow-xl hover:border-gray-400 
                                    dark:bg-dcontainer dark:border-gray-600 dark:hover:border-gray-400 
                                    before:absolute before:top-0 before:left-0 before:right-0 before:h-10 before:rounded-t-lg before:bg-white 
                                    dark:before:bg-dcontainer dark:before:border-dsecondary flex flex-col min-h-[400px]">

                                <!-- Logo Positioned Above Card with Border Around It -->
                                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-white dark:bg-dcontainer 
                                            p-1 rounded-full border border-gray-300 dark:border-dsecondary shadow-md">
                                    <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo"
                                        class="w-24 h-24 rounded-full" />
                                </div>

                                <!-- Card Content -->
                                <div class="mt-12 pt-12 p-3 flex flex-col flex-grow">
                                    <!-- Sponsor Name & Info -->
                                    <div class="flex flex-col items-center text-center gap-1">
                                        <span class="text-3xl font-semibold text-gray-800 dark:text-dtext">
                                            {{ sponsor.name }} 
                                            <span class="text-gray-500 dark:text-gray-400">({{ sponsor.abbreviation }})</span>
                                        </span>
                                        <span class="inline-flex bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-dsecondary dark:text-blue-400 border border-blue-400 dark:border-gray-600">
                                            Sponsoring Since: {{ sponsor.since }}
                                        </span>
                                    </div>


                                    <!-- Active Scholarships -->
                                    <div class="flex flex-col flex-grow mt-4 justify-end">
                                        <p class="text-sm text-gray-400 mb-2">Active Scholarships:</p>

                                        <div class="max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer flex flex-col gap-2">
                                            <!-- No Scholarships Available -->
                                            <div v-if="sponsor.scholarship.length === 0" 
                                                class="p-3 text-gray-500 text-center bg-gray-100 rounded-lg border border-gray-300 shadow-sm 
                                                    dark:bg-dsecondary dark:text-gray-400 dark:border-gray-600 flex items-center justify-center h-full">
                                                No scholarships available.
                                            </div>

                                            <!-- Scholarships List -->
                                            <div v-else v-for="scholarship in sponsor.scholarship" :key="scholarship.id">
                                                <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                                                    hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                                                    dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                                                
                                                    <!-- Scholarship Name -->
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-lg">
                                                            {{ scholarship.name }}
                                                        </span>
                                                        <span class="font-normal text-sm">
                                                            {{ scholarship.scholarshipType }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-row items-center gap-2">
                                                        <!-- Green Active Indicator -->
                                                        <span v-if="scholarship.status === 'Active'" 
                                                            class="w-3 h-3 bg-green-500 rounded-full shadow-md">
                                                        </span>

                                                        <!-- Status Text -->
                                                        <span class="text-sm font-medium">
                                                            {{ scholarship.status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sticky Button at the Bottom -->
                                <div class="p-3 mt-auto flex justify-end">
                                    <button @click="toggleCreate(sponsor.id)">
                                        <div class="text-sm text-gray-500 cursor-pointer"
                                            v-tooltip="'Create Scholarship'">
                                            <span
                                                class="material-symbols-rounded text-blue-900 dark:text-dtext bg-blue-100 hover:bg-gray-200 p-3 border rounded-lg dark:bg-dsecondary dark:border-gray-600 dark:hover:border-gray-300 dark:hover:bg-dsecondary">
                                                open_in_browser
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- creating a sponsor --> 
        <div v-if="isCreating"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                        Add New Scholarship
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                            Name</h3>
                        <input v-model="form.name" type="text" id="name"
                            placeholder="Enter Scholarship Name"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                            Type</h3>
                        <select v-model="form.scholarshipType" id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Select Scholarship Type</option>
                            <option value="Need-Based">Need-Based</option>
                            <option value="One-time Payment">One-time Payment</option>
                        </select>
                    </div>

                    <!-- Show only if One-time Payment is selected -->
                    <!-- <div v-if="form.scholarshipType === 'One-time Payment'" class="space-y-3">
                        <hr class="border-t border-gray-300 dark:border-gray-600 my-4">

                        <div class="flex flex-row gap-2">
                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Deadline</h3>
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <Button variant="outline"
                                            class="w-full h-10 justify-start text-left font-normal bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                                            <CalendarIcon class="mr-2 h-4 w-4" />
                                            {{ formatDate(form.birthdate) }}
                                        </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-auto p-0 ">
                                        <Calendar v-model="form.birthdate" initial-focus />
                                    </PopoverContent>
                                </Popover>
                            </div>

                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Limit Recipients</h3>
                                <input type="number" id="name"
                                    placeholder="Enter Number of Recipients"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                            </div>
                        </div>

                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Limit Course</h3>
                            <input type="text" id="name"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                    </div> -->

                    
                    <!-- <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">School Year</h3>
                        <input v-model="form.school_year" type="text" id="name"
                            placeholder="School Year"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                    </div>
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Semester</h3>
                        <select v-model="form.semester" id="scholarshipType"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                            <option value="" disabled>Select Semester</option>
                            <option value="merit">First Semester</option>
                            <option value="need">Second Semester</option>
                        </select>
                    </div> -->
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Create Scholarship</button>
                    </div>
                </form>
            </div>
        </div>
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Sponsor Added Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, watchEffect } from 'vue';
import { usePage } from "@inertiajs/vue3";
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { set } from 'date-fns';
import { DatePicker } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'


const props = defineProps({
    sponsors: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};

const isCreating = ref(false);
const isEditing = ref(false);
const Showcase = ref(false);
const sponsorid = ref(null);


const form = ref({
    sponsor_id: null,
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});

// const toggleCreate = (sponsorID) => {
//     isCreating.value = !isCreating.value;
//     if (isCreating.value) {
//         sponsorid.value = sponsorID;
//         form.value.sponsor_id = sponsorID;
//     }
// };

const toggleCreate = (sponsorID) => {
    isCreating.value = isCreating.value === sponsorID ? null : sponsorID;
    if (isCreating.value) {
        sponsorid.value = sponsorID;
        form.value.sponsor_id = sponsorID;
    }
};


const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const editScholarship = (scholarship) => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};


const resetForm = () => {
    form.value = { id: null, name: '', description: '', scholarshipType: '', school_year: '', semester: '', application: '', deadline: '' };
};


const submitForm = async () => {
    try {
        router.post(`/sponsors/create-scholarship`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const isPublishing = ref(false);

const toggleSetActive = () => {
    isPublishing.value = !isPublishing.value;
    currentPage.value = 1;
    if (isPublishing.value) {
        resetForm();
    }
};

// dynamic requirements
const newItem = ref('');
const items = ref([]);

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push(newItem.value.trim());
        form.value.requirements = items.value;
        newItem.value = '';

        
    }
};


const addRequirement = () => {
  if (newRequirement.value.name.trim()) {
    form.requirements[newRequirement.value.name.trim()] = {
      type: newRequirement.value.type,
      required: true
    };
    newRequirement.value.name = '';
  }
};

const removeItem = (index) => {
    items.value = items.value.filter((_, i) => i !== index);
};

const currentPage = ref(1);

const nextPage = () => {
    if (currentPage.value < 3) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
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


const df = new DateFormatter('en-US', {
    dateStyle: 'long',
})

const formatDate = (date) => {
    if (!date) return "Pick a date";
    return new Intl.DateTimeFormat("en-US", { dateStyle: "medium" }).format(new Date(date));
};

</script>

<style scoped>
.test {
    z-index: 9999;
}
</style>