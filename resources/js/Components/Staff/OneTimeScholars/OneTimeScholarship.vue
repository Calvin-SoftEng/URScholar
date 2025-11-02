<template>
    <!-- <div v-else-if="scholarship.scholarshipType == 'One-time Payment'"> -->
    <div>
        <form @submit.prevent="submitForm">
            <div class="pb-20 overflow-auto h-full scroll-py-4">
                <!-- <div class="mx-auto max-w-8xl sm:px-6 lg:px-8 "> -->
                <div
                    class="w-full block bg-white dark:bg-dsecondary py-6 flex-col items-center mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-lg font-semibold text-gray-700 dark:text-dtext">
                            Edit One-Time Payment Scholarship Details
                        </span>
                        <button type="submit" @click="submitForm"
                            class="px-6 py-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                            Save Scholarships
                        </button>
                    </div>
                    <div class="mt-5 font-inter text-lg space-y-3">
                        <div class="flex flex-row w-full gap-3">
                            <div class="flex flex-col space-y-2 w-full">
                                <label for="suffixName"
                                    class="text-sm font-medium text-gray-700 dark:text-dtext">Scholarship
                                    Name</label>
                                <!-- <input id="suffixName" :value="scholarship.name" readonly type="text"
                                    placeholder="Scholarship Name"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" /> -->
                                <input id="suffixName" readonly type="text"
                                    placeholder="Scholarship Name" :value="props.scholarship?.name"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                            </div>

                            <div class="flex flex-col space-y-2 w-full">
                                <label for="suffixName"
                                    class="text-sm font-medium text-gray-700 dark:text-dtext">Scholarship
                                    Type</label>
                                <!-- <input id="suffixName" :value="scholarship.scholarshipType" readonly
                                    type="text" placeholder="Scholarship Type"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" /> -->
                                <input id="suffixName" readonly :value="props.scholarship?.scholarshipType"
                                    type="text" placeholder="Scholarship Type"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                            </div>
                        </div>

                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <div class="flex flex-row w-6/12 gap-3 pr-2">
                            <div id="date-range-picker" date-rangepicker
                                class="flex items-center gap-4 w-full">
                                <!-- Application Start Date -->
                                <div class="flex flex-col w-full">
                                    <label for="datepicker-range-start"
                                        class="text-sm font-medium text-gray-700 mb-1 dark:text-dtext">Application
                                        Start</label>
                                    <InputError v-if="errors?.application" :message="errors.application"
                                        class="text-2xs text-red-500" />
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-range-start" name="start" type="text"
                                            autocomplete="off"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select start date">
                                    </div>
                                </div>

                                <span class="text-gray-500">to</span>

                                <!-- Application Deadline -->
                                <div class="flex flex-col w-full">
                                    <label for="datepicker-range-end"
                                        class="text-sm font-medium text-gray-700 mb-1 dark:text-dtext">Application
                                        Deadline</label>
                                    <InputError v-if="errors?.deadline" :message="errors.deadline"
                                        class="text-2xs text-red-500" />
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <input id="datepicker-range-end" name="end" type="text"
                                            autocomplete="off"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Select end date">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                            <div>
                                <!-- Total Recipients Input -->
                                <div class="flex w-6/12 pr-2 flex-col">
                                    <label for="totalRecipients"
                                        class="text-sm font-medium text-gray-700 dark:text-dtext">
                                        Number of Recipients
                                    </label>
                                    <input id="totalRecipients" type="number" v-model="form.totalRecipients"
                                        min="1" placeholder="Enter total recipients"
                                        class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded-lg"
                                        @input="distributeRecipients" />
                                </div>

                                <!-- Left Side: Campus Selection & Recipient Distribution -->
                                <div class="flex flex-col space-y-3">
                                    <!-- Header with Label & Stats -->
                                    <div class="flex flex-row justify-between items-center py-2">
                                        <div class="flex flex-col space-y-2">
                                            <label class="text-sm font-medium dark:text-dtext">Distribute
                                                Recipients per
                                                Selected Campus</label>
                                            <div class="flex flex-row text-sm gap-4 dark:text-dtext">
                                                <div>Allocated: {{ allocatedRecipients }} of {{
                                                    form.totalRecipients
                                                    }}</div>
                                                <div v-if="allocatedRecipients !== parseInt(form.totalRecipients)"
                                                    class="text-red-500 font-medium dark:text-dtext">
                                                    *{{ parseInt(form.totalRecipients) - allocatedRecipients
                                                    }}
                                                    recipients needs to be allocated
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col text-sm">
                                            <!-- Stats & Reset Button -->
                                            <div class="flex items-center text-sm text-gray-600 space-x-3">
                                                <!-- Reset to Auto-Distribution Button -->
                                                <button @click="distributeRecipients"
                                                    class="px-2 text-gray-700 flex items-center space-x-1 hover:text-blue-600">
                                                    <span
                                                        class="material-symbols-rounded text-base dark:text-dtext">restart_alt</span>
                                                    <span class="text-sm dark:text-dtext">Reset to Auto
                                                        Distribution</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campus Selection List -->
                                    <InputError v-if="errors?.campus_recipients"
                                        :message="errors.campus_recipients" class="text-2xs text-red-500" />
                                    <div class="space-y-2">
                                        <div v-for="campus in campusesData" :key="campus.id"
                                            class="flex items-center justify-between border p-2 rounded-md">

                                            <input type="checkbox" :id="`campus-${campus.id}`"
                                                v-model="campus.selected" @change="distributeRecipients"
                                                class="w-5 h-5 rounded border-gray-300 cursor-pointer" />


                                            <label :for="`campus-${campus.id}`"
                                                class="text-base font-medium leading-none cursor-pointer text-gray-700 flex-grow pl-2 dark:text-dtext">
                                                {{ campus.name }}
                                            </label>


                                            <input type="number" v-model="campus.recipients"
                                                :readonly="!campus.selected"
                                                class="w-16 px-2 py-1 border rounded-md text-center text-gray-700 disabled:bg-gray-100"
                                                min="0" :max="form.totalRecipients"
                                                @input="onRecipientManualChange(campus.id)" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Course List Display Grouped by Campus -->
                            <div class="flex flex-col space-y-4 overflow-y-auto flex-grow scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded"> <!-- You can adjust max-h based on your needs -->
                                <div v-for="campus in selectedCampuses" :key="campus.id" class="py-3 px-4 bg-gray-50 dark:bg-dnavy border rounded-md">
                                    <!-- Selected Campus Name -->
                                    <div class="text-sm font-semibold text-gray-700 mb-2 dark:text-dtext">
                                    {{ campus.name }}
                                    </div>

                                    <!-- Courses Offered for this Campus -->
                                    <div v-for="course in campus.courses" :key="course" class="mt-1">
                                    <input type="checkbox" :id="`course-${campus.id}-${course}`" v-model="selectedCoursesMap[course]" class="rounded" />
                                    <label :for="`course-${campus.id}-${course}`" class="text-sm ml-2 cursor-pointer dark:text-dtext">
                                        {{ course }}
                                    </label>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <h3 class="text-base font-medium text-black dark:text-dtext">List
                            Criteria and Eligibility</h3>
                            <div class="grid grid-cols-2 space-x-2">
                                <div class="w-full flex flex-col p-2">

                                    <div class="space-y-4">
                                        <div class="flex flex-col justify-center items-start">
                                            <div class="flex flex-row space-x-3 items-start">
                                                <span
                                                    class="text-sm font-medium text-black whitespace-nowrap mb-2 dark:text-dtext">General
                                                    Weighted Average must be:
                                                </span>
                                                
                                            <InputError v-if="errors?.conditions" :message="errors.conditions"
                                                class="text-2xs text-red-500" />
                                            </div>  

                                            <input v-model="form.grade" type="text" id="name"
                                                placeholder="Enter Grade Criteria (e.g., GWA 95 1.1)"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-base w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />

                                        </div>

                                        <div class="flex flex-col space-y-2 justify-center items-start">
                                            <span
                                                class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                Must be enrolled in:
                                            </span>

                                            <textarea v-model="selectedCoursesText" id="name" rows="3"
                                                placeholder="Specific courses will appear here if there are any selected... If none, will show for All Courses"
                                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-base w-full resize-none dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"
                                                readonly>
                                        </textarea>
                                        </div>

                                        <InputError v-if="errors?.conditions" :message="errors.conditions"
                                            class="text-2xs text-red-500" />
                                        <div v-for="eligiblity in eligibilities" :key="eligiblity.id"
                                            class="flex flex-col justify-center space-y-2 items-start">
                                            <span
                                                class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                {{ eligiblity.name }}
                                            </span>
                                            <div v-for="conditions in getFormData(eligiblity.id)"
                                                :key="conditions.id" class="flex items-center space-x-2 mb-1">
                                                <input id="accept-terms-{{ conditions.id }}" type="checkbox"
                                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                    :checked="conditionIncludes(conditions.id)"
                                                    @change="toggleCondition(conditions.id)">
                                                <label
                                                    class="text-base font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                    {{ conditions.name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                    <div class="p-4 ">
                                        <div class="flex flex-row space-x-3 items-center mb-2">
                                        <!-- <h4 class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                {{ scholarship_form.name }}</h4> -->
                                            <h4 class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                Scholarship Name</h4>
                                            <InputError v-if="errors?.criteria" :message="errors.criteria"
                                            class="text-2xs text-red-500" />
                                        </div>

                                        <div v-for="data in scholarship_form_data" :key="data.id"
                                            class="flex items-center space-x-2 mb-1">
                                            <input id="accept-terms-{{ data.id }}" type="checkbox"
                                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                :checked="criteriaIncludes(data.id)"
                                                @change="toggleCriteria(data.id)">
                                            <label :for="'accept-terms-' + data.id"
                                                class="text-sm font-medium text-gray-700 cursor-pointer dark:text-dtext">
                                                {{ data.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            
                            </div>

                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="w-full">
                                <div class="flex flex-row space-x-3 items-start mb-2">
                                    <label for="totalRecipients"
                                        class="text-sm font-medium text-gray-700 dark:text-dtext">
                                        List Requirements
                                    </label>
                                    <InputError v-if="errors?.requirements" :message="errors.requirements"
                                        class="text-2xs text-red-500" />
                                </div>
                                <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center mb-4 w-full">
                                        <form @submit.prevent="addItem" class="flex items-center w-full">
                                            <input v-model="newItem" type="text" placeholder="Enter an item"
                                                class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                Add
                                            </button>
                                        </form>
                                    </div>

                                    <form @submit.prevent="removeItem">
                                        <div class="flex flex-col gap-2">
                                            <div v-for="(item, index) in items" :key="index"
                                                class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                                <span>{{ item }}</span>
                                                <button @click="removeItem(index)"
                                                    class="flex items-center text-red-500 hover:text-red-700">
                                                    <span class="material-symbols-rounded text-red-600">
                                                        delete
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>

                            <div>
                                <label for="totalRecipients"
                                    class="text-sm font-medium text-gray-700 dark:text-dtext">
                                    Upload multiple file templates
                                </label>
                                <!-- <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">
                                Upload multiple file templates
                                </label> -->
                                <input ref="fileInput"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                        dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    id="multiple_files" type="file" multiple @change="handleFileChange" />

                                <!-- Preview Selected Files -->
                                <div v-if="selectedFiles.length"
                                    class="mt-3 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        Selected Files:</p>
                                    <ul class="space-y-2">
                                        <li v-for="(file, index) in selectedFiles" :key="index"
                                            class="flex items-center justify-between text-sm bg-white dark:bg-gray-700 p-2 rounded-lg">
                                            <span
                                                class="text-gray-700 dark:text-gray-300 truncate max-w-xs">{{
                                                    file.name
                                                }}</span>
                                            <button @click="removeFile(index)"
                                                class="text-red-500 hover:text-red-700 text-xs">
                                                ✖ Remove
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </div>

        </form>
    </div>

    <ToastProvider>
        <ToastRoot v-if="toastVisible"
            class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
            <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
        </ToastRoot>

        <ToastViewport class="fixed bottom-4 right-4" />
    </ToastProvider>

</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive, onMounted, watch, computed, onUnmounted, onBeforeUnmount } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
// import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Checkbox } from '@/Components/ui/checkbox'
import { Input } from '@/Components/ui/input'
import { initFlowbite } from 'flowbite';
import { Tooltip } from 'primevue';
import InputError from '@/Components/InputError.vue';
import BackLink from '@/Components/BackLink.vue'
import { generate } from '@syncfusion/ej2-vue-schedule';
import { nextTick } from 'vue';


// Define props to include scholars data
// Define props with the new batchesByCampus property
const props = defineProps({
    totalBatches: Number,
    scholarship_form: Object,
    scholarship_form_data: Array,
    eligibilities: Array,
    conditions: Array,
    batches: Array,
    batchesByCampus: Array, // New prop with batches organized by campus
    scholarship: Object,
    schoolyear: Object,
    selectedYear: String,
    selectedSem: String,
    selectedCampus: String,
    allBatchesInactive: Object,
    grantees: Array,
    campuses: Array,
    courses: Array,
    students: Array,
    payoutsByCampus: Array,
    total_approved: Array,
    total_scholars: Array,
    requirements: Array,
    completedBatches: Array,
    payoutBatches: Array,
    errors: Object,
    userType: String,
    userCampusId: Number,
    totalSubTotal: Number,
    approvedCount: Number,
    allBatches: Array,
    disableSendEmailButton: Boolean,
    noScholars: Boolean,
    inactiveBatches: Boolean,
    accomplishedBatches: Boolean,
    activeBatches: Array,
    allBatchesAccomplished: Array,
    inactivePayouts: Boolean,
    hasActiveGrantees: Boolean,
    valitedScholars: Boolean,
    myInactive: Boolean,
    allInactive: Boolean,
    valitedBatches: Boolean,
    myvalidated: Boolean,
    checkValidated: Boolean,
    granteeInactive: Boolean,
    validationStatus: Boolean,
    AllvalidationStatus: Boolean,
    total_verified_grantees: Object,
    total_unverified_grantees: Object,
    payouts: Object,
    payoutBatches: Array,
});

const goBack = () => {
    window.history.back();
};

// const selectedBatches = ref([]);
// const selectedCampuses = ref([]);


// Initialize selectedCampus with the value from props
const selectedCampus = ref(props.selectedCampus || '');

// Computed property for unread batches count - using allBatches instead of filtered batches
const filteredUnreadBatches = computed(() => {
    // Start with all unread batches from the complete batches list
    let unreadBatches = props.allBatches.filter(batch => batch.read === 0 || batch.read === false);

    // If user is coordinator, only count batches from their campus
    if (props.userType === 'coordinator') {
        unreadBatches = unreadBatches.filter(batch => {
            // Check if any scholars in this batch belong to the coordinator's campus
            return batch.scholars?.some(scholar => scholar.campus_id === props.userCampusId);
        });
    }

    return unreadBatches;
});

// Function to filter batches by campus
function filterByCampus() {
    router.get(route('scholarship.show', props.scholarship.id), {
        selectedYear: props.schoolyear.id,
        selectedSem: props.selectedSem,
        selectedCampus: selectedCampus.value
    }, {
        preserveState: true,
        replace: true
    });
}


const directives = {
    Tooltip,
};

const getFormData = (formId) => {
    return props.conditions.filter(data => data.eligibility_id === formId);
};

// Forward batch modal state
const ForwardBatchList = ref(false);
const selectedBatches = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const batchesWithScholars = ref([]);

const selectedStart = ref(""); // Stores the selected start date
const selectedEnd = ref("");   // Stores the selected end date

const StartPayout = ref(""); // Stores the selected start date
const EndPayout = ref("");   // Stores the selected end date

// Count scholars with "Verified" status
const verified_scholars = computed(() => {
    return props.total_scholars.filter(scholar => scholar.status === "Verified").length;
});

// Count scholars with "Unverified" status
const unverified_scholars = computed(() => {
    return props.total_scholars.filter(scholar => scholar.status === "Unverified").length;
});

const total_scholars = computed(() => {
    return props.total_scholars.filter(scholar => {
        // Add your conditions here, for example:
        // return scholar.isActive === true;
        return true; // Count all scholars by default
    }).length;
});

// const total_approved = computed(() => {
//     return props.total_approved.filter(scholar => {
//         // Add your conditions here, for example:
//         // return scholar.isActive === true;
//         return true; // Count all scholars by default
//     }).length;
// });


const requirementemplates = ref(false);
const selectedFiles = ref([]);
const fileInput = ref(null);
// Default to showing the Scholar List
const showPayrolls = ref(false);


const toggletemplates = () => {
    requirementemplates.value = !requirementemplates.value;
};

const handleFileChange = (event) => {
    selectedFiles.value = Array.from(event.target.files);
};

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1);

    // Reset the file input by clearing and re-adding remaining files
    const dataTransfer = new DataTransfer();
    selectedFiles.value.forEach(file => dataTransfer.items.add(file));

    if (fileInput.value) {
        fileInput.value.files = dataTransfer.files;
    }
};

