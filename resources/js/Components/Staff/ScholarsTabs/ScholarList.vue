<template>
  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">

        <!-- <div class="relative inline-block text-left w-52 text-primary">
        <select
          v-model="selectedReportType"
          @change="generateReport"
          placeholder="📄 Generate Report"
          class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-primary font-medium text-sm px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 appearance-none pr-10 focus:outline-none focus:ring-2 focus:ring-blue-300"
        >
          <option disabled value="">📄 Generate Report</option>
          <option value="grantees">🎓 Grantees</option>
          <option value="transfers">🔄 Transfers</option>
          <option value="graduates">🎉 Graduates</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div> -->

      <!-- <button @click="generateReportModal"
          class="flex items-center gap-2 dark:text-dtext bg-white dark:bg-white 
          border border-gray-300 dark:border-gray-500 hover:bg-gray-200 px-4 py-2 rounded-lg transition duration-200"
          type="button">
          <font-awesome-icon :icon="['fas', 'file-lines']" class="mr-2 text-sm" />Generate
          Report
      </button> -->

      <button
        class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300"
        @click="generateReportModal">
        <font-awesome-icon :icon="['fas', 'file-lines']" class="text-base" />
        Generate Report
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
          <!-- Loading indicator -->
          <div v-if="loading" class="flex justify-center items-center py-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
          </div>

          <!-- table -->
          <div v-else class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table rounded-lg">
              <!-- head -->
              <thead class="justify-center items-center bg-gray-50">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Grantee</th>
                  <th>Campus</th>
                  <th>Grant</th>
                  <th v-if="requirements.length > 0">Requirements</th>
                  <th v-if="requirements.length > 0">Status</th>
                  <th>Student Status</th>
                  <th v-if="requirements.length == 0">Validation</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- No results message -->
                <tr v-if="paginatedScholars.length === 0">
                  <td colspan="7" class="text-center py-6 text-gray-500">
                    No scholars found matching your search criteria
                  </td>
                </tr>
                <!-- Scholar rows - HIGHLIGHTING CHANGES START HERE -->
                <tr v-for="scholar in paginatedScholars" :key="scholar.id" class="text-sm"
                  :class="{ 'bg-red-50': scholar.scholar_status === 'Unverified' && scholar.student_status === 'Unenrolled' }">
                  <td>{{ scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img v-if="scholar.user.picture" :src="`/storage/user/profile/${scholar.user.picture}`"
                            alt="Profile Picture">
                          <img v-else src="../../../../assets/images/no_userpic.png" alt="Profile Picture">
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                          <span v-if="scholar.status === 'Verified'"
                            class="material-symbols-rounded text-sm text-blue-600">verified</span>
                        </div>
                        <div class="text-sm text-gray-400">
                          <span class="text-gray-500">{{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }}
                            year</span>, {{ scholar.course }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ scholar.campus }}
                  </td>
                  <td>
                    {{ scholar.grant }}
                  </td>
                  <td v-if="requirements.length > 0">
                    <span class="text-sm text-gray-700 mt-1 flex items-center justify-center">
                      {{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}
                    </span>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-yellow-300 h-full rounded-full" :style="{ width: scholar.progress + '%' }">
                      </div>
                    </div>
                  </td>
                  <td v-if="requirements.length > 0">
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
                  <td v-else>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.scholar_status === 'Verified',
                      'bg-red-200 text-red-800 border border-red-500 font-bold': scholar.scholar_status === 'Unverified'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                      <span v-if="scholar.scholar_status === 'Unverified'" class="inline-flex items-center">
                        {{ scholar.scholar_status }}
                      </span>
                      <span v-else>{{ scholar.scholar_status }}</span>
                    </span>
                  </td>
                  <td>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.student_status === 'Enrolled',
                      'bg-red-100 text-red-800 border border-red-400 font-bold': scholar.student_status === 'Unenrolled',
                      'bg-yellow-100 text-yellow-800 border border-yellow-400 font-bold': scholar.student_status === 'Dropped' || scholar.student_status === 'Graduated',
                      'bg-blue-100 text-blue-800 border border-blue-400 font-bold': scholar.student_status === 'Transferred',
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                      <span v-if="scholar.student_status === 'Unenrolled'" class="inline-flex items-center">
                        {{ scholar.student_status }}
                      </span>
                      <span v-else>{{ scholar.student_status }}</span>
                    </span>
                  </td>
                  <th v-if="requirements.length > 0 && scholar.student_status == 'Enrolled'">
                    <Link :href="scholar.userVerified ? `/scholarships/scholar=${scholar.id}` : '#'"
                      @click.prevent="!scholar.userVerified">
                    <button class="p-2 border bg-white text-primary rounded-lg transition-colors shadow-sm" :class="{
                      'hover:bg-blue-200 cursor-pointer': scholar.userVerified,
                      'opacity-50 cursor-not-allowed': !scholar.userVerified
                    }" :disabled="!scholar.userVerified"
                      v-tooltip.left="!scholar.userVerified ? 'Scholar has no data' : null" aria-label="View Details">
                      <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                    </button>
                    </Link>
                  </th>
                  <th v-else v-if="!batches.validated">
                    <button @click="setStatus(scholar)"
                      class="p-2 border bg-white text-primary rounded-lg transition-colors shadow-sm hover:bg-gray-200"
                      :class="{ 'ring-2 ring-red-500 animate-pulse': scholar.scholar_status === 'Unverified' || scholar.student_status === 'Unenrolled' }"
                      aria-label="View Details">
                      <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                    </button>
                  </th>
                </tr>
                <!-- HIGHLIGHTING CHANGES END HERE -->
              </tbody>
            </table>
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

    <!-- Toast notifications -->
    <ToastProvider>
      <ToastRoot v-if="toast.visible"
        class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
        <ToastTitle class="font-semibold dark:text-dtext">{{ toast.title }}</ToastTitle>
        <ToastDescription class="text-gray-100 dark:text-dtext">{{ toast.message }}</ToastDescription>
      </ToastRoot>
      <ToastViewport class="fixed bottom-4 right-4" />
    </ToastProvider>
  </div>

  <div v-if="StudentStatus && currentScholar"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
    <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
        <div class="flex items-center gap-3">
          <!-- Icon -->
          <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

          <!-- Title and Description -->
          <div class="flex flex-col">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
              Student Status
            </h2>
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

      <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-4 max-w-lg mx-auto">
        <!-- Full Name -->
        <div class="mb-2">
          <span class="text-sm text-gray-500">Full Name:</span>
          <p class="text-lg font-medium text-gray-900">{{ currentScholar.first_name }} {{ currentScholar.middle_name }}
            {{ currentScholar.last_name }}</p>
        </div>

        <!-- Course -->
        <div class="mb-2">
          <span class="text-sm text-gray-500">Course:</span>
          <p class="text-lg font-medium text-gray-900">{{ currentScholar.course }}</p>
        </div>

        <!-- Year Level -->
        <div class="mb-2">
          <span class="text-sm text-gray-500">Year Level:</span>
          <p class="text-lg font-medium text-gray-900">{{ currentScholar.year_level }}</p>
        </div>

        <!-- Dropdown: Dropped or Graduated -->
        <div class="w-full flex flex-col space-y-2 p-2">
          <h3 class="font-semibold text-gray-900 dark:text-white">Does the Student have Dropped or Graduated?</h3>
          <select v-model="statusValue" id="scholarshipType"
            class="bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
            <option value="" disabled selected>Select Status</option>
            <option value="Transferred">Transferred</option>
            <option value="Dropped">Dropped</option>
            <option value="Graduated">Graduated</option>
          </select>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
          <button type="submit"
            class="w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90">
            Set
          </button>
        </div>
      </form>
    </div>
  </div>

  <div v-if="GenerateReport"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Generate a Report Document
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Select what is needed to be generated
                            </span>
                        </div>
                    </div>
                    <button type="button" @click="closeReportGeneration"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="handleGenerateReports">
                    <div class="py-4 px-8 flex flex-col gap-3">
                        <!-- Batch Disbursement Status -->
                        <div>
                            <!-- Filters -->
                            <div class=" mb-4">
                                <!-- Report Type Filter -->
                                <div class="relative" ref="reportRef">
                                    <label class="block text-xs font-medium mb-1">Report Type</label>
                                    <button type="button"
                                        class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
                                        @click="toggleDropdown('reportType')">
                                        {{ selectedReportType ? selectedReportType : 'Select Report type' }}
                                    </button>

                                    <div v-if="openDropdown === 'reportType'"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
                                        <label v-for="type in reportTypeOptions" :key="type"
                                            class="block px-4 py-2 hover:bg-gray-100 cursor-pointer whitespace-nowrap text-sm">
                                            <input type="radio"
                                                class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                                :value="type" v-model="selectedReportType" name="reportType" />
                                            {{ type }}
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="button" @click="handleGenerateReports"
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!selectedReportType || selectedReportCampuses.length === 0 || selectedReportBatches.length === 0">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeMount } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
  scholarship: Object,
  schoolyear: Object,
  selectedSem: Object,
  batches: Array,
  scholars: Array,
  requirements: Array,
});

