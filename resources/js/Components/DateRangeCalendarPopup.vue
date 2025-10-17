<template>
  <div
    class="calendar-popup bg-white border border-gray-50 rounded-xl shadow-xl p-3 w-[20rem] text-gray-900 font-rethink select-none"
  >
    <!-- Header with prev/next -->
    <div class="flex justify-between items-center mb-0">
      <button type="button"
        @click="prevMonth"
        aria-label="Previous Month"
        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-light_blue transition"
      >
        <span class="material-icons-round text-lg text-blue">
          arrow_back_ios
        </span>
      </button>

      <div
        class="text-base lg:text-xl font-semibold text-blue flex-grow text-center"
        aria-live="polite"
        aria-atomic="true"
      >
        {{ monthYear }}
      </div>
      <button type="button"
        @click="nextMonth"
        aria-label="Next Month"
        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-light_blue transition"
      >
        <span class="material-icons-round text-lg text-blue">
          arrow_forward_ios
        </span>
      </button>
    </div>

    <!-- Weekday names -->
    <div
      class="grid grid-cols-7 text-center text-xs lg:text-sm font-semibold text-blue mb-2 select-none"
      aria-hidden="true"
    >
      <div v-for="day in weekDays" :key="day" class="py-1">{{ day }}</div>
    </div>

    
    <!-- Calendar days -->
    <div class="grid grid-cols-7 gap-0.5">
        <button
            v-for="date in calendarDays"
            :key="date.key"
            @click="!date.disabled && selectDate(date.date)"
            :disabled="date.disabled || !date.date"
            :class="[
                'py-2 text-xs lg:text-base rounded-lg transition-shadow duration-200',
                date.isToday && !date.disabled ? 'bg-lightblue text-white font-semibold' : '',
                isSelected(date.date) && !date.disabled ? 'bg-primary text-white shadow-md font-semibold' : '',
                isStartDate(date.date) && props.selecting === 'end' && !date.disabled ? 'bg-primary text-white shadow-md font-semibold' : '',
                isInRange(date.date) && !isSelected(date.date) && !date.disabled ? 'bg-primary text-white shadow-md font-semibold' : '',
                date.disabled || !date.date
                    ? 'bg-gray-100 text-red-500 cursor-not-allowed opacity-60' 
                    : 'cursor-pointer font-semibold text-blue hover:bg-indigo-100 hover:shadow-sm',
            ]"
            :aria-label="formatAriaLabel(date.date, date.disabled)"
            :tabindex="date.disabled || !date.date ? -1 : 0"
        >
            {{ date.date ? date.date.getDate() : '' }}
        </button>
    </div>

    
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
  modelValue: Date,
  minDate: Date,
  maxDate: Date,
  startDate: Date,
  endDate: Date,
  selecting: {
    type: String,
    default: 'start',
  },
  disabledRanges: {
    type: Array,
    default: () => [] // Expected format: [ [startDate, endDate], ... ]
  },
  // Add nights prop
  nights: {
    type: Number,
    default: 1
  }
});

function resetTime(date) {
  if (!date) return null;
  const d = new Date(date);
  d.setHours(0, 0, 0, 0);
  return d;
}

// Check if a single date is in any disabled range
function isInDisabledRange(date) {
  if (!props.disabledRanges || props.disabledRanges.length === 0) {
    return false;
  }
  
  const target = resetTime(date);
  
  return props.disabledRanges.some(([start, end]) => {
    if (!start || !end) return false;
    const startTime = resetTime(start);
    const endTime = resetTime(end);
    
    return target.getTime() >= startTime.getTime() && target.getTime() <= endTime.getTime();
  });
}

// NEW: Check if selecting this date would cause the computed end date to conflict
function wouldCauseConflict(selectedDate) {
  // Only check for conflicts when selecting start date and we have nights specified
  if (props.selecting !== 'start' || !props.nights || !selectedDate) {
    return false;
  }
  
  const checkIn = resetTime(selectedDate);
  const checkOut = new Date(checkIn);
  checkOut.setDate(checkOut.getDate() + props.nights);
  
  // Check if the computed end date would fall in any disabled range
  const conflict = props.disabledRanges.some(([rangeStart, rangeEnd]) => {
    if (!rangeStart || !rangeEnd) return false;
    
    const startTime = resetTime(rangeStart);
    const endTime = resetTime(rangeEnd);
    
    // Check if any part of the booking period overlaps with disabled range
    const overlap = (
      (checkIn.getTime() >= startTime.getTime() && checkIn.getTime() < endTime.getTime()) || // Check-in falls within disabled range
      (checkOut.getTime() > startTime.getTime() && checkOut.getTime() <= endTime.getTime()) || // Check-out falls within disabled range
      (checkIn.getTime() <= startTime.getTime() && checkOut.getTime() >= endTime.getTime()) // Booking encompasses disabled range
    );
    
    return overlap;
  });
  
  return conflict;
}