if (fileInput.value) {
    fileInput.value.files = dataTransfer.files;
}

// Submit reason form
const forwardCoor = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('scholarship.forward_coor', {
        scholarshipId: props.scholarship.id, selectedSem: props.selectedSem, school_year: props.schoolyear.id,
        selectedCampus: props.selectedCampus
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
};

const forwardSponsor = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('scholarship.forward_sponsor', {
        scholarshipId: props.scholarship.id, selectedSem: props.selectedSem, school_year: props.schoolyear.id,
        selectedCampus: props.selectedCampus
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
};

const forwardValidate = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('scholarship.forward_validate', {
        scholarshipId: props.scholarship.id, selectedSem: props.selectedSem, school_year: props.schoolyear.id,
        selectedCampus: props.selectedCampus
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Validated forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
};


const toggleView = () => {
    showPayrolls.value = !showPayrolls.value;
};

const toggleSendBatch = async () => {
    ForwardBatchList.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const ForwardCoorValidation = ref(false);

const toggleForwardValidation = async () => {
    ForwardCoorValidation.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const ForwardCoorCheckedRequirements = ref(false);

const toggleForwardRequirements = async () => {
    ForwardCoorCheckedRequirements.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const ForwardtoSponsor = ref(false);

const toggleForwardSponsor = async () => {
    ForwardtoSponsor.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};


// Check if all payouts are inactive
const allPayoutsInactive = computed(() => {
    if (!props.payoutsByCampus || Object.keys(props.payoutsByCampus).length === 0) {
        return false
    }

    // Check each payout in each campus
    for (const campusId in props.payoutsByCampus) {
        const payouts = props.payoutsByCampus[campusId]
        for (const payout of payouts) {
            if (payout.status !== 'Inactive') {
                return false
            }
        }
    }

    return true
})

const closeModal = () => {
    ForwardBatchList.value = false;
    ForwardCoorValidation.value = false;
    ForwardCoorCheckedRequirements.value = false;
    ForwardtoSponsor.value = false;
    GenerateReport.value = false;
    resetForm();
};

const loadBatchesData = async () => {
    isLoading.value = true;

    try {
        // Calculate scholar counts for each batch using the scholars prop
        setTimeout(() => {
            // Group scholars by batch_id and count them
            const scholarCountsByBatch = props.grantees.reduce((counts, grant) => {
                if (grant.batch_id) {
                    counts[grant.batch_id] = (counts[grant.batch_id] || 0) + 1;
                }
                return counts;
            }, {});

            // Map batches with their scholar counts
            batchesWithScholars.value = props.payoutBatches.map(batch => {
                return {
                    ...batch,
                    scholar_count: scholarCountsByBatch[batch.id] || 0
                };
            });

            // Automatically select all batches when data is loaded
            selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];

            isLoading.value = false;
        }, 300);
    } catch (error) {
        console.error('Error loading batch data:', error);
        toastMessage.value = 'Failed to load batch data';
        toastVisible.value = true;
        isLoading.value = false;
    }
};

const selectAllBatches = () => {
    if (selectedBatches.value.includes('all')) {
        // If 'all' is selected, select all batch IDs
        selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];
    } else {
        // If 'all' is unselected, clear all selections
        selectedBatches.value = [];
    }
};

// Form data
const form = ref({
    name: '',
    scholarshipType: '',
    totalRecipients: 0,
    requirements: [],
    criteria: [],
    conditions: [],
    grade: 0.0,
    amount: 0,
    application: '',
    deadline: '',
    payoutStartInput: '',
    payoutEndInput: '',
});

const clearForm = () => {
    form.value = {
        name: '',
        scholarshipType: '',
        totalRecipients: 0,
        requirements: [],
        criteria: [],
        grade: 0.0,
        amount: 0,
        application: '',
        deadline: '',
    };
};

// Safe check if criteria includes an ID
const conditionIncludes = (dataId) => {
    return form.value && form.value.conditions && Array.isArray(form.value.conditions)
        ? form.value.conditions.includes(dataId)
        : false;
};

// Handle criteria selection
const toggleCondition = (dataId) => {
    // Ensure criteria is initialized
    if (!form.value.conditions) {
        form.value.conditions = [];
    }

    const index = form.value.conditions.indexOf(dataId);
    if (index === -1) {
        // Add to criteria if not already present
        form.value.conditions.push(dataId);
    } else {
        // Remove from criteria if already present
        form.value.conditions.splice(index, 1);
    }
};

// Safe check if criteria includes an ID
const criteriaIncludes = (dataId) => {
    return form.value && form.value.criteria && Array.isArray(form.value.criteria)
        ? form.value.criteria.includes(dataId)
        : false;
};

// Handle criteria selection
const toggleCriteria = (dataId) => {
    // Ensure criteria is initialized
    if (!form.value.criteria) {
        form.value.criteria = [];
    }

    const index = form.value.criteria.indexOf(dataId);
    if (index === -1) {
        // Add to criteria if not already present
        form.value.criteria.push(dataId);
    } else {
        // Remove from criteria if already present
        form.value.criteria.splice(index, 1);
    }
};

const newReq = ref("");
const reqs = ref([]);

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

const removeItem = (index) => {
    items.value = items.value.filter((_, i) => i !== index);
};


// Create reactive campus array from props with selection state
const campusesData = ref([]);

// Initialize campus data from props
onMounted(() => {
    initFlowbite();
    document.addEventListener('click', handleClickOutside)
    // Make sure form.criteria is initialized
    if (!form.value.criteria) {
        form.value.criteria = [];
    }

    // Transform props.campuses into the format we need
    if (props.campuses && props.campuses.length > 0) {
        campusesData.value = props.campuses.map(campus => ({
            id: campus.id,
            name: campus.name,
            selected: false,
            recipients: 0,
            // Get courses associated with this campus
            courses: props.courses
                ? props.courses.filter(course => course.campus_id === campus.id)
                    .map(course => course.name)
                : []
        }));
    }

    if (props.batches && props.batches.length > 0) {
        expandedBatches.value = props.batches[0].id;
    }

    // Initialize Flowbite Datepicker
    const dateInput = document.getElementById("datepicker-autohide");
    if (dateInput) {
        const datepicker = new Datepicker(dateInput, {
            autohide: true,
            format: "yyyy-mm-dd", // Adjust format as needed
        });

        dateInput.addEventListener("changeDate", (event) => {
            form.value.birthdate = event.target.value;
        });
    }

    const startInput = document.getElementById("datepicker-range-start");
    if (startInput) {
        startInput.value = selectedStart.value; // Keep the previous value
        startInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value);

            // Correct for time zone issues
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

            form.value.application = date.toISOString().split("T")[0]; // Keeps the correct local date
            console.log("Application:", form.value.application);
            selectedStart.value = event.target.value;
        });
    }

    const endInput = document.getElementById("datepicker-range-end");
    if (endInput) {
        endInput.value = selectedEnd.value; // Keep the previous value
        endInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value);

            // Correct for time zone issues
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

            form.value.deadline = date.toISOString().split("T")[0]; // Keeps the correct local date
            selectedEnd.value = event.target.value;
        });
    }

    watch(ForwardBatchList, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                initFlowbite(); // Initialize Flowbite when modal is accessed

                const startInput = document.getElementById("datepicker-range-start");
                if (startInput) {
                    startInput.value = StartPayout.value; // Keep the previous value
                    startInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.payoutStartInput = date.toISOString().split("T")[0];
                        console.log("Application:", form.value.payoutStartInput);
                        StartPayout.value = event.target.value;
                    });
                } else {
                    console.warn("Start datepicker not found.");
                }

                const endInput = document.getElementById("datepicker-range-end");
                if (endInput) {
                    endInput.value = EndPayout.value; // Keep the previous value
                    endInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.payoutEndInput = date.toISOString().split("T")[0];
                        EndPayout.value = event.target.value;
                    });
                } else {
                    console.warn("End datepicker not found.");
                }

                // Initial distribution
                distributeRecipients();

            }, 200); // Small delay to ensure modal is in the DOM
        }
    });


    // Initial distribution
    distributeRecipients();
});

