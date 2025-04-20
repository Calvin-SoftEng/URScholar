<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li class="hover:text-gray-600">
                            <span>{{ scholarship.name }}</span>
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Batch 1</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholar Details</span>
                        </li>
                    </ul>
                </div>

                <div class="w-full h-full flex justify-center items-center dark:text-dprimary relative">
                    <!-- Close Button -->
                    <button @click="goBack" class="absolute top-4 right-10">
                        <span
                            class="material-symbols-rounded p-2 rounded-full bg-white dark:bg-dcontainer text-blue-900 dark:text-dprimary shadow-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            arrow_back
                        </span>
                    </button>

                    <div class="h-full grid grid-cols-[60%_40%] gap-3 py-3 w-10/12">

                        <!-- 25% Column -->
                        <div class="col-span-1 w-full h-full flex flex-col">
                            <div class="h-full rounded-xl p-5 shadow-md bg-white dark:bg-dcontainer flex flex-col">
                                <div class="flex flex-row gap-10 items-center justify-center">
                                    <div class="flex flex-row gap-10 items-start w-full">
                                        <!-- Profile Picture -->
                                        <div class="w-full max-w-xs aspect-square bg-black rounded-lg overflow-hidden">
                                            <img :src="`/storage/user/profile/${scholar.user?.picture}`"
                                                alt="Profile Picture" class="w-full h-full object-cover">
                                        </div>

                                        <!-- Personal Information -->
                                        <div class="w-full">
                                            <!-- Header with Divider -->
                                            <div class="flex items-center gap-2 mb-4">
                                                <h3 class="font-semibold text-lg text-blue-900 dark:text-white">
                                                    Applicant Information</h3>
                                                <div class="flex-1 h-0.5 bg-gray-300 rounded"></div>
                                            </div>

                                            <!-- Grid Info -->
                                            <div class="grid grid-cols-1 gap-6 w-full px-2 sm:px-4 md:px-10">
                                                <!-- Column 1 -->
                                                <div class="space-y-4">
                                                    <div class="text-black">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-500">Applicant
                                                            Name</span>
                                                        <p class="text-lg font-poppins text-primary">
                                                            {{ formatScholarName(scholar) }}
                                                        </p>

                                                    </div>
                                                    <div class="text-black">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-500">Contact
                                                            No.</span>
                                                        <p class="text-lg font-poppins text-primary">
                                                            <!-- {{ student.contact_no }} --> 0946 861 3366
                                                        </p>
                                                    </div>
                                                    <div class="text-black">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-500">Email
                                                            Address</span>
                                                        <p class="text-lg font-poppins text-primary">{{ scholar.email }}</p>
                                                    </div>
                                                    <div class="text-black">
                                                        <span
                                                            class="text-xs font-semibold uppercase text-gray-500">Address</span>
                                                        <p class="text-lg font-poppins text-primary">{{ scholar.street }} {{ scholar.municipality }}, {{ scholar.province }}</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div
                                    class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-base text-blue-900 dark:text-white">
                                        Educational Information
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full px-10">
                                    <!-- Column 2 -->
                                    <div class="space-y-4">
                                        <div class="text-black">
                                            <span class="text-xs font-semibold uppercase text-gray-500">URScholar
                                                ID</span>
                                            <p class="text-lg font-poppins text-primary">{{ scholar.urscholar_id }}</p>
                                        </div>
                                        <div class="text-black">
                                            <span class="text-xs font-semibold uppercase text-gray-500">Campus</span>
                                            <p class="text-lg font-poppins text-primary">{{ scholar.campus.name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col p-2 space-y-2">
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Program</span>
                                            <span class="text-xl font-poppins text-primary">{{ scholar.course.name }}</span>
                                        </div>
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">General Weighted
                                                Average</span>
                                            <span class="text-xl font-bold font-poppins" :class="grade ? 'text-primary' : 'text-red-500'">
                                                {{ grade ? grade.grade : 'No grade Uploaded' }}
                                            </span>

                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                                    <h3 class="font-semibold text-base text-blue-900 dark:text-white">
                                        Family Information
                                    </h3>
                                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-6 w-full px-10">
                                    <div class="flex flex-col p-2 space-y-3">
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Mother's
                                                Name</span>
                                            <span v-if="mother.first_name === 'n\/a'"
                                                class="text-xl font-poppins text-primary">
                                                Deceased
                                            </span>
                                            <span v-else class="text-xl font-poppins text-primary">
                                                {{ mother.last_name }},
                                                {{ mother.first_name }}
                                                {{mother.middle_name ? mother.middle_name.split(' ').map(word =>
                                                    word.charAt(0).toUpperCase()).join('.') + '.' : ''}}
                                            </span>
                                        </div>
                                        <div v-if="mother.first_name !== 'n\/a'" class="flex flex-col text-black">
                                            <span
                                                class="font-semibold uppercase text-xs text-gray-500">Occupation</span>
                                            <span class="text-xl font-poppins text-primary">{{ mother.occupation }}</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col p-2 space-y-2">
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Father's
                                                Name</span>
                                            <span v-if="father.first_name === 'n\/a'"
                                                class="text-xl font-poppins text-primary">
                                                Deceased
                                            </span>
                                            <span v-else class="text-xl font-poppins text-primary">
                                                {{ father.last_name }},
                                                {{ father.first_name }}
                                                {{father.middle_name ? father.middle_name.split(' ').map(word =>
                                                    word.charAt(0).toUpperCase()).join('.') + '.' : ''}}
                                            </span>
                                        </div>
                                        <div v-if="father.first_name !== 'n\/a'" class="flex flex-col text-black">
                                            <span
                                                class="font-semibold uppercase text-xs text-gray-500">Occupation</span>
                                            <span class="text-xl font-poppins text-primary">{{ father.occupation }}</span>
                                        </div>
                                    </div>

                                    <div class="col-span-2 flex flex-col p-2 space-y-3">
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Siblings</span>
                                            <div v-if="siblings.length === 0">
                                                <span class="text-xl font-poppins text-primary">
                                                    N/A
                                                </span>
                                            </div>
                                            <div v-else v-for="sibling in siblings" :key="sibling.id">
                                                <span class="text-xl font-poppins text-primary">
                                                    {{ sibling.last_name }},
                                                    {{ sibling.first_name }}
                                                    {{sibling.middle_name ? sibling.middle_name.split(' ').map(word =>
                                                        word.charAt(0).toUpperCase()).join('.') + '.' : ''}}
                                                </span>
                                                <span class="text-xl font-poppins text-primary">
                                                    - {{ sibling.occupation }}
                                                </span>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-span-2 flex flex-col p-2 space-y-3">
                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Marital Status
                                                of Parents</span>
                                            <span class="text-xl font-poppins text-primary">
                                                {{ family.marital_status }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Monthly Family
                                                Income</span>
                                            <span class="text-xl font-poppins text-primary">
                                                {{ family.monthly_income }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Other Source of
                                                Income</span>
                                            <span class="text-xl font-poppins text-primary">
                                                {{ family.other_income }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col text-black">
                                            <span class="font-semibold uppercase text-xs text-gray-500">Family Type of
                                                Housing</span>
                                            <span class="text-xl font-poppins text-primary">
                                                {{ family.family_housing }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 75% Column -->
                        <div class="col-span-1 h-full flex flex-col space-y-3">
                            <!-- Second Layer with Single Card -->
                            <div class="flex flex-col h-full gap-2">
                                <div
                                    class="bg-white p-6 box-border rounded shadow-md h-[100%] dark:bg-dcontainer flex flex-col space-y-3">
                                    <h1 class="font-semibold text-lg text-blue-900 dark:text-white">Application Requirements
                                    </h1>
                                    <div
                                        class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-gray-100 dark:scrollbar-track-gray-900">

                                        <!-- Requirement List -->
                                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                            <!-- Requirement Item -->
                                            <div v-for="req in submittedRequirements" :key="req.id"
                                                class="bg-gray-100 w-full rounded-lg p-3 flex justify-between items-center font-poppins text-primary">
                                                <div class="flex flex-col space-y-2">
                                                    <span class="font-bold">{{ req.requirement.requirements }}</span>
                                                    <div class="flex items-center gap-2 text-gray-800">
                                                        <font-awesome-icon :icon="['fas', 'file']"
                                                            class="text-blue-600 text-lg" />
                                                        <span class="text-base font-medium">{{
                                                            req.submitted_requirements }}</span>
                                                    </div>

                                                </div>

                                                <div class="flex flex-col gap-3 items-center justify-center">
                                                    <div>
                                                        <span :class="statusClass(req.status)"
                                                            class="text-sm font-medium px-2.5 py-0.5 rounded border">
                                                            {{ req.status }}
                                                        </span>
                                                    </div>
                                                    <button @click="toggleCheck(req)"
                                                        class="flex items-center gap-2 px-3 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all">
                                                        <span
                                                            class="material-symbols-rounded text-base">open_in_full</span>
                                                        <span class="font-medium text-sm">Check</span>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="bg-white p-6 box-border rounded shadow-md h-[100%] dark:bg-dcontainer flex flex-col space-y-3">
                                    <h1 class="font-semibold text-lg text-blue-900 dark:text-white">Monitoring</h1>
                                    <div
                                        class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700 scrollbar-track-gray-100 dark:scrollbar-track-gray-900">

                                        <div v-if="!grade"
                                            class="bg-gray-100 w-full rounded-lg p-3 flex justify-between items-center font-poppins text-primary mb-2">

                                            <!-- Message -->
                                            <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                                                <span class="font-medium">The student has not uploaded their grade
                                                    yet.</span>
                                            </div>

                                            <!-- Ping Button -->
                                            <button @click="notifyStudent"
                                                class="px-3 py-1 text-white text-sm font-medium rounded-lg transition"
                                                :class="notify?.read === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-primary hover:bg-primary/90'"
                                                :disabled="notify?.read === 0">
                                                {{ notify?.read === 0 ? 'Notified' : 'Ping Student' }}
                                            </button>



                                        </div>
                                        <div v-else
                                            class="bg-gray-100 w-full rounded-lg p-3 flex justify-between items-center font-poppins text-primary mb-2">
                                            <div class="flex flex-col space-y-2">
                                                <span>General Weighted Average</span>
                                                <span class="font-bold text-lg">{{ grade.grade }}</span>
                                            </div>
                                            <div class="flex flex-col space-y-2">
                                                <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                                                    <span class="font-medium">{{ grade.semester }} Semester -
                                                        {{ grade.school_year }}</span>
                                                </div>
                                                <div>
                                                    <button @click="toggleMonitor(grade)"
                                                        class="flex items-center gap-2 px-3 py-1 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all">
                                                        <span
                                                            class="material-symbols-rounded text-base">open_in_full</span>
                                                        <span class="font-medium text-sm">View Certificate of
                                                            Grades</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div v-if="Checking"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div
                class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-10/12 max-h-[95vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white pl-2">{{
                        selectedRequirement?.requirement.requirements }}</h2>
                    <div class="flex items-center justify-between gap-10">
                        <a :href="`/storage/${selectedRequirement?.path}`" target="_blank"
                            class="flex items-center gap-2 text-gray-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm px-3 py-1.5 dark:hover:bg-gray-600 dark:hover:text-white transition">
                            <span class="material-symbols-rounded text-lg">open_in_new</span>
                            <span class="font-medium">Open in New Tab</span>
                        </a>
                        <button type="button" @click="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-4 flex flex-col gap-3">
                    <div class="w-full flex justify-center p-5">
                        <!-- Display Image -->
                        <img v-if="selectedRequirement?.submitted_requirements.match(/\.(jpg|jpeg|png|gif)$/i)"
                            :src="`/storage/${selectedRequirement.path}`"
                            class="rounded-lg border shadow-sm max-w-full h-auto" alt="Submitted File">

                        <!-- Display PDF -->
                        <iframe v-else-if="selectedRequirement?.submitted_requirements.match(/\.(pdf)$/i)"
                            :src="`/storage/${selectedRequirement.path}#toolbar=0`"
                            class="w-full h-[600px] border rounded-lg"></iframe>

                        <!-- Display Message for Other File Types -->
                        <p v-else class="text-gray-600">
                            Cannot preview this file type. <a :href="`/storage/${selectedRequirement.path}`"
                                class="text-blue-600 underline" target="_blank">Download here</a>.
                        </p>
                    </div>

                    <!-- Returning Requirement Message -->
                    <div class="w-full flex flex-col space-y-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">*If Returning Requirement</h3>
                        <textarea id="return-requirement" placeholder="Add a message in returning"
                            v-model="returnMessage"
                            class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-6/12 h-32 resize-none text-left dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"></textarea>
                    </div>

                    <!-- Close Button -->
                    <div v-if="props.batch.status !== 'Inactive' && props.scholar.campus_id === $page.props.auth.user.campus_id"
                        class="mt-2 flex flex-row justify-between">
                        <button type="button" @click="updateRequirementStatus('Returned')"
                            class="text-white font-sans w-full bg-gradient-to-r from-red-700 via-red-800 to-red-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Return
                        </button>
                        <button type="button" @click="updateRequirementStatus('Approved')"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Approve
                        </button>
                    </div>
                    <div v-else class="mt-2 flex flex-row justify-between">
                        <button type="button" disabled
                            class="text-white font-sans w-full bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-70 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Return
                        </button>
                        <button type="button" disabled
                            class="text-white font-sans w-full bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 cursor-not-allowed opacity-70 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Approve
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div v-if="Monitoring"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div
                class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-10/12 max-h-[95vh] overflow-y-auto">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white pl-2">{{ selectedMonitor.school_year }}
                        {{ selectedMonitor.semester }} Semester</h2>
                    <div class="flex items-center justify-between gap-10">
                        <!-- <a :href="`/storage/${selectedRequirement?.path}`" target="_blank"
                            class="flex items-center gap-2 text-gray-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm px-3 py-1.5 dark:hover:bg-gray-600 dark:hover:text-white transition">
                            <span class="material-symbols-rounded text-lg">open_in_new</span>
                            <span class="font-medium">Open in New Tab</span>
                        </a> -->
                        <button type="button" @click="closeModal"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-4 flex flex-col gap-3">
                    <div class="w-full flex justify-center p-5">
                        <!-- Display Image -->
                        <img v-if="selectedMonitor?.cog.match(/\.(jpg|jpeg|png|gif)$/i)"
                            :src="`/storage/${selectedMonitor.path}`"
                            class="rounded-lg border shadow-sm max-w-full h-auto" alt="Submitted File">

                        <!-- Display PDF -->
                        <iframe v-else-if="selectedMonitor?.cog.match(/\.(pdf)$/i)"
                            :src="`/storage/${selectedMonitor.path}#toolbar=0`"
                            class="w-full h-[600px] border rounded-lg"></iframe>

                        <!-- Display Message for Other File Types -->
                        <p v-else class="text-gray-600">
                            Cannot preview this file type. <a :href="`/storage/${selectedMonitor.path}`"
                                class="text-blue-600 underline" target="_blank">Download here</a>.
                        </p>
                    </div>

                    <!-- Close Button -->
                    <!-- <div class="mt-2 flex flex-row justify-between">
                        <button type="button" @click="updateRequirementStatus('Returned')"
                            class="text-white font-sans w-full bg-gradient-to-r from-red-700 via-red-800 to-red-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Return
                        </button>
                        <button type="button" @click="updateRequirementStatus('Approved')"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Approve
                        </button>
                    </div> -->
                </div>
            </div>
        </div>

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">Scholars Added Successfully!</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps, ref, watchEffect, onBeforeMount, reactive, computed } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'


// components

const props = defineProps({
    scholar: Object,
    student: Object,
    education: Object,
    family: Object,
    siblings: Array,
    scholarship: Object,
    batch: Object,
    grade: Object,
    notify: Object,
    submittedRequirements: Array,
    requirements: Array,
});

const goBack = () => {
    window.history.back();
};

const isNotified = ref(false);

const notifyStudent = () => {
    router.post(route('scholarships.scholar_notifier', {
        scholarID: props.scholar.id
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
    isNotified.value = true;
};

// Submit reason form
const forwardCoor = () => {
    // No form data is actually being sent in your current implementation,
    // but you're using form.post. Let's simplify this:
    router.post(route('scholarship.forward_coor', {
        scholarshipId: props.scholarship.id, selectedSem: props.selectedSem, school_year: props.schoolyear.id,
        selectedCampus: props.selectedCampus
    }), {}, {
        onSuccess: () => {
            closeModal();
            showToast('Success', 'Batches forwarded successfully');
        },
        onError: (errors) => {
            console.error('Error forwarding batches:', errors);
        }
    });
};


const statusClass = (status) => {
    switch (status) {
        case 'Pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-gray-700 dark:text-yellow-400 border-yellow-400';
        case 'Returned':
            return 'bg-red-100 text-red-800 dark:bg-gray-700 dark:text-red-400 border-red-400';
        case 'Approved':
            return 'bg-green-100 text-green-800 dark:bg-gray-700 dark:text-green-400 border-green-400';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400 border-gray-400';
    }
};

// const parsedRequirements = computed(() => {
//     try {
//         if (Array.isArray(props.requirements.requirements)) {
//             return props.requirements.requirements;
//         }
//         return JSON.parse(props.requirements.requirements);
//     } catch (error) {
//         console.error("Error parsing requirements JSON:", error);
//         return [];
//     }
// });

const formatScholarName = (scholar) => {
    const middle = scholar.middle_name
        ? scholar.middle_name.split(' ').map(word => word.charAt(0).toUpperCase()).join('.') + '.'
        : '';
    return `${scholar.last_name}, ${scholar.first_name} ${middle}`;
};


const Checking = ref(false);
const Monitoring = ref(false);

const selectedRequirement = ref(null);
const selectedMonitor = ref(null);

const toggleCheck = (req) => {
    selectedRequirement.value = req;
    Checking.value = true;
};

const toggleMonitor = (monitor) => {
    selectedMonitor.value = monitor;
    Monitoring.value = true;
};

const closeModal = () => {
    Checking.value = false;
    selectedRequirement.value = null;
    Monitoring.value = false;
    resetForm();
};

const returnMessage = ref('');

const updateRequirementStatus = (status) => {

    if (selectedRequirement.value) {

        // Send an update request to the backend
        router.post('/scholarships/scholar/update-requirements', {
            submittedReq: selectedRequirement.value.id,
            status: status,
            message: status === 'Returned' ? returnMessage.value : null
        }, {
            onSuccess: () => {
                closeModal();
                toastMessage.value = `Requirement ${status.toLowerCase()} successfully!`;
                toastVisible.value = true;


                setTimeout(() => {
                    toastVisible.value = false;
                }, 3000);
            },
            onError: (errors) => {
                console.error(errors);
            }
        });
    }
};

const toastVisible = ref(false);
const toastMessage = ref("");

watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        console.log("Showing toast with message:", flashMessage);
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            console.log("Hiding toast...");
            toastVisible.value = false;
        }, 3000);
    }
});

const elementary = computed(() => {
    try {
        return JSON.parse(props.education.elementary);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const junior = computed(() => {
    try {
        return JSON.parse(props.education.junior);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const senior = computed(() => {
    try {
        return JSON.parse(props.education.senior);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const college = computed(() => {
    try {
        return JSON.parse(props.education.college);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const vocational = computed(() => {
    try {
        return JSON.parse(props.education.vocational);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const postgrad = computed(() => {
    try {
        return JSON.parse(props.education.postgrad);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const mother = computed(() => {
    try {
        return JSON.parse(props.family.mother);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const father = computed(() => {
    try {
        return JSON.parse(props.family.father);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});
</script>

<style>
/* override the prime vue componentss */

.p-fileupload-choose-button {
    background-color: #003366 !important;
    color: white !important;
    border-radius: 4px;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}

.slide-enter-to,
.slide-leave-from {
    transform: translateX(0);
}

/* Fade transition for backdrop */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>