// Data loading state
const loading = ref(false);

// Pagination state
const currentPage = ref(1);
const itemsPerPage = 10;
const searchQuery = ref('');

// Toast notification state
const toast = ref({
  visible: false,
  title: '',
  message: '',
  type: 'success'
});

// Computed properties for scholars filtering and pagination
const filteredScholars = computed(() => {
  const allScholars = props.scholars || [];

  if (!searchQuery.value) {
    return [...allScholars].sort((a, b) =>
      a.status === 'Verified' ? -1 : b.status === 'Verified' ? 1 : 0
    );
  }

  const query = searchQuery.value.toLowerCase();
  return allScholars.filter(scholar =>
    scholar.first_name?.toLowerCase().includes(query) ||
    scholar.last_name?.toLowerCase().includes(query) ||
    scholar.middle_name?.toLowerCase().includes(query) ||
    scholar.email?.toLowerCase().includes(query) ||
    scholar.course?.toLowerCase().includes(query) ||
    scholar.campus?.toLowerCase().includes(query) ||
    scholar.grant?.toLowerCase().includes(query) ||
    scholar.urscholar_id?.toLowerCase().includes(query)
  ).sort((a, b) =>
    a.status === 'Verified' ? -1 : b.status === 'Verified' ? 1 : 0
  );
});

