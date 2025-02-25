<script setup>
import { ref } from "vue";

const months = [
  "January", "February", "March", "April", "May", "June", 
  "July", "August", "September", "October", "November", "December"
];

const selectedMonth = ref(null);
const selectedDay = ref(null);
const daysInMonth = ref([]);

const updateDays = () => {
  if (!selectedMonth.value) return;
  const monthIndex = months.indexOf(selectedMonth.value);
  const days = new Date(2024, monthIndex + 1, 0).getDate(); // Leap year-safe
  daysInMonth.value = Array.from({ length: days }, (_, i) => i + 1);
  if (selectedDay.value > days) selectedDay.value = null;
};

const emit = defineEmits(["update:modelValue"]);
const updateValue = () => {
  emit("update:modelValue", { month: selectedMonth.value, day: selectedDay.value });
};
</script>

<template>
  <div class="w-full flex flex-row gap-3">
    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700">Select Month</label>
        <select v-model="selectedMonth" @change="updateDays(); updateValue()" class=" block w-full p-2 border rounded-md">
            <option disabled value="">-- Choose Month --</option>
            <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
        </select>
    </div>

    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700">Select Day</label>
        <select v-model="selectedDay" @change="updateValue()" class=" block w-full p-2 border rounded-md" :disabled="!selectedMonth">
            <option disabled value="">-- Choose Day --</option>
            <option v-for="day in daysInMonth" :key="day" :value="day">{{ day }}</option>
        </select>
    </div>
  </div>
</template>

<style scoped>
.calendar-picker {
  max-width: 300px;
  margin: 1rem auto;
}
</style>
