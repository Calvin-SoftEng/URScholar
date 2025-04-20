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
              <div v-for="event in day.events" :key="`${event.id}-${event.type}`" class="mt-1 mb-1">
                <div class="text-xs px-1 py-0.5 rounded cursor-pointer truncate"
                  :class="event.type === 'start' ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-green-500 text-white hover:bg-green-600'"
                  @click="showDetails(event)">
                  {{ event.displayText }}
                </div>
              </div>

              <!-- Payout Schedules -->
              <div v-for="payout in day.payouts" :key="`payout-${payout.id}`" class="mt-1 mb-1">
                <div class="text-xs px-1 py-0.5 rounded cursor-pointer truncate bg-yellow-500 text-white hover:bg-yellow-600"
                  @click="showPayoutDetails(payout)">
                  {{ payout.displayText }}
                </div>
              </div>

              <!-- Combined Payout Start Dates -->
              <div v-for="(payoutGroup, scholarshipId) in day.combinedPayoutStarts" :key="`payoutStart-${scholarshipId}`" class="mt-1 mb-1">
                <div class="text-xs px-1 py-0.5 rounded cursor-pointer truncate bg-orange-500 text-white hover:bg-orange-600"
                  @click="showCombinedPayoutDetails(payoutGroup, 'start')">
                  {{ payoutGroup[0].scholarship_name }} Payout Start
                </div>
              </div>

              <!-- Combined Payout End Dates -->
              <div v-for="(payoutGroup, scholarshipId) in day.combinedPayoutEnds" :key="`payoutEnd-${scholarshipId}`" class="mt-1 mb-1">
                <div class="text-xs px-1 py-0.5 rounded cursor-pointer truncate bg-red-500 text-white hover:bg-red-600"
                  @click="showCombinedPayoutDetails(payoutGroup, 'end')">
                  {{ payoutGroup[0].scholarship_name }} Payout End
                </div>
              </div>
            </div>
          </div>

          <!-- Toast or Modal -->
          <div v-if="toastVisible"
            class="fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-md">
            <pre class="whitespace-pre-wrap text-sm">{{ toastMessage }}</pre>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, watchEffect, watch, computed } from 'vue';
import { usePage } from "@inertiajs/vue3";
import { Head, useForm, Link, router } from '@inertiajs/vue3';

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

const showPayoutDetails = (payout) => {
  let message = `${payout.campus_name} Payouts\n`;
  
  if (payout.scheduled_date) {
    message += `Date: ${formatDateStr(payout.scheduled_date)}\n`;
  }
  
  if (payout.scheduled_time) {
    message += `Time: ${formatTime(payout.scheduled_time)}\n`;
  }
  
  message += `Scholarship: ${payout.scholarship_name}\n`;
  message += `Semester: ${payout.semester}\n`;
  message += `Payout Period: ${formatDateStr(payout.date_start)} - ${formatDateStr(payout.date_end)}\n`;
  
  if (payout.reminders) {
    message += `Reminders: ${payout.reminders}`;
  }

  toastMessage.value = message;
  toastVisible.value = true;
  setTimeout(() => (toastVisible.value = false), 8000);
};

// New function to show combined payout details
const showCombinedPayoutDetails = (payoutGroup, type) => {
  const eventType = type === 'start' ? 'Start' : 'End';
  const scholarshipName = payoutGroup[0].scholarship_name;
  const semester = payoutGroup[0].semester;
  
  let message = `${scholarshipName} Payout ${eventType}\n`;
  message += `Semester: ${semester}\n`;
  
  message += '\nCampuses:\n';
  payoutGroup.forEach((payout, index) => {
    message += `${index + 1}. ${payout.campus_name}\n`;
  });
  
  // Calculate totals
  const totalScholars = payoutGroup.reduce((sum, payout) => sum + (payout.total_scholars || 0), 0);
  const totalAmount = payoutGroup.reduce((sum, payout) => sum + (payout.sub_total || 0), 0);

  
  if (type === 'start') {
    message += `\nPayout Period Starts: ${formatDateStr(payoutGroup[0].date_start)}`;
  } else {
    message += `\nPayout Period Ends: ${formatDateStr(payoutGroup[0].date_end)}`;
  }

  toastMessage.value = message;
  toastVisible.value = true;
  setTimeout(() => (toastVisible.value = false), 8000);
};

