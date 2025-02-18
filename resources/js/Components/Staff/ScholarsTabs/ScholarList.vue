<template>
  <!-- Global "No Scholars" Message (if no batches exist) -->
  <!-- <div v-if="!batches || batches.length === 0" class="text-center py-5 mt-5">
    <p class="text-lg text-gray-700 dark:text-gray-300">No scholars added yet</p>
  </div> -->

  <div class="w-full mt-5 bg-white rounded-xl">
    <div class="px-4 pt-4 flex flex-row justify-between items-center">
      <div class="flex flex-row gap-2">
        <button class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded" @click="openReport">
          <font-awesome-icon :icon="['fas', 'file-lines']" class="mr-2 text-sm" />Generate Report
        </button>
        <button class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded" @click="openRequirements">
          <font-awesome-icon :icon="['fas', 'folder-open']" class="mr-2 text-sm" />View Requirements
        </button>
        <button class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded" @click="openReport">
          <font-awesome-icon :icon="['fas', 'file-export']" class="mr-2 text-sm" />Export
        </button>
      </div>
      <form class="w-3/12">
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
    <!-- <div class="bg-gray-100 mx-4 rounded-lg p-1">
      <ul class="flex space-x-5 flex-grow justify-left font-quicksand font-semibold">
        <li v-for="batch in batches" :key="batch.id"><button @click="toggleBatch(batch.id)"
            class="px-10 py-1 border-b-2 cursor-pointer hover:bg-gray-300 hover:text-gray-600 rounded-lg"
            :class="expandedBatches === batch.id ? 'bg-white text-primary' : 'bg-gray-100 text-gray-400'">Batch {{ batch.batch_no
            }}</button>
        </li>
      </ul>
    </div> -->

    <div v-for="batch in batches" :key="batch.id">
      <div>
        <div v-if="expandedBatches === batch.id" class="w-full bg-white h-full p-4">

          <!-- table -->
          <div v-show="!showRequirements" class="overflow-x-auto font-poppins border rounded-lg">
            <table class="table rounded-lg">
              <!-- head -->
              <thead class="justify-center items-center bg-gray-100">
                <tr class="text-xs uppercase">
                  <th>URScholar ID</th>
                  <th>Scholar</th>
                  <th>Campus</th>
                  <th>Grant</th>
                  <th>Requirements</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <!-- row 1 -->
                <tr v-for="scholar in paginatedScholars" :key="scholar.id" class="text-sm">
                  <td>{{ scholar.id || 'test1' }}</td>
                  <td>
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-10 w-10">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                            {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                            <!-- Show verified icon if scholar.status is 'Verified' -->
                            <span v-if="scholar.status === 'Verified'" class="material-symbols-rounded text-sm text-blue-600">verified</span>
                        </div>
                        <div class="text-sm opacity-50">
                          {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }}
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
                  <td>
                    <!-- Progress Label -->
                    <span class="text-sm text-gray-700 mt-1 flex items-center justify-center">
                      {{ scholar.requirements_progress || calculateRequirementsProgress(scholar) }}
                    </span>
                    <!-- Progress Bar Container -->
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <!-- Progress Bar -->
                      <div class="bg-yellow-300 h-full rounded-full" 
                           :style="`width: ${calculateRequirementsPercentage(scholar)}%;`"></div>
                    </div>
                  </td>
                  <td>
                    <span 
                      :class="{
                        'bg-green-100 text-green-800 border border-green-400': isRequirementsComplete(scholar),
                        'bg-yellow-100 text-yellow-800 border border-yellow-400': !isRequirementsComplete(scholar)
                      }" 
                      class="text-xs font-medium px-2.5 py-0.5 rounded">
                      {{ isRequirementsComplete(scholar) ? 'Complete' : 'Incomplete' }}
                    </span>
                  </td>
                  <th>
                    <Link :href="`/scholarships/scholar=${scholar.id}`">
                    <button class="p-2 border bg-white text-primary rounded-lg hover:bg-blue-200 transition-colors shadow-sm"
                      aria-label="View Details">
                      <font-awesome-icon :icon="['fas', 'ellipsis']" class="px-1" />
                    </button>
                    </Link>
                  </th>
                </tr>
              </tbody>
            </table>
            <!-- Pagination controls -->
            <div v-if="totalScholars > 10" class="mt-5 flex flex-col items-right">
              <span class="text-sm text-gray-700 dark:text-gray-400">
                Showing 
                <span class="font-semibold text-gray-900 dark:text-white">{{ startIndex }}</span> 
                to 
                <span class="font-semibold text-gray-900 dark:text-white">{{ endIndex }}</span> 
                of 
                <span class="font-semibold text-gray-900 dark:text-white">{{ totalScholars }}</span> 
                Scholars
              </span>
              <div class="inline-flex mt-2 xs:mt-0">
                <button
                  @click="prevPage"
                  :disabled="currentPage === 1"
                  :class="[
                    'flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 rounded-s hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                    currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  Prev
                </button>
                <button
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  :class="[
                    'flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-blue-800 border-0 border-s border-gray-700 rounded-e hover:bg-blue-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                    currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                >
                  Next
                </button>
              </div>
            </div>
          </div>

          <div v-show="showRequirements">
            <div class="w-full">
                <h3 class="font-semibold text-gray-900 dark:text-white">Requirements</h3>
                <div class="overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3">Scholar ID</th>
                                <th scope="col" class="px-4 py-3">Requirement ID</th>
                                <th scope="col" class="px-4 py-3">Form</th>
                                <th scope="col" class="px-4 py-3">Submitted Requirements</th>
                                <th scope="col" class="px-4 py-3">Path</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Created At</th>
                                <th scope="col" class="px-4 py-3">Updated At</th>
                                <th scope="col" class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(scholar, index) in getAllSubmittedRequirements()" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3">{{ scholar.id }}</td>
                                <td class="px-4 py-3">{{ scholar.scholar_id }}</td>
                                <td class="px-4 py-3">{{ scholar.requirement_id }}</td>
                                <td class="px-4 py-3">{{ scholar.requirement ? scholar.requirement.name : 'N/A' }}</td>
                                <td class="px-4 py-3">{{ getFileNameFromPath(scholar.path) }}</td>
                                <td class="px-4 py-3">{{ truncatePath(scholar.path) }}</td>
                                <td class="px-4 py-3">
                                    <span 
                                        :class="{
                                            'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Approved',
                                            'bg-yellow-100 text-yellow-800 border border-yellow-400': scholar.status === 'Pending',
                                            'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Rejected'
                                        }" 
                                        class="px-2 py-1 rounded text-xs">
                                        {{ scholar.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ formatDate(scholar.created_at) }}</td>
                                <td class="px-4 py-3">{{ formatDate(scholar.updated_at) }}</td>
                                <td class="px-4 py-3 flex gap-2">
                                    <a :href="scholar.path" target="_blank" class="text-blue-600 hover:underline">View</a>
                                    <button @click="updateStatus(scholar.id, 'Approved')" 
                                        :class="{'opacity-50': scholar.status === 'Approved'}"
                                        :disabled="scholar.status === 'Approved'"
                                        class="text-green-600 hover:underline">Approve</button>
                                    <button @click="updateStatus(scholar.id, 'Rejected')" 
                                        :class="{'opacity-50': scholar.status === 'Rejected'}"
                                        :disabled="scholar.status === 'Rejected'"
                                        class="text-red-600 hover:underline">Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div>

    </div>



    <ToastProvider>
      <ToastRoot v-if="toastVisible"
        class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
        <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
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
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

