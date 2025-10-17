<template>
  <div ref="pickerWrapper" class="relative w-full">
    <label v-if="label" class="block mb-1 text-sm font-medium text-gray-900 dark:text-gray-200">
      {{ label }}
    </label>

    <!-- Input -->
    <div
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      @click.stop="togglePicker"
    >
      {{ displayDate || 'Select date' }}
    </div>

    <!-- Popup Picker -->
    <div
      v-if="isOpen"
      class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-3"
      @click.stop
    >
      <!-- Year Selection -->
      <div v-if="step === 'year'" class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto">
        <button
          v-for="y in years"
          :key="y"
          @click="selectYear(y)"
          class="py-1 text-sm rounded hover:bg-blue-100 dark:hover:bg-blue-900 dark:text-white w-full"
        >
          {{ y }}
        </button>
      </div>

      <!-- Month Selection -->
      <div v-else-if="step === 'month'" class="grid grid-cols-3 gap-2">
        <button
          v-for="(m, index) in months"
          :key="index"
          @click="selectMonth(index + 1)"
          class="py-1 text-sm rounded hover:bg-blue-100 dark:hover:bg-blue-900 dark:text-white w-full"
        >
          {{ m }}
        </button>
      </div>

      <!-- Day Selection -->
      <div v-else-if="step === 'day'" class="grid grid-cols-7 gap-1 text-center">
        <button
          v-for="d in daysInMonth"
          :key="d"
          @click="selectDay(d)"
          class="p-1 text-sm rounded hover:bg-blue-100 dark:hover:bg-blue-900 dark:text-white w-full"
        >
          {{ d }}
        </button>
      </div>

      <!-- Navigation -->
      <div class="mt-2 flex justify-between items-center text-xs">
        <button
          v-if="step !== 'year'"
          @click="goBack"
          class="text-blue-600 hover:underline"
        >
          ← Back
        </button>
        <button @click="closePicker" class="text-gray-500 hover:underline dark:text-white">
          Close ✕
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' },
  label: { type: String, default: '' }
})
const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const step = ref('year')
const year = ref('')
const month = ref('')
const day = ref('')
const pickerWrapper = ref(null)

const currentYear = new Date().getFullYear()
const years = Array.from({ length: 126 }, (_, i) => currentYear - i)
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

const daysInMonth = computed(() => {
  if (!year.value || !month.value) return []
  const days = new Date(year.value, month.value, 0).getDate()
  return Array.from({ length: days }, (_, i) => i + 1)
})

const displayDate = computed(() => {
  if (!year.value || !month.value || !day.value) return ''
  const m = String(month.value).padStart(2, '0')
  const d = String(day.value).padStart(2, '0')
  return `${year.value}-${m}-${d}`
})

watch(displayDate, (newVal) => emit('update:modelValue', newVal))

const togglePicker = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value) step.value = 'year'
}
const closePicker = () => (isOpen.value = false)

// Clicking outside closes picker
const handleClickOutside = (event) => {
  if (pickerWrapper.value && !pickerWrapper.value.contains(event.target)) {
    isOpen.value = false
  }
}
onMounted(() => document.addEventListener('click', handleClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside))

// Step sequence
const selectYear = (y) => {
  year.value = y
  step.value = 'month'
}
const selectMonth = (m) => {
  month.value = m
  step.value = 'day'
}
const selectDay = (d) => {
  day.value = d
  isOpen.value = false // closes only after day chosen
}
const goBack = () => {
  if (step.value === 'month') step.value = 'year'
  else if (step.value === 'day') step.value = 'month'
}

// Prefill existing date
if (props.modelValue) {
  const [y, m, d] = props.modelValue.split('-').map(Number)
  if (y && m && d) {
    year.value = y
    month.value = m
    day.value = d
  }
}
</script>
