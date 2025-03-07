<template>
    <div class="bg-white w-full min-h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">

        <!-- Header with Filter -->
        <div class="flex justify-between items-center">
            <span class="font-poppins font-semibold text-xl dark:text-dtext">Recent Submissions</span>

            <!-- Scholarship Type Filter -->
            <div class="flex items-center gap-3">
                
                <!-- Date Filter Buttons -->
                <div class="flex border rounded-lg overflow-hidden">
                    <button 
                        v-for="filter in ['day', 'week', 'month']" 
                        :key="filter" 
                        @click="selectedDateFilter = filter"
                        class="px-4 py-2 text-sm font-medium border-r last:border-r-0 dark:bg-gray-700 dark:text-white"
                        :class="{
                            'bg-blue-600 text-white': selectedDateFilter === filter,
                            'hover:bg-gray-200 dark:hover:bg-gray-600': selectedDateFilter !== filter
                        }"
                    >
                        {{ filter.charAt(0).toUpperCase() + filter.slice(1) }}
                    </button>
                </div>
                <!-- Scholarship Type Filter -->
                <select v-model="selectedScholarshipType" class="p-2 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="one-time">One-Time Payment</option>
                    <option value="need-based">Need-Based</option>
                </select>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 border rounded-lg shadow-sm dark:bg-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Applicants</span>
                <p class="text-2xl font-semibold">{{ totalApplicants }}</p>
            </div>
            <div class="p-4 border rounded-lg shadow-sm dark:bg-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Verified Scholars</span>
                <p class="text-2xl font-semibold">{{ totalVerifiedScholars }}</p>
            </div>
        </div>

        <!-- Scholarship List Container -->
        <div class="flex flex-col gap-3 flex-grow relative">

            <!-- Table -->
            <div class="overflow-x-auto font-poppins border rounded-lg">
                <table class="table rounded-lg">
                    <!-- Head -->
                    <thead class="bg-gray-100">
                        <tr class="text-xs uppercase">
                            <th>URScholar ID</th>
                            <th>Name</th>
                            <th>Scholarship</th>
                            <th v-if="selectedScholarshipType === 'need-based'">Submitted Requirements</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="scholar in filteredScholars" :key="scholar.id" class="text-sm">
                            <td>{{ scholar.id }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-normal">
                                            {{ scholar.name }}
                                            <span v-if="scholar.status === 'Verified'" class="material-symbols-rounded text-sm text-blue-600">verified</span>
                                        </div>
                                        <div class="text-sm opacity-50">{{ scholar.campus }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>TDP</td>
                            <td v-if="selectedScholarshipType === 'need-based'">
                                {{ scholar.submittedRequirements ? "✔ Submitted" : "❌ Not Submitted" }}
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
                            <td>Kahapon</td>
                            <td>{{ scholar.remarks || "N/A" }}</td>
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
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    scholarships: Array,
    sponsors: Array,
});

const selectedScholarshipType = ref('one-time');

// Example Data
const scholars = ref([
    { id: "URS-001", name: "John Doe", email: "john@example.com", campus: "Main", status: "Verified", submittedRequirements: true, remarks: "Good to go" },
    { id: "URS-002", name: "Jane Smith", email: "jane@example.com", campus: "South", status: "Pending", submittedRequirements: false, remarks: "Waiting for approval" },
    { id: "URS-003", name: "Alice Brown", email: "alice@example.com", campus: "North", status: "Rejected", submittedRequirements: true, remarks: "Incomplete documents" },
]);

// Filtered Scholars based on Scholarship Type
const filteredScholars = computed(() => {
    return selectedScholarshipType.value === "one-time"
        ? scholars.value // Display all applicants
        : scholars.value.filter(scholar => scholar.submittedRequirements); // Show only those who submitted requirements
});

// Analytics
const totalApplicants = computed(() => scholars.value.length);
const totalVerifiedScholars = computed(() => scholars.value.filter(scholar => scholar.status === "Verified").length);

// Pagination (Placeholder logic)
const currentPage = ref(1);
const itemsPerPage = 5;
const totalScholars = computed(() => scholars.value.length);
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const endIndex = computed(() => Math.min(currentPage.value * itemsPerPage, totalScholars.value));

const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};
const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
};
const totalPages = computed(() => Math.ceil(totalScholars.value / itemsPerPage));
</script>