<template>
    <SettingsLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <h1 class="text-2xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                    <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>University of Rizal
                    System Students
                </h1>
                <div class="text-3xl font-semibold text-gray-700">
                    <span class="text-xl">Academic Year: {{ current_year.school_year.year || '2024' }} - {{
                        current_year.semester || 'Semester'
                    }} Semester</span>
                </div>

                <div class="flex justify-end items-center w-full gap-3">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="year-select"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">School
                                Year</label>
                            <Select v-model="selectedYear" required @update:modelValue="updateSemesters">
                                <SelectTrigger class="w-full border">
                                    <SelectValue placeholder="Select year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup v-for="schoolyear in [...schoolyears].reverse()" :key="schoolyear.id">
                                        <SelectItem :value="schoolyear.id">
                                            {{ schoolyear.year }}
                                            {{
                                                schoolyear.id === current_year?.school_year?.id ? '(Current)' : ''
                                            }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                        <div>
                            <label for="semester-select"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Semester</label>
                            <Select v-model="selectedSem" required :disabled="!availableSemesters.length">
                                <SelectTrigger class="w-full border">
                                    <SelectValue placeholder="Select Semester" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="semester in availableSemesters" :key="semester.id"
                                            :value="semester.semester">
                                            {{ semester.semester === '1st' ? 'First Semester' : 'Second Semester' }}
                                            {{ semester.status === 'Active' ? '(Active)' : '(Inactive)' }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <button @click="toggleCreate"
                        class="mt-1 flex items-center gap-2 rounded-md py-2.5 px-5 bg-white border text-sm hover:bg-slate-100 dark:border-gray-600 dark:bg-dprimary dark:text-dtext dark:hover:bg-primary">
                        <span class="material-symbols-rounded dark:text-dtext text-sm">
                            library_add
                        </span>
                        Import Students
                    </button>


                    <div class="w-3/12" v-if="students && students.length > 0">
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
                    <!-- Empty state message when no students are available -->
                    <div v-if="!getSelectedAcademicYearId"
                        class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">Select an Academic Year
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">Please select a school
                            year and semester to view the student list.</p>
                    </div>

                    <!-- Empty state when academic year is selected but no students are found -->
                    <div v-else-if="getSelectedAcademicYearId && filteredStudents.length === 0"
                        class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">No Students Available</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">
                            No students found for {{
                                props.schoolyears.find(sy => sy.id === selectedYear)?.year || ''
                            }} - {{
                                selectedSem === '1st' ? 'First' : 'Second'
                            }} Semester. Upload the student list to start managing your university students.
                        </p>
                    </div>

                    <!-- Student table shown only when students are available -->
                    <div v-else
                        class="relative overflow-x-auto border border-gray-200 dark:border-gray-600 rounded-lg w-full">
                        <!-- Add an enclosing div for scroll functionality -->
                        <div class="overflow-x-auto w-full max-w-full">
                            <table
                                class="min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-dprimary">
                                    <tr class="dark:text-dtext">
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Student Number</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Student Name</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Course</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Campus</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Year Level</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Email</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Contact Number</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Permanent Address</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Facebook Account (if any)
                                        </th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Place of Birth</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Date of Birth</th>
                                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Religion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="student in paginatedStudents" :key="student.id">
                                        <tr
                                            class="bg-white border-b dark:bg-dsecondary dark:border-gray-700 border-gray-200">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.student_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.first_name }} {{
                                                student.last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.course.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.campus.name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.year_level }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.contact_no ?? 'N/A'
                                            }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.permanent_address ??
                                                'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.facebook_account ?? 'N/A'
                                            }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.birthplace ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.birthdate ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ student.religion ?? 'N/A' }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination controls - only shown when there are students -->
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
                            <InputError v-if="errors?.file" :message="errors.file" class=" text-red-500" />
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
        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </SettingsLayout>
</template>

