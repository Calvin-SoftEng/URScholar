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
                <tr v-for="payout in filteredPayouts" :key="payout.id" class="text-sm">
                  <td>{{ payout.scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ payout.scholar.last_name }}, {{ payout.scholar.first_name }} {{ payout.scholar.middle_name }}
                        </div>
                        <div class="text-sm opacity-50">
                          {{ payout.scholar.year_level }}{{ getYearSuffix(payout.scholar.year_level) }} year, {{
                          payout.scholar.course }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ payout.scholar.grant }}
                  </td>
                  <td>
                    {{ payout.scholar.campus }}
                  </td>
                  <td>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': payout.status === 'Claimed',
                      'bg-yellow-100 text-yellow-800 border border-yellow-400': payout.status === 'Pending'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                      {{ payout.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
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

const props = defineProps({
  scholarship: Object,
  batch: Object,
  payouts: Array,
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
const scannedResult = ref(null);
const errorMessage = ref(null);
const successMessage = ref(null);
const isScanning = ref(true);
const toastVisible = ref(false);
const toastTitle = ref('');
const toastMessage = ref('');

// Filtered payouts based on search query
const filteredPayouts = computed(() => {
  return props.payouts.filter(payout =>
    payout.scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    payout.scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    payout.scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    payout.scholar.urscholar_id?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    payout.scholar.course?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    payout.scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase())
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

// Handle QR code detection
const onDetect = async (detectedCodes) => {
  if (detectedCodes.length > 0) {
    scannedResult.value = detectedCodes[0].rawValue;
    isScanning.value = false;

    // Send scanned QR code data to Laravel
    router.post("/cashier/verify-qr", { scanned_data: scannedResult.value }, {
      onSuccess: (page) => {
        const flashMessage = page.props.flash.message;
        successMessage.value = flashMessage;
        errorMessage.value = null;

        // Show toast notification
        showToast('Success', flashMessage);

        // If successful, refresh the payouts list
        if (page.props.flash.type === 'success') {
          router.reload();
        }
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

// Check for flash messages on component mount
onMounted(() => {
  const { flash } = usePage().props;
  if (flash && flash.message) {
    showToast(flash.type === 'success' ? 'Success' : 'Error', flash.message);
  }
});


const reportTypeOptions = ['Enrollees Summary', 'Enrolled List', 'Graduate Summary', 'Payroll'];
const batchRef = ref(null);
const campusRef = ref(null);

const selectedReportType = ref('');
const selectedReportBatches = ref([]);
const selectedReportCampuses = ref([]);

// Improved click outside handler
const handleClickOutside = (event) => {
    // Make sure all refs are defined before using them
    if (openDropdown.value) {
        const campusEl = campusRef.value;
        const batchEl = batchRef.value;
        const reportEl = reportRef.value;

        // Check if click is outside all active dropdowns
        const clickedOutside = (
            (campusEl && !campusEl.contains(event.target) || !campusEl) &&
            (batchEl && !batchEl.contains(event.target) || !batchEl) &&
            (reportEl && !reportEl.contains(event.target) || !reportEl)
        );

        if (clickedOutside) {
            openDropdown.value = null;
        }
    }
};

// const handleGenerateReports = () => {
//     const filters = {
//         type: selectedReportType.value,
//         campuses: selectedReportCampuses.value,
//         batches: selectedReportBatches.value,
//     };

//     if (!filters.type || filters.campuses.length === 0 || filters.batches.length === 0) {
//         console.warn('Please select a report type, campuses, and batches.');
//         return;
//     }

//     switch (filters.type) {
//         case 'Enrollees Summary':
//             generateEnrolleesSummary(filters);
//             break;
//         case 'Enrolled List':
//             generateEnrolledList(filters);
//             break;
//         case 'Graduate Summary':
//             generateGraduateList(filters);
//             break;
//         case 'Payroll':
//             generatePayroll(filters);
//             break;
//         // case 'Scholars List':
//         //     generateScholarsList(filters);
//         //     break;
//         default:
//             console.warn('No valid report type selected.');
//     }
// };

const openReport = () => {
    // Open the report generation modal
    generatePayroll();
};

const generatePayroll = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/payroll-report`;
        const queryParams = new URLSearchParams({
            batch_ids: filters.batches.join(','),
            campus_ids: filters.campuses.join(','),
            school_year_id: props.schoolyear.id,
            semester: props.selectedSem
        });

        window.open(`${url}?${queryParams.toString()}`, '_blank');
    } catch (err) {
        console.error('Failed to generate report:', err);
    }
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