<template>  
  <div class="w-full">
    <!-- line sections -->
    <div class="flex flex-row justify-between items-center mb-4">
      <div class="flex justify-between items-center">

        <!-- <Link :href="`/scholarships/${scholarship.id}/send-access`"> -->
        <div>
          <Link :href="`/scholarships/${props.scholarship.id}/send-access`">
          <button type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Send Access Details</button>
          </Link>
        </div>
        <!-- </Link> -->

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
    <div class="">
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">URScholar ID</th>
              <th scope="col" class="px-6 py-3">Full Name</th>
              <th v-for="(requirement, index) in requirements" :key="index" scope="col" class="px-6 py-3">
      {{ requirement.requirements[index] }}
    </th>
              <th scope="col" class="px-6 py-3">
                <span class="sr-only">Expand</span>
              </th>
              <th scope="col" class="px-6 py-3">
                <span class="sr-only">Expand</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(submitted, index) in submitRequirements" :key="index">
              <!-- Main Row -->
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ submitted.scholar_id }}
                </th>
                <td class="px-6 py-4">
                  
                </td>
                <td class="px-6 py-4">
                  {{ submitted.submitted_requirements }}
                </td>
                <td class="px-6 py-4">
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-blue-400 border border-blue-400">
                  <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                  </svg>
                  Pending
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <Link :href="route('requirements.expand_requirements')">
                    <span class="material-symbols-rounded cursor-pointer">
                    open_in_full
                    </span>
                  </Link>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
    <div class="mt-5 flex flex-col items-right">
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
    scholars: Array,
    requirements: Object,
    submitRequirements: Array,
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