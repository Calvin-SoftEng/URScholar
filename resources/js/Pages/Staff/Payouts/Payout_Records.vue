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
                            <span class="text-blue-400 font-semibold dark:text-gray-300">Scholaship Payouts</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>Payout Records
                    </h1>
                </div>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 h-full">
                <!-- Column 3: Pending Payouts -->
                <div class="h-full flex flex-col">
                <div class="grid grid-rows-4 gap-4 flex-grow">
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-center">Payout 1</div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-center">Payout 2</div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-center">Payout 3</div>
                    <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-center">Payout 4</div>
                </div>
                </div>
                
                <div class="bg-white shadow-lg rounded-2xl p-6 text-left h-full flex flex-col">
                    <h2 class="text-2xl font-bold mb-4">Pending Payouts</h2>
                    <div class="space-y-4">
                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <p class="text-lg font-semibold text-red-500">DBP-Rise</p>
                        <p class="text-lg font-semibold text-red-500">2023-2022</p>
                        <p class="text-sm text-gray-600">Expected on: 01/01/2023</p>
                        </div>
                    </div>
                </div>
                
                <!-- Column 2: Next Expected Payout -->
                <div class="bg-white shadow-lg rounded-2xl p-6 text-left h-full flex flex-col">
                    <h2 class="text-2xl font-bold mb-4">Payout Histories</h2>
                    <div class="space-y-4">
                        <Link :href="route('payouts_list.payouts')">
                            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                            <p class="text-lg font-semibold text-red-500">DBP-Rise</p>
                            <p class="text-lg font-semibold text-red-500">2023-2022</p>
                            <p class="text-sm text-gray-600">Expected on: 01/01/2023</p>
                            </div>
                        </Link>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <p class="text-lg font-semibold text-red-500">DBP-Rise</p>
                        <p class="text-lg font-semibold text-red-500">2023-2022</p>
                        <p class="text-sm text-gray-600">Expected on: 01/01/2023</p>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <p class="text-lg font-semibold text-red-500">DBP-Rise</p>
                        <p class="text-lg font-semibold text-red-500">2023-2022</p>
                        <p class="text-sm text-gray-600">Expected on: 01/01/2023</p>
                        </div>
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

const pendingPayouts = [
  { id: 1, amount: '₱5,000', date: 'April 5, 2025' },
  { id: 2, amount: '₱3,000', date: 'April 15, 2025' },
  { id: 3, amount: '₱7,000', date: 'April 25, 2025' }
];


onMounted(() => {

});

</script>

<style scoped>
.test {
    z-index: 9999;
}

.custom-event {
    background-color: #4caf50;
    /* Green background */
    color: white;
    padding: 5px;
    border-radius: 5px;
    font-size: 14px;
}

.custom-day {
    border: 1px solid #ddd;
    text-align: center;
    font-weight: bold;
}
</style>