// Watch errors.date_start and open the modal if an error exists
// watch(() => props.errors.date_start, (newError) => {
//     if (newError) {
//         ForwardBatchList.value = true; // Show modal
//         setTimeout(() => initFlowbite(), 200); // Initialize Flowbite modal
//     }
// });

// watch(selectedStart, (newVal) => {
//     document.getElementById("datepicker-range-start").value = newVal;
// });

// watch(selectedEnd, (newVal) => {
//     document.getElementById("datepicker-range-end").value = newVal;
// });

// // 🎯 Sync Input Values
// watch(() => StartPayout.value, (newVal) => {
//     const input = document.getElementById("datepicker-range-start");
//     if (input) input.value = newVal;
// });

// watch(() => EndPayout.value, (newVal) => {
//     const input = document.getElementById("datepicker-range-end");
//     if (input) input.value = newVal;
// });


// watch(ForwardBatchList, (newValue) => {
//     if (newValue) {
//         setTimeout(() => {
//             initFlowbite(); // Initialize the modal components
//         }, 200);
//     }
// });

// Compute selected campuses dynamically
const selectedCampuses = computed(() =>
    campusesData.value.filter(campus => campus.selected)
);

// Calculate total allocated recipients
const allocatedRecipients = computed(() => {
    return campusesData.value.reduce(
        (sum, campus) => sum + parseInt(campus.recipients || 0), 0
    );
});

