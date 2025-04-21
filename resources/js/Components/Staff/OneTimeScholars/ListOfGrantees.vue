<template>
  <div class="w-full bg-white rounded-xl">
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
                  <th>Course</th>
                  <th>Year Level</th>
                  <th>Student Status</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- No results message -->
                <tr v-if="paginatedGrantees.length === 0">
                  <td colspan="8" class="text-center py-6 text-gray-500">
                    No grantees found matching your search criteria
                  </td>
                </tr>
                <!-- Grantee rows -->
                <tr v-for="grantee in paginatedGrantees" :key="grantee.id" class="text-sm">
                  <td>{{ grantee.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img
                            :src="`/storage/user/profile/${grantee.picture}` || '../../../../assets/images/no_userpic.png'"
                            alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ grantee.full_name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{ grantee.campus }}</td>
                  <td>{{ grantee.course }}</td>
                  <td>{{ grantee.year_level }}{{ getYearSuffix(grantee.year_level) }}</td>
                  <td>{{ grantee.student_status }}</td>
                  <td>
                    <span :class="{
                      'bg-green-100 text-green-800 border border-green-400': grantee.status === 'Accomplished',
                      'bg-gray-200 text-gray-500 border border-gray-400': grantee.status === 'Pending',
                      'bg-red-100 text-red-800 border border-red-400': grantee.status === 'Rejected'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded w-full">
                      {{ grantee.status }}
                    </span>
                  </td>
                  <th>
                    <Link :href="`/scholarships/scholar=${grantee.scholar_id}`">
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
          <div v-if="totalGrantees > itemsPerPage" class="mt-5 flex justify-between items-center">
            <span class="text-sm text-gray-700 dark:text-gray-400">
              Showing
              <span class="font-semibold text-gray-900 dark:text-white">{{ startIndex }}</span>
              to
              <span class="font-semibold text-gray-900 dark:text-white">{{ endIndex }}</span>
              of
              <span class="font-semibold text-gray-900 dark:text-white">{{ totalGrantees }}</span>
              Grantees
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
  batch: Object,
  schoolyear: Object,
  campus: Array,
  selectedSem: Object,
  grantees: Array,
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

// Computed properties for grantees filtering and pagination
const filteredGrantees = computed(() => {
  const allGrantees = props.grantees || [];

  if (!searchQuery.value) {
    return [...allGrantees];
  }

  const query = searchQuery.value.toLowerCase();
  return allGrantees.filter(grantee =>
    grantee.full_name?.toLowerCase().includes(query) ||
    grantee.urscholar_id?.toLowerCase().includes(query) ||
    grantee.campus?.toLowerCase().includes(query) ||
    grantee.course?.toLowerCase().includes(query) ||
    grantee.student_status?.toLowerCase().includes(query) ||
    grantee.status?.toLowerCase().includes(query)
  );
});

const totalGrantees = computed(() => filteredGrantees.value.length);
const totalPages = computed(() => Math.ceil(totalGrantees.value / itemsPerPage));

const paginatedGrantees = computed(() => {
  const startIdx = (currentPage.value - 1) * itemsPerPage;
  const endIdx = startIdx + itemsPerPage;
  return filteredGrantees.value.slice(startIdx, endIdx);
});

const startIndex = computed(() =>
  totalGrantees.value === 0 ? 0 : (currentPage.value - 1) * itemsPerPage + 1
);

const endIndex = computed(() =>
  Math.min(currentPage.value * itemsPerPage, totalGrantees.value)
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
const fetchGrantees = async () => {
  loading.value = true;
  try {
    // Using Inertia's router.reload() to refresh the current page data
    await router.reload({
      only: ['grantees'],
      onSuccess: () => {
        showToast('Data Updated', 'Grantee data has been refreshed');
      },
      onError: () => {
        showToast('Error', 'Failed to refresh grantee data', 'error');
      }
    });
  } catch (error) {
    console.error('Error fetching grantees:', error);
    showToast('Error', 'Failed to refresh grantee data', 'error');
  } finally {
    loading.value = false;
  }
};

// Generate report function
const generateReport = async () => {
  loading.value = true;
  try {
    const batchId = props.batch?.id;
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
  if (!props.grantees || props.grantees.length === 0) {
    fetchGrantees();
  }

  // Set up polling to refresh data every 5 minutes (adjust as needed)
  const dataRefreshInterval = setInterval(() => {
    fetchGrantees();
  }, 300000); // 5 minutes

  // Clean up interval on component unmount
  return () => {
    clearInterval(dataRefreshInterval);
  };
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