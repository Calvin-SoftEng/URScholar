<template>
    <AuthenticatedLayout>
        <div class="w-full h-full px-10 py-5 bg-[#F8F8FA] overflow-x-auto">
            <div class="w-full mx-auto p-3 rounded-xl text-white overflow-x-auto">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li>
                            <a>
                                <span class="material-symbols-rounded mr-2" style="color: #0D47A1; font-size: 20px;">
                                    dashboard
                                </span>
                                Home
                            </a>
                        </li>
                        <li>
                            <a>
                                <span>Ongoing Scholarships</span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="text-blue-400 font-semibold">List of Scholars</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <h1
                        class="text-3xl font-poppins font-extrabold text-[darkblue] text-left underline underline-offset-4">
                        <span>{{ scholarship.name }}</span> Scholars
                    </h1>

                    <div class="space-x-2">
                        <Link :href="`/scholarships/${scholarship.id}/send-access`">
                        <button class="btn bg-blue-900 text-white">
                            <span class="material-symbols-rounded">
                                forward_to_inbox
                            </span>
                            Send Access Details
                        </button>
                        </Link>

                        <button class="btn bg-gray-300" @click="toggleCreate">
                            <span class="material-symbols-rounded">
                                library_add
                            </span>
                            Add Scholars
                        </button>
                    </div>
                </div>
            </div>

            <!-- table -->
            <div class="bg-white">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">URScholar ID</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Course</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Expand</span>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Expand</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(scholar, index) in scholars" :key="scholar.id">
                                <!-- Main Row -->
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ scholar.id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ `${scholar.first_name} ${scholar.last_name}` }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ scholar.course }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ scholar.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                                            </svg>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button>
                                            <span class="material-symbols-rounded">
                                                unfold_more
                                            </span>
                                        </button>
                                    </td>
                                </tr>

                            </template>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="flex flex-col items-center">
                <!-- Help text -->
                <span class="text-sm text-gray-700 dark:text-gray-400">
                    Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span
                        class="font-semibold text-gray-900 dark:text-white">10</span> of <span
                        class="font-semibold text-gray-900 dark:text-white">100</span> Entries
                </span>
                <!-- Buttons -->
                <div class="inline-flex mt-2 xs:mt-0">
                    <button
                        class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        Prev
                    </button>
                    <button
                        class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        Next
                    </button>
                </div>
            </div>
        </div>


        <!-- Right side panel with transition -->
        <Transition name="slide">
            <div v-show="showPanel"
                class="h-full overflow-y-auto z-50 bg-white w-3/12 fixed right-0 top-0 shadow-lg transition-transform duration-300">
                <div class="p-4 border-b flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Add Scholars</h2>
                    <button @click="closePanel" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <!-- <form @submit.prevent="uploadCSV"> -->
                <div class="card">
                    <FileUpload name="demo[]" @uploader="onUpload" :multiple="true" accept=".csv" :maxFileSize="1000000"
                        customUpload @select="handleFileUpload" @clear="clearPreview" id="file-upload" />
                </div>

                <!-- Error Message -->
                <div v-if="error" class="text-red-500 mb-4">
                    {{ error }}
                </div>

                <!-- Preview Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mt-4">
                    <table v-if="previewData.length > 0" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th v-for="header in headers" :key="header" class="px-6 py-3">
                                    {{ header }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, rowIndex) in previewData" :key="rowIndex"
                                class="bg-white border-b hover:bg-gray-50">
                                <td v-for="(header, colIndex) in headers" :key="colIndex"
                                    class="px-6 py-4 whitespace-nowrap">
                                    {{ row[header] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div v-if="previewData.length === 0 && !error" class="text-center py-4 text-gray-500">
                        No data to display. Please upload a CSV file.
                    </div>
                </div>
                <!-- </form> -->
            </div>
        </Transition>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, onBeforeMount, reactive } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Papa from 'papaparse';

const components = {
    DataTable,
    Column,
    Button,
    FileUpload,
    Papa,
};

const expandedRows = ref([]);

function expandAll() {
    expandedRows.value = products.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
}

function collapseAll() {
    expandedRows.value = null;
}

const toggleRow = (index) => {
    const position = this.expandedRows.indexOf(index)
    if (position !== -1) {
        this.expandedRows.splice(position, 1)
    } else {
        this.expandedRows.push(index)
    }
}

// testing

const showPanel = ref(false)

const toggleCreate = () => {
    showPanel.value = !showPanel.value
}

const closePanel = () => {
    previewData.value = [];
    headers.value = [];
    document.getElementById('file-upload').value = null;
    showPanel.value = false

}

const props = defineProps({
    scholarship: Object,
    scholars: Array
});

const form = useForm({
    file: null,
});

const previewData = ref([]);
const headers = ref([]);
const error = ref('');

const handleFileUpload = (event) => {
    // eto ay para mafetch yung file
    const file = event.files[0];
    // eto naman ay para mafetch yung file as object
    if (event && event.files && event.files.length > 0) {
        const file = event.files[0]; // Get first file

        const reader = new FileReader();

        reader.onload = function (e) {
            Papa.parse(e.target.result, {
                header: true,
                complete: (results) => {
                    if (results.data && results.data.length > 0) {
                        // Filter out empty rows
                        const filteredData = results.data.filter(row =>
                            Object.values(row).some(value => value !== '')
                        );

                        if (filteredData.length > 0) {
                            headers.value = Object.keys(filteredData[0]);
                            previewData.value = filteredData;
                            error.value = '';
                            console.log('Processed data:', previewData.value); // Debug log
                        } else {
                            error.value = 'No valid data found in the file';
                            previewData.value = [];
                            headers.value = [];
                        }
                    } else {
                        error.value = 'No data found in the file';
                        previewData.value = [];
                        headers.value = [];
                    }
                },
                error: (err) => {
                    error.value = 'Error parsing CSV: ' + err.message;
                    previewData.value = [];
                    headers.value = [];
                }
            });
        };
        reader.readAsText(file);
    }

};

const clearPreview = () => {
    previewData.value = [];
    headers.value = [];
};

const onUpload = async (event) => {
    form.file = event.files[0];

    const response = await form.post(`/scholarships/${props.scholarship.id}/upload`);

    if (response.ok) {
        headers.value = [];
        previewData.value = [];
        error.value = "";
        document.getElementById('file-upload').value = null; // Clear file input
        // Optionally, you can also refresh the scholars list here if needed
        // For example, you can emit an event or call a method to fetch the updated list
    } else {
        error.value = "Failed to upload file. Please try again.";
    }
}


const uploadCSV = () => {
    form.post(`/scholarships/${props.scholarship.id}/upload`, {
        preserveScroll: true,
    });
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