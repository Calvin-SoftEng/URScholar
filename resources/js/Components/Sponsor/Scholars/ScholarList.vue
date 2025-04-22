<template>
  <div class="w-full bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2 w-5/12">
        <form class="w-7/12">
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
      </div>

      <div class="flex flex-wrap gap-10">
        <!-- Campus Filter -->
        <div>
          <label class="text-sm block mb-1">Filter Campus</label>
          <select v-model="selectedCampus"
            class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
            <option value="">All Campuses</option>
            <option v-for="campus in availableCampuses" :key="campus" :value="campus">{{ campus }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Batches List -->
    <div class="p-4">
      <div v-if="loading" class="flex justify-center items-center py-10">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
      </div>

      <div v-else>
        <!-- Display batches -->
        <div v-for="batch in filteredBatches" :key="batch.batch.id" class="mb-8">
          <div class="flex justify-between items-center mb-3 bg-gray-100 p-3 rounded-lg">
            <h3 class="text-lg font-semibold">
              Batch #{{ batch.batch.batch_no }}
              <span class="text-sm text-gray-500">
                ({{ batch.batch.school_year.year || 'N/A' }}, {{ batch.semester || 'N/A' }} Semester)
              </span>
            </h3>
            <div class="text-sm text-gray-600">
              Complete: {{ batch.completeSubmissionsCount }}/{{ batch.scholars.length }} Scholars
            </div>
          </div>

          <!-- Scholars -->
          <div class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table rounded-lg w-full">
              <!-- head -->
              <thead class="justify-center items-center bg-gray-100">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar</th>
                  <th>Campus</th>
                  <th>Course</th>
                  <th>Grant</th>
                  <th>Monitoring</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- No scholars message -->
                <tr v-if="batch.scholars.length === 0">
                  <td colspan="8" class="text-center py-6 text-gray-500">
                    No scholars found in this batch
                  </td>
                </tr>

                <!-- Scholar rows -->
                <tr v-for="scholar in batch.scholars" :key="scholar.id" class="text-sm">
                  <td>{{ scholar.urscholar_id }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img v-if="scholar.user?.picture" :src="`/storage/user/profile/${scholar.user.picture}`"
                            alt="Profile Picture">
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name || '' }}
                          <span v-if="scholar.userVerified"
                            class="material-symbols-rounded text-sm text-blue-600">verified</span>
                        </div>
                        <div class="text-sm opacity-50">
                          {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{ scholar.campus }}</td>
                  <td>{{ scholar.course }}</td>
                  <td>{{ scholar.grant }}</td>
                  <td>{{ scholar.grade ?? 'No Grade' }}</td>
                  <td>
                    <span class="px-2 py-1 rounded-md text-xs" :class="{
                      'bg-green-100 text-green-800 border border-green-400': scholar.student_status === 'Enrolled',
                      'bg-yellow-100 text-yellow-800 border border-yellow-400': scholar.student_status === 'Dropped' || scholar.student_status === 'Graduated',
                      'bg-red-100 text-red-800 border border-red-400': scholar.student_status === 'Unenrolled' || scholar.student_status === 'Unenrolled',
                      'bg-blue-100 text-blue-800 border border-blue-400': scholar.student_status === 'Transferred',
                    }">
                      {{ scholar.student_status }}
                    </span>
                  </td>
                  <th>
                    <Link :href="route('sponsor.sponsor_scholar', scholar.id)">
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

          
        </div>

        <!-- No batches message (outside the v-for loop) -->
        <div v-if="filteredBatches.length === 0" class="text-center py-10 text-gray-500">
          No matching scholars found
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
  selectedSem: String,
  processedBatches: Array,
  requirements: Array,
  payout: Number,
});

// Data loading state
const loading = ref(false);
const searchQuery = ref('');
const showPayrolls = ref(false);

// Filter states
const selectedCampus = ref('');
const selectedYear = ref(props.schoolyear?.id || '');
const selectedSemester = ref(props.selectedSem || '');

// Toast notification state
const toast = ref({
  visible: false,
  title: '',
  message: '',
  type: 'success'
});

// Updated filteredBatches computed property with merged functionality
const filteredBatches = computed(() => {
  const query = searchQuery.value.toLowerCase();

  return (props.processedBatches || [])
    .map(batch => {
      // Apply search and campus filter to scholars
      const filteredScholars = batch.scholars.filter(scholar =>
        (!query || // If no search query, include all scholars
          scholar.first_name?.toLowerCase().includes(query) ||
          scholar.last_name?.toLowerCase().includes(query) ||
          scholar.middle_name?.toLowerCase().includes(query) ||
          scholar.urscholar_id?.toLowerCase().includes(query) ||
          scholar.campus?.toLowerCase().includes(query) ||
          scholar.course?.toLowerCase().includes(query) ||
          scholar.grant?.toLowerCase().includes(query)
        ) &&
        (!selectedCampus.value || scholar.campus === selectedCampus.value) // Apply campus filter
      );

      // Return batch with filtered scholars
      return {
        ...batch,
        scholars: filteredScholars,
        hasMatchingScholars: filteredScholars.length > 0 // Flag for batches with matches
      };
    })
    // Filter out batches with no matching scholars
    .filter(batch => batch.scholars.length > 0);
});

// Additional computed properties for filters
const availableCampuses = computed(() => {
  const campuses = new Set();
  (props.processedBatches || []).forEach(batch => {
    batch.scholars.forEach(scholar => {
      if (scholar.campus) campuses.add(scholar.campus);
    });
  });
  return Array.from(campuses).sort();
});

const availableYears = computed(() => {
  // This would need to come from your backend
  // For now, just return the current year if available
  return props.schoolyear ? [props.schoolyear] : [];
});

// Methods for filtering
const matchesFilters = (scholar) => {
  if (selectedCampus.value && scholar.campus !== selectedCampus.value) {
    return false;
  }
  return true;
};

const updateFilters = () => {
  // Navigate to the same route with updated query parameters
  router.get(route(route().current()), {
    selectedYear: selectedYear.value,
    selectedSem: selectedSemester.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

// Fetch data from the server
// const fetchData = async () => {
//   loading.value = true;
//   try {
//     await router.reload({
//       only: ['processedBatches', 'requirements'],
//       data: {
//         selectedYear: selectedYear.value,
//         selectedSem: selectedSemester.value,
//       },
//       onSuccess: () => {
//         showToast('Data Updated', 'Scholarship data has been refreshed');
//       },
//       onError: () => {
//         showToast('Error', 'Failed to refresh scholarship data', 'error');
//       }
//     });
//   } catch (error) {
//     console.error('Error fetching data:', error);
//     showToast('Error', 'Failed to refresh data', 'error');
//   } finally {
//     loading.value = false;
//   }
// };

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
  // Initial data load if needed
  if (!props.processedBatches || props.processedBatches.length === 0) {
    fetchData();
  }

  // Set up polling to refresh data every 5 minutes
  const dataRefreshInterval = setInterval(() => {
    fetchData();
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

/* Table styling */
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 0.75rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.table th {
  font-weight: 600;
}
</style>