// Helper to access the total recipients value
const totalRecipients = computed(() => parseInt(form.value.totalRecipients) || 0);

// Function to distribute recipients equally when checking/unchecking a campus
const distributeRecipients = () => {
    const selectedCount = selectedCampuses.value.length;

    if (selectedCount === 0 || totalRecipients.value === 0) {
        campusesData.value.forEach(campus => campus.recipients = 0);
        return;
    }

    const share = Math.floor(totalRecipients.value / selectedCount);
    const remainder = totalRecipients.value % selectedCount;

    campusesData.value.forEach(campus => {
        if (!campus.selected) {
            campus.recipients = 0;
            return;
        }

        // Find the index in the selected campuses array
        const index = selectedCampuses.value.findIndex(c => c.id === campus.id);
        campus.recipients = share + (index < remainder ? 1 : 0);
    });
};

// Handle manual change to a campus's recipients
const onRecipientManualChange = (changedCampusId) => {
    const changedCampus = campusesData.value.find(c => c.id === changedCampusId);

    // Ensure value is a valid number and not less than 0
    changedCampus.recipients = Math.max(0, parseInt(changedCampus.recipients) || 0);

    // If changing this would exceed total, cap it
    if (allocatedRecipients.value > totalRecipients.value) {
        changedCampus.recipients = Math.max(0,
            parseInt(changedCampus.recipients) - (allocatedRecipients.value - totalRecipients.value)
        );
    }
};

