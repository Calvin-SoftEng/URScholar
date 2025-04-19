<template>

  <Head title="Scholarships" />
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
              <span class="text-blue-400 font-semibold dark:text-gray-300">Calendar</span>
            </li>
          </ul>
        </div>

        <div class="flex justify-between items-center mb-4">
          <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Scholarship Calendar
          </h1>
        </div>

        <div class="w-full mx-auto">
          <!-- Header with Month/Year Selection -->
          <div class="flex items-center justify-between mb-4">
            <div class="flex text-2xl font-poppins font-semibold text-gray-800 dark:text-white">
              {{ formatDate(currentDate) }}
            </div>
            <div class="flex items-center space-x-4">
              <!-- Month & Year Selectors -->
              <div class="flex items-center space-x-2">
                <select v-model="currentMonth"
                  class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                  <option v-for="(month, index) in monthNames" :key="index" :value="index">
                    {{ month }}
                  </option>
                </select>
                <select v-model="currentYear"
                  class="px-3 py-1 bg-white border border-gray-300 rounded text-gray-700 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                  <option v-for="year in yearRange" :key="year" :value="year">
                    {{ year }}
                  </option>
                </select>
              </div>

              <!-- Navigation Buttons -->
              <div class="space-x-2">
                <button @click="prevMonth"
                  class="px-3 py-1 bg-primary text-white rounded hover:bg-blue-600">Prev</button>
                <button @click="currentDate = new Date()"
                  class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">Today</button>
                <button @click="nextMonth"
                  class="px-3 py-1 bg-primary text-white rounded hover:bg-blue-600">Next</button>
              </div>
            </div>
          </div>

          <!-- Calendar Grid -->
          <div class="grid grid-cols-7 text-center font-semibold text-gray-700 dark:text-gray-300 border-b pb-2">
            <div v-for="(day, index) in daysOfWeek" :key="index">{{ day }}</div>
          </div>
          <div class="grid grid-cols-7 gap-2">
            <div v-for="(day, index) in calendarDays" :key="index" class="h-28 border rounded p-1 relative"
              :class="{ 'bg-blue-50 dark:bg-blue-900/20': isToday(day.date), 'bg-gray-50 dark:bg-gray-800/50': !isCurrentMonth(day.date) }">

              <div class="absolute top-1 left-1 w-6 h-6 flex items-center justify-center text-xs"
                :class="isToday(day.date)
                  ? 'bg-blue-500 text-white rounded-full font-bold'
                  : isCurrentMonth(day.date) ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400 dark:text-gray-500'">
                {{ day.date.getDate() }}
              </div>

              <!-- Scholarship Events -->
              <!-- Event in the calendar -->
              <div v-for="event in day.events" :key="`${event.id}-${event.type}`" class="mt-1 mb-1">
                <div class="text-xs px-1 py-0.5 rounded cursor-pointer truncate"
                  :class="event.type === 'start' ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-green-500 text-white hover:bg-green-600'"
                  @click="showDetails(event)">
                  {{ event.displayText }}
                </div>
              </div>
            </div>
          </div>

          <!-- Toast or Modal -->
          <div v-if="toastVisible"
            class="fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            {{ toastMessage }}
          </div>
        </div>
      </div>
    </div>

    <ToastProvider>
      <ToastRoot v-if="toastVisible"
        class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
        <ToastTitle class="font-semibold dark:text-dtext">Scholarship Information</ToastTitle>
        <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
      </ToastRoot>

      <ToastViewport class="fixed bottom-4 right-4" />
    </ToastProvider>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, watchEffect, watch, computed } from 'vue';
import { usePage } from "@inertiajs/vue3";
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { set } from 'date-fns';
import { DatePicker } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

const currentDate = ref(new Date());

// Month names array for the dropdown
const monthNames = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
];

// Generate a range of years for the dropdown (current year -5 to +5)
const yearRange = computed(() => {
  const currentYear = new Date().getFullYear();
  const years = [];
  for (let i = currentYear - 5; i <= currentYear + 5; i++) {
    years.push(i);
  }
  return years;
});

const formatDate = (date) => {
  const options = { month: 'long', day: 'numeric', year: 'numeric' };
  return date.toLocaleDateString(undefined, options);
};

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

// Update currentDate when month or year changes
watch([currentMonth, currentYear], ([newMonth, newYear]) => {
  currentDate.value = new Date(newYear, newMonth, 1);
});

// Watch for changes to currentDate to update month and year
watch(currentDate, (newDate) => {
  currentMonth.value = newDate.getMonth();
  currentYear.value = newDate.getFullYear();
});

const scholarships = ref([
  { title: 'RISE Application Deadline', date: '2025-04-17', description: 'Submit form A1 by 5:00 PM EST to be considered for the RISE Scholarship.' },
  { title: 'Payouts', date: '2025-04-23', description: 'Scholarship funds will be disbursed to student accounts.' },
  { title: 'Merit Scholarship Interview', date: '2025-05-10', description: 'Virtual interviews for finalists begin at 9:00 AM.' },
  { title: 'Summer Grant Deadline', date: '2025-05-15', description: 'Last day to submit summer research grant proposals.' },
]);

const toastMessage = ref('');
const toastVisible = ref(false);


