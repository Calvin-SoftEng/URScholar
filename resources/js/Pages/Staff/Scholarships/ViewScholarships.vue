<template>

    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li class="hover:text-gray-600">
                        <li>
                            <span class="text-blue-400 font-semibold dark:text-gray-300">Scholarships</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS
                        Scholarships
                    </h1>
                </div>

                <div class="mx-auto py-5">
                    <div class="flex w-full flex-col gap-6">

                        <!-- Need-Based Scholarships -->
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                            Grant-Based Scholarships
                        </h2>

                        <template v-if="grantBasedScholarships.length > 0">
                            <button v-for="scholarship in grantBasedScholarships" :key="scholarship.id"
                                @click="toggleSpecification(scholarship)" class="w-full text-left">
                                <div
                                    class="relative border rounded-2xl bg-white dark:bg-dcontainer dark:border-gray-700 hover:shadow-lg transition-all duration-300 p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <!-- Notification Badge -->
                                    <span v-if="scholarship.read !== 1"
                                        class="absolute -top-3 left-4 bg-dprimary dark:bg-white dark:text-dprimary  text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                        New
                                    </span>

                                    <!-- Scholarship Info -->
                                    <div class="flex-1 space-y-3">
                                        <!-- Sponsor -->
                                        <div
                                            class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900 text-xs font-medium px-3 py-1 rounded-full">
                                            {{ getSponsorName(scholarship.sponsor_id) }}
                                        </div>

                                        <!-- Scholarship Name -->
                                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-dtext ">
                                            {{ scholarship.name }}
                                        </h2>

                                        <!-- Dates -->
                                        <div class="text-sm text-dprimary opacity-70 dark:text-dtext space-y-1">
                                            <p><font-awesome-icon :icon="['far', 'calendar']" class="mr-1" /> Created:
                                                {{
                                                    new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                }) }}</p>
                                            <p><font-awesome-icon :icon="['fas', 'circle-dollar-to-slot']"
                                                    class="mr-1" />
                                                Sponsoring Since:
                                                {{ getSponsorNSince(scholarship.sponsor_id) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end">
                                        <!-- 3-dot menu -->
                                        <div class="relative">
                                            <button
                                            @click="toggleMenu"
                                            class="w-8 h-8 flex items-center justify-center border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition absolute right-0 -top-3 translate-y-[-100%] translate-x-[20px]"
                                            >
                                            <font-awesome-icon :icon="['fas', 'pen-to-square']" class="text-myblack" />
                                            </button>

                                        </div>
                                        <!-- Additional Info -->
                                        <div class="flex justify-end items-end gap-6 text-center px-4 py-3 bg-white dark:bg-dcontainer rounded-xl shadow-sm border border-gray-200 dark:border-gray-600">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Batches</span>
                                                <span class="text-2xl font-semibold text-gray-900 dark:text-dtext">{{scholarship.batches ? scholarship.batches.length : 0 }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </button>
                        </template>
                        <div v-else class="text-gray-500 text-center py-4">No available scholarships.</div>



                        <!-- One-Time Payment Scholarships -->
                        <h2
                            class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                            One-Time Payment Scholarships
                        </h2>

                        <template v-if="oneTimeScholarships.length > 0">
                            <button v-for="scholarship in oneTimeScholarships" :key="scholarship.id"
                                @click="toggleSpecification(scholarship)" class="w-full text-left">

                                <div
                                    class="relative border rounded-2xl bg-white dark:bg-dcontainer dark:border-gray-700 hover:shadow-lg transition-all duration-300 p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <!-- Notification Badge -->
                                    <span
                                        class="absolute -top-3 left-4 bg-dprimary dark:bg-white dark:text-dprimary text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                        New Activities
                                    </span>

                                    <!-- Scholarship Info -->
                                    <div class="flex-1 space-y-3">
                                        <!-- Sponsor -->
                                        <div
                                            class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-200 dark:text-blue-900 text-xs font-medium px-3 py-1 rounded-full">
                                            {{ getSponsorName(scholarship.sponsor_id) }}
                                        </div>

                                        <!-- Scholarship Name -->
                                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                            {{ scholarship.name }}
                                        </h2>

                                        <!-- Dates -->
                                        <div class="text-sm text-dprimary opacity-70 dark:text-dtext space-y-1">
                                            <p><font-awesome-icon :icon="['far', 'calendar']" class="mr-1" /> Created:
                                                {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric'
                                                }) }}</p>
                                            <p><font-awesome-icon :icon="['fas', 'circle-dollar-to-slot']"
                                                    class="mr-1" /> Sponsoring Since:
                                                {{ scholarship.since }}
                                            </p>
                                            <!-- <p>
                                                <font-awesome-icon :icon="['fas', 'clock']" class="mr-1" /> <span
                                                    class="font-medium">Requirements Deadline: </span>
                                                <span
                                                    v-if="scholarship.requirements && scholarship.requirements.length > 0 && scholarship.requirements[0].date_end">
                                                    {{ new
                                                        Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US',
                                                            {
                                                                year: 'numeric',
                                                                month: 'long',
                                                                day: 'numeric'
                                                            }) }}
                                                </span>
                                                <span v-else>No Deadline</span>
                                            </p> -->
                                        </div>
                                    </div>
                                    <!-- 3-dot menu -->
                                    <div class="relative">
                                        <button
                                        @click="toggleMenu"
                                        class="w-8 h-8 flex items-center justify-center border rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition absolute right-0 -top-12 translate-y-[-100%] translate-x-[20px]"
                                        >
                                        <font-awesome-icon :icon="['fas', 'pen-to-square']" class="text-myblack" />
                                        </button>

                                    </div>
                                </div>
                            </button>
                        </template>
                        <div v-else class="text-gray-500 text-center py-4">No available scholarships.</div>

                    </div>
                </div>


            </div>
        </div>
        <div v-if="ScholarshipSpecification"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-dprimary rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between px-4 py-2 md:px-5 rounded-t">
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <div class="bg-white dark:bg-dprimary flex justify-center items-center p-5 rounded-lg">
                    <div class="flex flex-col space-y-2 items-center">
                        <h1 class="text-4xl font-sora font-extrabold text-[darkblue] text-center dark:text-dtext">
                            {{ selectedScholarship.name }}<span> Scholars</span>
                        </h1>

                        <div class="py-5 text-gray-500 dark:text-gray-300">
                            Select School Year and Semester
                        </div>
                        <div class="grid grid-cols-3 justify-center items-center gap-3">
                            <InputError v-if="errors?.selectedSem" :message="errors.selectedSem"
                                class="text-2xs text-red-500 col-span-3 flex justify-center items-center" />
                            <div
                                class="col-span-1 text-dprimary dark:text-dtext font-quicksand font-bold text-base justify-center">
                                Academic Year:
                            </div>
                            <div class="col-span-2 w-full">
                                <Select v-model="selectedYear" required @update:modelValue="updateSemesters">
                                    <SelectTrigger class="w-full border"
                                        :class="formErrors.selectedYear ? 'border-red-500' : 'border-gray-300'">
                                        <SelectValue placeholder="Select year" />
                                    </SelectTrigger>
                                    <SelectContent class="dark:bg-white dark:text-dprimary">
                                        <SelectGroup v-for="schoolyear in [...schoolyears].reverse()"
                                            :key="schoolyear.id">
                                            <SelectItem :value="schoolyear.id" class="dark:hover:bg-gray-700">
                                                {{ schoolyear.year }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div
                                class="col-span-1 text-dprimary dark:text-dtext font-quicksand font-bold text-base justify-center">
                                Semester:
                            </div>
                            <div class="col-span-2 w-full">
                                <Select v-model="selectedSem" required :disabled="!availableSemesters.length">
                                    <SelectTrigger class="w-full border"
                                        :class="formErrors.selectedSem ? 'border-red-500' : 'border-gray-300'">
                                        <SelectValue placeholder="Select Semester" />
                                    </SelectTrigger>
                                    <SelectContent class="dark:bg-white dark:text-dprimary">
                                        <SelectGroup>
                                            <SelectItem
                                            class="dark:hover:bg-gray-700 flex justify-between items-center"
                                            v-for="semester in availableSemesters"
                                            :key="semester.id"
                                            :value="semester.semester"
                                            >
                                            <div class="flex items-center gap-2">
                                                {{ semester.semester === '1st' ? 'First Semester' : 'Second Semester' }}
                                                <span
                                                class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                                :class="semester.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600'"
                                                >
                                                {{ semester.status }}
                                                </span>
                                            </div>
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <p v-if="formErrors.selectedSem" class="text-red-500 text-sm mt-1">
                            {{ formErrors.selectedSem }}
                        </p>
                        <p v-if="formErrors.selectedYear" class="text-red-500 text-sm mt-1">
                            {{ formErrors.selectedYear }}
                        </p>
                        <div class="pt-10 w-full">
                            <button @click="openScholarship"
                                class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Proceed to Scholarship
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- editing scholarship name -->
        <div v-if="isEditing"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">

                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Edit the Scholarship
                            </h2>
                        </div>
                    </div>


                    <!-- Close Button -->
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-10">

                    <!-- Page 1: Basic Information -->
                    <div>
                        <div class="flex flex-col gap-3">
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Name</h3>
                                <input v-model="form.name" type="text" id="name" placeholder="Enter Scholarship Name" autocomplete="off"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                            </div>
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Type</h3>
                                <select v-model="form.scholarshipType" id="scholarshipType"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                    <option value="" disabled>Select Scholarship Type</option>
                                    <option value="Grant-Based">Grant-Based</option>
                                    <option value="One-time Payment">One-time Payment</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-2 flex justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span v-for="index in totalPages" :key="index"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="isActive(index).value ? 'bg-blue-500 w-7' : 'bg-gray-300 w-2'">
                            </span>
                        </div>

                        <div class="flex justify-end gap-2">
                            <!-- Cancel Button (Always Visible) -->
                            <button type="button" @click="cancel"
                                class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-400 shadow-sm rounded-lg text-sm px-5 py-2.5">
                                Cancel
                            </button>

                            <!-- Next Button: Only Show if Scholarship Type is Selected & Not Grant-Based -->
                            <!-- <template v-if="form.scholarshipType && form.scholarshipType !== 'Grant-Based'">
                                <button v-if="currentPage < totalPages" type="submit"
                                    class="text-white bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-7 py-2.5">
                                    Proceed to Setup
                                </button>
                            </template> -->

                            <button 
                                class="text-white bg-[#8E0000] hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-5 py-2.5">
                                Delete
                            </button>

                            <!-- Direct "Create Scholarship" Button if Grant-Based is Selected -->
                            <button 
                                class="text-white bg-navy hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-5 py-2.5">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toastMessage }}</ToastTitle>
                <!-- <ToastDescription class="text-gray-100 dark:text-dtext"></ToastDescription> -->
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed, onUnmounted, watch, watchEffect } from 'vue';
import { Head, useForm, Link, router, usePage } from '@inertiajs/vue3';
import { useRouter, useRoute } from 'vue-router'
import Echo from 'laravel-echo';
import InputError from '@/Components/InputError.vue';