// Watch total recipients and automatically redistribute
watch(() => form.value.totalRecipients, distributeRecipients);

// Initial distribution
distributeRecipients();

// Store selected courses
const selectedCoursesMap = ref({});

// Compute selected courses dynamically based on checked campuses
const selectedCourses = computed(() => {
    let courses = [];
    campusesData.value.forEach((campus) => {
        if (campus.selected) {
            courses = [...new Set([...courses, ...campus.courses])]; // Remove duplicates
        }
    });

    // Sync the selected courses in the map
    selectedCoursesMap.value = courses.reduce((acc, course) => {
        acc[course] = selectedCoursesMap.value[course] || false;
        return acc;
    }, {});

    return courses;
});

// Compute selected courses dynamically based on checked checkboxes
const selectedCoursesText = computed(() => {
    return Object.keys(selectedCoursesMap.value)
        .filter(course => selectedCoursesMap.value[course]) // Get only checked courses
        .join(", "); // Convert to a comma-separated string
});

// Update selected courses whenever a campus is checked/unchecked
const updateSelectedCourses = () => {
    selectedCourses.value; // Triggers computed property update
};

const submitForm = () => {
    // Prepare campus recipients data for the backend
    const campusRecipients = selectedCampuses.value.map(campus => ({
        campus_id: campus.id,
        slots: parseInt(campus.recipients),
        remaining_slots: parseInt(campus.recipients),
        selected_campus: JSON.stringify(
            campus.courses
                .filter(course => selectedCoursesMap.value[course])
                .map(course => ({ course }))
        ),
    }));

    // Create FormData object to handle files
    const formData = new FormData();

    // Add all non-file fields to FormData
    formData.append('total_recipients', form.value.totalRecipients);
    formData.append('grade', form.value.grade);
    formData.append('application', form.value.application);
    formData.append('deadline', form.value.deadline);
    formData.append('semester', props.selectedSem);
    formData.append('school_year', props.schoolyear.id);

    // Add amount if scholarship type is One-Time
    if (form.value.scholarshipType === 'One-Time') {
        formData.append('amount', form.value.amount);
    }

    // Add requirements as array
    form.value.requirements.forEach((req, index) => {
        formData.append(`requirements[${index}]`, req);
    });

    // Add criteria as array
    form.value.criteria.forEach((criteriaId, index) => {
        formData.append(`criteria[${index}]`, criteriaId);
    });

    // Add conditions as array
    form.value.conditions.forEach((conditionId, index) => {
        formData.append(`conditions[${index}]`, conditionId);
    });

    // Add campus recipients as JSON
    campusRecipients.forEach((recipient, index) => {
        formData.append(`campus_recipients[${index}][campus_id]`, recipient.campus_id);
        formData.append(`campus_recipients[${index}][slots]`, recipient.slots);
        formData.append(`campus_recipients[${index}][remaining_slots]`, recipient.remaining_slots);
        formData.append(`campus_recipients[${index}][selected_campus]`, recipient.selected_campus);
    });

    // Add template files
    selectedFiles.value.forEach((file, index) => {
        formData.append(`templates[${index}]`, file);
    });

    // Submit the form using FormData
    router.post(`/sholarships/${props.scholarship.id}/one-time-payment`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        onSuccess: () => {
            showToast('Success', 'Scholarship with templates created successfully');
            clearForm();
            setTimeout(() => {
                router.visit('/scholarships');
            }, 1500);
        },
        onError: (errors) => {
            errors.value = errors;
            isSubmitting.value = false;
        },
    });
}


