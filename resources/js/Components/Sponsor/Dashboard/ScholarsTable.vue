<template>
    <div class="space-y-5">

        <div class="flex flex-row justify-between items-center">
            <span class="font-poppins font-semibold text-xl dark:text-dtext">Scholars</span>
            <Link :href="route('sponsor.scholarship')" class="flex justify-between items-center">

                    <!-- Other content of your card goes here -->
                    <!-- expand -->
                    <font-awesome-icon :icon="['fas', 'up-right-and-down-left-from-center']" class="ml-auto" />

            </Link>

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
                                        </div>
                                        <div class="text-sm opacity-50">Bianngonan</div>
                                    </div>
                                </div>
                            </td>
                            <td>RDP</td>
                            <td>fefeaf</td>
                            <td>feafae</td>
                            <td>
                                <button class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

</script>