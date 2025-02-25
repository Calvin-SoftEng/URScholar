<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  scholarship: Object,
  batches: Array,
  schoolyear: Object,
  selectedSem: String
})

const selectedYear = ref(props.schoolyear?.id || '')
const selectedSemester = ref(props.selectedSem || '')
const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default

const semesters = ['First', 'Second', 'Summer']

const toggleBatch = (batchId) => {
  if (expandedBatches.value.has(batchId)) {
    expandedBatches.value.delete(batchId)
  } else {
    expandedBatches.value.add(batchId)
  }
}

watch([selectedYear, selectedSemester], ([year, sem]) => {
  router.get(route('scholarships.show', props.scholarship.id), {
    selectedYear: year,
    selectedSem: sem
  }, {
    preserveState: true,
    preserveScroll: true
  })
})
</script>

<template>
  <div class="p-6">
    <!-- Header Section -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold mb-2">{{ scholarship.name }}</h1>
      <div class="text-gray-600">
        <p>Type: {{ scholarship.scholarshipType }}</p>
        <p>Status: {{ scholarship.status }}</p>
      </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
      <div class="w-64">
        <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
        <select
          v-model="selectedYear"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option value="">All School Years</option>
          <!-- Add your school years options here -->
        </select>
      </div>

      <div class="w-64">
        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
        <select
          v-model="selectedSemester"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option value="">All Semesters</option>
          <option v-for="sem in semesters" :key="sem" :value="sem">
            {{ sem }} Semester
          </option>
        </select>
      </div>
    </div>

    <!-- Batches and Scholars -->
    <div class="space-y-6">
      <div v-for="batch in batches" :key="batch.id" class="bg-white rounded-lg shadow">
        <!-- Batch Header -->
        <div 
          @click="toggleBatch(batch.id)"
          class="p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50"
        >
          <div>
            <h2 class="text-lg font-semibold">Batch {{ batch.batch_no }}</h2>
            <p class="text-sm text-gray-600">
              {{ batch.school_year }} - {{ batch.semester }} Semester
              <span class="ml-2 text-gray-500">
                ({{ batch.scholars.length }} scholars)
              </span>
            </p>
          </div>
          <button class="p-2 hover:bg-gray-100 rounded">
            <svg 
              class="w-5 h-5 transform transition-transform"
              :class="{ 'rotate-180': expandedBatches.has(batch.id) }"
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
        </div>

        <!-- Scholars Table -->
        <div v-show="expandedBatches.has(batch.id)" class="border-t">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HEI</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="scholar in batch.scholars" :key="scholar.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ `${scholar.last_name}, ${scholar.first_name} ${scholar.middle_name}` }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ scholar.course }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ scholar.hei_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="{
                      'px-2 py-1 text-xs font-medium rounded-full': true,
                      'bg-green-100 text-green-800': scholar.status === 'Verified',
                      'bg-yellow-100 text-yellow-800': scholar.status === 'Unverified'
                    }"
                  >
                    {{ scholar.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button 
                    class="text-indigo-600 hover:text-indigo-900"
                    @click="router.visit(route('scholars.show', scholar.id))"
                  >
                    View
                  </button>
                </td>
              </tr>

              <tr v-if="batch.scholars.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                  No scholars in this batch
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- No Batches Message -->
      <div v-if="batches.length === 0" class="text-center py-8 text-gray-500">
        No batches found for the selected filters
      </div>
    </div>
  </div>
</template>