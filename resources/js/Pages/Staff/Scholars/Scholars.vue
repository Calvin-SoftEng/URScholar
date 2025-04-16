<template>
  <AuthenticatedLayout>
    <div
      class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
      <div class="w-full mx-auto space-y-3">
        <div class="breadcrumbs text-sm text-gray-400 mb-5">
          <ul>
            <li class="hover:text-gray-600">
              Home
            </li>
            <li>
              <span class="text-blue-400 font-semibold">Scholars</span>
            </li>
          </ul>
        </div>


        <!-- Header section with title and search -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
          <!-- <h1 class="text-2xl sm:text-4xl font-poppins font-extrabold text-[darkblue] text-left">
              <span>{{ scholarship.name }}</span> Scholars 2024-2025
            </h1> -->

          <div class="flex flex-row items-center gap-4">
            <div class="bg-white w-full sm:w-auto border border-gray-200 rounded-lg p-5 flex items-center gap-4">
              <!-- Icon -->
              <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <font-awesome-icon :icon="['fas', 'users']" class="text-2xl" />
              </div>

              <!-- Text Content -->
              <div class="flex flex-col">
                <span class="font-normal text-base text-gray-600">Total Active Scholars</span>
                <span class="font-semibold text-xl text-gray-900">{{ props.scholars.length }}</span>
              </div>
            </div>

            <div class="bg-white w-full sm:w-auto border border-gray-200 rounded-lg p-5 flex items-center gap-4">
              <!-- Icon -->
              <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <font-awesome-icon :icon="['fas', 'users']" class="text-2xl" />
              </div>

              <!-- Text Content -->
              <div class="flex flex-col">
                <span class="font-normal text-base text-gray-600">Total Active Scholarships</span>
                <span class="font-semibold text-xl text-gray-900">{{ props.scholars.length }}</span>
              </div>
            </div>
          </div>


          <!-- Search Bar -->
          <div class="flex items-center justify-end w-full sm:w-auto sm:max-w-md">
            <div class="relative w-full">
              <input type="text" v-model="searchQuery" placeholder="Search grantee..."
                class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" />
              <span class="absolute right-3 top-2.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <!-- table -->
        <!-- Empty state message when no students are available -->
        <div class="w-full mt-5">
          <div v-if="!scholars || scholars.length === 0"
            class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
            <font-awesome-icon :icon="['fas', 'graduation-cap']"
              class="text-blue-600 text-2xl flex-shrink-0 w-16 h-16" />
            <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">No Scholar Available</h3>
          </div>

          <div v-else
            class="relative overflow-x-auto border border-gray-200 dark:border-gray-600 scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded rounded-lg w-full">
            
            <!-- Horizontal scroll container -->
            <div class="overflow-x-auto w-full">
              <table class="table-auto w-full whitespace-nowrap">
                <!-- head -->
                <thead class="sticky top-0 z-10 shadow-sm bg-white">
                  <tr class="font-normal text-xs uppercase">
                    <th class="border-x border-gray-100 px-4 py-2">URScholar ID</th>
                    <th class="border-x border-gray-100 px-4 py-2">Scholar</th>
                    <th class="border-x border-gray-100 px-4 py-2">Course</th>
                    <th class="border-x border-gray-100 px-4 py-2">Campus</th>
                    <th class="border-x border-gray-100 px-4 py-2">Scholarship</th>
                    <th class="border-x border-gray-100 px-4 py-2">Grant</th>
                    <th class="border-x border-gray-100 px-4 py-2">Email</th>
                    <th class="border-x border-gray-100 px-4 py-2">Phone</th>
                    <th class="border-x border-gray-100 px-4 py-2">Status</th>
                    <th class="sticky right-0 z-30 bg-white px-6 py-2 border-l border-gray-200 text-sm font-semibold text-center">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="scholar in filteredScholars" :key="scholar.id" class="text-sm bg-white">
                      <td class="px-10 py-8">{{ scholar.urscholar_id }}</td>
                      <td class="px-10">
                        <div class="flex items-center gap-3">
                          <div class="avatar">
                            <div class="mask rounded-full h-12 w-12">
                              <img :src="`/storage/user/profile/${scholar.user?.picture}`" alt="Profile Picture"
                                class="w-full h-full object-cover">
                            </div>
                          </div>
                          <div>
                            <div class="font-normal">
                              {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                              <font-awesome-icon v-if="scholar.sex === 'Male'" :icon="['fas', 'mars']"
                                class="text-blue-500" />
                              <font-awesome-icon v-if="scholar.sex === 'Female'" :icon="['fas', 'venus']"
                                class="text-pink-500" />
                            </div>

                            <div class="text-sm opacity-50">
                              <!-- {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }} -->
                              <span> <font-awesome-icon :icon="['far', 'calendar']"
                                  class="mr-1 text-gray-500" /> {{ formatDate(scholar.birthdate) }}</span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-10 items-center">
                        <span class="material-symbols-rounded text-sm text-gray-400 mr-1">
                          local_library
                        </span> {{ scholar.course.name }}
                        <br />
                        <span class="badge badge-ghost badge-base">{{ scholar.year_level }}{{
                          getYearSuffix(scholar.year_level) }} year</span>
                      </td>
                      <td class="px-10">
                        {{ scholar.campus.name }}
                      </td>
                      <td class="px-10">
                       Tulong Dunong
                        <br />
                        <span class="badge badge-ghost badge-sm">{{ scholar.grant }}</span>
                      </td>
                      <td class="px-10">
                        Batch {{ scholar.batch ? scholar.batch.batch_no : 'N/A' }}
                        <br />
                        <span class="badge badge-ghost badge-sm">1st sem - 2023-2024</span>
                      </td>
                      <td class="px-5">
                        <font-awesome-icon :icon="['fas', 'at']" class="mr-1 text-gray-500" /> {{ scholar.email ?
                          scholar.email : 'dummy@gmail.com' }}
                      </td>
                      <td class="px-5">
                        <font-awesome-icon :icon="['fas', 'phone']" class="mr-1 text-gray-500" /> 0493245293
                      </td>
                      <td class="px-5">
                        <span :class="{
                          'bg-blue-100 text-blue-800 dark:bg-gray-700 dark:text-blue-400 border border-blue-400': scholar.status === 'Verified',
                          'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border border-red-400': scholar.status !== 'Verified'
                        }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                          {{ scholar.status }}
                        </span>
                      </td>
                      <td class="sticky right-0 z-20 bg-white px-6 py-2 border-gray-200 text-center"
                          style="box-shadow: -4px 0 6px -2px rgba(0,0,0,1);">
                        <Link :href="route('scholars.scholar_information')">
                          <button
                            class="p-2 border bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            aria-label="View Details"
                          >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </button>
                        </Link>
                      </td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, computed, onBeforeMount, reactive } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Papa from 'papaparse';

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
  Papa,
};


