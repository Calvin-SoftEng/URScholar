<template>
    <div class="bg-white dark:bg-gray-800 px-10 py-5">
        <div class="w-full h-full flex flex-row justify-center items-center gap-4 text-black mx-auto pt-1 pb-5 ">
            <button @click="toggleManual"
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200" :class="{
                    'bg-primary text-white border-primary': ManualAdding,
                    'text-primary bg-white border border-blue-700 hover:bg-gray-200': !ManualAdding
                }">Manual Upload
            </button>
            <span>|</span>
            <button @click="toggleBulk"
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200" :class="{
                    'bg-primary text-white border-primary': BulkAdding,
                    'text-primary bg-white border border-primary hover:bg-gray-200': !BulkAdding
                }">Bulk Upload
            </button>
        </div>

        <div v-if="ManualAdding" class="mx-auto w-full h-full justify-center items-center flex flex-col gap-5">
            <form @submit.prevent="submitManual" class="w-full flex flex-col gap-5">
                <div v-if="errors?.student" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.student }}</p>
                </div>
                <div class="flex flex-col w-full gap-5">
                     <div class="w-full flex items-end gap-5">
                        <div class="relative w-1/2">
                            <div class="w-full flex flex-col gap-0 font-rethink">
                                <span class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Search for
                                    Students</span>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="searchQuery" type="text" placeholder="Search student..." @focus="
                                    () =>
                                        setTimeout(
                                            () => (dropdownIsOpen = true),
                                            50
                                        )
                                " @blur="
                                    () =>
                                        setTimeout(
                                            () => (dropdownIsOpen = false),
                                            150
                                        )
                                "
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                                <button
                                    class="bg-primary text-white border-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200"
                                    @click="selectFirstMatch">
                                    Search
                                </button>
                            </div>

                            <ul v-if="dropdownIsOpen && searchQuery !== ''"
                                class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-y-auto">
                                <li v-if="filteredStudents.length === 0" class="px-4 py-2 text-sm text-gray-500">
                                    No results found.
                                </li>
                                <li v-for="student in filteredStudents" :key="student.id" @click="selectStudent(student)"
                                    class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-base font-medium font-rethink text-darker">
                                    <div class="flex flex-row items-center gap-3 leading-tight">
                                        <span>{{ student.first_name }} {{ student.last_name }}</span>
                                        <span class="text-xs text-gray-500">{{ student.email }}</span>
                                    </div>
                                </li>
                            </ul>
                            
                        </div>
                        <button type="button"
                            class="bg-white hover:bg-gray-200 border border-primary text-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-200"
                            @click="clearFirstMatch">
                            Clear Selection
                        </button>
                    </div>

                    <div class="w-full h-0.5 bg-gray-200 my-2"></div>

                    <div class="w-full grid grid-cols-4 items-center gap-5">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                            <input v-model="manual.first_name" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Last Name</label>
                            <input v-model="manual.last_name" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Middle Name</label>
                            <input v-model="manual.middle_name" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <SelectBox v-model="manual.sex" :options="sex" label="Sex" placeholder="Select sex" />
                        </div>
                    </div>
                    <div class="w-full grid grid-cols-4 items-center gap-5">
                        <!-- <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Date of Birth</label>
                            <DatePicker
                            v-model:modelValueStart="startDate"
                            />
                        </div> -->
                        <div class="w-full">
                            <Datepicker v-model="startDate" label="Date of Birth" />
                            <!-- <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                            Selected: {{ formatDate(startDate) }}
                            </p> -->
                        </div>

                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Province</label>
                            <input v-model="manual.province" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Municipality</label>
                            <input v-model="manual.municipality" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Street</label>
                            <input v-model="manual.street" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                    </div>

                    <div class="w-full h-0.5 bg-gray-200 my-2"></div>

                    <div class="w-full flex flex-row items-center gap-5">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Higher Education
                                Institution</label>
                            <input type="text" id="first_name" placeholder="Ex. Unversity of Rizal System"
                                v-model="manual.hei_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required value="University of Rizal System" readonly />
                        </div>

                        <div class="w-full">
                            <!-- <label
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                Campus
                            </label> -->
                            <!-- <select v-model="manual.campus_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option disabled value="">Select campus</option>
                                <option v-for="campus in campuses" :key="campus.id" :value="campus.id">{{ campus.name }}</option>
                            </select> -->
                            <SelectBox v-model="manual.campus_id" :options="campuses" label="Campus"
                                placeholder="Select Campus" />
                        </div>

                        <!-- <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Campus</label>
                            <Select v-model="manual.campus_id" >
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Campus" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                        {{ campus.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div> -->
                        <div class="w-full">
                            <!-- <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Course and
                                Program</label>
                            <Select v-model="manual.course_id">
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Course" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="course in course" :key="course.id" :value="course.id">
                                            {{ course.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select> -->
                            <SelectBox v-model="manual.course_id" :options="course" label="Course"
                                placeholder="Select Course" />
                        </div>
                        <div class="w-full">
                            <div class="w-full">
                                <SelectBox v-model="manual.year" :options="years" label="Year Level"
                                    placeholder="Select Year Level" />
                            </div>
                        </div>
                    </div>

                    <div class="w-full grid grid-cols-4 items-center gap-5">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Grant</label>
                            <input type="text" id="first_name" placeholder="Ex. LISTAHANAN" v-model="manual.grant"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Batch No</label>
                            <input type="text" id="batch_id" placeholder="Enter Batch No" v-model="manual.batch_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Endorsed By</label>
                            <input type="text" id="batch_id" v-model="manual.endorsed"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                    </div>

                    <div class="w-full h-0.5 bg-gray-200 my-2"></div>

                    <div class="w-full grid grid-cols-4 items-center gap-5">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Application
                                No.</label>
                            <input v-model="manual.app_no" type="text" id="first_name"
                                placeholder="00000-00000000-00000"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Award No.</label>
                            <input v-model="manual.award_no" type="text" id="first_name"
                                placeholder="###-00-00-00000-0000-00000"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" @submit.prevent="submitManual"
                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Add Scholar
                    </button>
                </div>
            </form>
        </div>

        <div v-if="BulkAdding" class="mx-auto w-full justify-center items-center flex flex-col gap-4 ">

            <form @submit.prevent="submitForm" class="w-6/12 flex flex-col gap-4">
                <div v-if="errors?.student" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.student }}</p>
                </div>
                <div v-else-if="errors?.nofile" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.nofile }}</p>
                </div>
                <div class="flex flex-col w-full gap-4 mt-5">

                    <!-- <button @click="downloadFile" 
                            class="px-4 py-2 bg-white border border-blue-700 text-primary rounded-lg hover:bg-blue-700 hover:text-white transition-all"
                            :disabled="isLoading">
                        <span v-if="isLoading">Downloading...</span>
                        <span v-else>Download Scholar CSV File Template</span>
                    </button> -->

                    <a @click="downloadFile"
                        class="px-4 py-2 bg-white border border-blue-700 text-primary rounded-lg hover:bg-blue-700 hover:text-white transition-all"
                        :disabled="isLoading">
                        <span v-if="isLoading">Downloading...</span>
                        <span v-else>Download Scholar CSV File Template</span>
                    </a>

                    <!-- File Drop Zone -->
                    <label for="first_name" class="block text-sm font-medium text-gray-900 dark:text-white">Import CSV
                        File of Scholars</label>
                    <label for="dropzone-file" @dragover.prevent="handleFileDragOver" @dragleave="handleFileDragLeave"
                        @drop.prevent="handleFileDrop"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                        :class="{ 'border-blue-500 bg-blue-50': isDragging }">
                        <div v-if="!form.file" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Only CSV files are allowed</p>
                        </div>
                        <div v-else class="flex flex-col items-center justify-center">
                            <template v-if="form.filePreview && !form.fileName.endsWith('.csv')">
                                <img :src="form.filePreview" alt="Uploaded Preview" class="h-32 mb-2 rounded-lg" />
                            </template>
                            <template v-else>
                                <img src="../../../../assets/images/previewdocs.png" alt="Document Icon"
                                    class="h-48 mb-2" />
                            </template>
                            <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" accept=".csv"
                            @change="(e) => handleFile(e.target.files[0])" />
                    </label>
                    <div class="flex justify-end gap-2">
                        <button type="submit"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Add Scholars
                        </button>
                    </div>

                </div>
            </form>
        </div>
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
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastViewport } from 'radix-vue'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

