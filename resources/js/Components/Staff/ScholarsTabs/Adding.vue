<template>
    <div class="bg-white dark:bg-gray-800 p-4">
        <div class="w-full h-full flex flex-row justify-center items-center gap-4 text-black mx-auto py-1 ">
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
                    'text-primary bg-white border border-blue-700 hover:bg-gray-200': !BulkAdding
                }">Bulk Upload
            </button>
        </div>

        <div v-if="ManualAdding" class="mx-auto w-full h-full justify-center items-center flex flex-col gap-3">
            <form @submit.prevent="submitManual" class="w-full flex flex-col gap-2">
                <div class="flex flex-col w-full gap-2">
                    <div class="w-full flex flex-row items-center gap-3">
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
                            <Select v-model="manual.batch_id">
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Batch" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="batchItem in batch" :key="batchItem.id"
                                            :value="batchItem.id">
                                            {{ batchItem.id }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Higher Education
                                Institution</label>
                            <input type="text" id="first_name" placeholder="Ex. Unversity of Rizal System"
                                v-model="manual.hei_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required value="University of Rizal System" readonly />
                        </div>
                    </div>
                    <div class="w-full flex flex-row items-center gap-3">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Campus</label>
                            <Select v-model="manual.campus">
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Campus" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                        {{ campus.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Course and
                                Program</label>
                            <Select v-model="manual.course">
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
                            </Select>
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Year Level</label>
                            <Select v-model="manual.year">
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Year" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <!-- <SelectLabel>Gender</SelectLabel> -->
                                        <SelectItem value="1">
                                            1st
                                        </SelectItem>
                                        <SelectItem value="2">
                                            2nd
                                        </SelectItem>
                                        <SelectItem value="3">
                                            3rd
                                        </SelectItem>
                                        <SelectItem value="4">
                                            4th
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <div class="w-full h-0.5 bg-gray-200 my-2"></div>

                    <div class="w-full flex flex-row items-center gap-3">
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
                    <div class="w-full flex flex-row items-center gap-3">
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
                    </div>
                    <div class="w-full flex flex-row items-center gap-3">
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Middle Name</label>
                            <input v-model="manual.middle_name" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Sex</label>
                            <Select v-model="manual.sex">
                                <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                                    <SelectValue placeholder="Select Sex" class="text-black" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <!-- <SelectLabel>Gender</SelectLabel> -->
                                        <SelectItem value="Male">
                                            Male
                                        </SelectItem>
                                        <SelectItem value="Female">
                                            Female
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <div class="w-full flex flex-row items-center gap-3">
                        <div class="w-full">
                            <Label for="birthdate">Date of Birth</Label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div>
                                <input v-model="manual.birthdate" id="datepicker-autohide" datepicker
                                    datepicker-autohide type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Select Birthdate">
                            </div>
                        </div>
                        <div class="w-full">
                            <label for="first_name"
                                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Province</label>
                            <input v-model="manual.province" type="text" id="first_name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                    </div>
                    <div class="w-full flex flex-row items-center gap-3">
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
                <div class="flex flex-col w-full gap-4 mt-5">

                    <button @click="downloadFile" 
                            class="px-4 py-2 bg-white border border-blue-700 text-primary rounded-lg hover:bg-blue-700 hover:text-white transition-all"
                            :disabled="isLoading">
                        <span v-if="isLoading">Downloading...</span>
                        <span v-else>Download Scholar CSV File Template</span>
                    </button>

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
                            <img :src="form.filePreview" alt="Uploaded Preview"
                                class="max-h-24 mb-2 rounded-lg text-gray-500" />
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
            <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
            <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
        </ToastRoot>

        <ToastViewport class="fixed bottom-4 right-4" />
    </ToastProvider>


</template>

<script setup>
import { ref, onBeforeMount, reactive, defineEmits, watchEffect, computed, watch, onMounted } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import FileUpload from 'primevue/fileupload';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { initFlowbite } from 'flowbite';

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
})

const formatDate = (date) => {
    if (!date) return "Pick a date";
    return new Intl.DateTimeFormat("en-US", { dateStyle: "medium" }).format(new Date(date));
};

//File download
const props = defineProps({
    scholarship: Object,
    scholars: Array,
    schoolyear: Object,
    selectedSem: Object,
    batch: Array,
    campuses: Array,
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

/**
 * Downloads a file from Laravel storage via controller
 */
 const downloadFile = async () => {
    try {
        isLoading.value = true;

        const formData = new FormData();
        formData.append('filePath', props.filePath);
        formData.append('fileName', props.fileName);

        const response = await fetch('/api/download-file', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Failed to download file');
        }

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', props.fileName);
        document.body.appendChild(link);
        link.click();
        window.URL.revokeObjectURL(url);
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


const components = {
    DataTable,
    Column,
    Button,
    FileUpload,
    Papa,
};


const closePanel = () => {
    previewData.value = [];
    headers.value = [];
    uploadingPanel.value = false;
    addingPanel.value = false;
    entries.value = false;
};


const manual = ref({
    grant: '',
    batch_id: '',
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
    birthdate: null,
    province: '',
    municipality: '',
    street: '',
    semester: props.selectedSem,         // Add this line
    schoolyear: props.schoolyear.id      // Add this line
});

const submitManual = async () => {
    try {
        router.post(`/scholarships/${props.scholarship.id}/manual-upload`, manual.value, {
            onSuccess: () => {
                // Show success message
                showToast("Scholar added successfully!");
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

onMounted(() => {
    initFlowbite();
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