// Props definition with user type and coordinator campus
const props = defineProps({
  scholarship: Object,
  scholars: Array,
  userType: {
    type: String,
    required: true,
    validator: (value) => ['super_admin', 'coordinator'].includes(value)
  },
  coordinatorCampus: {
    type: String,
    required: false,
    default: null
  }
});

// Computed property for filtered scholars
// Add search functionality
const searchQuery = ref('');

// Replace the existing filteredScholars computed property with this:
const filteredScholars = computed(() => {
  if (!props.scholars) return [];

  let filtered = props.scholars;  // No filtering by userType anymore

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();

    filtered = filtered.filter(scholar =>
      scholar.first_name?.toLowerCase().includes(query) ||
      scholar.last_name?.toLowerCase().includes(query) ||
      scholar.middle_name?.toLowerCase().includes(query) ||
      scholar.email?.toLowerCase().includes(query) ||
      scholar.course?.toLowerCase().includes(query) ||
      scholar.campus?.toLowerCase().includes(query) ||
      scholar.grant?.toLowerCase().includes(query)
    );
  }

  return filtered.sort((a, b) => (a.status === 'Verified' ? -1 : 1));
});

const getYearSuffix = (year) => {
  if (year === 1) return "st";
  if (year === 2) return "nd";
  if (year === 3) return "rd";
  return "th";
};

const formatDate = (dateString) => {
  if (!dateString) return "N/A"; // Handle empty/null values
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    month: "short",
    day: "2-digit",
    year: "numeric",
  });
};

const expandedRows = ref([]);
const showPanel = ref(false);
const previewData = ref([]);
const headers = ref([]);
const error = ref('');

const form = useForm({
  file: null,
});

function expandAll() {
  expandedRows.value = products.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
}

function collapseAll() {
  expandedRows.value = null;
}

const toggleRow = (index) => {
  const position = expandedRows.value.indexOf(index);
  if (position !== -1) {
    expandedRows.value.splice(position, 1);
  } else {
    expandedRows.value.push(index);
  }
};

const toggleCreate = () => {
  showPanel.value = !showPanel.value;
};

const closePanel = () => {
  previewData.value = [];
  headers.value = [];
  document.getElementById('file-upload').value = null;
  showPanel.value = false;
};

const handleFileUpload = (event) => {
  const file = event.files[0];

  if (event && event.files && event.files.length > 0) {
    const file = event.files[0];

    const reader = new FileReader();

    reader.onload = function (e) {
      Papa.parse(e.target.result, {
        header: true,
        complete: (results) => {
          if (results.data && results.data.length > 0) {
            const filteredData = results.data.filter(row =>
              Object.values(row).some(value => value !== '')
            );

            if (filteredData.length > 0) {
              headers.value = Object.keys(filteredData[0]);
              previewData.value = filteredData;
              error.value = '';
              console.log('Processed data:', previewData.value);
            } else {
              error.value = 'No valid data found in the file';
              previewData.value = [];
              headers.value = [];
            }
          } else {
            error.value = 'No data found in the file';
            previewData.value = [];
            headers.value = [];
          }
        },
        error: (err) => {
          error.value = 'Error parsing CSV: ' + err.message;
          previewData.value = [];
          headers.value = [];
        }
      });
    };
    reader.readAsText(file);
  }
};

const clearPreview = () => {
  previewData.value = [];
  headers.value = [];
};

const onUpload = async (event) => {
  form.file = event.files[0];

  const response = await form.post(`/scholarships/${props.scholarship.id}/upload`);

  if (response.ok) {
    headers.value = [];
    previewData.value = [];
    error.value = "";
    document.getElementById('file-upload').value = null;
  } else {
    error.value = "Failed to upload file. Please try again.";
  }
};

const uploadCSV = () => {
  form.post(`/scholarships/${props.scholarship.id}/upload`, {
    preserveScroll: true,
  });
};
</script>

<style>
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>