// Watch for changes in individual batch selections
watchEffect(() => {
    // If any individual batch is unselected and 'all' was selected, unselect 'all'
    const allBatchIds = batchesWithScholars.value.map(batch => batch.id);

    if (selectedBatches.value.includes('all') &&
        !allBatchIds.every(id => selectedBatches.value.includes(id))) {
        selectedBatches.value = selectedBatches.value.filter(id => id !== 'all');
    }

    // If all individual batches are selected, also select 'all'
    if (allBatchIds.length > 0 &&
        allBatchIds.every(id => selectedBatches.value.includes(id)) &&
        !selectedBatches.value.includes('all')) {
        selectedBatches.value.push('all');
    }

    // const flash = usePage().props.flash;

    // if (flash?.message) {
    //     console.log("Showing toast with message:", flash.message);
    //     toastMessage.value = flash.message;
    //     toastVisible.value = true;

    //     setTimeout(() => {
    //         console.log("Hiding toast...");
    //         toastVisible.value = false;
    //     }, 3000);
    // }
});



const forwardBatches = async () => {
    isSubmitting.value = true;

    try {
        // Prepare data for submission
        const batchesToForward = selectedBatches.value.includes('all')
            ? batchesWithScholars.value.map(batch => batch.id)
            : selectedBatches.value;

        // Create payload with selected batches
        const payload = {
            scholarship_id: props.scholarship.id,
            grantees: batchesWithScholars.value.reduce((grantees, batch) => {
                if (batchesToForward.includes(batch.id)) {
                    // Filter the grantees based on the batch_id and map their scholar_ids
                    grantees.push(...props.grantees
                        .filter(s => s.batch_id === batch.id)
                        .map(grantee => ({
                            ...grantee,
                            scholar_id: grantee.scholar ? grantee.scholar.scholar_id : null // Ensure scholar_id is added
                        })));
                }
                return grantees;
            }, []),
            batch_ids: batchesToForward,
            date_start: form.value.payoutStartInput,
            date_end: form.value.payoutEndInput,
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        };

        // Send the request only when user confirms
        router.post(`/scholarship/forward-batches`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                // Only proceed if request was successful
                const totalScholars = batchesToForward.reduce((total, batchId) => {
                    const batch = batchesWithScholars.value.find(b => b.id === batchId);
                    return total + (batch ? batch.scholar_count : 0);
                }, 0);

                toastMessage.value = `Successfully forwarded to University Cashier`;
                toastVisible.value = true;


            },
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            // Store the validation errors returned from the backend
            errors.value = error.response.data.errors;
        }

        console.error('Error forwarding batches:', error);
        toastMessage.value = error.message || 'Failed to forward batches';
        toastVisible.value = true;
    } finally {
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    selectedBatches.value = [];
    batchesWithScholars.value = [];
};

