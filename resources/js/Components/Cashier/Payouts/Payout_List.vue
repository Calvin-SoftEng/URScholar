<template>
    <div class="w-full mt-5 bg-white rounded-xl">
      <div class="px-4 pt-4 flex flex-row justify-between items-center">
        <div class="flex flex-row gap-2">
          <button
            class="bg-white hover:bg-gray-200 text-gray-600 border border-2-gray-300 font-normal text-sm py-2 px-4 rounded"
            @click="openReport">
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
      <!-- <div class="bg-gray-100 mx-4 rounded-lg p-1">
        <ul class="flex space-x-5 flex-grow justify-left font-quicksand font-semibold">
          <li v-for="batch in batches" :key="batch.id"><button @click="toggleBatch(batch.id)"
              class="px-10 py-1 border-b-2 cursor-pointer hover:bg-gray-300 hover:text-gray-600 rounded-lg"
              :class="expandedBatches === batch.id ? 'bg-white text-primary' : 'bg-gray-100 text-gray-400'">Batch {{ batch.batch_no
              }}</button>
          </li>
        </ul>
      </div> -->
  
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
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr v-for="scholar in paginatedScholars" :key="scholar.id" class="text-sm">
                    <td>test1</td>
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
                            <span v-if="scholar.status === 'Verified'"
                              class="material-symbols-rounded text-sm text-blue-600">verified</span>
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
                      <span class="text-sm text-gray-700 mt-1 flex items-center justify-center">
                        {{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}
                      </span>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-300 h-full rounded-full" :style="{ width: scholar.progress + '%' }">
                        </div>
                      </div>
                    </td>
                    <td>
                      <span :class="{
                        'bg-green-100 text-green-800 border border-green-400': scholar.status === 'Complete',
                        'bg-gray-200 text-gray-500 border border-gray-400': scholar.status === 'No submission',
                        'bg-red-100 text-red-800 border border-red-400': scholar.status === 'Incomplete'
                      }" class="text-xs font-medium px-2.5 py-0.5 rounded">
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
                  </tr> -->
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
  import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'
  
  const props = defineProps({
    scholarship: Object,
    schoolyear: Object,
    selectedSem: Object,
    batches: Object,
    scholars: Array,
    requirements: Array,
  });
  
  const components = {
    DataTable,
    Column,
    Button,
    FileUpload,
  };
  
  
  const currentPage = ref(1);
  const itemsPerPage = 10;
  const searchQuery = ref('');
  
  // Modify the filteredScholars function to handle pagination
  const filteredScholars = computed(() => {
    // Use props.scholars directly instead of accessing from batches
    const allScholars = props.scholars || [];
  
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
  
  
  const showRequirements = ref(false);
  
  // Function to toggle the visibility of the table and second div
  const openRequirements = () => {
    showRequirements.value = !showRequirements.value;
  };
  
  const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default
  
  onMounted(() => {
    if (props.batches && props.batches.length > 0) {
      expandedBatches.value = props.batches[0].id;
    }
  });
  
  // Add download report functionality
  const openReport = async (batchId) => {
    try {
      // Open URL in new tab instead of creating blob
      window.open(`/scholarships/${props.scholarship.id}/batch/${batchId}/report`, '_blank');
    } catch (err) {
      console.error('Failed to open report:', err);
    }
  };
  
  // const toggleBatch = (batchId) => {
  //   if (expandedBatches.value.has(batchId)) {
  //     expandedBatches.value.delete(batchId)
  //   } else {
  //     expandedBatches.value.add(batchId)
  //   }
  // }
  // const expandedBatches = ref(null); // Track the currently open batch
  
  const toggleBatch = (batchId) => {
    expandedBatches.value = expandedBatches.value === batchId ? null : batchId;
  };
  
  const addingPanel = ref(false)
  const uploadingPanel = ref(false)
  
  const toggleAdd = () => {
    addingPanel.value = !addingPanel.value
  }
  
  const toggleUpload = () => {
    uploadingPanel.value = !uploadingPanel.value
  }
  
  const closePanel = () => {
    previewData.value = [];
    headers.value = [];
    uploadingPanel.value = false;
    addingPanel.value = false;
    entries.value = false;
  };
  
  const getYearSuffix = (year) => {
    if (year === 1) return "st";
    if (year === 2) return "nd";
    if (year === 3) return "rd";
    return "th";
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