const formatTime = (timeStr) => {
  if (!timeStr) return '';
  
  const timeParts = timeStr.split(':');
  if (timeParts.length < 2) return timeStr;
  
  let hours = parseInt(timeParts[0]);
  const minutes = timeParts[1];
  const ampm = hours >= 12 ? 'PM' : 'AM';
  
  hours = hours % 12;
  hours = hours ? hours : 12;
  
  return `${hours}:${minutes} ${ampm}`;
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

// Props definition
const props = defineProps({
  scholarships: Array,
  payouts: Array,
  campuses: Array
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

// New computed property for payout schedules
const payoutSchedules = computed(() => {
  if (!props.payouts || !props.payouts.length) {
    return [];
  }

  return props.payouts.flatMap(payout => {
    if (!payout.payout_schedule || !payout.payout_schedule.length) {
      return [];
    }

    const campusName = props.campuses?.find(c => c.id === payout.campus_id)?.name || 'Unknown Campus';
    
    return payout.payout_schedule.map(schedule => {
      return {
        id: schedule.id,
        payout_id: payout.id,
        campus_id: payout.campus_id,
        campus_name: campusName,
        scheduled_date: schedule.scheduled_date,
        scheduled_time: schedule.scheduled_time,
        reminders: schedule.reminders,
        scholarship_id: payout.scholarship_id,
        scholarship_name: props.scholarships?.find(s => s.id === payout.scholarship_id)?.name || 'Unknown Scholarship',
        semester: payout.semester,
        total_scholars: payout.total_scholars,
        sub_total: payout.sub_total,
        date_start: payout.date_start,
        date_end: payout.date_end,
        displayText: `${campusName} Payouts`
      };
    });
  });
});

// Extract all payouts for displaying start and end dates
const allPayouts = computed(() => {
  if (!props.payouts || !props.payouts.length) {
    return [];
  }

  return props.payouts.map(payout => {
    const campusName = props.campuses?.find(c => c.id === payout.campus_id)?.name || 'Unknown Campus';
    
    return {
      id: payout.id,
      campus_id: payout.campus_id,
      campus_name: campusName,
      scholarship_id: payout.scholarship_id,
      scholarship_name: props.scholarships?.find(s => s.id === payout.scholarship_id)?.name || 'Unknown Scholarship',
      semester: payout.semester,
      total_scholars: payout.total_scholars,
      sub_total: payout.sub_total,
      date_start: payout.date_start,
      date_end: payout.date_end,
      payout_schedules: payout.payout_schedules
    };
  });
});

// Helper function to format date strings
const formatDateStr = (dateStr) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString();
};

// Add this helper function to format dates in a compact way
const formatSimpleDate = (dateStr) => {
  const date = new Date(dateStr);
  return `${date.getMonth() + 1}/${date.getDate()}`;
};

// Group payouts by scholarship and date
const groupPayoutsByScholarshipAndDate = (payouts, dateField) => {
  const grouped = {};
  
  payouts.forEach(payout => {
    const dateStr = payout[dateField];
    const scholarshipId = payout.scholarship_id;
    
    // Create a unique key for this scholarship and date
    const key = `${scholarshipId}-${dateStr}`;
    
    if (!grouped[key]) {
      grouped[key] = [];
    }
    
    grouped[key].push(payout);
  });
  
  return grouped;
};

// Updated calendar days computed property to include combined payouts
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
    const dayPayouts = [];
    
    // For combined payouts data structures
    const startPayoutsByScholarship = {}; // Scholarship ID -> Array of payouts
    const endPayoutsByScholarship = {}; // Scholarship ID -> Array of payouts

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

    // Process payouts for this date
    payoutSchedules.value.forEach(payout => {
      const payoutDate = new Date(payout.scheduled_date);
      payoutDate.setHours(0, 0, 0, 0);

      const currentDate = new Date(dateCopy);
      currentDate.setHours(0, 0, 0, 0);

      if (currentDate.getTime() === payoutDate.getTime()) {
        dayPayouts.push(payout);
      }
    });

    // Process payout start and end dates - but group by scholarship
    allPayouts.value.forEach(payout => {
      const payoutStartDate = new Date(payout.date_start);
      const payoutEndDate = new Date(payout.date_end);
      
      payoutStartDate.setHours(0, 0, 0, 0);
      payoutEndDate.setHours(0, 0, 0, 0);
      
      const currentDate = new Date(dateCopy);
      currentDate.setHours(0, 0, 0, 0);
      
      if (currentDate.getTime() === payoutStartDate.getTime()) {
        // Group by scholarship_id for start dates
        if (!startPayoutsByScholarship[payout.scholarship_id]) {
          startPayoutsByScholarship[payout.scholarship_id] = [];
        }
        startPayoutsByScholarship[payout.scholarship_id].push(payout);
      }
      
      if (currentDate.getTime() === payoutEndDate.getTime()) {
        // Group by scholarship_id for end dates
        if (!endPayoutsByScholarship[payout.scholarship_id]) {
          endPayoutsByScholarship[payout.scholarship_id] = [];
        }
        endPayoutsByScholarship[payout.scholarship_id].push(payout);
      }
    });

    days.push({ 
      date: new Date(dateCopy), 
      events: dayEvents,
      payouts: dayPayouts,
      combinedPayoutStarts: startPayoutsByScholarship,
      combinedPayoutEnds: endPayoutsByScholarship
    });
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