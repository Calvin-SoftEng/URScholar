<template>
  <div ref="wrapper" class="relative w-full">
    <!-- Label -->
    <label v-if="label" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
      {{ label }}
    </label>

    <!-- Display box -->
    <div
      @click="toggle"
      class="flex justify-between items-center w-full cursor-pointer px-4 py-2.5
             shadow-sm transition-all duration-150 ease-in-out focus:ring-2
             
            bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
    >
      <span>{{ selectedLabel || placeholder }}</span>
      <svg
        class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform duration-200"
        :class="{ 'rotate-180': open }"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="2"
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
      </svg>
    </div>

    <!-- Dropdown -->
    <transition name="fade">
      <ul
        v-if="open"
        class="absolute z-50 mt-1 w-full max-h-48 overflow-auto rounded-lg border border-gray-200 dark:border-gray-700
               bg-white dark:bg-gray-900 shadow-lg text-sm"
      >
        <li
          v-for="option in options"
          :key="option.value ?? option.id"
          @click="select(option)"
          class="px-4 py-2 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900 text-gray-700 dark:text-gray-100 transition"
          :class="{ 'bg-blue-100 dark:bg-blue-800': optionValue(option) === modelValue }"
        >
          {{ optionLabel(option) }}
        </li>
      </ul>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => []
  },
  label: String,
  placeholder: {
    type: String,
    default: 'Select an option'
  },
  labelKey: {
    type: String,
    default: 'name'
  },
  valueKey: {
    type: String,
    default: 'id'
  }
})

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const wrapper = ref(null)

const toggle = () => (open.value = !open.value)
const close = () => (open.value = false)

const optionLabel = (option) => option[props.labelKey] ?? option.label ?? option
const optionValue = (option) => option[props.valueKey] ?? option.value ?? option

const selectedLabel = computed(() => {
  const found = props.options.find((o) => optionValue(o) === props.modelValue)
  return found ? optionLabel(found) : ''
})

const select = (option) => {
  emit('update:modelValue', optionValue(option))
  close()
}

// Detect clicks outside the component to close dropdown
const onClickOutside = (e) => {
  if (wrapper.value && !wrapper.value.contains(e.target)) close()
}
onMounted(() => document.addEventListener('click', onClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