const totalScholars = computed(() => filteredScholars.value.length);
const totalPages = computed(() => Math.ceil(totalScholars.value / itemsPerPage));

const paginatedScholars = computed(() => {
  const startIdx = (currentPage.value - 1) * itemsPerPage;
  const endIdx = startIdx + itemsPerPage;
  return filteredScholars.value.slice(startIdx, endIdx);
});

const startIndex = computed(() =>
  totalScholars.value === 0 ? 0 : (currentPage.value - 1) * itemsPerPage + 1
);

const endIndex = computed(() =>
  Math.min(currentPage.value * itemsPerPage, totalScholars.value)
);

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

// Reset pagination when search changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

const statusValue = ref('');


const StudentStatus = ref(false);

// Handles setting the selected scholar and showing the modal
const setStatus = (scholar) => {
  // Set the current scholar object
  currentScholar.value = scholar;

  // Set the select input to match the current scholar's status
  statusValue.value = scholar.student_status;

  // Toggle modal visibility
  StudentStatus.value = !StudentStatus.value;
};


// Also update the submitForm function to use the currentScholar data
const submitForm = () => {
  if (!statusValue.value) {
    showToast('Error', 'Please select a status', 'error');
    return;
  }

  // Here you would typically send the data to your backend
  // For example:
  loading.value = true;
  router.post(`/scholars/${currentScholar.value.id}/update-status`, {
    status: statusValue.value,
  }, {
    onSuccess: () => {
      showToast('Success', `Scholar status updated to ${statusValue.value}`, 'success');
      closeModal();
      fetchScholars(); // Refresh the data
    },
    onError: () => {
      showToast('Error', 'Failed to update scholar status', 'error');
    },
    onFinish: () => {
      loading.value = false;
    }
  });
};

// Update the closeModal function to also reset the form
const closeModal = () => {
  StudentStatus.value = false;
  statusValue.value = '';
  currentScholar.value = null;
};

// Add this near your other ref declarations
const currentScholar = ref(null);