<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref, watchEffect, computed, watch, inject } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SettingsLayout from '@/Layouts/Settings_Layout.vue';
import { usePage } from "@inertiajs/vue3";
import { Tooltip } from 'primevue';
import { DatePicker } from 'primevue';
import InputError from '@/Components/InputError.vue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'


const dataOpenSideBar = inject('dataOpenSideBar');

// Add a check for undefined in case it hasn't been provided
if (!dataOpenSideBar) {
    console.error('Failed to inject dataOpenSideBar');
}

watch(dataOpenSideBar, (newValue) => {
    console.log('Sidebar state changed:', newValue);
});

const props = defineProps({
    sponsors: Array,
    students: Array,
    errors: Object,
    current_year: Object,
    schoolyears: Array,
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

// Initialize selectedYear and selectedSem with the current active year values
const selectedYear = ref(props.current_year?.school_year?.id || "");
const selectedSem = ref(props.current_year?.semester || "");
const availableSemesters = ref([]);

// Computed property to get the currently active academic year ID
const activeAcademicYearId = computed(() => {
    if (props.current_year) {
        return props.current_year.id;
    }
    return null;
});

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

    // If no semester is selected yet, try to select active semester if available
    if (!selectedSem.value && availableSemesters.value.length > 0) {
        const activeSem = availableSemesters.value.find(sem => sem.status === 'Active');
        if (activeSem) {
            selectedSem.value = activeSem.semester;
        } else {
            selectedSem.value = availableSemesters.value[0].semester;
        }
    }
};

// Function to get the academic_year_id based on the selected year and semester
const getSelectedAcademicYearId = computed(() => {
    if (!selectedYear.value || !selectedSem.value) return null;

    const selectedSchoolYear = props.schoolyears.find(sy => sy.id === selectedYear.value);
    if (!selectedSchoolYear || !selectedSchoolYear.academic_year) return null;

    const academicYears = Array.isArray(selectedSchoolYear.academic_year)
        ? selectedSchoolYear.academic_year
        : [selectedSchoolYear.academic_year];

    const selectedAcademicYear = academicYears.find(ay => ay.semester === selectedSem.value);
    return selectedAcademicYear ? selectedAcademicYear.id : null;
});

// Modified filteredStudents computed property to filter by academic year
const filteredStudents = computed(() => {
    const allStudents = props.students || [];
    const academicYearId = getSelectedAcademicYearId.value;

    // If no academic year is selected, return an empty array (don't show any students)
    if (!academicYearId) {
        return [];
    }

    // First filter by academic year
    let filtered = allStudents.filter(student => student.academic_year_id === academicYearId);

    // Then filter by search query
    const query = searchQuery.value.toLowerCase();
    if (query) {
        filtered = filtered.filter(student =>
            student.first_name?.toLowerCase().includes(query) ||
            student.last_name?.toLowerCase().includes(query) ||
            student.middle_name?.toLowerCase().includes(query) ||
            student.email?.toLowerCase().includes(query) ||
            student.student_number?.toLowerCase().includes(query) ||
            student.course?.name?.toLowerCase().includes(query) ||
            student.campus?.name?.toLowerCase().includes(query)
        );
    }

    return filtered;
});

// Watch for changes to the selected year or semester to update the filtered list
watch([selectedYear, selectedSem], () => {
    currentPage.value = 1; // Reset to first page when filters change
});

// The rest of the pagination code remains the same
const totalStudents = computed(() => filteredStudents.value.length);
const totalPages = computed(() => Math.ceil(totalStudents.value / itemsPerPage));

const paginatedStudents = computed(() => {
    const startIdx = (currentPage.value - 1) * itemsPerPage;
    return filteredStudents.value.slice(startIdx, startIdx + itemsPerPage);
});

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
                // usePage().props.flash = { success: "Scholars added to the scholarship!" };
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

// Initialize the available semesters based on the selected year
watchEffect(() => {
    updateSemesters();
});

// radix vue testing
// Pagination state
const currentPage = ref(1);
const itemsPerPage = 10;
const searchQuery = ref('');

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