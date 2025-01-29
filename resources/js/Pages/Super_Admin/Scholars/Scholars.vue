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
                  <span>Scholarships</span>
              </a>
              </li>
              <li>
              <a>
                  <span class="text-blue-400 font-semibold">{{ scholarship.name }} Dashboard</span>
              </a>
              </li>
            </ul>
          </div>
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-4xl font-poppins font-extrabold text-[darkblue] text-left">
              <span>{{ scholarship.name }}</span> Scholars 2024-2025 ganyan</h1>
          </div>

          <div>
            <h1 class="text-black">
              bfbeafbaefpbeafaiefbapeb
            </h1>
          </div>

          <div class="w-full h-full bg-[#F8F8FA] justify-between items-center mb-4 px-32">
            <!-- table -->
            <div class="bg-white">
              <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                      <th scope="col" class="px-6 py-3">Requirements</th>
                      <th scope="col" class="px-6 py-3">File Uploaded</th>
                      <th scope="col" class="px-6 py-3">Date Submitted</th>
                      <th scope="col" class="px-6 py-3">Status</th>
                      <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Expand</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(scholar, index) in scholars" :key="scholar.id">
                      <!-- Main Row -->
                      <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                          {{ scholar.id }}
                        </th>
                        <td class="px-6 py-2">
                          {{ `${scholar.first_name} ${scholar.last_name}` }}
                        </td>
                        <td class="px-6 py-2">
                          {{ scholar.course }}
                        </td>
                        <td class="px-6 py-2">
                          {{ scholar.course }}
                        </td>
                        <td class="px-6 py-2">
                          <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">Default</span>
                        </td>
                        <td class="px-2 py-2 flex flex-row-reverse text-right items-center">
                            <button type="button" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                              View Requirements</button>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
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
    
    reader.onload = function(e) {
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
  } else {
    error.value = "Failed to upload file. Please try again.";
  }
}

// toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
  // form.post(`/scholarships/${props.scholarship.id}/upload`, {
  //   preserveScroll: true,
  // });

  // if (response.ok) {
  //         headers.value = [];
  //         previewData.value = [];
  //         error.value = "";
  //         alert("Data inserted successfully!");
  //       } else {
  //         const errorResponse = await response.json();
  //         error.value = errorResponse.message || "Failed to insert data. Please try again.";
  //       }

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