const today = resetTime(new Date());

const calendarDays = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()
  const firstDayOfMonth = new Date(year, month, 1)
  const lastDayOfMonth = new Date(year, month + 1, 0)
  const daysInMonth = lastDayOfMonth.getDate()
  const startDay = firstDayOfMonth.getDay() // Sunday = 0

  const days = []

  // Add empty slots for previous month days
  for (let i = 0; i < startDay; i++) {
    days.push({ key: `empty-prev-${i}`, date: null, disabled: true })
  }

  // Add actual days
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day)
    date.setHours(0, 0, 0, 0) // Normalize time to midnight for comparison

    // Check all disabled conditions
    const isBeforeMin = props.minDate && date < resetTime(props.minDate);
    const isAfterMax = props.maxDate && date > resetTime(props.maxDate);
    const isBeforeToday = date < today;
    const isInDisabled = isInDisabledRange(date);
    const causesConflict = wouldCauseConflict(date); // NEW: Check for conflicts

    const disabled = isBeforeMin || isAfterMax || isBeforeToday || isInDisabled || causesConflict;

    // Check if date is today
    const isToday = date.toDateString() === new Date().toDateString()

    days.push({
      key: `day-${day}`,
      date,
      disabled,
      isToday,
    })
  }

  return days
});

const emit = defineEmits(["update:modelValue"]);

const currentMonth = ref(
  props.modelValue
    ? new Date(props.modelValue.getFullYear(), props.modelValue.getMonth(), 1)
    : new Date(today.getFullYear(), today.getMonth(), 1)
);

const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

const monthYear = computed(() => {
  return currentMonth.value.toLocaleDateString(undefined, {
    year: "numeric",
    month: "long",
  });
});

watch(
  () => props.modelValue,
  (newDate) => {
    if (
      newDate &&
      (newDate.getMonth() !== currentMonth.value.getMonth() ||
        newDate.getFullYear() !== currentMonth.value.getFullYear())
    ) {
      currentMonth.value = new Date(newDate.getFullYear(), newDate.getMonth(), 1);
    }
  }
);

function isSelected(date) {
  return (
    props.modelValue &&
    date &&
    resetTime(props.modelValue).getTime() === resetTime(date).getTime()
  );
}

function isInRange(date) {
  if (!props.startDate || !props.endDate || !date) return false;
  const time = resetTime(date).getTime();
  return (
    time >= resetTime(props.startDate).getTime() &&
    time <= resetTime(props.endDate).getTime()
  );
}

function isStartDate(date) {
  if (!props.startDate || !date) return false;
  return resetTime(props.startDate).getTime() === resetTime(date).getTime();
}

function selectDate(date) {
  if (!date) {
    console.log('No date provided');
    return;
  }
  
  console.log('Attempting to select:', date.toDateString());
  
  // Check if date is disabled using the same logic as the calendar
  const isBeforeMin = props.minDate && date < resetTime(props.minDate);
  const isAfterMax = props.maxDate && date > resetTime(props.maxDate);
  const isBeforeToday = date < today;
  const isInDisabled = isInDisabledRange(date);
  const causesConflict = wouldCauseConflict(date); // NEW: Check for conflicts
  
  const isDisabled = isBeforeMin || isAfterMax || isBeforeToday || isInDisabled || causesConflict;
  
  console.log('Date disabled check:', {
    date: date.toDateString(),
    isBeforeMin,
    isAfterMax,
    isBeforeToday,
    isInDisabled,
    causesConflict, // NEW
    isDisabled
  });
  
  if (isDisabled) {
    console.log('❌ Cannot select disabled date:', date.toDateString());
    if (causesConflict) {
      alert(`Cannot select ${date.toDateString()} - this would conflict with existing bookings for your ${props.nights} night stay`);
    } else {
      alert(`Cannot select ${date.toDateString()} - this date is not available`);
    }
    return;
  }
  
  console.log('✅ Selecting date:', date.toDateString());
  emit('update:modelValue', date);
}

// Optional: Add a function to format aria labels for better accessibility
function formatAriaLabel(date, disabled) {
  if (!date) return '';
  
  const dateStr = date.toLocaleDateString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  });
  
  if (disabled) {
    return `${dateStr} - unavailable`;
  }
  
  return dateStr;
}

function isDisabled(date) {
  return (
    (props.minDate && date < resetTime(props.minDate)) ||
    (props.maxDate && date > resetTime(props.maxDate))
  );
}

function prevMonth() {
  currentMonth.value = new Date(
    currentMonth.value.getFullYear(),
    currentMonth.value.getMonth() - 1,
    1
  );
}

function nextMonth() {
  currentMonth.value = new Date(
    currentMonth.value.getFullYear(),
    currentMonth.value.getMonth() + 1,
    1
  );
}

</script>

<style scoped>
.calendar-popup button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5);
}
</style>