const props = defineProps({
  scholarship: Object,
  schoolyear: Object,
  selectedSem: Object,
  batches: Array,
});

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
  Papa,
};

const currentPage = ref(1);
const itemsPerPage = 10;
const searchQuery = ref('');
const toastVisible = ref(false);
const toastMessage = ref('');

// Requirements Management
const showRequirements = ref(false);
const newItem = ref('');
const items = ref([]);

// Helper functions for requirements
const calculateRequirementsProgress = (scholar) => {

  if (!scholar.submitted_requirements) return '0/0';
  
  const totalRequired = getBatchRequirementsCount(scholar);
  const approved = scholar.submitted_requirements.filter(req => req.status === 'Approved').length;
  
  return `${approved}/${totalRequired}`;
};

const calculateRequirementsPercentage = (scholar) => {
  if (!scholar.submitted_requirements) return 0;
  
  const totalRequired = getBatchRequirementsCount(scholar);
  if (totalRequired === 0) return 0;
  
  const approved = scholar.submitted_requirements.filter(req => req.status === 'Approved').length;
  return (approved / totalRequired) * 100;
};

const isRequirementsComplete = (scholar) => {
  if (!scholar.submitted_requirements) return false;
  
  const totalRequired = getBatchRequirementsCount(scholar);
  if (totalRequired === 0) return false;
  
  const approved = scholar.submitted_requirements.filter(req => req.status === 'Approved').length;
  return approved === totalRequired;
};

const getBatchRequirementsCount = (scholar) => {
  
  // Find which batch this scholar belongs to
  for (const batch of props.batches) {
    if (batch.scholars && batch.scholars.find(s => s.id === scholar.id)) {
      return batch.requirements ? batch.requirements.length : 0;
    }
  }
  return 0;
};

