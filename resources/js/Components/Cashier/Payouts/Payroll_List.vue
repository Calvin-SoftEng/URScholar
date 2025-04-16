<template>
  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">
        <button
          class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded"
          @click="openReport">
          <font-awesome-icon :icon="['fas', 'file-export']" class="mr-2 text-sm" />Generate Report
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
            placeholder="Search Grantee" required />
        </div>
      </form>
    </div>

    <div>
      <div>
        <div class="w-full bg-white h-full p-4">
          <div class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table rounded-lg">
              <thead class="justify-center items-center bg-gray-50">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar</th>
                  <th>Grant</th>
                  <th>Campus</th>
                  <th>Time Claimed</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="scholar in filteredScholars" :key="scholar.id" class="text-sm">
                  <td>{{ scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img 
                            :src="`/storage/user/profile/${scholar.user?.picture}` || '../../../../assets/images/no_userpic.png'" 
                            alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ scholar.first_name }} {{ scholar.middle_name }} {{ scholar.last_name }}
                        </div>
                        <div class="text-sm opacity-50">
                          {{ scholar.course }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ scholar.grant }}
                  </td>
                  <td>
                    {{ scholar.campus }}
                  </td>
                  <td>
                    <!-- This would be filled with actual claim time data -->
                    N/A
                  </td>
                  <td>
                    <span 
                      :class="{
                        'bg-green-100 text-green-800 border border-green-400': scholar.claimed_status === 'Claimed',
                        'bg-yellow-100 text-yellow-800 border border-yellow-400': scholar.claimed_status === 'Pending',
                        'bg-red-100 text-red-800 border border-red-400': scholar.claimed_status === 'Not Claimed'
                      }" 
                      class="text-xs font-medium px-2.5 py-0.5 rounded">
                      {{ scholar.claimed_status ?? 'Pending' }}
                    </span>
                  </td>
                  <td v-if="scholar.student_status === 'Not Claimed'">
                    <button @click="toggleReason(scholar)"
                      class="p-2 border bg-white text-primary rounded-lg hover:bg-blue-200 transition-colors shadow-sm"
                      aria-label="View Details">
                      <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- reasoning -->
        <div v-if="Reasoning"
          class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
          <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">

              <div class="flex items-center gap-3">
                <!-- Icon -->
                <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

                <!-- Title and Description -->
                <div class="flex flex-col">
                  <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                    Reason for Not Claim
                  </h2>
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

            <div class="p-4 flex flex-col space-y-4">
              <div class="relative space-y-3">
                <h3 class="font-semibold text-gray-900 dark:text-white">
                  As per Scholar: <span>{{ selectedScholar ? `${selectedScholar.first_name} ${selectedScholar.last_name}` : '' }}</span>
                </h3>
                <textarea id="subject" rows="4" v-model="reasonText"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Write a message"></textarea>
                <InputError v-if="errors.reason" :message="errors.reason" class="mt-1" />
              </div>

              <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                  Download Additional Documents for Reason
                </label>

                <ul class="space-y-2">
                  <li v-for="file in uploadedFiles" :key="file.name"
                    class="flex items-center gap-2 p-2 border rounded-lg bg-gray-50 dark:bg-gray-700">
                    <font-awesome-icon :icon="['fas', 'file-alt']" class="text-gray-600 dark:text-gray-300" />
                    <a :href="file.url" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">
                      {{ file.name }}
                    </a>
                  </li>
                </ul>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">Click a file to download.</p>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>

    <ToastProvider>
      <ToastRoot v-if="toastVisible"
        class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
        <ToastTitle class="font-semibold dark:text-dtext">{{ toastTitle }}</ToastTitle>
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
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  scholarship: Object,
  batch: Object,
  scholars: Array,
  errors: Object,
  flash: Object,
  scholar: Object,
});

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
};

const searchQuery = ref('');
const showRequirements = ref(false);
const OpenCamera = ref(false);
const Reasoning = ref(false);
const scannedResult = ref(null);
const scannedScholar = ref(null);
const errorMessage = ref(null);
const successMessage = ref(null);
const isScanning = ref(true);
const toastVisible = ref(false);
const toastTitle = ref('');
const toastMessage = ref('');
const selectedScholar = ref(null);
const reasonText = ref('');
const uploadedFiles = ref([]);

// Filtered scholars based on search query
const filteredScholars = computed(() => {
  if (!props.scholars) return [];
  
  return props.scholars.filter(scholar => {
    if (!scholar) return false;
    
    return (
      scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.urscholar_id?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.course?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.campus?.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  });
});

// Toggle camera visibility
const toggleCamera = () => {
  OpenCamera.value = !OpenCamera.value;
  if (OpenCamera.value) {
    isScanning.value = true;
    scannedResult.value = null;
    errorMessage.value = null;
    successMessage.value = null;
  }
};

const closeCamera = () => {
  OpenCamera.value = false;
}

const toggleReason = (scholar) => {
  selectedScholar.value = scholar;
  Reasoning.value = true;
};

const closeModal = () => {
  Reasoning.value = false;
  selectedScholar.value = null;
  reasonText.value = '';
}

function formatDate(dateString) {
  const options = {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
  };
  return new Date(dateString).toLocaleString('en-US', options);
}

// Restart QR scanner
const restartScan = () => {
  scannedResult.value = null;
  isScanning.value = true;
  errorMessage.value = null;
  successMessage.value = null;
};

// Open batch report
const openReport = () => {
  try {
    window.open(`/scholarships/${props.scholarship.id}/batch/${props.batch.id}/report`, '_blank');
  } catch (err) {
    console.error('Failed to open report:', err);
    showToast('Error', 'Failed to open report');
  }
};

// Show toast notification
const showToast = (title, message) => {
  toastTitle.value = title;
  toastMessage.value = message;
  toastVisible.value = true;

  // Hide toast after 3 seconds
  setTimeout(() => {
    toastVisible.value = false;
  }, 3000);
};

// Helper function for year level suffix
const getYearSuffix = (year) => {
  if (year === 1) return "st";
  if (year === 2) return "nd";
  if (year === 3) return "rd";
  return "th";
};

watchEffect(() => {
  const flashMessage = usePage().props.flash?.success;

  if (flashMessage) {
    console.log("Showing toast with message:", flashMessage);
    usePage().props.scholar = flashMessage;
    toastTitle.value = "Success";
    toastMessage.value = flashMessage.first_name ? `${flashMessage.first_name} ${flashMessage.last_name} updated successfully` : flashMessage;
    toastVisible.value = true;
    setTimeout(() => {
      console.log("Hiding toast...");
      toastVisible.value = false;
    }, 3000);
  }
});

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

/* In your global styles (e.g., styles.css or a <style> block) */
@keyframes scanLine {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(100%);
    /* Move the line down the whole camera view */
  }

  100% {
    transform: translateY(0);
  }
}

.animate-scan-line {
  animation: scanLine 2s infinite ease-in-out;
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