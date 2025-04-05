<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>University of Rizal
                    System Students
                </h1>

                <div class="flex justify-end items-center w-full gap-3">
                    <button @click="toggleCreate"
                        class="btn bg-white border dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                        <span class="material-symbols-rounded dark:text-dtext">
                            library_add
                        </span>
                        Import Students
                    </button>

                    <div class="w-3/12">
                        <form class="max-w-md mx-auto">
                            <label for="default-search"
                                class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative w-full">
                                <input type="search" id="search-dropdown" v-model="searchQuery"
                                    class="p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                    placeholder="Search" required />
                                <button type="submit"
                                    class="absolute top-0 end-0 p-2 text-sm font-medium h-full text-white bg-blue-900 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="w-full mt-5">
                    <div class="relative overflow-x-auto border border-gray-200 dark:border-gray-600 rounded-lg w-full">
                        <div
                            class="grid grid-cols-4 w-full py-1 items-center justify-between gap-10 bg-gray-50 dark:bg-dcontainer ">
                            <div class="flex flex-col w-full px-14">
                                <span class="flex items-center text-xs font-bold text-gray-700 dark:text-dtext uppercase ">
                                    Name
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                                <input type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" />
                            </div>
                            <div class="flex flex-col w-full px-14">
                                <span class="flex items-center text-xs font-bold text-gray-700 dark:text-dtext uppercase">
                                    Campus
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                                <input type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" />
                            </div>
                            <div class="flex flex-col w-full px-14">
                                <span class="flex items-center text-xs font-bold text-gray-700 dark:text-dtext uppercase">
                                    Course
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                                <input type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" />
                            </div>
                            <div class="flex flex-col w-full px-14">
                                <span class="flex items-center text-xs font-bold text-gray-700 dark:text-dtext uppercase">
                                    Year Level
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </span>
                                <input type="text" id="first_name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="overflow-x-auto max-w-[1400px]">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg overflow-x-auto max-w-[1000px]">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dprimary">
                                    <tr class="dark:text-dtext">
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Student Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Course
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Campus
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Year Level
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Contact Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Permanent Address
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Facebook Account (if any)
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Place of Birth
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap" >
                                            Date of Birth
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">
                                            Religion
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="student in paginatedStudents" :key="student.id">
                                        <tr class="bg-white border-b dark:bg-dsecondary dark:border-gray-700 border-gray-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.student_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.first_name }} {{ student.last_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.course.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.campus.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.year_level }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.contact_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.permanent_address ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.facebook_account ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.place_of_birth ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.date_of_birth ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ student.religion ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- Pagination controls -->
                    <div v-if="totalStudents > itemsPerPage" class="mt-5 flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                            Showing
                            <span class="font-semibold text-gray-900 dark:text-white">{{ startIndex }}</span>
                            to
                            <span class="font-semibold text-gray-900 dark:text-white">{{ endIndex }}</span>
                            of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ totalStudents }}</span>
                            Scholars
                        </span>
                        <div class="inline-flex">
                            <button @click="prevPage" :disabled="currentPage === 1" :class="[
                                'flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 rounded-s hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                                currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''
                            ]">
                                Prev
                            </button>
                            <button @click="nextPage" :disabled="currentPage === totalPages" :class="[
                                'flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 border-0 border-s border-gray-700 rounded-e hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                                currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''
                            ]">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- creating a sponsor -->
        <div v-if="isCreating || isEditing"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Adding University Students
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Import student information from a CSV file.
                            </span>
                        </div>
                    </div>
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div class="w-full flex flex-col">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">University Students current year
                            <InputError v-if="errors?.file" :message="errors.file"
                                class=" text-red-500" />
                        </h3>
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            :class="{ 'border-blue-500 bg-blue-50': isDragging }" @dragover.prevent="handleFileDragOver"
                            @dragleave="handleFileDragLeave" @drop.prevent="handleFileDrop">
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
                                <p class="text-xs text-gray-500 dark:text-gray-400">CSV</p>
                            </div>
                            <div v-else class="flex flex-col items-center justify-center">
                                <template v-if="form.filePreview && !form.fileName.endsWith('.csv')">
                                    <img :src="form.filePreview" alt="Uploaded Preview" class="h-32 mb-2 rounded-lg" />
                                </template>
                                <template v-else>
                                    <img src="../../../../assets/images/previewdocs.png" alt="Document Icon"
                                        class="h-32 mb-2" />
                                </template>
                                <p class="text-sm text-gray-500">{{ form.fileName }}</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" accept=".csv"
                                @change="(e) => handleFile(e.target.files[0])" />
                        </label>
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Import all Students</button>
                    </div>
                </form>
            </div>
        </div>
    </SettingsLayout>

</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, watchEffect, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import InputError from '@/Components/InputError.vue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'


const props = defineProps({
    sponsors: Array,
    students: Array,
    errors: Object,
});

const directives = {
    Tooltip,
    DatePicker,
};

const form = ref({
    file: null,
    fileName: null,
    filePreview: null,
});

const scholarships = ref({
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: null,
    deadline: null,
});

const isCreating = ref(false);
const isEditing = ref(false);

const toggleCreate = () => {
    isCreating.value = !isCreating.value;
    if (isCreating.value) {
        resetForm();
    }
};

const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const resetForm = () => {
    form.value = {
        file: null,
        fileName: null,
        filePreview: null,
    };
};

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

    try {
        // Send the request only when user confirms
        router.post(`/settings/adding-students/store`, formData, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                usePage().props.flash = { success: "Scholars added to the scholarship!" };
                headers.value = [];
                previewData.value = [];
                error.value = "";
                fileReadyToUpload.value = false;
                document.getElementById("dropzone-file").value = null; // Clear file input


            },
        });
    } catch (err) {
        error.value = "An error occurred while uploading the file.";
        console.error("Error during file upload:", err);
    }
};

const activeateForm = async () => {
    try {
        await useForm(scholarships.value).post(route('scholarships.store'));
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};


// radix vue testing

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

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 10;
const searchQuery = ref('');

// Computed property for filtering students
const filteredStudents = computed(() => {
    const allStudents = props.students || [];

    const query = searchQuery.value.toLowerCase();
    return allStudents.filter(student =>
        student.first_name?.toLowerCase().includes(query) ||
        student.last_name?.toLowerCase().includes(query) ||
        student.middle_name?.toLowerCase().includes(query) ||
        student.email?.toLowerCase().includes(query) ||
        student.course?.toLowerCase().includes(query) ||
        student.campus?.toLowerCase().includes(query) ||
        student.grant?.toLowerCase().includes(query) ||
        student.urscholar_id?.toLowerCase().includes(query)
    );
});

// Computed properties for pagination
const totalStudents = computed(() => filteredStudents.value.length);
const totalPages = computed(() => Math.ceil(totalStudents.value / itemsPerPage));

const paginatedStudents = computed(() => {
    const startIdx = (currentPage.value - 1) * itemsPerPage;
    return filteredStudents.value.slice(startIdx, startIdx + itemsPerPage);
});

const startIndex = computed(() =>
    totalStudents.value === 0 ? 0 : (currentPage.value - 1) * itemsPerPage + 1
);

const endIndex = computed(() =>
    Math.min(currentPage.value * itemsPerPage, totalStudents.value)
);

// Pagination methods
const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// Reset pagination when search changes
watch(searchQuery, () => {
    currentPage.value = 1;
});

</script>