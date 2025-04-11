<template>
  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">
        <!-- Publish Button -->
        <Link :href="(route('scholarship.onetime_scholars'))">
          <button
            @click="generateReport"
            class="flex items-center gap-2 border border-blue-600 font-poppins text-primary px-4 py-2 rounded-lg transition duration-200
                  hover:bg-blue-300 disabled:opacity-50 disabled:cursor-not-allowed">
            <font-awesome-icon :icon="['fas', 'file-lines']" class="text-base" />
            <span class="font-normal">Publish <span class="font-semibold">Applicant List</span></span>
          </button>
        </Link>

        <!-- Forward to Sponsor -->
        <div>
          <button @click="toggleForwardSponsor"
            class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg transition duration-200
                  hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
            <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
            <span class="font-normal">Forward to <span class="font-semibold">Sponsor</span></span>
          </button>
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
            <div v-if="filteredScholars.length === 0"
              class="bg-white w-full dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
              <font-awesome-icon :icon="['fas', 'user-graduate']"
                class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
              <p class="text-lg text-gray-700 dark:text-gray-300">
                No applicants for this campus yet
              </p>
            </div>
            <div v-else>
              <table class="table rounded-lg">
                <!-- Table Head -->
                <thead class="justify-center items-center bg-gray-100">
                  <tr class="text-xs uppercase">
                    <th>URScholar ID</th>
                    <th>Applicant</th>
                    <th>Campus</th>
                    <th>Date Applied</th>
                    <th>Requirements</th>
                    <th>GWA</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <!-- Scholars within recipient limit -->
                  <template v-for="(scholar, index) in scholarsWithinLimit" :key="scholar.id">
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
                  <tr v-if="hasScholarsOutsideLimit">
                    <td colspan="8" class="text-center font-semibold text-red-600 py-4 bg-red-50">
                      Cut-Off Line: Below applicants are NOT within the required {{ recipientLimit }} recipients.
                    </td>
                  </tr>

                  <!-- Scholars below recipient limit -->
                  <template v-for="scholar in scholarsOutsideLimit" :key="scholar.id">
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
                <font-awesome-icon :icon="['fas', 'graduation-cap']"
                    class="text-blue-600 text-2xl flex-shrink-0" />

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
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form >
            <div class="py-4 px-8 flex flex-col gap-3">
                <!-- Loading Indicator -->
                <!-- <div class="flex justify-center items-center py-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                </div> -->

                <!-- Batch List -->
                <div
                    class="flex flex-col divide-y divide-gray-300">
                    <p>
                        rgsrgrsg
                    </p>
                    <div 
                        class="py-3 px-4 flex justify-between items-center">
                        <div>
                            <p class="text-base font-medium text-gray-900 dark:text-white">Batch fefef</p>
                            <p class="text-sm text-gray-500">Completed:fefefef</p>
                        </div>
                        <!-- <span
                            :class="`text-sm font-medium px-3 py-1 rounded-full ${batch.sub_total === batch.total_scholars ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'}`">
                            {{ batch.sub_total === batch.total_scholars ? 'Ready to Send' : 'Incomplete'
                            }}
                        </span> -->
                    </div>
                </div>

                <!-- Forward Button -->
                <!-- <div v-if="completedBatches === batches.length" class="mt-4">
                    <button type="submit" :disabled="isSubmitting || selectedBatches.length === 0"
                        @click="forwardSponsor"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ isSubmitting ? 'Processing...' : 'Forward' }}
                    </button>
                </div> -->
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
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
  scholarship: Object,
  schoolyear: Object,
  selectedSem: Object,
  batches: Array,
  applicants: Array,
  scholars: Array,
  requirements: Array,
  campusRecipients: Array,
  totalSlots: Number,
  currentUser: Object,
});

// Get current user's campus ID from Inertia page props
const page = usePage();

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

// First filter by campus - exception for super_admin
const filteredByCampus = computed(() => {
  if (!props.scholars) return [];

  // If user is super_admin, show all scholars across all campuses
  if (props.currentUser && props.currentUser.usertype === 'super_admin') {
    return props.scholars;
  }

  // Otherwise filter by campus
  return props.scholars.filter(scholar => {
    return scholar.campus === props.currentUser.campus_id;
  });
});

// Then filter by search query
const filteredScholars = computed(() => {
  if (!searchQuery.value) {
    return [...filteredByCampus.value].sort((a, b) =>
      a.status === 'Verified' ? -1 : b.status === 'Verified' ? 1 : 0
    );
  }

  const query = searchQuery.value.toLowerCase();
  return filteredByCampus.value.filter(scholar =>
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

// Sort by grades (1.0 being highest)
const sortedScholars = computed(() => {
  return [...filteredScholars.value].sort((a, b) => {
    // First priority: Complete vs incomplete requirements
    if (a.status === 'Complete' && b.status !== 'Complete') return -1;
    if (a.status !== 'Complete' && b.status === 'Complete') return 1;

    // Second priority: Compare grades (lower numeric value is better in 1.0-5.0 scale)
    const gradeA = parseFloat(a.grade) || 5.0; // Default to lowest grade if null
    const gradeB = parseFloat(b.grade) || 5.0;

    if (gradeA !== gradeB) {
      return gradeA - gradeB; // Lower grade value first (1.0 is better than 2.0)
    }

    // If grades are equal, sort by date applied (earlier first)
    return new Date(a.date_applied) - new Date(b.date_applied);
  });
});

// Split scholars into within limit and outside limit
const scholarsWithinLimit = computed(() => {
  return sortedScholars.value.slice(0, props.totalSlots || 0);
});

const scholarsOutsideLimit = computed(() => {
  return props.totalSlots && sortedScholars.value.length > props.totalSlots
    ? sortedScholars.value.slice(props.totalSlots)
    : [];
});

// Check if there are scholars outside the limit
const hasScholarsOutsideLimit = computed(() => {
  // Make sure the totalSlots is properly defined
  if (!props.totalSlots) return false;

  // For any user type, check if the total number of scholars for their campus
  // exceeds the recipient limit
  return filteredByCampus.value.length > props.totalSlots;
});
// Computed properties for pagination
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

const ForwardtoSponsor = ref(false);

const toggleForwardSponsor = async () => {
    ForwardtoSponsor.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const closeModal = () => {
    ForwardtoSponsor.value = false;
    resetForm();
};

const forwardSponsor = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('scholarship.forward_sponsor', {
        scholarshipId: props.scholarship.id, selectedSem: props.selectedSem, school_year: props.schoolyear.id,
        selectedCampus: props.selectedCampus
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
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

  // Set up polling to refresh data every 5 minutes
  const dataRefreshInterval = setInterval(() => {
    fetchScholars();
  }, 300000); // 5 minutes

  // Clean up interval on component unmount
  return () => {
    clearInterval(dataRefreshInterval);
  };
});

// Replace the hard-coded recipientLimit with data from the backend
const recipientLimit = computed(() => props.totalSlots || 0);
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