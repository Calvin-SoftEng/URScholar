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
                  <th>Campus</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="disbursement in filteredDisbursements" :key="disbursement.id" class="text-sm">
                  <td>{{ disbursement.scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ disbursement.scholar.last_name }}, {{ disbursement.scholar.first_name }} {{
                            disbursement.scholar.middle_name
                          }}
                        </div>
                        <div class="text-sm opacity-50">
                          {{ disbursement.scholar.year_level }}{{ getYearSuffix(disbursement.scholar.year_level) }}
                          year, {{
                            disbursement.scholar.course.name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ disbursement.scholar.grant }}
                  </td>
                  <td>
                    {{ disbursement.scholar.campus.name }}
                  </td>
                  <td>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': disbursement.status === 'Claimed',
                      'bg-yellow-100 text-yellow-800 border border-yellow-400': disbursement.status === 'Pending',
                      'bg-red-100 text-red-800 border border-red-400': disbursement.status === 'Not Claimed'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                      {{ disbursement.status }}
                    </span>
                  </td>
                  <td>
                    <button @click="toggleReason"
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
        <!-- open cam -->
        <div v-if="OpenCamera"
          class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
          <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
              
              <div class="flex items-center gap-3">
                  <!-- Icon -->
                  <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

                  <!-- Title and Description -->
                  <div class="flex flex-col">
                      <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                          Scan your QR Code here
                      </h2>
                      <span class="text-sm text-gray-600 dark:text-gray-400">
                          
                      </span>
                  </div>
              </div>


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
              <!-- QR Code Scanner Stream with Animation -->
              <div v-if="isScanning"
                class="relative w-full h-96 bg-gray-200 mt-4 flex items-center justify-center custom-corners">
                <!-- Scanning Line Animation (across the full camera view) -->
                <div
                  class="absolute top-0 z-20 left-0 w-full h-full border-t-4 border-dashed border-blue-500 animate-scan-line"
                  v-if="isScanning"></div>

                <!-- QR Code Stream -->
                <QrcodeStream @detect="onDetect" class="border p-2 relative z-10" />
              </div>

              <!-- Result Section (Success/Failure Message) -->
              <div v-if="scannedResult && scholar" class="mt-4 flex justify-center items-center">
                <div class="w-full max-w-lg text-center p-6">
                  <div class="text-center text-green-500 font-medium">
                    <InputError v-if="errors?.message" :message="errors.message" class="text-red-500" />
                    
                    <div class="flex justify-center items-center mb-6 overflow-hidden">
                      <img :src="`/storage/user/profile/${scholar.picture}`" alt="Profile Picture"
                                    class="w-40 h-40 object-cover rounded-full border-2 border-primary">
                    </div>

                    <div class="mb-4">
                      <span class="font-italic font-sora text-2xl font-bold uppercase">{{ scholar.last_name }}, {{ scholar.first_name }}</span>
                    </div>

                    <div class="flex items-center justify-center gap-2 mb-4">
                      <font-awesome-icon :icon="['fas', 'school']" class="p-2 w-5 h-5 bg-primary rounded-md text-white" />
                      <span class="text-gray-900 text-lg font-semibold">{{ scholar.campus }}, Campus</span>
                    </div>

                    <div class="flex items-center justify-center gap-2 mb-6 border-b-2 pb-4">
                      <span class="px-2 py-1 bg-primary rounded-md text-xl text-white font-albert font-bold">@</span>
                      <span class="pl-2 text-gray-900 text-lg font-bold">{{ scholar.email }}</span>
                    </div>
                  </div>

                  <div v-if="errorMessage" class="text-red-500 font-medium mb-4">
                    <!-- Display error message if exists -->
                  </div>

                  <div class="flex justify-center">
                    <button @click="restartScan" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-lg">
                      Scan Again
                    </button>
                  </div>
                </div>
              </div>
            </div>
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
                      <span class="text-sm text-gray-600 dark:text-gray-400">
                          You can add here the reason of the student for not claiming 
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

            
            <div class="p-4 flex flex-col space-y-4">
              <div class="relative space-y-3">
                  <h3 class="font-semibold text-gray-900 dark:text-white">
                      Reason of Not Claim</h3>
                  <textarea id="subject" rows="4"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-dsecondary dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      placeholder="Write a message"></textarea>
                  <!-- <InputError v-if="errors.reason" :message="errors.reason" class="mt-1" /> -->
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
  disbursements: Array,
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

// Filtered payouts based on search query
const filteredDisbursements = computed(() => {
  return props.disbursements.filter(disbursements =>
    disbursements.scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.urscholar_id?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.course?.name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    disbursements.scholar.campus?.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
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

const toggleReason = () => {
  Reasoning.value = !Reasoning.value;
  
};

const closeModal = () => {
  Reasoning.value = false;
}

// Handle QR code detection
const onDetect = async (detectedCodes) => {
  if (detectedCodes.length > 0) {
    scannedResult.value = detectedCodes[0].rawValue;
    isScanning.value = false;

    // Send scanned QR code data to Laravel
    router.post("/cashier/verify-qr", { scanned_data: scannedResult.value }, {
      onSuccess: (page) => {
        // const flashMessage = page.props.flash.message;
        // successMessage.value = flashMessage;
        // errorMessage.value = null;

        // Show toast notification
        // showToast('Success', flashMessage);

        // If successful, refresh the payouts list
        // if (page.props.flash.type === 'success') {
        //   router.reload();
        // }
      },
      onError: (errors) => {
        errorMessage.value = errors.message || 'An error occurred';
        successMessage.value = null;

        // Show toast notification for error
        showToast('Error', errors.message || 'An error occurred');
      }
    });
  }
};

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
// const showToast = (title, message) => {
//   toastTitle.value = title;
//   toastMessage.value = message;
//   toastVisible.value = true;

//   // Hide toast after 3 seconds
//   setTimeout(() => {
//     toastVisible.value = false;
//   }, 3000);
// };

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
    toastMessage.value = flashMessage.first_name;  // This sets the toast message to "Carl Vincent"
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