// Existing code remains the same
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

const openBatch = (batchId) => {
    router.visit(`/scholarships/${props.scholarship.id}/batch/${batchId}`, {
        data: {
            scholarship: props.scholarship.id,
            selectedYear: props.schoolyear.id,
            selectedSem: props.selectedSem
        },
        preserveState: true
    });
};

const openPayroll = (batchId) => {
    router.visit(`/scholarships/${props.scholarship.id}/batch/${batchId}/payroll`, {
        data: {
            scholarship: props.scholarship.id,
            selectedYear: props.schoolyear.id,
            selectedSem: props.selectedSem
        },
        preserveState: true
    });
};

const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default


const selectedSem = ref("");

selectedSem.value = props.selectedSem;

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};

const openSendEmail = () => {
    router.visit(`/scholarships/${props.scholarship.id}/send-access`, {
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

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });

    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
});

onBeforeMount(() => {
    document.addEventListener('click', handleClickOutside);
})

const openDropdown = ref('');

const toggleDropdown = (dropdownName) => {
    openDropdown.value = openDropdown.value === dropdownName ? null : dropdownName
}

// Computed property to filter batches based on selected campuses
const filteredBatches = computed(() => {
    // If no campuses selected, return empty array
    if (selectedReportCampuses.value.length === 0) {
        return [];
    }

    // Filter batches that belong to selected campuses
    return props.batches.filter(batch => {
        // Look for batches with matching campus_id
        return selectedReportCampuses.value.includes(batch.campus_id) ||
            // Or check if any scholars in the batch belong to selected campuses
            batch.scholars?.some(scholar =>
                selectedReportCampuses.value.includes(scholar.campus_id)
            );
    });
});


