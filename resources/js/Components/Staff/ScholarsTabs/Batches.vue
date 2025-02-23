<template>
  <!-- Global "No Scholars" Message (if no batches exist) -->
  <div v-if="!batches || batches.length === 0" class="text-center py-5 mt-5">
    <p class="bg-white p-5 rounded-lg text-lg shadow-sm text-gray-700 dark:text-gray-300">No scholars added yet</p>
  </div>

  <div v-else class="w-full mt-5 rounded-xl space-y-5">
    <!-- <div class="p-4 flex flex-row justify-between items-center">
      <span>SY 2024 - Semester</span>
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
          <input type="search" id="default-search" v-model="searchQuery"
            class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Search Scholar" required />
        </div>
      </form>
    </div> -->
    <!-- <div class="bg-gray-100 mx-4 rounded-lg p-1">
      <ul class="flex space-x-5 flex-grow justify-left font-quicksand font-semibold">
        <li v-for="batch in batches" :key="batch.id"><button @click="toggleBatch(batch.id)"
            class="px-10 py-1 border-b-2 cursor-pointer hover:bg-gray-300 hover:text-gray-600 rounded-lg"
            :class="expandedBatches === batch.id ? 'bg-white text-primary' : 'bg-gray-100 text-gray-400'">Batch {{ batch.batch_no
            }}</button>
        </li>
      </ul>
    </div> -->
    <!-- Stats Section -->
    <div class="w-full h-[1px] bg-gray-200"></div>

    <div class="grid grid-cols-5">
        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Scholarship Batches</p>
            </div>
            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                <p class="text-4xl font-semibold font-kanit">55</p>
                <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new Batch</button>
            </div>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Total Verified Scholars</p>
            </div>
            <div class="w-full flex flex-row justify-between space-x-3 items-end">
                <p class="text-4xl font-semibold font-kanit">55</p>
            </div>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Unverified Scholars</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">1</p>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Submitted Requirements</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">2</p>
        </div>

        <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
            <div class="flex flex-row space-x-3 items-center">
                <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base"/>
                <p class="text-gray-500 text-sm">Completed Scholars</p>
            </div>
            <p class="text-4xl font-semibold font-kanit">2</p>
        </div>
    </div>

    <div class="w-full h-[1px] bg-gray-200"></div>

    <div class="flex flex-row justify-between items-center">
      <span>List of Batches {{ props.selectedSem }} {{ schoolyear.year }}</span>

      <button @click="toggleSendBatch" class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
        <span class="font-normal">Forward Completed Scholars</span>
      </button>
    </div>
    

    <div v-for="batch in batches" :key="batch.id"
      class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
      <div @click="() => openBatch(batch.id)" class="flex flex-row justify-between items-center cursor-pointer">
        <span>Batch {{ batch.batch_no }}</span>
        <div class="grid grid-cols-2">
          <div class="flex flex-col">
            <span>No of Scholars</span>
            <span>200</span>
          </div>
          <div class="flex flex-col">
            <span>No of Unverified Scholars</span>
            <span>200</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- forwarding batch list -->
  <div v-if="ForwardBatchList"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
    <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
            <span class="text-xl font-semibold text-gray-900 dark:text-white">
                <h2 class="text-2xl font-bold">
                  Forwarding Batch List
                </h2>
            </span>
            <button type="button" @click="closeModal"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="default-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <!-- body -->
        <div class="p-5">
          <label for="batchSelection" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Select a Batch to Forward:
          </label>
          <select id="batchSelection" v-model="selectedBatch"
              class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
              <option value="all">📤 Send All Batches</option>
              <option v-for="batch in batches" :key="batch.id" :value="batch.id">
                  {{ batch.batch_no }}
              </option>
          </select>

          <!-- Forward Button -->
          <!-- <div class="mt-4 flex justify-end">
              <button @click="forwardBatches"
                  class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                  Forward Selected Batches
              </button>
          </div> -->
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
</template>

<script setup>
import { ref, onBeforeMount, reactive, defineEmits, watchEffect, onMounted } from 'vue';
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

const ForwardBatchList = ref(false);

const toggleSendBatch = () => {
  ForwardBatchList.value = !ForwardBatchList.value;
}

const closeModal = () => {
    ForwardBatchList.value = false;
    resetForm();
};

const searchQuery = ref('');

// Add the filtered scholars function
const filteredScholars = (batch) => {
  if (!batch.scholars) return [];

  let scholars = batch.scholars.filter(scholar =>
    scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.email?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.course?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.campus?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );

  // Sort so that 'Verified' scholars appear first
  scholars.sort((a, b) => (a.status === 'Verified' ? -1 : 1));

  return scholars;
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

const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default

onMounted(() => {
  if (props.batches && props.batches.length > 0) {
    expandedBatches.value = props.batches[0].id;
  }
});

// Add download report functionality
const openReport = async (batchId) => {
  try {
    // Open URL in new tab instead of creating blob
    window.open(`/scholarships/${props.scholarship.id}/batch/${batchId}/report`, '_blank');
  } catch (err) {
    console.error('Failed to open report:', err);
  }
};

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

const getYearSuffix = (year) => {
  if (year === 1) return "st";
  if (year === 2) return "nd";
  if (year === 3) return "rd";
  return "th";
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