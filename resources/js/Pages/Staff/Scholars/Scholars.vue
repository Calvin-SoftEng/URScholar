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
              <span class="text-blue-400 font-semibold">Grantees</span>
            </li>
          </ul>
        </div>

        <!-- Header section with title and search -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
          <div class="flex flex-row items-center gap-4">
            <div class="bg-white w-full sm:w-auto border border-gray-200 rounded-lg p-5 flex items-center gap-4">
              <!-- Icon -->
              <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <font-awesome-icon :icon="['fas', 'award']" class="text-2xl" />
              </div>

              <!-- Text Content -->
              <div class="flex flex-col">
                <span class="font-normal text-base text-gray-600">Total Active Grantees</span>
                <span class="font-semibold text-xl text-gray-900">{{ filteredGrantees.length }}</span>
              </div>
            </div>

            <div class="bg-white w-full sm:w-auto border border-gray-200 rounded-lg p-5 flex items-center gap-4">
              <!-- Icon -->
              <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <font-awesome-icon :icon="['fas', 'users']" class="text-2xl" />
              </div>

              <!-- Text Content -->
              <div class="flex flex-col">
                <span class="font-normal text-base text-gray-600">Total Scholars</span>
                <span class="font-semibold text-xl text-gray-900">{{ uniqueScholarsCount }}</span>
              </div>
            </div>
          </div>

          <div class="flex flex-row items-center gap-4">
            <div class="flex flex-row items-center gap-3 w-full sm:w-auto">
              <span class="mb-2 text-xs">Filter:</span>
              <div class="grid grid-cols-4 gap-4 mb-4">
                <div>
                  <label for="scholarship-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Scholarship
                  </label>
                  <Select v-model="selectedScholarship">
                    <SelectTrigger class="w-full border">
                      <SelectValue :placeholder="displayedScholarshipName" />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectGroup>
                        <SelectItem value="all">All Scholarships</SelectItem>
                        <SelectItem v-for="scholarship in props.scholarships" :key="scholarship.id"
                          :value="scholarship.id">
                          {{ scholarship.name }}
                        </SelectItem>
                      </SelectGroup>
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <label for="year-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">School
                    Year</label>
                  <Select v-model="selectedSchoolYear">
                    <SelectTrigger class="w-full border">
                      <SelectValue
                        :placeholder="selectedSchoolYear ? getAcademicYearName(selectedSchoolYear) : 'All Years'" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectGroup>
                        <SelectItem :value="null">All Years</SelectItem>
                        <SelectItem v-for="year in academicYearOptions" :key="year.id" :value="year.id">
                          {{ year.name }}
                        </SelectItem>
                      </SelectGroup>
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <label for="semester-select"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Semester</label>
                  <Select v-model="selectedSemester">
                    <SelectTrigger class="w-full border">
                      <SelectValue
                        :placeholder="selectedSemester ? (selectedSemester === '1st' ? 'First Semester' : 'Second Semester') : 'All Semesters'" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectGroup>
                        <SelectItem :value="null">All Semesters</SelectItem>
                        <SelectItem value="1st">First Semester</SelectItem>
                        <SelectItem value="2nd">Second Semester</SelectItem>
                      </SelectGroup>
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <label for="campus-select"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Campus</label>
                  <Select v-model="selectedCampus">
                    <SelectTrigger class="w-full border">
                      <SelectValue :placeholder="selectedCampus ? getCampusName(selectedCampus) : 'All Campuses'" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectGroup>
                        <SelectItem :value="null">All Campuses</SelectItem>
                        <SelectItem v-for="camp in props.campus" :key="camp.id" :value="camp.id">
                          {{ camp.name }}
                        </SelectItem>
                      </SelectGroup>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>
            <div>
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
          </div>
        </div>

        <!-- table -->
        <!-- Empty state message when no grantees are available -->
        <div class="w-full mt-5">
          <div v-if="!filteredGrantees || filteredGrantees.length === 0"
            class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
            <font-awesome-icon :icon="['fas', 'award']" class="text-blue-600 text-2xl flex-shrink-0 w-16 h-16" />
            <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">No Grantees Available</h3>
          </div>

          <div v-else
            class="relative overflow-x-auto border border-gray-200 dark:border-gray-600 scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded rounded-lg w-full">

            <!-- Horizontal scroll container -->
            <div class="overflow-x-auto w-full">
              <table class="table-auto w-full whitespace-nowrap">
                <!-- head -->
                <thead class="sticky top-0 z-10 shadow-sm bg-white">
                  <tr class="font-normal text-xs uppercase">
                    <th class="border-x border-gray-100 px-4 py-2">Grantee ID</th>
                    <th class="border-x border-gray-100 px-4 py-2">Scholar</th>
                    <th class="border-x border-gray-100 px-4 py-2">Course</th>
                    <th class="border-x border-gray-100 px-4 py-2">Campus</th>
                    <th class="border-x border-gray-100 px-4 py-2">Scholarship</th>
                    <th class="border-x border-gray-100 px-4 py-2">Batch</th>
                    <th class="border-x border-gray-100 px-4 py-2">Academic Year</th>
                    <th class="border-x border-gray-100 px-4 py-2">Semester</th>
                    <th class="border-x border-gray-100 px-4 py-2">Status</th>
                    <th
                      class="sticky right-0 z-30 bg-white px-6 py-2 border-l border-gray-200 text-sm font-semibold text-center">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="grantee in filteredGrantees" :key="grantee.id" class="text-sm bg-white">
                    <td class="px-10 py-8">{{ grantee.id }}</td>
                    <td class="px-10">
                      <div class="flex items-center gap-3">
                        <div class="avatar">
                          <div class="mask rounded-full h-12 w-12">
                            <img :src="`/storage/user/profile/${grantee.scholar?.user?.picture}`" alt="Profile Picture"
                              class="w-full h-full object-cover">
                          </div>
                        </div>
                        <div>
                          <div class="font-normal">
                            {{ grantee.scholar?.last_name }}, {{ grantee.scholar?.first_name }} {{
                              grantee.scholar?.middle_name }}
                            <font-awesome-icon v-if="grantee.scholar?.sex === 'Male'" :icon="['fas', 'mars']"
                              class="text-blue-500" />
                            <font-awesome-icon v-if="grantee.scholar?.sex === 'Female'" :icon="['fas', 'venus']"
                              class="text-pink-500" />
                          </div>
                          <div class="text-sm opacity-50">
                            <span>URScholar ID: {{ grantee.scholar?.urscholar_id }}</span>
                          </div>
                          <div class="text-sm opacity-50">
                            <span><font-awesome-icon :icon="['far', 'calendar']" class="mr-1 text-gray-500" /> {{
                              formatDate(grantee.scholar?.birthdate) }}</span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-10 items-center">
                      <span class="material-symbols-rounded text-sm text-gray-400 mr-1">
                        local_library
                      </span> {{ grantee.scholar?.course?.name }}
                      <br />
                      <span class="badge badge-ghost badge-base">{{ grantee.scholar?.year_level }}{{
                        getYearSuffix(grantee.scholar?.year_level) }} year</span>
                    </td>
                    <td class="px-10">
                      {{ grantee.scholar?.campus?.name }}
                    </td>
                    <td class="px-10">
                      {{ grantee.scholarship?.name || "Tulong Dunong" }}
                      <br />
                      <span class="badge badge-ghost badge-sm">{{ grantee.scholar?.grant }}</span>
                    </td>
                    <td class="px-10">
                      Batch {{ grantee.batch?.name || grantee.batch?.batch_no }}
                    </td>
                    <td class="px-10">
                      {{ getAcademicYearName(grantee.school_year_id) }}
                    </td>
                    <td class="px-10">
                      {{ grantee.semester === '1st' ? 'First Semester' : 'Second Semester' }}
                    </td>
                    <td class="px-5">
                      <span :class="{
                        'bg-green-100 text-green-800 dark:bg-gray-700 dark:text-green-400 border border-green-400': grantee.status === 'Active',
                        'bg-blue-100 text-blue-800 dark:bg-gray-700 dark:text-blue-400 border border-blue-400': grantee.status === 'Accomplished',
                        'bg-yellow-100 text-yellow-800 dark:bg-gray-700 dark:text-yellow-400 border border-yellow-400': grantee.status === 'Pending',
                        'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border border-red-400': grantee.status === 'Inactive'
                      }" class="text-xs font-medium px-2.5 py-0.5 rounded">
                        {{ grantee.status }}
                      </span>
                      <br>
                      <span class="text-xs font-medium mt-1 inline-block">
                        Scholar Status:
                        <span :class="{
                          'text-green-600': grantee.scholar?.status === 'Verified',
                          'text-red-600': grantee.scholar?.status !== 'Verified'
                        }">
                          {{ grantee.scholar?.status }}
                        </span>
                      </span>
                    </td>
                    <td class="sticky right-0 z-20 bg-white px-6 py-2 border-gray-200 text-center"
                      style="box-shadow: -4px 0 6px -2px rgba(0,0,0,1);">
                      <button @click="() => openGranteeDetails(grantee)"
                        class="p-2 border bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        aria-label="View Details">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </button>
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
import { defineProps, ref, computed, onMounted } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';

