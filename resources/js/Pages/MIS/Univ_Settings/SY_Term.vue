<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="bg-dirtywhite p-6 h-full w-full space-y-10">
            <div>
                <div>
                    <h1 class="text-2xl font-bold mb-5">School year</h1>
                </div>
                <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                    Here is the active School year of the University where you can activate new semesters.
                </p>

                <div class="w-full h-full flex flex-col px-5 mt-5 gap-2">
                    <div class="grid grid-cols-2 items-center gap-3">
                        <h1 class="px-2">Academic Year: </h1>
                        <span class="items-center justify-center">2025-2026</span>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-3">
                        <h1 class="px-2">Status: </h1>
                        <span class="items-center justify-center">Active</span>
                    </div>
                    <div class="grid grid-cols-2 items-center">
                        <h1 class="px-2">Start Month: </h1>
                        <div class="justify-center">
                            <CalendarPicker v-model="selectedDate" />
                            <!-- <p class="mt-4">Selected Date: {{ selectedDate.month }} {{ selectedDate.day }}</p> -->
                        </div>
                    </div>
                    <div class="grid grid-cols-2 items-center">
                        <h1 class="px-2 mr-2">End Month: </h1>
                        <div class="justify-center">
                            <CalendarPicker v-model="selectedDate" />
                            <!-- <p class="mt-4">Selected Date: {{ selectedDate.month }} {{ selectedDate.day }}</p> -->
                        </div>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-3">
                        <h1 class="px-2">Next Academic Year Change: </h1>
                        <span class="items-center justify-center">Estimations</span>
                    </div>
                </div>
            </div>

            <div class="h-0.5 w-full bg-gray-200"></div>

            <div>
                <div>
                    <h1 class="text-2xl font-bold mb-5">Semester</h1>
                </div>
                <p class="font-quicksand text-base text-gray-600 dark:text-gray-400">
                    Here is the active School year of the University where you can activate new semesters.
                </p>

                <div class="w-full h-full flex flex-col px-5 mt-5 gap-2">
            
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Color
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Apple MacBook Pro 17"
                                    </th>
                                    <td class="px-6 py-4">
                                        Silver
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop
                                    </td>
                                    <td class="px-6 py-4">
                                        $2999
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Microsoft Surface Pro
                                    </th>
                                    <td class="px-6 py-4">
                                        White
                                    </td>
                                    <td class="px-6 py-4">
                                        Laptop PC
                                    </td>
                                    <td class="px-6 py-4">
                                        $1999
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        Magic Mouse 2
                                    </th>
                                    <td class="px-6 py-4">
                                        Black
                                    </td>
                                    <td class="px-6 py-4">
                                        Accessories
                                    </td>
                                    <td class="px-6 py-4">
                                        $99
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ContentDashboard from '@/Pages/Super_Admin/Dashboard/Content-Dashboard.vue';
import { ref } from 'vue';
import CalendarPicker from "@/Components/MIS/CalendarPicker.vue";

const selectedDate = ref({ month: "", day: "" });


</script>