import { Tooltip } from 'primevue';

import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    },
    schoolyears: {
        type: Array,
        required: true
    },
    errors: Object,
    flash: Object,
});

onMounted(() => {
    const echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        authEndpoint: "/broadcasting/auth", // Required for private channels
    });

    // Listen for general notifications
    echo.channel('general-notifications')
        .listen('GeneralNotification', (event) => {
            // Check if this is a scholarship read notification
            if (event.type === 'scholarship_read' &&
                event.data.scholarship_id === scholarship.value.id) {
                scholarship.value.read = event.data.read
            }
        })
})


const directives = {
    Tooltip,
};

const grantBasedScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'Grant-Based')
);

const oneTimeScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'One-time Payment')
);

const errorMessage = ref(null);

const form = ref({
    id: null,
    name: '',
    description: '',
    sponsor_id: ''
});

const getSponsorName = (sponsorId) => {
    const sponsor = props.sponsors.find(s => s.id === sponsorId);
    return sponsor ? sponsor.name : 'Unknown Sponsor';
};

const getSponsorNSince = (sponsorId) => {
    const sponsor = props.sponsors.find(s => s.id === sponsorId);
    return sponsor ? sponsor.since : 'Unknown Sponsor';
};

// const formatDate = (date) => {
//     return new Date(date).toLocaleDateString();
// };

