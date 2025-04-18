<template>

    <Head title="Scholarships" />
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
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
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4 ">
                        <div class="flex text-2xl font-poppins font-semibold text-center justify-center items-center text-gray-800 dark:text-white mb-4">
                        {{ formatDate(currentDate) }}
                        </div>
                    <div class="space-x-2">
                        <button @click="prevMonth" class="px-3 py-1 bg-primary rounded">Prev</button>
                        <button @click="nextMonth" class="px-3 py-1 bg-primary rounded">Next</button>
                    </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 text-center font-semibold text-gray-700 border-b pb-2">
                    <div v-for="(day, index) in daysOfWeek" :key="index">{{ day }}</div>
                    </div>
                    <div class="grid grid-cols-7 gap-2">
                    <div v-for="(day, index) in calendarDays" :key="index" class="h-28 border rounded p-1 relative">
                        
                        <div
                        class="absolute top-1 left-1 w-6 h-6 flex items-center justify-center text-xs"
                        :class="isToday(day.date)
                            ? 'bg-blue-500 text-white rounded-full font-bold'
                            : 'text-gray-500'">
                        {{ day.date.getDate() }}
                        </div>

                        <!-- Scholarship Events -->
                        <div v-for="event in day.events" :key="event.title" class="mt-5">
                        <div
                            class="text-xs bg-blue-500 text-white px-1 py-0.5 rounded cursor-pointer"
                            @click="showDetails(event)">
                            {{ event.title }}
                        </div>
                        </div>
                    </div>
                    </div>


                    <!-- Toast or Modal -->
                    <div v-if="toastVisible" class="fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50">
                    {{ toastMessage }}
                    </div>
                </div>

            </div>
        </div>



        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Sponsor Added Successfully!</ToastTitle>
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

// import { Input } from '@/Components/ui/input'
// import { Button } from '@/Components/ui/button'
// import { Calendar } from '@/Components/ui/calendar'

// import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
// import { cn } from '@/lib/utils'
// import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
// import { Calendar as CalendarIcon } from 'lucide-vue-next'

// import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
// import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
// import { initFlowbite } from 'flowbite';
// import { Datepicker } from "flowbite";

// import { SchedulePlugin } from '@syncfusion/ej2-vue-schedule';
// import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda } from '@syncfusion/ej2-vue-schedule';
// import { useDate } from 'vuetify';

const currentDate = ref(new Date());

const formatDate = (date) => {
  const options = { month: 'long', day: 'numeric', year: 'numeric' };
  return date.toLocaleDateString(undefined, options);
};

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const today = new Date()
const currentMonth = ref(today.getMonth())
const currentYear = ref(today.getFullYear())

const scholarships = ref([
  { title: 'RISE Application Deadline', date: '2025-04-17', description: 'Submit form A1' },
  { title: 'Payouts', date: '2025-04-23', description: 'Final application' },
])

const toastMessage = ref('')
const toastVisible = ref(false)

const showDetails = (event) => {
  toastMessage.value = `${event.title}: ${event.description}`
  toastVisible.value = true
  setTimeout(() => (toastVisible.value = false), 3000)
}

const isToday = (date) => {
  const d = new Date()
  return (
    date.getDate() === d.getDate() &&
    date.getMonth() === d.getMonth() &&
    date.getFullYear() === d.getFullYear()
  )
}

const calendarDays = computed(() => {
  const firstDay = new Date(currentYear.value, currentMonth.value, 1)
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0)

  const startDate = new Date(firstDay)
  startDate.setDate(firstDay.getDate() - firstDay.getDay())

  const endDate = new Date(lastDay)
  endDate.setDate(lastDay.getDate() + (6 - lastDay.getDay()))

  const days = []
  for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
    const dateCopy = new Date(date)
    const eventList = scholarships.value.filter((event) => {
      const eventDate = new Date(event.date)
      return (
        eventDate.getDate() === dateCopy.getDate() &&
        eventDate.getMonth() === dateCopy.getMonth() &&
        eventDate.getFullYear() === dateCopy.getFullYear()
      )
    })
    days.push({ date: new Date(dateCopy), events: eventList })
  }

  return days
})

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value -= 1
  } else {
    currentMonth.value -= 1
  }
}

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value += 1
  } else {
    currentMonth.value += 1
  }
}


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

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>