// Update the toggleAll function to work with filtered batches
function toggleAll(type) {
    if (type === 'batch') {
        toggleAllBatches();
    } else if (type === 'campus') {
        const allIds = props.campuses.map(c => c.id);
        if (selectedReportCampuses.value.length === allIds.length) {
            // If all campuses are selected, deselect all and clear batch selection
            selectedReportCampuses.value = [];
            selectedReportBatches.value = [];
        } else {
            // Otherwise, select all campuses
            selectedReportCampuses.value = [...allIds];
        }
    }
}

// Add a function to toggle all batches
const toggleAllBatches = () => {
    if (filteredBatches.value.length === 0) return;

    if (selectedReportBatches.value.length === filteredBatches.value.length) {
        // If all batches are selected, deselect all
        selectedReportBatches.value = [];
    } else {
        // Otherwise, select all filtered batches
        selectedReportBatches.value = filteredBatches.value.map(batch => batch.id);
    }
};

// Detect outside click
const GenerateReport = ref(false);

const generateReportModal = () => {
    GenerateReport.value = !GenerateReport.value;
}

// Close modal function
const closeReportGeneration = () => {
    GenerateReport.value = false;
    resetSelections();
};

// Reset all selections
const resetSelections = () => {
    selectedReportType.value = '';
    selectedReportBatches.value = [];
    selectedReportCampuses.value = [];
};

const reportTypeOptions = ['Enrollees Summary', 'Enrolled List', 'Graduate Summary', 'Transferees', 'Payroll'];
const batchRef = ref(null);
const campusRef = ref(null);

const selectedReportType = ref('');
const selectedReportBatches = ref([]);
const selectedReportCampuses = ref([]);

// Improved click outside handler
const handleClickOutside = (event) => {
    // Make sure all refs are defined before using them
    if (openDropdown.value) {
        const campusEl = campusRef.value;
        const batchEl = batchRef.value;
        const reportEl = reportRef.value;

        // Check if click is outside all active dropdowns
        const clickedOutside = (
            (campusEl && !campusEl.contains(event.target) || !campusEl) &&
            (batchEl && !batchEl.contains(event.target) || !batchEl) &&
            (reportEl && !reportEl.contains(event.target) || !reportEl)
        );

        if (clickedOutside) {
            openDropdown.value = null;
        }
    }
};

const handleGenerateReports = () => {
    const filters = {
        type: selectedReportType.value,
        campuses: selectedReportCampuses.value,
        batches: selectedReportBatches.value,
    };

    if (!filters.type || filters.campuses.length === 0 || filters.batches.length === 0) {
        console.warn('Please select a report type, campuses, and batches.');
        return;
    }

    switch (filters.type) {
        case 'Enrollees Summary':
            generateEnrolleesSummary(filters);
            break;
        case 'Enrolled List':
            generateEnrolledList(filters);
            break;
        case 'Graduate Summary':
            generateGraduateList(filters);
            break;
        case 'Payroll':
            generatePayroll(filters);
            break;
        // case 'Scholars List':
        //     generateScholarsList(filters);
        //     break;
        case 'Transferees':
            generateTransferredList(filters);
            break;
        default:
            console.warn('No valid report type selected.');
    }
};

const generateEnrolleesSummary = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/enrollees-summary`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
};

const generateEnrolledList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/enrolled-scholars`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
};


const generateGraduateList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/graduate-scholars`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
};

const generateTransferredList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/transferred-grantees`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
};

const generatePayroll = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/payroll-report`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
};

// Watch for changes in selected campuses
watch(selectedReportCampuses, (newCampuses) => {
    // If campus selection changes, filter batch selection to only keep valid batches
    if (newCampuses.length > 0) {
        // Let filteredBatches compute first
        nextTick(() => {
            // Get IDs of valid batches based on selected campuses
            const validBatchIds = filteredBatches.value.map(batch => batch.id);

            // Filter selected batches to only include valid ones
            selectedReportBatches.value = selectedReportBatches.value.filter(
                batchId => validBatchIds.includes(batchId)
            );
        });
    } else {
        // If no campuses selected, clear batch selection
        selectedReportBatches.value = [];
    }
});

</script>

<style scoped>
/* override the prime vue componentss */
:root {
    --p-tooltip-background: #D97706 !important;
    /* Yellow warning color */
}

.p-tooltip-text {
    font-size: 12px !important;
    color: white !important;
}

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