const selectedYear = ref("");
const selectedSem = ref("");

const selectedScholarship = ref(null);

const formErrors = ref({
    selectedSem: "",
    selectedYear: "",
});

const ScholarshipSpecification = ref(false);

const toggleSpecification = (Scholarship) => {
    ScholarshipSpecification.value = !ScholarshipSpecification.value;

    if (ScholarshipSpecification.value) {
        selectedScholarship.value = Scholarship;
    }
    resetForm();
};

const availableSemesters = ref([]);

const updateSemesters = () => {
    if (!selectedYear.value) {
        availableSemesters.value = [];
        selectedSem.value = "";
        return;
    }

    const selectedSchoolYear = props.schoolyears.find(sy => sy.id === selectedYear.value);
    if (selectedSchoolYear && selectedSchoolYear.academic_year) {
        // If academic_year is an array of semester objects
        availableSemesters.value = Array.isArray(selectedSchoolYear.academic_year)
            ? selectedSchoolYear.academic_year
            : [selectedSchoolYear.academic_year];
    } else {
        availableSemesters.value = [];
    }

    // Reset semester selection when changing year
    selectedSem.value = "";
};

const isEditing = ref(false);

const toggleMenu = () => {
    isEditing.value = !isEditing.value;
    ScholarshipSpecification.value = true;
};



const date_end = new Date(props.scholarships.date_end).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric"
});


const openScholarship = () => {
    formErrors.value.selectedSem = "";
    formErrors.value.selectedYear = "";

    // Validate
    if (!selectedSem.value && !selectedYear.value) {
        formErrors.value.selectedSem = "Semester selection is required.";
        formErrors.value.selectedYear = "Academic Year selection is required.";
        return; // Stop form submission
    }

    const formData = new FormData();
    formData.append("selectedYear", selectedYear.value);
    formData.append("semester", selectedSem.value); // Make sure it's being passed


    router.visit(`/scholarships/${selectedScholarship.value.id}`, {
        data: { selectedYear: selectedYear.value, selectedSem: selectedSem.value },
        preserveState: true
    });
};


const closeModal = () => {
    ScholarshipSpecification.value = false;
    selectedScholarship.value = null;
    selectedYear.value = "";
    selectedSem.value = "";
    formErrors.value.selectedSem = "";
    formErrors.value.selectedYear = "";
    isEditing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        description: '',
        sponsor_id: '',
        selectedYear: null,
        selectedSem: null,
    };
};

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
});

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

<style scoped>
.p-tooltip-text {
    margin-left: 0px;
    font-size: 13px !important;
}
</style>