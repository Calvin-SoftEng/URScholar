<template>
    <div>

        <!-- Analytics Section -->
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 border rounded-lg shadow-sm dark:bg-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Applicants</span>
                <p class="text-2xl font-semibold">43242</p>
            </div>
            <div class="p-4 border rounded-lg shadow-sm dark:bg-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Verified Scholars</span>
                <p class="text-2xl font-semibold">4324</p>
            </div>
        </div>

        <!-- Scholarship List Container -->
        <div class="flex flex-col gap-3 flex-grow relative">

            <!-- Table -->
            <div class="overflow-x-auto font-poppins border rounded-lg">
                <table class="table rounded-lg w-full">
                    <!-- Head -->
                    <thead class="bg-gray-100">
                        <tr class="text-xs uppercase">
                            <th>URScholar ID</th>
                            <th>Name</th>
                            <th>Scholarship</th>
                            <!-- <th v-if="selectedScholarshipType === 'Need-Based'">Submitted Requirements</th> -->
                            <th>Status</th>
                            <th>Date Submitted</th>
                            <th>Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-sm">
                            <td>fefa</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-normal">
                                            feafe
                                            <!-- <span v-if="scholar.status === 'Verified'"
                                                class="material-symbols-rounded text-sm text-blue-600">verified</span> -->
                                        </div>
                                        <div class="text-sm opacity-50">Bianngonan</div>
                                    </div>
                                </div>
                            </td>
                            <td>RDP</td>
                            <!-- <td v-if="selectedScholarshipType === 'Need-Based'">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            :style="{ width: scholar.progress + '%' }"></div>
                                    </div>
                                    <span>{{ scholar.submittedRequirements }}/{{ scholar.totalRequirements }}</span>
                                </div>
                            </td> -->
                            <!-- <td>
                                <span :class="{
                                    'text-green-600': scholar.status === 'Verified',
                                    'text-yellow-500': scholar.status === 'Pending',
                                    'text-red-600': scholar.status === 'Rejected'
                                }">
                                    {{ scholar.status }}
                                </span>
                            </td> -->
                            <td>fefeaf</td>
                            <td>feafae</td>
                            <td>
                                <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    View
                                </button>
                            </td>
                        </tr>
                        <!-- <tr v-if="latestSubmissions.length === 0">
                            <td :colspan="selectedScholarshipType === 'Need-Based' ? 8 : 7" class="text-center py-4">No
                                recent submissions found</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script setup>

</script>