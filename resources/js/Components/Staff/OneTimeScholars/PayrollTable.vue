<template>
  <div class="w-full bg-white rounded-xl">
    <div>
      <div>
        <div class="w-full bg-white h-full p-4">
          <!-- Disbursements table -->
          <div class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table w-full rounded-lg">
              <!-- head -->
              <thead class="justify-center items-center bg-gray-100">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar</th>
                  <th>Grant</th>
                  <th>Campus</th>
                  <th>Status</th>
                  <th>Date Claimed</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="disbursement in filteredDisbursements" :key="disbursement.id" class="text-sm">
                  <td>
                    {{ getScholarDetails(disbursement.scholar_id).urscholar_id }}
                  </td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ getScholarDetails(disbursement.scholar_id).last_name }}, 
                          {{ getScholarDetails(disbursement.scholar_id).first_name }} 
                          {{ getScholarDetails(disbursement.scholar_id).middle_name }}
                        </div>
                        <div class="text-sm opacity-50">
                          {{ getScholarDetails(disbursement.scholar_id).year_level }}{{ getYearSuffix(getScholarDetails(disbursement.scholar_id).year_level) }} year,
                          {{ getScholarDetails(disbursement.scholar_id).course }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ getScholarDetails(disbursement.scholar_id).grant }}
                  </td>
                  <td>
                    {{ getScholarDetails(disbursement.scholar_id).campus.name }}
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
                    {{ disbursement.claimed_at ? formatDate(disbursement.claimed_at) : 'Not claimed yet' }}
                  </td>
                </tr>
                
                <!-- Empty state when no results -->
                <tr v-if="filteredDisbursements.length === 0">
                  <td colspan="7" class="text-center py-4">
                    <div class="flex flex-col items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <p class="mt-2 text-gray-500">No disbursements found</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </div>

    <!-- Toast notifications -->
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
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
import { QrcodeStream } from "vue-qrcode-reader";

const props = defineProps({
  scholarship: Object,
  batch: Object,
  disbursements: Array, // Changed from payout to disbursements
  scholars: Array,
  payout: Object,
});

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

// Get scholar details by ID
const getScholarDetails = (scholarId) => {
  return props.scholars.find(s => s.id === scholarId) || { 
    urscholar_id: 'Unknown',
    last_name: 'Unknown',
    first_name: '',
    middle_name: '',
    year_level: 0,
    course: 'Unknown',
    campus: 'Unknown',
    grant: 'Unknown'
  };
};

// Format date for display
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Filtered disbursements based on search query
const filteredDisbursements = computed(() => {
  if (!props.disbursements) return [];
  
  return props.disbursements.filter(disbursement => {
    const scholar = getScholarDetails(disbursement.scholar_id);
    return (
      scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.urscholar_id?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.course?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      disbursement.status?.toLowerCase().includes(searchQuery.value.toLowerCase())
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

// Handle QR code detection
const onDetect = async (detectedCodes) => {
  if (detectedCodes.length > 0) {
    scannedResult.value = detectedCodes[0].rawValue;
    isScanning.value = false;

    // Send scanned QR code data to Laravel
    router.post("/cashier/verify-qr", { 
      scanned_data: scannedResult.value,
      batch_id: props.batch.id,
      payout_id: props.payout?.id
    }, {
      onSuccess: (page) => {
        const flashMessage = page.props.flash.message;
        successMessage.value = flashMessage;
        errorMessage.value = null;

        // Show toast notification
        showToast('Success', flashMessage);

        // If successful, refresh the disbursements list
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

// Process disbursement manually
const processDisbursement = (disbursementId) => {
  router.post(`/cashier/process-disbursement/${disbursementId}`, {}, {
    onSuccess: (page) => {
      const flashMessage = page.props.flash?.message || 'Disbursement processed successfully';
      showToast('Success', flashMessage);
      router.reload();
    },
    onError: (errors) => {
      showToast('Error', errors.message || 'Failed to process disbursement');
    }
  });
};

// View disbursement details
const viewDetails = (disbursementId) => {
  router.get(`/disbursements/${disbursementId}/details`);
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
  if (!year) return '';
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

/* Transitions */
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>