import Datepicker from '@/Components/RawComponents/Datepicker.vue';
import SelectBox from '@/Components/RawComponents/SelectBox.vue';

import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { initFlowbite } from 'flowbite';


//File download
const props = defineProps({
    scholarship: Object,
    scholars: Array,
    schoolyear: Object,
    selectedSem: Object,
    batch: Array,
    campuses: Array,
    students: Array,
    course: Array,
    errors: Object,
    filePath: {
        type: String,
        required: true
    },
    fileName: {
        type: String,
        required: true
    },
    buttonText: {
        type: String,
        default: 'Download File'
    }
});

const isLoading = ref(false);

const startDate = ref(null);

const endDate = ref(null);

// Helper functions
function formatModelDate(date) {
    if (!date) return '';

    // If it's a string, convert it to a Date object
    if (typeof date === 'string') {
        date = new Date(date);
    }

    // Check if it's a valid Date
    if (isNaN(date.getTime())) {
        console.warn('Invalid date:', date);
        return '';
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}/${month}/${day}`;
}


const formattedStartDate = computed(() => formatModelDate(startDate.value));
const formattedEndDate = computed(() => formatModelDate(endDate.value));

/**
 * Downloads a file from Laravel storage via controller
 */
const downloadFile = async () => {
    try {
        isLoading.value = true;

        // Corrected path: remove "/public"
        const link = document.createElement('a');
        link.href = '/assets/URSCHOLAR-SCHOLAR_FORMAT.csv'; // ✅ served from public/
        link.download = 'URSCHOLAR-SCHOLAR_FORMAT.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error('Error downloading file:', error);
        alert('Failed to download file. Please try again.');
    } finally {
        isLoading.value = false;
    }
};





const ManualAdding = ref(true);
const BulkAdding = ref(false);

const toggleManual = () => {
    ManualAdding.value = true;
    BulkAdding.value = false;
};

const toggleBulk = () => {
    BulkAdding.value = true;
    ManualAdding.value = false;
};

//Populate Student Name
//palit props ikaw na dito
const searchQuery = ref("");
const selectedCustomer = ref(null);
const dropdownIsOpen = ref(false);
let skipNextWatch = false;

watch(searchQuery, (newVal) => {
    if (skipNextWatch) {
        skipNextWatch = false;
        return;
    }
    dropdownIsOpen.value = newVal !== "";
});


const filteredStudents = computed(() =>
    props.students.filter(
        (student) =>
            student.first_name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            student.last_name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            student.email
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase())
    )
);

function selectStudent(student) {
    selectedStudent.value = student;
    searchQuery.value = `${student.first_name} ${student.last_name}`;
    dropdownIsOpen.value = false;
    skipNextWatch = true;

    // Auto-fill form with student data
    manual.value.first_name = student.first_name || "";
    manual.value.last_name = student.last_name || "";
    manual.value.middle_name = ""; // Not in students table
    manual.value.sex = ""; // Not in students table
    manual.value.campus_id = student.campus_id || "";
    manual.value.course_id = student.course_id || "";
    manual.value.year = student.year_level || "";

    // Parse birthdate if exists
    if (student.birthdate) {
        startDate.value = new Date(student.birthdate);
    }

    // Parse permanent_address if exists (assuming format: "street, municipality, province")
    if (student.permanent_address) {
        const addressParts = student.permanent_address.split(',').map(s => s.trim());
        manual.value.street = addressParts[0] || "";
        manual.value.municipality = addressParts[1] || "";
        manual.value.province = addressParts[2] || "";
    }
}

// Add this method to your existing methods section
function clearFirstMatch() {
    selectedStudent.value = null;
    searchQuery.value = "";
    dropdownIsOpen.value = false;

    // Clear the form fields
    manual.value = {}; // Reset the form data
}

function selectedStudent(student) {
    selectedStudent.value = student;
    form.user_id = student.id;
    searchQuery.value = student.name;
    dropdownIsOpen.value = false;
    skipNextWatch = true;

    // Auto-fill form with customer data directly from props
    form.first_name = student.first_name || "";
    form.last_name = student.last_name || "";
    form.email = student.email || "";
    form.contact = student.contact || "";
    form.address = student.address || "";
}

function selectFirstMatch() {
    if (filteredStudents.value.length > 0) {
        selectStudent(filteredStudents.value[0]);
    }
}


const closePanel = () => {
    previewData.value = [];
    headers.value = [];
    uploadingPanel.value = false;
    addingPanel.value = false;
    entries.value = false;
};

const sex = [
    { id: 'male', name: 'Male' },
    { id: 'female', name: 'Female' },
]

const years = [
    { id: '1', name: '1st Year' },
    { id: '2', name: '2nd Year' },
    { id: '3', name: '3rd Year' },
    { id: '4', name: '4th Year' },
]



const manual = ref({
    grant: '',
    batch_id: '',
    endorsed: '',
    hei_name: 'University of Rizal System',
    campus: '',
    course: '',
    year: '',
    app_no: '',
    award_no: '',
    first_name: '',
    last_name: '',
    middle_name: '',
    sex: '',
    birthdate: formattedStartDate,
    province: '',
    municipality: '',
    street: '',
    semester: props.selectedSem,         // Add this line
    schoolyear: props.schoolyear.id      // Add this line
});

const submitManual = async () => {
    console.log(manual.birthdate);
    try {
        router.post(`/scholarships/${props.scholarship.id}/manual-upload`, manual.value, {
            onSuccess: () => {
                // Show success message
                // showToast("Scholar added successfully!");
                // Reset form
                resetManualForm();
            },
            onError: (errors) => {
                // Handle validation errors
                console.error('Validation errors:', errors);
            }
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const resetManualForm = () => {
    manual.value = {
        grant: '',
        batch_id: '',
        endorsed: '',
        hei_name: 'University of Rizal System',
        campus_id: '',
        course_id: '',
        year: '',
        app_no: '',
        award_no: '',
        first_name: '',
        last_name: '',
        middle_name: '',
        sex: '',
        birthdate: null,
        province: '',
        municipality: '',
        street: '',
        semester: props.selectedSem,
        schoolyear: props.schoolyear.id
    };
};

const form = ref({
    file: null,
    semester: props.selectedSem,
    schoolyear: props.schoolyear.id,
    fileName: null,
    filePreview: null,

    // Other form fields...
});

// Add new reactive refs for courses data
const filteredCourses = computed(() => {
    if (!form.value.campus) return [];
    return props.course.filter(course => course.campus_id === form.value.campus);
});

// Watch for campus changes to reset course selection
watch(() => form.value.campus, (newValue) => {
    if (newValue !== form.value.campus) {
        form.value.course = null;
    }
});


const previewData = ref([]);
const headers = ref([]);
const error = ref('');
const fileReadyToUpload = ref(false);

const isFileDragging = ref(false);

const previewFile = (event) => {
    const file = event.target.files[0];
    handleFile(file);
};

const handleFileDragOver = () => {
    isFileDragging.value = true;
};

const handleFileDragLeave = () => {
    isFileDragging.value = false;
};

const handleFileDrop = (event) => {
    isFileDragging.value = false;
    const file = event.dataTransfer.files[0];
    handleFile(file);
};


const handleFile = (file) => {
    if (file) {
        form.value.file = file;
        form.value.fileName = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.filePreview = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitForm = async () => {
    const formData = new FormData();
    formData.append("file", form.value.file);
    formData.append("semester", form.value.semester);
    formData.append("schoolyear", form.value.schoolyear);

    try {
        // Send the request only when user confirms
        router.post(`/scholarships/${props.scholarship.id}/upload`, formData, {
            preserveScroll: true,
            onSuccess: () => {
                console.log("Scholars added to the scholarship!");
                headers.value = [];
                previewData.value = [];
                error.value = "";
                fileReadyToUpload.value = false;
                document.getElementById("dropzone-file").value = null; // Clear file input

                // Handle flash message for success toast
                const successMessage = usePage().props.flash?.success;
                if (successMessage) {
                    usePage().props.flash = { success: successMessage }; // Show toast message
                }

                closePanel();
                closeModal();
            },
        });
    } catch (err) {
        error.value = "An error occurred while uploading the file.";
        console.error("Error during file upload:", err);
    }
};


// Function to confirm and upload the file
const confirmUpload = async () => {
    if (!form.value.file) return;

    const formData = new FormData();
    formData.append("file", form.value.file);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    try {
        // Send the request only when user confirms
        router.post(`/scholarships/${props.scholarship.id}/upload`, formData, {
            preserveScroll: true,
            onSuccess: () => {
                headers.value = [];
                previewData.value = [];
                error.value = "";
                uploadingPanel.value = false;
                fileReadyToUpload.value = false;
                document.getElementById("dropzone-file").value = null; // Clear file input
                usePage().props.flash = { success: "Scholars added to the scholarship!" };
                closePanel();
            },
        });
    } catch (err) {
        error.value = "An error occurred while uploading the file.";
        console.error("Error during file upload:", err);
    }
};

//adding

const addingheaders = ["First Name", "Last Name", "Email", "Course"]

const formData = reactive({
    first_name: '',
    last_name: '',
    email: '',
    course: ''
})
const entries = ref([])


const resetForm = () => {
    formData.first_name = ''
    formData.last_name = ''
    formData.email = ''
    formData.course = ''
}

const removeEntry = (index) => {
    entries.value.splice(index, 1)
}


const initDatepicker = () => {
    const datepickerEl = document.getElementById("datepicker-autohide");

    if (datepickerEl) {
        if (!datepickerEl.dataset.initialized) {
            const datepicker = new window.Datepicker(datepickerEl, {
                autohide: true,
                format: "yyyy-mm-dd",
            });

            datepickerEl.dataset.initialized = "true";

            // Store selected date when user types or selects a date
            datepickerEl.addEventListener("input", () => {
                manual.value.birthdate = datepickerEl.value;
            });

            datepickerEl.addEventListener("blur", () => {
                manual.value.birthdate = datepickerEl.value;
            });
        }

        // 🔥 Restore value manually when switching steps
        if (manual.value.birthdate) {
            datepickerEl.value = manual.value.birthdate;
        }
    }
};

onMounted(() => {
    initFlowbite();
    initDatepicker();
});
</script>


<style>
/* override the prime vue componentss */

.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}
</style>