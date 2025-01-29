<template>  
  <div class="w-full">
    <!-- line sections -->
    <div class="flex flex-row justify-between items-center mb-4">
      <div class="flex justify-between items-center">

        <div>
          <button @click="toggleCreate" type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Add Scholars</button>
        </div>

      </div>

      <form class="w-3/12">   
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" id="default-search" class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Scholar" required />
            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-0.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
        </div>
      </form>
    </div>

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
    <div class="mt-5 flex flex-col items-left">
      <!-- Help text -->
      <span class="text-sm text-gray-700 dark:text-gray-400">
          Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white">10</span> of <span class="font-semibold text-gray-900 dark:text-white">100</span> Scholars
      </span>
      <!-- Buttons -->
      <div class="inline-flex mt-2 xs:mt-0">
          <button class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 rounded-s hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              Prev
          </button>
          <button class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 border-0 border-s border-gray-700 rounded-e hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              Next
          </button>
      </div>
    </div>
  </div>

  <!-- Overlay to detect clicks outside -->
  <Transition name="slide">
      <div 
        v-show="showPanel" 
        class="fixed inset-0 z-50 flex justify-end"
        @click.self="closePanel" 
      >
        <!-- Right side panel -->
        <div 
          class="h-full overflow-y-auto bg-white w-4/12 shadow-lg transition-transform duration-300"
          @click.stop 
        >
          <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Add Scholars</h2>
            <button 
              @click="closePanel"
              class="p-2 hover:bg-gray-100 rounded-full transition-colors"
            >
              <span class="material-symbols-rounded">close</span>
            </button>
          </div>
  
          <!-- File Upload -->
          <div class="card">
            <FileUpload 
              name="demo[]" 
              @uploader="onUpload" 
              :multiple="true" 
              accept=".csv" 
              :maxFileSize="1000000"
              customUpload 
              @select="handleFileUpload"
              @clear="clearPreview"
              id="file-upload"
            />
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
                <tr v-for="(row, rowIndex) in previewData" :key="rowIndex" class="bg-white border-b hover:bg-gray-50">
                  <td v-for="(header, colIndex) in headers" :key="colIndex" class="px-6 py-4 whitespace-nowrap">
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
        </div>
      </div>
    </Transition>
  
  
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



