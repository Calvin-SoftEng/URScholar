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
                            <span class="text-blue-400 font-semibold dark:text-gray-300">URS Calendar</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">

                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Scholarship
                        Calendar
                    </h1>

                </div>

                <div>
                    <v-sheet class="d-flex p-10" height="54" tile>
                        <v-select v-model="type" :items="types" class="ma-2 p-5" density="compact" label="View Mode"
                            variant="outlined" hide-details></v-select>
                        <v-select v-model="weekday" :items="weekdays" class="ma-2" density="compact" label="weekdays"
                            variant="outlined" hide-details></v-select>
                    </v-sheet>
                    <v-sheet>
                        <!-- // Add this to your template part where the v-calendar component is -->
                        <v-calendar ref="calendar" v-model="value" :events="events" :view-mode="type"
                            :weekdays="weekday" @click:event="showEventDetails">
                            <template v-slot:event="{ event }">
                                <div class="custom-event">
                                    {{ event.title }}
                                </div>
                            </template>
                        </v-calendar>
                    </v-sheet>
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
import { ref, onMounted, watchEffect, watch } from 'vue';
import { usePage } from "@inertiajs/vue3";
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { Tooltip } from 'primevue';
import { set } from 'date-fns';
import { DatePicker } from 'primevue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { initFlowbite } from 'flowbite';
import { Datepicker } from "flowbite";

import { SchedulePlugin } from '@syncfusion/ej2-vue-schedule';
import { ScheduleComponent, Day, Week, WorkWeek, Month, Agenda } from '@syncfusion/ej2-vue-schedule';
import { useDate } from 'vuetify';




const props = defineProps({
    scholarships: Array,
});

const directives = {
    Tooltip,
    DatePicker,
};


// Vuetify Date Adapter
const adapter = useDate();

// Reactive State
const type = ref('month');
const types = ref(['month', 'week', 'day']);

const weekday = ref([0, 1, 2, 3, 4, 5, 6]);
const weekdays = ref([
    { title: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
    { title: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
    { title: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
    { title: 'Mon, Wed, Fri', value: [1, 3, 5] },
]);

const value = ref([new Date()]);
const events = ref([]);

const colors = ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'];
const titles = ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'];

// Replace the getEvents function with this:
const getScholarshipEvents = () => {
    if (!props.scholarships || props.scholarships.length === 0) return;

    const scholarshipEvents = props.scholarships.map(scholarship => {
        // Convert date_end string to a Date object
        const endDate = new Date(scholarship.date_start);

        return {
            title: scholarship.name || scholarship.title || 'Scholarship Deadline', // Use name as first priority
            start: endDate,
            end: endDate,
            color: 'blue',
            allDay: true,
            details: scholarship.description || 'Application deadline'
        };
    });

    events.value = scholarshipEvents;
};

// Add this function to your script
const showEventDetails = (event) => {
    // You could show a modal or toast with scholarship details here
    console.log('Scholarship details:', event);
    // Example: show toast with scholarship deadline info
    toastMessage.value = `Deadline for ${event.event.title} is on ${new Date(event.event.start).toLocaleDateString()}`;
    toastVisible.value = true;
    setTimeout(() => {
        toastVisible.value = false;
    }, 3000);
};

// Add these reactive variables if not already present
const toastVisible = ref(false);
const toastMessage = ref('');


// Utility Function for Random Number Generation
const rnd = (a, b) => Math.floor((b - a + 1) * Math.random()) + a;

// Fetch Scholarship Events on Mount
onMounted(() => {
    getScholarshipEvents();
});

// Add this after your onMounted hook
watch(() => props.scholarships, () => {
    getScholarshipEvents();
}, { deep: true });

</script>

<style scoped>
.test {
    z-index: 9999;
}

.custom-event {
    background-color: #003366 ;
    /* Green background */
    color: white;
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
    margin-bottom: 2px;
}

.custom-day {
    border: 1px solid #ddd;
    text-align: center;
    font-weight: bold;
}
</style>