const components = {
  DataTable,
  Column,
  Button,
  FileUpload,
};

const openGranteeDetails = (grantee) => {
  router.visit(`/urs-scholars/grantee-information/${grantee.id}`, {
    preserveState: true
  });
};

// Props definition with user type and coordinator campus
const props = defineProps({
  grantees: Array,
  academicYear: Array,
  campus: Array,
  scholarships: Array,
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

// Filter state variables

const selectedScholarship = ref('');
const selectedSchoolYear = ref('');
const selectedSemester = ref('');
const selectedCampus = ref(props.coordinatorCampus ? parseInt(props.coordinatorCampus) : '');
const searchQuery = ref('');

// helper to find the display text
const displayedScholarshipName = computed(() => {
  if (selectedScholarship.value === 'all') return 'All Scholarships'
  const found = props.scholarships.find(
    s => s.id === selectedScholarship.value
  )
  return found ? found.name : 'Select Scholarship'
})

// Create academic year options
const academicYearOptions = computed(() => {
  if (!props.academicYear || props.academicYear.length === 0) return [];

  // Make sure we're properly accessing the school year data
  return props.academicYear
    .filter(year => year.school_year) // Make sure school_year exists
    .map(year => ({
      id: year.school_year_id,
      name: year.school_year.name || year.school_year.year || `${year.school_year_id}`
    }))
    .filter((v, i, a) => a.findIndex(t => t.id === v.id) === i); // Remove duplicates
});

// Count of unique scholars
const uniqueScholarsCount = computed(() => {
  if (!props.grantees) return 0;

  const uniqueScholarIds = new Set();
  props.grantees.forEach(grantee => {
    if (grantee.scholar?.id) {
      uniqueScholarIds.add(grantee.scholar.id);
    }
  });

  return uniqueScholarIds.size;
});

// Get academic year name by ID
const getAcademicYearName = (schoolYearId) => {
  const year = props.academicYear.find(year => year.school_year_id === schoolYearId);
  return year?.school_year?.name || 'Unknown Academic Year';
};

// Get campus name by ID
const getCampusName = (campusId) => {
  const campus = props.campus.find(camp => camp.id === campusId);
  return campus?.name || 'Unknown Campus';
};

// Get scholarship name by ID
const getScholarshipName = (scholarshipId) => {
  const scholarship = props.scholarships.find(s => s.id === scholarshipId);
  return scholarship?.name || 'Unknown Scholarship';
};

// Filtered grantees based on all criteria
const filteredGrantees = computed(() => {
  if (!props.grantees) return [];

  let filtered = props.grantees;

  // Apply scholarship filter
  if (selectedScholarship.value) {
    filtered = filtered.filter(grantee => grantee.scholarship_id === selectedScholarship.value);
  }

  // Apply school year filter
  if (selectedSchoolYear.value) {
    filtered = filtered.filter(grantee => grantee.school_year_id === selectedSchoolYear.value);
  }

  // Apply semester filter
  if (selectedSemester.value) {
    filtered = filtered.filter(grantee => grantee.semester === selectedSemester.value);
  }

  // Apply campus filter
  if (selectedCampus.value) {
    filtered = filtered.filter(grantee => grantee.scholar?.campus_id === selectedCampus.value);
  }

  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();

    filtered = filtered.filter(grantee =>
      grantee.scholar?.first_name?.toLowerCase().includes(query) ||
      grantee.scholar?.last_name?.toLowerCase().includes(query) ||
      grantee.scholar?.middle_name?.toLowerCase().includes(query) ||
      grantee.scholar?.email?.toLowerCase().includes(query) ||
      grantee.scholar?.course?.name?.toLowerCase().includes(query) ||
      grantee.scholar?.campus?.name?.toLowerCase().includes(query) ||
      grantee.scholar?.grant?.toLowerCase().includes(query) ||
      grantee.batch?.name?.toLowerCase().includes(query) ||
      grantee.status?.toLowerCase().includes(query)
    );
  }

  // Sort by grantee status (Active and Accomplished first)
  return filtered.sort((a, b) => {
    const statusOrder = { 'Active': 0, 'Accomplished': 1, 'Pending': 2, 'Inactive': 3 };
    return statusOrder[a.status] - statusOrder[b.status];
  });
});

const getYearSuffix = (year) => {
  if (!year) return "";
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

// If coordinator, pre-select their campus
onMounted(() => {
  if (props.userType === 'coordinator' && props.coordinatorCampus) {
    selectedCampus.value = parseInt(props.coordinatorCampus);
  }
});

const form = useForm({
  file: null,
});

const expandedRows = ref([]);
const showPanel = ref(false);
const previewData = ref([]);
const headers = ref([]);
const error = ref('');

function expandAll() {
  expandedRows.value = filteredGrantees.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
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

  try {
    await form.post(`/grantees/upload`);
    headers.value = [];
    previewData.value = [];
    error.value = "";
    document.getElementById('file-upload').value = null;
  } catch (err) {
    error.value = "Failed to upload file. Please try again.";
  }
};

const uploadCSV = () => {
  form.post(`/grantees/upload`, {
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