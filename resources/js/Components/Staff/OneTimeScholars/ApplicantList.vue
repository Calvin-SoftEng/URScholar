<template>
  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">
        <!-- Publish Button -->
        <Link :href="(route('scholarship.onetime_scholars'))">
        <button @click="generateReport" class="flex items-center gap-2 border border-blue-600 font-poppins text-primary px-4 py-2 rounded-lg transition duration-200
                  hover:bg-blue-300 disabled:opacity-50 disabled:cursor-not-allowed">
          <font-awesome-icon :icon="['fas', 'file-lines']" class="text-base" />
          <span class="font-normal">Publish <span class="font-semibold">Applicant List</span></span>
        </button>
        </Link>

        <!-- Forward to Sponsor -->
        <div>
          <button @click="toggleForwardSponsor" class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg transition duration-200
                  hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
            <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
            <span class="font-normal">Forward to <span class="font-semibold">Sponsor</span></span>
          </button>
        </div>
      </div>

      <!-- Applicant Status Filter -->
      <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-gray-700">Filter by Status:</span>
        <div class="flex space-x-3">
          <label class="inline-flex items-center">
            <input type="radio" v-model="applicantStatusFilter" value="approved"
              class="form-radio h-4 w-4 text-blue-600">
            <span class="ml-2 text-sm text-gray-700">Approved</span>
          </label>
          <label class="inline-flex items-center">
            <input type="radio" v-model="applicantStatusFilter" value="pending"
              class="form-radio h-4 w-4 text-blue-600">
            <span class="ml-2 text-sm text-gray-700">Pending</span>
          </label>
        </div>
      </div>
    </div>

    <div>
      <div>
        <div class="w-full bg-white h-full p-4">
          <!-- Loading indicator -->
          <div v-if="loading" class="flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
          </div>

          <!-- table -->
          <div v-else class="overflow-x-auto font-poppins border rounded-lg">
            <div v-if="filteredByStatus.length === 0"
              class="bg-white w-full dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
              <font-awesome-icon :icon="['fas', 'user-graduate']"
                class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
              <p class="text-lg text-gray-700 dark:text-gray-300">
                No applicants found for the selected status
              </p>
            </div>
            <div v-else>
              <table class="table rounded-lg">
                <!-- Table Head - Conditional based on filter -->
                <thead class="justify-center items-center bg-gray-100">
                  <tr class="text-xs uppercase">
                    <th>URScholar ID</th>
                    <th>Applicant</th>
                    <th>Campus</th>
                    <th>Date Applied</th>
                    <!-- Show different columns based on filter -->
                    <template v-if="applicantStatusFilter === 'pending'">
                      <th>Applicant Status</th>
                    </template>
                    <template v-else>
                      <th>Requirements</th>
                      <th>GWA</th>
                      <th>Status</th>
                    </template>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <!-- Scholars within recipient limit -->
                  <template v-for="(scholar, index) in scholarsWithinLimitFiltered" :key="scholar.id">
                    <tr class="text-sm">
                      <td>{{ scholar.urscholar_id }}</td>
                      <td>
                        <div class="flex items-center gap-3">
                          <div class="avatar">
                            <div class="mask rounded-full h-10 w-10">
                              <img v-if="scholar.picture" :src="`/storage/user/profile/${scholar.picture}`"
                                alt="Profile Picture">
                            </div>
                          </div>
                          <div>
                            <div class="font-normal">
                              {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                              <span v-if="scholar.status === 'Verified'"
                                class="material-symbols-rounded text-sm text-blue-600">verified</span>
                            </div>
                            <div class="text-sm opacity-50">
                              {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>{{ scholar.campus }}</td>
                      <td>{{ new Date(scholar.date_applied).toLocaleString('en-US', {
                        month: 'long', day: 'numeric',
                        year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true
                      }) }}</td>

                      <!-- Show different columns based on filter -->
                      <template v-if="applicantStatusFilter === 'pending'">
                        <td>
                          <span :class="{
                            'bg-yellow-100 text-yellow-800 border border-yellow-400': scholar.applicant_status === 'Pending',
                            'bg-green-100 text-green-800 border border-green-400': scholar.applicant_status === 'Approved',
                            'bg-red-100 text-red-800 border border-red-400': scholar.applicant_status === 'rejected'
                          }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                            {{ scholar.applicant_status }}
                          </span>
                        </td>
                      </template>
                      <template v-else>
                        <td>
                          <span class="text-sm text-gray-700 mt-1 flex items-center justify-center">
                            {{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}
                          </span>
                          <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-300 h-full rounded-full" :style="{ width: scholar.progress + '%' }">
                            </div>
                          </div>
                        </td>
                        <td><span class="font-bold text-gray-800">{{ scholar.grade }}</span></td>
                        <td>
                          <span :class="{
                            'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                            'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                            'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete',
                            'bg-blue-100 text-blue-800 border border-blue-400': scholar.status === 'Submitted',
                            'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Returned'
                          }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                            {{ scholar.status }}
                          </span>
                        </td>
                      </template>

                      <td>
                        <Link :href="`/scholarships/scholar=${scholar.id}/one-time`">
                        <button
                          class="p-2 border bg-white text-primary rounded-lg hover:bg-blue-200 transition-colors shadow-sm"
                          aria-label="View Details">
                          <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                        </button>
                        </Link>
                      </td>
                    </tr>
                  </template>

                  <!-- Cut-Off Line - Always show if there are scholars outside limit -->
                  <tr v-if="hasScholarsOutsideLimitFiltered">
                    <td colspan="8" class="text-center font-semibold text-red-600 py-4 bg-red-50">
                      Cut-Off Line: Below applicants are NOT within the required {{ recipientLimit }} recipients.
                    </td>
                  </tr>

                  <!-- Scholars below recipient limit -->
                  <template v-for="scholar in scholarsOutsideLimitFiltered" :key="scholar.id">
                    <tr class="text-sm">
                      <td>{{ scholar.urscholar_id }}</td>
                      <td>
                        <div class="flex items-center gap-3">
                          <div class="avatar">
                            <div class="mask rounded-full h-10 w-10">
                              <img v-if="scholar.picture" :src="`/storage/user/profile/${scholar.picture}`"
                                alt="Profile Picture">
                            </div>
                          </div>
                          <div>
                            <div class="font-normal">
                              {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                              <span v-if="scholar.status === 'Verified'"
                                class="material-symbols-rounded text-sm text-blue-600">verified</span>
                            </div>
                            <div class="text-sm opacity-50">
                              {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>{{ scholar.campus }}</td>
                      <td>{{ new Date(scholar.date_applied).toLocaleString('en-US', {
                        month: 'long', day: 'numeric',
                        year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true
                      }) }}</td>

                      <!-- Show different columns based on filter -->
                      <template v-if="applicantStatusFilter === 'pending'">
                        <td>
                          <span :class="{
                            'bg-yellow-100 text-yellow-800 border border-yellow-400': scholar.applicant_status === 'pending',
                            'bg-green-100 text-green-800 border border-green-400': scholar.applicant_status === 'approved',
                            'bg-red-100 text-red-800 border border-red-400': scholar.applicant_status === 'rejected'
                          }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                            {{ scholar.applicant_status }}
                          </span>
                        </td>
                      </template>
                      <template v-else>
                        <td>
                          <span class="text-sm text-gray-700 mt-1 flex items-center justify-center">
                            {{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}
                          </span>
                          <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-300 h-full rounded-full" :style="{ width: scholar.progress + '%' }">
                            </div>
                          </div>
                        </td>
                        <td><span class="font-bold text-gray-800">{{ scholar.grade }}</span></td>
                        <td>
                          <span :class="{
                            'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                            'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                            'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete',
                            'bg-blue-100 text-blue-800 border border-blue-400': scholar.status === 'Submitted',
                            'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Returned'
                          }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                            {{ scholar.status }}
                          </span>
                        </td>
                      </template>

                      <td>
                        <Link :href="`/scholarships/scholar=${scholar.id}/one-time`">
                        <button
                          class="p-2 border bg-white text-primary rounded-lg hover:bg-blue-200 transition-colors shadow-sm"
                          aria-label="View Details">
                          <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                        </button>
                        </Link>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
          <!-- Pagination controls -->
          <div v-if="totalScholars > itemsPerPage" class="mt-5 flex justify-between items-center">
            <span class="text-sm text-gray-700 dark:text-gray-400">
              Showing
              <span class="font-semibold text-gray-900 dark:text-white">{{ startIndex }}</span>
              to
              <span class="font-semibold text-gray-900 dark:text-white">{{ endIndex }}</span>
              of
              <span class="font-semibold text-gray-900 dark:text-white">{{ totalScholars }}</span>
              Scholars
            </span>
            <div class="inline-flex">
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
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Simplified forwarding batch list modal -->
  <div v-if="ForwardtoSponsor"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
    <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
      <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
        <div class="flex items-center gap-3">
          <!-- Icon -->
          <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

          <!-- Title and Description -->
          <div class="flex flex-col">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
              Forward to Sponsor
            </h2>
            <span class="text-sm text-gray-600 dark:text-gray-400">
              Send the applicant list to the sponsor account
            </span>
          </div>
        </div>
        <button type="button" @click="closeModal"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="default-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
        </button>
      </div>

      <!-- Form -->
      <form>
        <div class="py-4 px-8 flex flex-col gap-3">
          <!-- Loading Indicator -->
          <!-- <div class="flex justify-center items-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                </div> -->

          <!-- Batch List -->
          <div class="flex flex-col divide-y divide-gray-300">
            <p>
              rgsrgrsg
            </p>
            <div class="py-3 px-4 flex justify-between items-center">
              <div>
                <p class="text-base font-medium text-gray-900 dark:text-white">Batch fefef</p>
                <p class="text-sm text-gray-500">Completed:fefefef</p>
              </div>
            </div>
          </div>

          <!-- Forward Button -->
          <div class="mt-4">
            <button v-tooltip.left="'Complete all batches'" disabled
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
              Forward
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Toast notifications -->
  <ToastProvider>
    <ToastRoot v-if="toast.visible"
      class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
      <ToastTitle class="font-semibold dark:text-dtext">{{ toast.title }}</ToastTitle>
      <ToastDescription class="text-gray-100 dark:text-dtext">{{ toast.message }}</ToastDescription>
    </ToastRoot>
    <ToastViewport class="fixed bottom-4 right-4" />
  </ToastProvider>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ToastProvider, ToastRoot, ToastTitle, ToastDescription, ToastViewport } from 'radix-vue';

// Props definition
const props = defineProps({
  scholars: Array,
  scholarship: Object,
  totalSlots: Number,
  campusRecipients: Array,
  currentUser: Object,
  itemsPerPage: {
    type: Number,
    default: 10
  }
});

// State variables
const loading = ref(false);
const ForwardtoSponsor = ref(false);
const toast = ref({
  visible: false,
  title: '',
  message: ''
});

// Pagination state
const currentPage = ref(1);

// Filter state - default to 'approved' instead of 'all'
const applicantStatusFilter = ref('approved');

// Computed values for recipient limits and filtering
const recipientLimit = computed(() => {
  const campusRecipient = props.campusRecipients.find(
    (campus) => campus.campus_id === props.currentUser.campus_id
  );
  return campusRecipient ? campusRecipient.slots : 0;
});

// Add a new campus filter state variable
const campusFilter = ref(null);

// Update the filtered scholars computed property to include campus filtering
const filteredByStatus = computed(() => {
  // Convert the filter value to match database case (capitalize first letter)
  const filterValue = applicantStatusFilter.value.charAt(0).toUpperCase() +
    applicantStatusFilter.value.slice(1).toLowerCase();

  // Start with status filtering
  let filtered = props.scholars.filter(scholar => scholar.applicant_status === filterValue);

  // Apply campus filter if one is selected
  if (campusFilter.value) {
    filtered = filtered.filter(scholar => {
      // For campus filtering, we need to match the campus_id from the backend
      // Since we have campus name in the UI, we might need a mapping or direct comparison
      return props.currentUser.campus_id === campusFilter.value;
    });
  }

  return filtered;
});

// Add a new method to toggle campus filter
function toggleCampusFilter() {
  // Toggle between Binangonan campus ID and null (all campuses)
  campusFilter.value = campusFilter.value ? null : props.currentUser.campus_id;
}

// Total number of scholars after filtering
const totalScholars = computed(() => filteredByStatus.value.length);

// Total pages for pagination
const totalPages = computed(() => Math.ceil(totalScholars.value / props.itemsPerPage));

// Start and end indices for displayed items
const startIndex = computed(() => (currentPage.value - 1) * props.itemsPerPage + 1);
const endIndex = computed(() => Math.min(currentPage.value * props.itemsPerPage, totalScholars.value));

// Scholars within recipient limit (with pagination and status filtering)
const scholarsWithinLimitFiltered = computed(() => {
  const start = (currentPage.value - 1) * props.itemsPerPage;
  const end = start + props.itemsPerPage;

  // First get all scholars within limit after filtering by status
  const scholarsWithinLimit = filteredByStatus.value
    .slice(0, recipientLimit.value);

  // Then apply pagination
  return scholarsWithinLimit.slice(start, end);
});

// Scholars outside recipient limit (with pagination and status filtering)
const scholarsOutsideLimitFiltered = computed(() => {
  const start = (currentPage.value - 1) * props.itemsPerPage;
  const end = start + props.itemsPerPage;

  // First get all scholars outside limit after filtering by status
  const scholarsOutsideLimit = filteredByStatus.value
    .slice(recipientLimit.value);

  // Calculate how many scholars within limit are shown on this page
  const shownWithinLimit = scholarsWithinLimitFiltered.value.length;

  // Determine how many more scholars we can show on this page
  const remainingSlots = props.itemsPerPage - shownWithinLimit;

  if (remainingSlots <= 0) {
    return [];
  }

  // Return the appropriate slice of outside-limit scholars
  const outsideLimitStart = Math.max(0, start - recipientLimit.value);
  return scholarsOutsideLimit.slice(outsideLimitStart, outsideLimitStart + remainingSlots);
});

// Check if there are any scholars outside the limit after filtering
const hasScholarsOutsideLimitFiltered = computed(() => {
  return filteredByStatus.value.length > recipientLimit.value;
});

// Methods
function toggleForwardSponsor() {
  ForwardtoSponsor.value = !ForwardtoSponsor.value;
}

function closeModal() {
  ForwardtoSponsor.value = false;
}

function generateReport() {
  // Implementation of report generation
  showToast('Report Generated', 'Applicant list has been published successfully.');
}

function showToast(title, message) {
  toast.value = {
    visible: true,
    title,
    message
  };

  setTimeout(() => {
    toast.value.visible = false;
  }, 3000);
}

function getYearSuffix(year) {
  if (year === 1) return 'st';
  if (year === 2) return 'nd';
  if (year === 3) return 'rd';
  return 'th';
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

// Lifecycle hooks
onMounted(() => {
  // Any initialization code
});
</script>

<style>
/* override the prime vue components */
.p-fileupload-choose-button {
  background-color: #003366 !important;
  color: white !important;
  border-radius: 4px;
}

/* Animation classes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
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
</style>