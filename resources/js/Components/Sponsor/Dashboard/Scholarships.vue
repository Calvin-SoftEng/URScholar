<template>
    <div class="space-y-5">

        <div class="flex flex-row justify-between items-center">
            <span class="font-poppins font-semibold text-xl dark:text-dtext">Scholarships</span>

                    <!-- Other content of your card goes here -->
                    <!-- expand -->

            <!-- :href="route('sponsor.scholarship')" -->
        </div>

        <div class="mx-auto py-5">
            <div class="flex w-full flex-col gap-6">

                <!-- Need-Based Scholarships -->
                <h2
                    class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                    Grant-Based Scholarships
                </h2>

                <Link :href="route('sponsor.scholars')">
                    <button
                        class="w-full">

                        <div class="relative border rounded-lg bg-white dark:bg-dcontainer dark:border-gray-600 
                            hover:shadow-md transition-all duration-300 py-5 px-10 flex flex-col md:flex-row 
                            justify-between items-start md:items-center space-y-5 md:space-y-0">

                            <!-- Scholarship Info -->
                            <div class="space-y-4 flex flex-col items-start justify-start">
                                <div class="badge badge-info text-xs badge-outline px-3 py-1">
                                    <!-- {{ getSponsorName(scholarship.sponsor_id) }} -->CHED
                                </div>

                                <h2
                                    class="w-full items-start text-3xl md:text-2xl font-semibold text-gray-900 dark:text-dtext">
                                    <!-- {{ scholarship.name }} -->Tulong Dunong
                                </h2>

                                <p class="flex flex-col text-sm text-gray-500 items-start space-y-1">
                                    <!-- <span class="items-start">Created on: {{ new
                                        Date(scholarship.created_at).toLocaleDateString() }}</span>
                                    <span class="items-start">Sponsoring Since:
                                        {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                            year:
                                                'numeric', month: 'long', day: 'numeric'
                                        }) }}
                                    </span> -->
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    <!-- <span class="font-medium">Requirements Deadline:</span>
                                    <span
                                        v-if="scholarship.requirements && scholarship.requirements.length > 0 && scholarship.requirements[0].date_end">
                                        {{ new
                                            Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US', {
                                                year: 'numeric', month: 'long', day: 'numeric'
                                            }) }}
                                    </span>
                                    <span v-else>
                                        No Deadline
                                    </span> -->
                                </p>
                            </div>

                            <!-- Additional Info -->
                            <div class="flex flex-row gap-6">
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-500 text-sm">Batches</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-dtext">34</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-500 text-sm">Campuses</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-dtext">2</span>
                                </div>
                            </div>

                        </div>

                    </button>
                </Link>

                <!-- One-Time Payment Scholarships -->
                <h2
                    class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                    One-Time Payment Scholarships
                </h2>

                <template >
                    <button  class="w-full">

                        <div class="relative border rounded-lg bg-white dark:bg-dcontainer dark:border-gray-600 
                            hover:shadow-md transition-all duration-300 py-5 px-10 flex flex-col md:flex-row 
                            justify-between items-start md:items-center space-y-5 md:space-y-0">

                            <!-- Notification Badge -->
                            <!-- <span
                                class="absolute top-[-13px] right-2 bg-primary text-white text-sm font-bold px-5 py-1 rounded-full flex items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'bell']" class="text-base" />
                                New Activities
                            </span> -->


                            <!-- Scholarship Info -->
                            <div class="space-y-4 flex flex-col items-start justify-start">
                                <div class="badge badge-info text-xs badge-outline px-3 py-1">
                                    <!-- {{ getSponsorName(scholarship.sponsor_id) }} -->CHED
                                </div>

                                <h2
                                    class="w-full items-start text-3xl md:text-2xl font-semibold text-gray-900 dark:text-dtext">
                                    <!-- {{ scholarship.name }} --> DBP Rise
                                </h2>

                                <p class="flex flex-col text-sm text-gray-500 items-start space-y-1">
                                    <!-- <span class="items-start">Created on: {{ new
                                        Date(scholarship.created_at).toLocaleDateString() }}</span>
                                    <span class="items-start">Sponsoring Since:
                                        {{ new Date(scholarship.created_at).toLocaleDateString('en-US', {
                                            year:
                                                'numeric', month: 'long', day: 'numeric'
                                        }) }}
                                    </span> -->
                                </p>

                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    <!-- <span class="font-medium">Requirements Deadline:</span>
                                    <span
                                        v-if="scholarship.requirements && scholarship.requirements.length > 0 && scholarship.requirements[0].date_end">
                                        {{ new
                                            Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US', {
                                                year: 'numeric', month: 'long', day: 'numeric'
                                            }) }}
                                    </span>
                                    <span v-else>
                                        No Deadline
                                    </span> -->
                                </p>
                            </div>

                            <!-- Additional Info -->
                            <div class="flex flex-row gap-6">
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-500 text-sm">Batches</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-dtext">34</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-500 text-sm">Campuses</span>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-dtext">2</span>
                                </div>
                            </div>

                        </div>
                    </button>
                </template>
                <!-- <div v-else class="text-gray-500 text-center py-4">No available scholarships.</div> -->

            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

</script>