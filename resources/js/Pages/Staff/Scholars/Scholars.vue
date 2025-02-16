<template>
  <AuthenticatedLayout>
    <div class="flex-1 h-full max-w-full overflow-hidden">
      <!-- Main content container with max width constraint -->
      <div class="w-full h-full p-5 bg-[#F8F8FA]">
        <div class="w-full p-3 rounded-xl">
          <!-- Breadcrumbs navigation -->
          <div class="breadcrumbs text-sm text-gray-400 mb-5">
            <ul>
              <li>
                <a>
                  <span class="material-symbols-rounded mr-2" style="color: #0D47A1; font-size: 20px;">
                    dashboard
                  </span>
                  Home
                </a>
              </li>
              <li>
                <a>
                  <span class="text-blue-400 font-semibold">Scholars</span>
                </a>
              </li>
            </ul>
          </div>

          
          <!-- Header section with title and search -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <!-- <h1 class="text-2xl sm:text-4xl font-poppins font-extrabold text-[darkblue] text-left">
              <span>{{ scholarship.name }}</span> Scholars 2024-2025
            </h1> -->

            <div class="bg-white w-full sm:w-auto border border-gray-200 rounded-lg p-5 flex items-center gap-4">
              <!-- Icon -->
              <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <font-awesome-icon :icon="['fas', 'users']" class="text-2xl" />
              </div>

              <!-- Text Content -->
              <div class="flex flex-col">
                <span class="font-normal text-base text-gray-600">Total Active Scholars</span>
                <span class="font-semibold text-xl text-gray-900">1,000</span>
              </div>
            </div>


            <!-- Search Bar -->
            <div class="flex items-center justify-end w-full sm:w-auto sm:max-w-md">
              <div class="relative w-full">
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Search scholars..."
                  class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                />
                <span class="absolute right-3 top-2.5 text-gray-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <!-- table -->
          <div class="bg-white overflow-x-auto text-black font-poppins border rounded-lg overflow-y-scroll scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-[70vh]">
            <table class="table rounded-lg">
              <!-- head -->
              <thead class="justify-center items-center sticky top-[-1px] z-10 shadow-sm bg-white">
                <tr class="font-normal text-sm uppercase">
                  <th class="border-x border-gray-100">URScholar ID</th>
                  <th class="border-x border-gray-100">Scholar</th>
                  <th class="border-x border-gray-100">Course</th>
                  <th class="border-x border-gray-100">Campus</th>
                  <th class="border-x border-gray-100">Grant</th>
                  <th class="border-x border-gray-100">Email</th>
                  <th class="border-x border-gray-100">Status</th>
                  <th class="border-x border-gray-100"></th>
                </tr>
              </thead>
              <tbody>
                <!-- row 1 -->
                <tr v-for="scholar in filteredScholars" :key="scholar.id" class="text-sm">
                  <td class="px-5">test1</td>
                  <td class="px-5">
                    <div class="flex items-center gap-3">
                      <div class="avatar">
                        <div class="mask rounded-full h-12 w-12">
                          <img src="../../../../assets/images/no_userpic.png" alt="Avatar Tailwind CSS Component" />
                        </div>
                      </div>
                      <div>
                        <div class="font-normal">
                          {{ scholar.last_name }}, {{ scholar.first_name }} {{ scholar.middle_name }}
                          <font-awesome-icon v-if="scholar.sex === 'Male'" :icon="['fas', 'mars']" class="text-blue-500" />
                          <font-awesome-icon v-if="scholar.sex === 'Female'" :icon="['fas', 'venus']" class="text-pink-500" />
                        </div>

                        <div class="text-sm opacity-50">
                          <!-- {{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year, {{ scholar.course }} -->
                          {{ scholar.sex }}, <span> <font-awesome-icon :icon="['far', 'calendar']" class="mr-1 text-gray-500" /> {{ formatDate(scholar.birthdate) }}</span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-5 items-center">
                    <span class="material-symbols-rounded text-sm text-gray-400 mr-1">
                    local_library
                    </span> {{ scholar.course }}
                    <br />
                    <span class="badge badge-ghost badge-base">{{ scholar.year_level }}{{ getYearSuffix(scholar.year_level) }} year</span>
                  </td>
                  <td class="px-5">
                    {{ scholar.campus }}
                  </td>
                  <td class="px-5">
                    Batch {{ scholar.batch ? scholar.batch.batch_no : 'N/A' }}
                    <br />
                    <span class="badge badge-ghost badge-base">{{ scholar.grant }}</span>
                  </td>
                  <td class="px-5">
                    <font-awesome-icon :icon="['fas', 'at']" class="mr-1 text-gray-500"/> {{ scholar.email ? scholar.email : 'dummy@gmail.com' }}
                  </td>
                  <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-300">
                    {{ scholar.year_level }}
                  </td>
                  <td class="whitespace-nowrap px-6 py-4 items-center justify-center">
                    <span :class="{
                      'bg-blue-100 text-blue-800 dark:bg-gray-700 dark:text-blue-400 border border-blue-400': scholar.status === 'Verified',
                      'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border border-red-400': scholar.status !== 'Verified'
                    }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                      {{ scholar.status }}
                    </span>
                  </td>
                  <th>
                    <Link :href="route('scholarships.scholar_scholarship_details')">
                    <button class="p-2 border bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                      aria-label="View Details">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                    </Link>
                  </th>
                </tr>
              </tbody>
            </table>
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

// Update the filteredScholars computed property to include search
const filteredScholars = computed(() => {
  if (!props.scholars) return [];

  let filtered = props.userType === 'super_admin' 
    ? props.scholars 
    : props.userType === 'coordinator' && props.coordinatorCampus
      ? props.scholars.filter(scholar => scholar.campus === props.coordinatorCampus)
      : [];

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

  // Prioritize scholars with "Verified" status
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