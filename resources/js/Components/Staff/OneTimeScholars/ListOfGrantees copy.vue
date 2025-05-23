<template>
  <div class="w-full bg-white rounded-xl">
    <div class="px-4 flex flex-row justify-between items-center">
      <!-- <form class="w-3/12">
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
      </form> -->
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
              <thead class="justify-center items-center bg-gray-100">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar Name</th>
                  <th>Campus</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- No results message -->
                <!-- <tr v-if="paginatedScholars.length === 0">
                  <td colspan="7" class="text-center py-6 text-gray-500">
                    No scholars found matching your search criteria
                  </td>
                </tr> -->
                <!-- Scholar rows -->
                <tr v-for="scholar in eligibleScholars" :key="scholar.id" class="text-sm">
                  <td>{{ scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img :src="scholar.profile_image || '../../../../assets/images/no_userpic.png'"
                            :alt="`${scholar.first_name}'s profile`" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                          <span v-if="scholar.status === 'Verified'"
                            class="material-symbols-rounded text-sm text-blue-600">verified</span>
                        </div>
                        <div class="text-sm opacity-50">
                          {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }} course
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    {{ scholar.campus }}
                  </td>
                  <td>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                      'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                      'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                      {{ scholar.status }}
                    </span>
                  </td>
                  <th>
                    <Link :href="`/scholarships/scholar=${scholar.id}`">
                    <button
                      class="p-2 border bg-white text-primary rounded-lg hover:bg-blue-200 transition-colors shadow-sm"
                      aria-label="View Details">
                      <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                    </button>
                    </Link>
                  </th>
                </tr>


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
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
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
  loading.value = true;
  try {
    const batchId = props.batches?.[0]?.id;
    if (!batchId) {
      showToast('Error', 'No batch selected', 'error');
      return;
    }

    window.open(`/scholarships/${props.scholarship.id}/batch/${batchId}/report`, '_blank');
    showToast('Report Generated', 'Your report is being downloaded');
  } catch (err) {
    console.error('Failed to generate report:', err);
    showToast('Error', 'Failed to generate report', 'error');
  } finally {
    loading.value = false;
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





// Define the required number of recipients
const requiredRecipients = 10;

// Dummy scholars data
const scholars = ref([
  { id: 1, urscholar_id: "URS001", first_name: "John", last_name: "Doe", middle_name: "A", campus: "Main", grant: "2024-03-10", submittedRequirements: 5, totalRequirements: 5, progress: 100, status: "Complete", profile_image: "" },
  { id: 2, urscholar_id: "URS002", first_name: "Jane", last_name: "Smith", middle_name: "B", campus: "West", grant: "2024-03-11", submittedRequirements: 3, totalRequirements: 5, progress: 60, status: "Incomplete", profile_image: "" },
  { id: 3, urscholar_id: "URS003", first_name: "Alice", last_name: "Brown", middle_name: "C", campus: "North", grant: "2024-03-12", submittedRequirements: 0, totalRequirements: 5, progress: 0, status: "No submission", profile_image: "" },
  { id: 4, urscholar_id: "URS004", first_name: "Bob", last_name: "Johnson", middle_name: "D", campus: "South", grant: "2024-03-13", submittedRequirements: 4, totalRequirements: 5, progress: 80, status: "Complete", profile_image: "" },
  { id: 5, urscholar_id: "URS005", first_name: "Michael", last_name: "Williams", middle_name: "E", campus: "East", grant: "2024-03-14", submittedRequirements: 2, totalRequirements: 5, progress: 40, status: "Incomplete", profile_image: "" },
  { id: 6, urscholar_id: "URS006", first_name: "Emma", last_name: "Taylor", middle_name: "F", campus: "Central", grant: "2024-03-15", submittedRequirements: 5, totalRequirements: 5, progress: 100, status: "Complete", profile_image: "" },
  { id: 7, urscholar_id: "URS007", first_name: "David", last_name: "Anderson", middle_name: "G", campus: "Main", grant: "2024-03-16", submittedRequirements: 1, totalRequirements: 5, progress: 20, status: "Incomplete", profile_image: "" },
  { id: 8, urscholar_id: "URS008", first_name: "Sophia", last_name: "Martinez", middle_name: "H", campus: "West", grant: "2024-03-17", submittedRequirements: 0, totalRequirements: 5, progress: 0, status: "No submission", profile_image: "" },
  { id: 9, urscholar_id: "URS009", first_name: "Daniel", last_name: "Clark", middle_name: "I", campus: "North", grant: "2024-03-18", submittedRequirements: 4, totalRequirements: 5, progress: 80, status: "Complete", profile_image: "" },
  { id: 10, urscholar_id: "URS010", first_name: "Olivia", last_name: "Lee", middle_name: "J", campus: "South", grant: "2024-03-19", submittedRequirements: 3, totalRequirements: 5, progress: 60, status: "Incomplete", profile_image: "" },
  // Cut-off applicants
  { id: 11, urscholar_id: "URS011", first_name: "Lucas", last_name: "Hall", middle_name: "K", campus: "East", grant: "2024-03-20", submittedRequirements: 5, totalRequirements: 5, progress: 100, status: "Complete", profile_image: "" },
  { id: 12, urscholar_id: "URS012", first_name: "Mia", last_name: "Lopez", middle_name: "L", campus: "Central", grant: "2024-03-21", submittedRequirements: 2, totalRequirements: 5, progress: 40, status: "Incomplete", profile_image: "" }
]);

// Compute the scholars that are within the required recipients
const eligibleScholars = computed(() => scholars.value.slice(0, requiredRecipients));
const cutoffScholars = computed(() => scholars.value.slice(requiredRecipients));
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