<template>
  <!-- Global "No Scholars" Message (if no batches exist) -->
  <div v-if="!batches || batches.length === 0" class="text-center py-5 mt-5">
    <p class="text-lg text-gray-700 dark:text-gray-300">No scholars added yet</p>
  </div>
  
  <div v-else class="w-full mt-5 bg-white">
    <ul class="text-primary flex space-x-5 flex-grow justify-left font-quicksand font-semibold">
      <li v-for="batch in batches" :key="batch.id"><button  @click="toggleBatch(batch.id)" class="px-4 py-2 border-b-2 cursor-pointer hover:border-gray-200"
          :class="expandedBatches === batch.id ? 'border-blue-400' : 'border-transparent'">Batch {{ batch.batch_no }}</button>
      </li>
    </ul>

        <div v-for="batch in batches" :key="batch.id">
          <div>
            <div v-if="expandedBatches === batch.id" class="w-full bg-white h-full p-4">
              <!-- line sections -->
              <div class="flex flex-row justify-between items-center mb-4">
                <div>
                  <Link>
                  <button type="button"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Send Access Details</button>
                  </Link>
                </div>
                <form class="w-3/12">
                  <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                  <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                      <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                      </svg>
                    </div>
                    <input type="search" id="default-search"
                      class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Search Scholar" required />
                    <button type="submit"
                      class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-0.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                  </div>
                </form>
              </div>

              <!-- table -->
              <div class="w-full justify-center items-center overflow-x-auto border rounded-lg shadow-sm ">
                <!-- <div v-if="scholars.length === 0" class="text-center py-5">
                <p class="text-lg text-gray-700 dark:text-gray-300">No scholars added</p>
              </div> -->
                <!-- Container with horizontal scroll -->
                <div class="w-[1535px] relative overflow-x-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded">
                  <table class="w-full">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                      <tr>
                        <!-- First 5 columns visible by default -->
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          URScholar ID
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Batch No.
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Grant
                        </th>
                        <th class="whitespace-nowrap px-8 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Campus
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Scholar's Name
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Degree Program
                        </th>
                        <!-- Additional scrollable columns -->
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Year Level
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Status
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Sex
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Birthday
                        </th>
                        <th class="whitespace-nowrap px-6 py-3 font-medium text-gray-700 dark:text-gray-300">
                          Email Address
                        </th>

                        <!-- Sticky actions column -->
                        <th
                          class="whitespace-nowrap px-10 py-3 font-medium text-gray-700 dark:text-gray-300 sticky right-0 bg-gray-200 dark:bg-gray-700 shadow-lg sr-only">
                          Action
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                      <tr v-for="scholar in batch.scholars" :key="scholar.id"
                        class="bg-white text-sm dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          test1
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ batch.batch_no }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.grant }}
                        </td>
                        <td class="whitespace-nowrap px-8 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.campus }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.course }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.year_level }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 items-center justify-center">
                          <span
                            :class="{
                              'bg-blue-100 text-blue-800 dark:bg-gray-700 dark:text-blue-400 border border-blue-400': scholar.status === 'Verified',
                              'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border border-red-400': scholar.status !== 'Verified'
                            }"
                            class="text-xs font-medium px-2.5 py-0.5 rounded">
                            {{ scholar.status }}
                          </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.sex }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                          {{ scholar.birthdate }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">

                        </td>
                        <!-- Sticky actions cell -->
                        <td class="whitespace-nowrap px-10 py-4 sticky right-0 bg-gray-200 dark:bg-gray-800 shadow-lg">
                          <Link :href="route('scholars_expand_details.details')">
                            <button class="p-2 border bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                              aria-label="View Details">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                            </button>
                          </Link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="mt-5 flex flex-col items-right">
                <!-- Help text -->
                <span class="text-sm text-gray-700 dark:text-gray-400">
                  Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span
                    class="font-semibold text-gray-900 dark:text-white">10</span> of <span
                    class="font-semibold text-gray-900 dark:text-white">100</span> Scholars
                </span>
                <!-- Buttons -->
                <div class="inline-flex mt-2 xs:mt-0">
                  <button
                    class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 rounded-s hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Prev
                  </button>
                  <button
                    class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 border-0 border-s border-gray-700 rounded-e hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Next
                  </button>
                </div>
              </div>
            </div>
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
  </div>
</template>

<script setup>
import { ref, onBeforeMount, reactive, defineEmits, watchEffect } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

const props = defineProps({
  scholarship: Object,
  schoolyear: Object,
  selectedSem: Object,
  batches: Array,
});

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
  Papa,
};

const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default


// const toggleBatch = (batchId) => {
//   if (expandedBatches.value.has(batchId)) {
//     expandedBatches.value.delete(batchId)
//   } else {
//     expandedBatches.value.add(batchId)
//   }
// }
// const expandedBatches = ref(null); // Track the currently open batch

const toggleBatch = (batchId) => {
  expandedBatches.value = expandedBatches.value === batchId ? null : batchId;
};

const addingPanel = ref(false)
const uploadingPanel = ref(false)

const toggleAdd = () => {
  addingPanel.value = !addingPanel.value
}

const toggleUpload = () => {
  uploadingPanel.value = !uploadingPanel.value
}

const closePanel = () => {
  previewData.value = [];
  headers.value = [];
  uploadingPanel.value = false;
  addingPanel.value = false;
  entries.value = false;
};



const form = ref({
  file: null,
  fileName: null,
  filePreview: null,
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


const handleFile = async (file) => {
  if (!file) return;

  // Set file details
  form.value.file = file;
  form.value.fileName = file.name;

  const reader = new FileReader();
  reader.onload = (e) => {
    form.value.filePreview = e.target.result;

    // Parse CSV file
    Papa.parse(e.target.result, {
      header: true,
      skipEmptyLines: true,
      complete: (results) => {
        if (results.data && results.data.length > 0) {
          const filteredData = results.data.filter((row) =>
            Object.values(row).some((value) => value !== "")
          );

          if (filteredData.length > 0) {
            headers.value = Object.keys(filteredData[0]);
            previewData.value = filteredData;
            error.value = "";
            fileReadyToUpload.value = true; // Set flag to enable confirm button
          } else {
            error.value = "No valid data found in the file";
            previewData.value = [];
            headers.value = [];
            fileReadyToUpload.value = false;
          }
        } else {
          error.value = "No data found in the file";
          previewData.value = [];
          headers.value = [];
          fileReadyToUpload.value = false;
        }
      },
      error: (err) => {
        error.value = "Error parsing CSV: " + err.message;
        previewData.value = [];
        headers.value = [];
        fileReadyToUpload.value = false;
      },
    });
  };

  reader.readAsText(file); // Read file
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

const addEntry = () => {
  if (formData.first_name && formData.last_name && formData.email && formData.course) {
    entries.value.push({ ...formData })
    resetForm()
  }
}

const resetForm = () => {
  formData.first_name = ''
  formData.last_name = ''
  formData.email = ''
  formData.course = ''
}

const removeEntry = (index) => {
  entries.value.splice(index, 1)
}


const submitForm = async () => {
  try {
    if (entries.value.length === 0) {
      alert('No data to submit!');
      return;
    }

    const response = await fetch('/api/insert-data', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(entries.value)
    });

    if (!response.ok) throw new Error('Failed to submit');

    // alert('Data submitted successfully!');

    // Clear entries after successful submission
    entries.value = [];
  } catch (error) {
    console.error('Error:', error);
  }
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