// Fetch fresh data from the server
const fetchScholars = async () => {
  loading.value = true;
  try {
    // Using Inertia's router.reload() to refresh the current page data
    // This will maintain the current URL and just refresh the data
    await router.reload({
      only: ['scholars', 'requirements'],
      onSuccess: () => {
        showToast('Data Updated', 'Scholar data has been refreshed');
      },
      onError: () => {
        showToast('Error', 'Failed to refresh scholar data', 'error');
      }
    });
  } catch (error) {
    console.error('Error fetching scholars:', error);
    showToast('Error', 'Failed to refresh scholar data', 'error');
  } finally {
    loading.value = false;
  }
};

// Generate report function
const generateReport = async () => {
  try {

    // Instead of opening multiple windows, send all data in one request
    const url = `/scholarships/${props.scholarship.id}/enrolled-scholars`;
    const queryParams = new URLSearchParams({
      batch_ids: props.batches.id,
      campus_ids: props.batches.campus_id,
      school_year_id: props.schoolyear.id,
      semester: props.selectedSem
    });

    window.open(`${url}?${queryParams.toString()}`, '_blank');
  } catch (err) {
    console.error('Failed to generate report:', err);
  }
};

// Detect outside click
const GenerateReport = ref(false);

const generateReportModal = () => {
    GenerateReport.value = !GenerateReport.value;
}

// Close modal function
const closeReportGeneration = () => {
    GenerateReport.value = false;
    resetSelections();
};

// Reset all selections
const resetSelections = () => {
    selectedReportType.value = '';
    selectedReportBatches.value = [];
    selectedReportCampuses.value = [];
};

const reportTypeOptions = ['Enrollees Summary', 'Enrolled List', 'Graduate Summary', 'Transferees'];
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

const handleGenerateReports = () => {
    const filters = {
        type: selectedReportType.value,
        campuses: selectedReportCampuses.value,
        batches: selectedReportBatches.value,
    };

    if (!filters.type || filters.campuses.length === 0 || filters.batches.length === 0) {
        console.warn('Please select a report type, campuses, and batches.');
        return;
    }

    switch (filters.type) {
        case 'Enrollees Summary':
            generateEnrolleesSummary(filters);
            break;
        case 'Enrolled List':
            generateEnrolledList(filters);
            break;
        case 'Graduate Summary':
            generateGraduateList(filters);
            break;
        case 'Payroll':
            generatePayroll(filters);
            break;
        // case 'Scholars List':
        //     generateScholarsList(filters);
        //     break;
        case 'Transferred Grantee':
            generateTransferredList(filters);
            break;
        default:
            console.warn('No valid report type selected.');
    }
};

const generateEnrolleesSummary = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/enrollees-summary`;
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

const generateEnrolledList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/enrolled-scholars`;
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


const generateGraduateList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/graduate-scholars`;
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

const generateTransferredList = async (filters) => {
    try {
        if (!Array.isArray(filters.campuses) || filters.campuses.length === 0 ||
            !Array.isArray(filters.batches) || filters.batches.length === 0) {
            console.warn('Campuses or batches are not selected properly.');
            return;
        }

        // Instead of opening multiple windows, send all data in one request
        const url = `/scholarships/${props.scholarship.id}/transferred-grantees`;
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




// Toast helper function
const showToast = (title, message, type = 'success') => {
  toast.value = {
    visible: true,
    title,
    message,
    type
  };

  // Hide toast after 3 seconds
  setTimeout(() => {
    toast.value.visible = false;
  }, 3000);
};

// Helper function for year suffix
const getYearSuffix = (year) => {
  if (!year) return '';
  const yearNum = Number(year);
  if (yearNum === 1) return "st";
  if (yearNum === 2) return "nd";
  if (yearNum === 3) return "rd";
  return "th";
};

// Lifecycle hooks
onMounted(() => {
  initFlowbite();
  document.addEventListener('click', handleClickOutside);
  // Initial data load
  if (!props.scholars || props.scholars.length === 0) {
    fetchScholars();
  }

  // Set up polling to refresh data every 5 minutes (adjust as needed)
  const dataRefreshInterval = setInterval(() => {
    fetchScholars();
  }, 300000); // 5 minutes

  // Clean up interval on component unmount
  return () => {
    clearInterval(dataRefreshInterval);
  };
  
});


onBeforeMount(() => {
    document.addEventListener('click', handleClickOutside);
})

const openDropdown = ref('');

const toggleDropdown = (dropdownName) => {
    openDropdown.value = openDropdown.value === dropdownName ? null : dropdownName
}
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