const showDetails = (event) => {
  const eventType = event.type === 'start' ? 'Start' : 'End';
  let message = `${event.scholarship} (${eventType})\n`;

  if (event.requirements && event.requirements.length > 0) {
    message += "\nRequirements:\n";
    event.requirements.forEach((req, index) => {
      message += `${index + 1}. ${req.text} (${formatDateStr(req.startDate)} - ${formatDateStr(req.endDate)})\n`;
    });
  } else {
    message += `\nValid from ${formatDateStr(event.startDate)} to ${formatDateStr(event.endDate)}`;
  }

  toastMessage.value = message;
  toastVisible.value = true;
  setTimeout(() => (toastVisible.value = false), 6000);
};

const isToday = (date) => {
  const d = new Date();
  return (
    date.getDate() === d.getDate() &&
    date.getMonth() === d.getMonth() &&
    date.getFullYear() === d.getFullYear()
  );
};

const isCurrentMonth = (date) => {
  return date.getMonth() === currentMonth.value && date.getFullYear() === currentYear.value;
};

// Update the scholarships ref to match the data structure coming from the backend
// Replace your current scholarships ref with:
const props = defineProps({
  scholarships: Array
});

const scholarshipEvents = computed(() => {
  // Check if scholarships exists and has items
  if (!props.scholarships || !props.scholarships.length) {
    return [];
  }

  // Group requirements by scholarship
  const groupedEvents = props.scholarships.map(scholarship => {
    // Skip if no requirements
    if (!scholarship.requirements || !Array.isArray(scholarship.requirements) || scholarship.requirements.length === 0) {
      return null;
    }

    // Find the earliest start date and latest end date
    let earliestStart = null;
    let latestEnd = null;

    scholarship.requirements.forEach(req => {
      const startDate = new Date(req.date_start);
      const endDate = new Date(req.date_end);

      if (!earliestStart || startDate < earliestStart) {
        earliestStart = startDate;
      }

      if (!latestEnd || endDate > latestEnd) {
        latestEnd = endDate;
      }
    });

    // Skip if dates are invalid
    if (!earliestStart || !latestEnd) {
      return null;
    }

    // Create a combined event
    return {
      id: `scholarship-${scholarship.id}`,
      title: scholarship.name,
      startDate: earliestStart.toISOString().split('T')[0],
      endDate: latestEnd.toISOString().split('T')[0],
      scholarship: scholarship.name,
      requirements: scholarship.requirements.map(req => ({
        text: req.requirements,
        startDate: req.date_start,
        endDate: req.date_end
      }))
    };
  }).filter(event => event !== null); // Remove null entries

  return groupedEvents;
});

// Helper function to format date strings
const formatDateStr = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString();
};

// Add this helper function to format dates in a compact way
const formatSimpleDate = (dateStr) => {
  const date = new Date(dateStr);
  return `${date.getMonth() + 1}/${date.getDate()}`;
};

// First, update the calendar days computed property to properly handle both start and end dates
const calendarDays = computed(() => {
  const firstDay = new Date(currentYear.value, currentMonth.value, 1);
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0);

  const startDate = new Date(firstDay);
  startDate.setDate(firstDay.getDate() - firstDay.getDay());

  const endDate = new Date(lastDay);
  endDate.setDate(lastDay.getDate() + (6 - lastDay.getDay()));

  const days = [];
  for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
    const dateCopy = new Date(date);

    // Process each scholarship to check if this date is a start or end date
    const dayEvents = [];

    scholarshipEvents.value.forEach(event => {
      const eventStartDate = new Date(event.startDate);
      const eventEndDate = new Date(event.endDate);

      // Strip time components
      eventStartDate.setHours(0, 0, 0, 0);
      eventEndDate.setHours(0, 0, 0, 0);

      const currentDate = new Date(dateCopy);
      currentDate.setHours(0, 0, 0, 0);

      // Check if current date is start date
      if (currentDate.getTime() === eventStartDate.getTime()) {
        dayEvents.push({
          ...event,
          type: 'start',
          displayText: `${event.scholarship} (Start)`
        });
      }

      // Check if current date is end date
      if (currentDate.getTime() === eventEndDate.getTime()) {
        dayEvents.push({
          ...event,
          type: 'end',
          displayText: `${event.scholarship} (End)`
        });
      }
    });

    days.push({ date: new Date(dateCopy), events: dayEvents });
  }

  return days;
});

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value -= 1;
  } else {
    currentMonth.value -= 1;
  }
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value += 1;
  } else {
    currentMonth.value += 1;
  }
};

const formatShortDate = (dateStr) => {
  const date = new Date(dateStr);
  return `${date.getMonth() + 1}/${date.getDate()}`;
};
</script>

<style scoped>
/* Calendar header styling */
:deep(.v-calendar-weekly__head-weekday) {
  font-family: 'Roboto', sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: #424242;
}

/* Day number styling */
:deep(.v-calendar-weekly__day-label) {
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  color: #616161;
}

/* Today highlight */
:deep(.v-calendar-weekly__day--today) {
  background-color: rgba(25, 118, 210, 0.1) !important;
}

/* Custom event styling */
.custom-event {
  padding: 4px 6px;
  border-radius: 4px;
  font-family: 'Roboto', sans-serif;
  font-size: 12px;
  font-weight: 500;
  color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Overall calendar styling */
:deep(.v-calendar) {
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  font-family: 'Roboto', sans-serif;
}

/* Selected day styling */
:deep(.v-calendar-weekly__day--selected) {
  background-color: rgba(25, 118, 210, 0.2) !important;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>