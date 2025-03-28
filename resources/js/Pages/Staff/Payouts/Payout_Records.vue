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

            <div class="flex w-full border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                <a v-for="item in menuItems" :key="item.key" href="#" @click.prevent="selectMenu(item.key)"
                    :ref="`menu-${item.key}`" :class="[
                        'relative inline-block text-center whitespace-nowrap px-6 py-3 text-sm font-medium',
                        selectedMenu === item.key
                            ? 'text-blue-700 dark:text-white'
                            : 'text-gray-900 dark:text-white hover:text-blue-700 dark:hover:bg-gray-700'
                    ]">
                    {{ item.name }}
                    <!-- Active underline for selected item -->
                    <span v-if="selectedMenu === item.key"
                        class="absolute left-0 bottom-0 w-full h-1 bg-blue-700 dark:bg-white"
                        :style="{ width: `${$refs[`menu-${item.key}`]?.offsetWidth}px` }"></span>
                </a>
            </div>

            <!-- Recent Section (Visible if 'recent' is selected) -->
            <div v-show="selectedMenu === 'recent'"
                class="grid grid-cols-[15%_85%] md:grid-cols-[15%_85%] gap-6 p-6 h-full justify-center">
                <!-- Left Column (15%) -->
                <div class="w-full">
                    <span class="text-gray-700 font-medium">Date</span>
                </div>

                <!-- Right Column (85%) -->
                <div class="relative block">
                    <div v-for="payout in payouts" :key="payout.id" class="bg-white p-5 rounded-lg shadow-md relative">
                        <span
                            class="absolute -top-3 right-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1 shadow-lg">
                            Pending
                        </span>
                        <p class="text-lg font-semibold text-red-500">{{ payout.scholarship_id }}</p>
                        <p class="text-lg font-semibold text-red-500">2023-2022</p>
                        <p class="text-sm text-gray-600">Expected on: <span v-if="payout.date_end">
                                {{ new
                                    Date(payout.date_end).toLocaleDateString('en-US', {
                                        year: 'numeric', month: 'long', day: 'numeric'
                                    }) }}
                            </span>
                            <span v-else>
                                No Deadline
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- History Section (Visible if 'history' is selected) -->
            <div v-show="selectedMenu === 'history'"
                class="grid grid-cols-[15%_85%] md:grid-cols-[15%_85%] gap-6 p-6 h-full justify-center">
                <!-- Left Column (15%) -->
                <div class="w-full">
                    <span class="text-gray-700 font-medium">Date</span>
                </div>

                <!-- Right Column (85%) -->
                <div class="relative block">
                    <div class="bg-white p-5 rounded-lg shadow-md relative">
                        <span
                            class="absolute -top-3 right-3 bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1 shadow-lg">
                            Accomplished
                        </span>
                        <p class="text-lg font-semibold text-red-500">Lala</p>
                        <p class="text-lg font-semibold text-red-500">2023-2022</p>
                        <p class="text-sm text-gray-600">01/01/2023</p>
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
    payouts: Array,
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

// Updated menu items to match user types
const menuItems = [
    { name: "Recent Payouts", key: "recent" },
    { name: "Payout History", key: "history" },
];

// Track the selected menu
const selectedMenu = ref('recent');

// Function to change the selected menu
const selectMenu = (key) => {
    selectedMenu.value = key;
};


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