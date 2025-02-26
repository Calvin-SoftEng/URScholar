<template>
  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">
        <button
          class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded"
          @click="toggleCamera">
          <font-awesome-icon :icon="['fas', 'file-lines']" class="mr-2 text-sm" />Open Camera
        </button>
        <button
          class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded"
          @click="openReport">
          <font-awesome-icon :icon="['fas', 'file-export']" class="mr-2 text-sm" />Export
        </button>
      </div>
      <form class="w-3/12">
        <label for="default-search"
          class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
          </div>
          <input type="search" id="default-search" v-model="searchQuery"
            class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Search Scholar" required />
        </div>
      </form>
    </div>

    <div>
      <div>
        <div class="w-full bg-white h-full p-4">

          <!-- table -->
          <div v-show="!showRequirements" class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table rounded-lg">
              <!-- head -->
              <thead class="justify-center items-center bg-gray-100">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar</th>
                  <th>Grant</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-sm">
                  <td>test1</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          Raul, John Mark
                          <!-- <span v-if="scholar.status === 'Verified'"
                            class="material-symbols-rounded text-sm text-blue-600">verified</span> -->
                        </div>
                        <div class="text-sm opacity-50">
                          <!-- {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }} -->
                          4th year, Bachelor of Science in Information Technology
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Binangonan
                  </td>
                  <td>
                    LISTAHANAN
                  </td>
                  <td>
                    <!-- <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                      'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                      'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                      Claimed
                    </span> -->
                    <span
                      class="bg-green-100 text-green-800 border border-green-400 text-xs font-medium px-2.5 py-0.5 rounded">Claimed</span>
                  </td>
                </tr>
                <tr class="text-sm">
                  <td>test2</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          Eyy, Eyy
                          <!-- <span v-if="scholar.status === 'Verified'"
                            class="material-symbols-rounded text-sm text-blue-600">verified</span> -->
                        </div>
                        <div class="text-sm opacity-50">
                          <!-- {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }} -->
                          4th year, Bachelor of Science in Information Technology
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    Binangonan
                  </td>
                  <td>
                    LISTAHANAN
                  </td>
                  <td>
                    <!-- <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                      'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                      'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                      Claimed
                    </span> -->
                    <span
                      class="bg-gray-200 text-gray-500 border border-gray-400 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- <div v-if="totalScholars > 10" class="mt-5 flex flex-col items-right">
              <span class="text-sm text-gray-700 dark:text-gray-400">
                Showing
                <span class="font-semibold text-gray-900 dark:text-white">{{ startIndex }}</span>
                to
                <span class="font-semibold text-gray-900 dark:text-white">{{ endIndex }}</span>
                of
                <span class="font-semibold text-gray-900 dark:text-white">{{ totalScholars }}</span>
                Scholars
              </span>
              <div class="inline-flex mt-2 xs:mt-0">
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
            </div> -->
          </div>
        </div>
        <!-- open cam -->
        <div v-if="OpenCamera"
          class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
          <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
              <span class="text-xl font-semibold text-gray-900 dark:text-white">
                <h2 class="text-2xl font-bold">Scan QR here</h2>
              </span>
              <button type="button" @click="closeCamera"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="default-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
              </button>
            </div>

            <!-- QR Code Scanner -->
            <div class="p-4 flex flex-col space-y-4">
              <div class="qr-scanner">
                <QrcodeStream @decode="onDecode" @init="onInit" class="qr-scanner-box" />

                <div v-if="loading" class="loading">
                  <p>Processing QR Code...</p>
                </div>

                <div v-if="successMessage" class="success">
                  <p class="text-green-600 font-semibold">{{ successMessage }}</p>
                </div>

                <div v-if="errorMessage" class="error">
                  <p class="text-red-500 font-semibold">{{ errorMessage }}</p>
                </div>
              </div>
            </div>
            <!-- <div class="mt-2">
                  <button type="submit"
                      class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                      {{ isEditing ? 'Update Scholarship' : 'Create Scholarship' }}</button>
              </div> -->
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
import { ref, onBeforeMount, reactive, defineEmits, watchEffect, onMounted, computed, watch } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
import { QrcodeStream } from "vue-qrcode-reader";

const props = defineProps({
  scholarship: Object,
  schoolyear: Object,
  selectedSem: Object,
  batches: Object,
  scholars: Array,
  requirements: Array,
});

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
};


const currentPage = ref(1);
const itemsPerPage = 10;
const searchQuery = ref('');

// Modify the filteredScholars function to handle pagination
const filteredScholars = computed(() => {
  // Use props.scholars directly instead of accessing from batches
  const allScholars = props.scholars || [];

  // Apply search filter
  let scholars = allScholars.filter(scholar =>
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
});

// Pagination computed properties
const paginatedScholars = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filteredScholars.value.slice(startIndex, endIndex);
});

const totalScholars = computed(() => {
  return filteredScholars.value.length;
});

const totalPages = computed(() => {
  return Math.ceil(totalScholars.value / itemsPerPage);
});

// Display range computed properties
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const endIndex = computed(() => Math.min(currentPage.value * itemsPerPage, totalScholars.value));

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

// Reset page when search changes
watch(searchQuery, () => {
  currentPage.value = 1;
});


const OpenCamera = ref(false);

const toggleCamera = () => {
  OpenCamera.value = !OpenCamera.value;
};

const closeCamera = () => {
  OpenCamera.value = false;
}

const loading = ref(false);
const successMessage = ref(null);
const errorMessage = ref(null);
const debugData = ref(null);

const onDecode = (decodedText) => {
  try {
    loading.value = true;
    successMessage.value = null;
    errorMessage.value = null;
    debugData.value = decodedText; // Debugging output

    const scannedData = JSON.parse(decodedText); // Parse JSON from QR code

    // Send scanned data to Laravel backend
    router.post(
      "/cashier/verify-qr",
      {
        id: scannedData.id,
        timestamp: scannedData.timestamp,
      },
      {
        onSuccess: (page) => {
          successMessage.value = page.props.flash.message || "QR Code Verified!";
        },
        onError: (err) => {
          errorMessage.value = err.message || "Invalid QR Code";
        },
        onFinish: () => {
          loading.value = false;
        },
      }
    );
  } catch (err) {
    loading.value = false;
    errorMessage.value = "Invalid QR Code format";
  }
};

const onInit = (promise) => {
  promise.catch(() => {
    errorMessage.value = "Camera access was denied.";
  });
};

// Add download report functionality
const openReport = async (batchId) => {
  try {
    // Open URL in new tab instead of creating blob
    window.open(`/scholarships/${props.scholarship.id}/batch/${batchId}/report`, '_blank');
  } catch (err) {
    console.error('Failed to open report:', err);
  }
};

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



</script>


<style>
.qr-scanner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.qr-scanner-box {
  width: 100%;
  max-width: 400px;
  height: 300px;
  border: 2px solid #ddd;
  border-radius: 10px;
}

.loading {
  background: #fffbcc;
  padding: 10px;
  border-radius: 5px;
  font-weight: bold;
}

.success {
  background: #d4edda;
  padding: 10px;
  border-radius: 5px;
}

.error {
  background: #f8d7da;
  padding: 10px;
  border-radius: 5px;
}


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