// Get all submitted requirements from all scholars in all batches
const getAllSubmittedRequirements = () => {
  const allRequirements = [];
  
  props.batches.forEach(batch => {
    if (batch.scholars) {
      batch.scholars.forEach(scholar => {
        if (scholar.submitted_requirements) {
          scholar.submitted_requirements.forEach(req => {
            allRequirements.push({
              ...req,
              scholar_id: scholar.id,
              scholar_name: `${scholar.last_name}, ${scholar.first_name} ${scholar.middle_name || ''}`
            });
          });
        }
      });
    }
  });
  
  return allRequirements;
};

// Helper functions for file paths
const getFileNameFromPath = (path) => {
  if (!path) return 'No file';
  return path.split('/').pop() || path;
};

const truncatePath = (path) => {
  if (!path) return '';
  return path.length > 30 ? `...${path.substring(path.length - 30)}` : path;
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleString();
};

// Update status of a submitted requirement
const updateStatus = (id, status) => {
  // This would typically make an API call
  router.post(`/requirements/${id}/status`, { status }, {
    preserveScroll: true,
    onSuccess: () => {
      toastMessage.value = `Requirement status updated to ${status}`;
      toastVisible.value = true;
      setTimeout(() => { toastVisible.value = false; }, 3000);
      
      // Refresh data or update local state
      // This is a simplified approach - you might want to refresh the page or update the state directly
      props.batches.forEach(batch => {
        if (batch.scholars) {
          batch.scholars.forEach(scholar => {
            if (scholar.submitted_requirements) {
              scholar.submitted_requirements.forEach(req => {
                if (req.id === id) {
                  req.status = status;
                }
              });
            }
          });
        }
      });
    }
  });
};

// Modify the filteredScholars function to handle pagination
const filteredScholars = computed(() => {
  // Combine all scholars from all batches
  const allScholars = props.batches.reduce((acc, batch) => {
    if (batch.scholars) {
      acc.push(...batch.scholars);
    }
    return acc;
  }, []);

  // Apply search filter
  let scholars = allScholars.filter(scholar =>
    scholar.first_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.last_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.middle_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.email?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.course?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.campus?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    scholar.grant?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );

  // Sort so that 'Verified' scholars appear first
  scholars.sort((a, b) => (a.status === 'Verified' ? -1 : 1));

  return scholars;
});

// Pagination computed properties
const paginatedScholars = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filteredScholars.value.slice(startIndex, endIndex);
});

const totalScholars = computed(() => {
  return filteredScholars.value.length;
});

const totalPages = computed(() => {
  return Math.ceil(totalScholars.value / itemsPerPage);
});

// Display range computed properties
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const endIndex = computed(() => Math.min(currentPage.value * itemsPerPage, totalScholars.value));

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

// Reset page when search changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Function to toggle the visibility of the table and requirements view
const openRequirements = () => {
  showRequirements.value = !showRequirements.value;
};

const expandedBatches = ref(null); // Track the currently open batch

onMounted(() => {
  if (props.batches && props.batches.length > 0) {
    expandedBatches.value = props.batches[0].id;
  }
});

// Add download report functionality
const openReport = async () => {
  try {
    // Open URL in new tab instead of creating blob
    window.open(`/scholarships/${props.scholarship.id}/batch/${expandedBatches.value}/report`, '_blank');
  } catch (err) {
    console.error('Failed to open report:', err);
  }
};

const toggleBatch = (batchId) => {
  expandedBatches.value = expandedBatches.value === batchId ? null : batchId;
};

const addingPanel = ref(false);
const uploadingPanel = ref(false);

const toggleAdd = () => {
  addingPanel.value = !addingPanel.value;
};

const toggleUpload = () => {
  uploadingPanel.value = !uploadingPanel.value;
};

const closePanel = () => {
  previewData.value = [];
  headers.value = [];
  uploadingPanel.value = false;
  addingPanel.value = false;
};

const getYearSuffix = (year) => {
  if (!year) return '';
  const yearNum = parseInt(year);
  if (yearNum === 1) return "st";
  if (yearNum === 2) return "nd";
  if (yearNum === 3) return "rd";
  return "th";
};

// Function to add a new requirement
const addItem = () => {
  if (newItem.value.trim()) {
    items.value.push(newItem.value.trim());
    newItem.value = '';
    
    // Here you would typically also make an API call to add this requirement to the backend
  }
};

// Function to remove a requirement
const removeItem = (index) => {
  if (index >= 0 && index < items.value.length) {
    items.value.splice(index, 1);
    
    // Here you would typically also make an API call to remove this requirement from the backend
  }
};
</script>

<style>
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