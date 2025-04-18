<template>
    <div class="bg-white w-full flex-grow min-h-0 p-5 gap-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col overflow-hidden">

        <!-- Header with Filter -->
        <div class="flex justify-between items-center">
            <span class="font-poppins font-semibold text-xl dark:text-dtext">Recent Submissions</span>

            <!-- Scholarship Type Filter -->
            <div class="flex items-center gap-3">

                <!-- Date Filter Buttons -->
                <div class="flex border rounded-lg overflow-hidden">
                    <button v-for="filter in ['day', 'week', 'month']" :key="filter"
                        @click="selectedDateFilter = filter"
                        class="px-4 py-2 text-sm font-medium border-r last:border-r-0 dark:bg-dprimary dark:text-white"
                        :class="{
                            'bg-blue-600 dark:bg-dtext text-white': selectedDateFilter === filter,
                            'hover:bg-gray-200 dark:hover:bg-dprimary': selectedDateFilter !== filter
                        }">
                        {{ filter.charAt(0).toUpperCase() + filter.slice(1) }}
                    </button>
                </div>
                <!-- Scholarship Type Filter -->
                <select v-model="selectedScholarshipType"
                    class="p-2 text-sm border border-gray-200 rounded-lg dark:bg-dprimary dark:text-white">
                    <option value="Grant-Based">Grant-Based</option>
                    <option value="one-time">One-Time Payment</option>
                </select>
            </div>
        </div>

        <!-- Analytics Section -->
        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
           
            <div class="p-6 bg-white dark:bg-dcontainer rounded-lg shadow-lg flex flex-col justify-center items-center border border-gray-200 dark:border-gray-600">
                <span class="text-sm text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Applicants</span>
                <p class="text-3xl font-extrabold text-primary dark:text-lprimary mt-2">{{ totalApplicants }}</p>
            </div>


            <div class="p-6 bg-white dark:bg-dcontainer rounded-lg shadow-lg flex flex-col justify-center items-center border border-gray-200 dark:border-gray-600">
                <span class="text-sm text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Verified Scholars</span>
                <p class="text-3xl font-extrabold text-primary dark:text-lprimary mt-2">{{ totalVerifiedScholars }}</p>
            </div>
        </div> -->


        <!-- Scholarship List Container -->
        <div class="flex flex-col gap-3 flex-grow relative">

            <!-- Table -->
            <div class="overflow-x-auto font-poppins border rounded-lg">
                <table class="table rounded-lg w-full">
                    <!-- Head -->
                    <thead class="bg-gray-50 dark:bg-dprimary">
                        <tr class="text-xs uppercase dark:text-dtext tracking-wide">
                            <th>URScholar ID</th>
                            <th>Name</th>
                            <th>Scholarship</th>
                            <th v-if="selectedScholarshipType === 'Need-Based'">Submitted Requirements</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="scholar in latestSubmissions" :key="scholar.id" class="text-sm dark:text-dtext">
                            <td>{{ scholar.urscholar_id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-normal">
                                            {{ scholar.first_name }} {{ scholar.last_name }}
                                            <span v-if="scholar.status === 'Verified'"
                                                class="material-symbols-rounded text-sm text-blue-600">verified</span>
                                        </div>
                                        <div class="text-sm opacity-50">{{ scholar.campus }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ scholar.scholarship_name }}</td>
                            <td v-if="selectedScholarshipType === 'Need-Based'">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            :style="{ width: scholar.progress + '%' }"></div>
                                    </div>
                                    <span>{{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}</span>
                                </div>
                            </td>
                            <td>
                                <span :class="{
                                    'text-green-600': scholar.status === 'Verified',
                                    'text-yellow-500': scholar.status === 'Pending',
                                    'text-red-600': scholar.status === 'Rejected'
                                }">
                                    {{ scholar.status }}
                                </span>
                            </td>
                            <td>{{ formatDate(scholar.submission_date) }}</td>
                            <td>{{ scholar.remarks || 'N/A' }}</td>
                            <td>
                                <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr v-if="latestSubmissions.length === 0">
                            <td :colspan="selectedScholarshipType === 'Need-Based' ? 8 : 7" class="text-center py-4 dark:text-dtext">No
                                recent submissions found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
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
                    <button @click="prevPage" :disabled="currentPage === 1"
                        class="px-4 h-10 text-base font-medium text-white bg-blue-800 rounded-s hover:bg-blue-900 dark:bg-gray-800 dark:hover:bg-gray-700"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                        Prev
                    </button>
                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="px-4 h-10 text-base font-medium text-white bg-blue-800 border-0 border-s border-gray-700 rounded-e hover:bg-blue-900 dark:bg-gray-800 dark:hover:bg-gray-700"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    scholars: {
        type: Array,
        default: () => []
    }
});

// Filters
const selectedDateFilter = ref('day');
const selectedScholarshipType = ref('Grant-Based');

// Get latest submissions from props or page data
const page = usePage();
const scholars = computed(() => {
    return props.scholars.length > 0
        ? props.scholars
        : (page.props.scholars || []);
});

// Filter scholars based on scholarship type
const latestSubmissions = computed(() => {
    const dateFiltered = filterByDate(scholars.value);

    // Apply scholarship type filter
    return selectedScholarshipType.value === "Grant-Based"
        ? dateFiltered.filter(scholar => scholar.scholarshipType === "Grant-Based")
        : dateFiltered.filter(scholar => scholar.scholarshipType === "One-time Payment");
});

// Function to filter scholars by date
const filterByDate = (scholars) => {
    if (!scholars || scholars.length === 0) return [];

    const now = new Date();
    let startDate = new Date();

    switch (selectedDateFilter.value) {
        case 'day':
            startDate.setDate(now.getDate() - 1);
            break;
        case 'week':
            startDate.setDate(now.getDate() - 7);
            break;
        case 'month':
            startDate.setMonth(now.getMonth() - 1);
            break;
    }

    return scholars.filter(scholar => {
        const submissionDate = new Date(scholar.submission_date);
        return submissionDate >= startDate && submissionDate <= now;
    }).slice(0, 5); // Get top 5
};

// Analytics
const totalApplicants = computed(() => scholars.value.length);
const totalVerifiedScholars = computed(() => scholars.value.filter(scholar => scholar.status === "Verified").length);

// Pagination
const currentPage = ref(1);
const itemsPerPage = 5;
const totalScholars = computed(() => scholars.value.length);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const endIndex = computed(() => Math.min(currentPage.value * itemsPerPage, totalScholars.value));
const totalPages = computed(() => Math.ceil(totalScholars.value / itemsPerPage));

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};
const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};

// Format date helper
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    }).format(date);
};
</script>