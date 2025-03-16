<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-5">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">{{ scholarship.name }} </span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <!-- <span>{{ scholarship.name }}</span> <span>{{schoolyear.year}} {{props.selectedSem}} Semester</span> -->
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span><span>{{
                                scholarship.name }}</span> <span>{{ scholarship.scholarshipType }}</span>
                        </h1>
                    </div>
                    <!--Condition for scholarship type-->
                    <div v-if="scholarship.scholarshipType == 'Need-Based'" class="flex gap-2">
                        <div v-if="students.length == 0" class="flex flex-col items-end">
                            <button 
                                v-tooltip.left="'You need to add students before importing scholars'"
                                disabled 
                                class="px-4 py-2 text-sm text-primary dark:text-dtext bg-yellow-100 dark:bg-yellow-800 
                                    border border-yellow-300 dark:border-yellow-500 rounded-lg hover:bg-yellow-200 
                                    font-poppins flex items-center gap-2"
                            >
                                <i class="pi pi-exclamation-triangle text-yellow-600 dark:text-yellow-300"></i>
                                <font-awesome-icon :icon="['fas', 'user-plus']" class="text-sm dark:text-dtext" />
                                <span>Import Scholars</span>
                            </button>

                        </div>
                        <div v-else>
                            <button @click="openScholarship"
                                class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                                <span><font-awesome-icon :icon="['fas', 'user-plus']"
                                        class="mr-2 text-sm dark:text-dtext" />Import Scholars</span>
                            </button>
                        </div>
                        <Link :href="`/scholarships/${props.scholarship.id}/send-access`">
                        <button @click="importScholars"
                            class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins">
                            <span><font-awesome-icon :icon="['far', 'envelope']"
                                    class="mr-2 text-sm dark:text-dtext" />Send Email</span>
                        </button>
                        </Link>
                    </div>
                </div>

                <!-- <ScholarList :scholarship="scholarship" :batches="batches" /> -->
                <!-- <Batches :scholarship="scholarship" :batches="batches" :schoolyear="schoolyear" :selectedSem="selectedSem" class="w-full h-full"/> -->

                <div v-if="scholarship.scholarshipType == 'Need-Based'">
                    <div v-if="!batches || batches.length === 0" class="text-center py-5 mt-5">
                        <p class="bg-white p-5 rounded-lg text-lg shadow-sm text-gray-700 dark:text-gray-300">No
                            scholars
                            added yet</p>
                    </div>

                    <div v-else class="w-full mt-5 rounded-xl space-y-5">
                        <!-- Stats Section -->
                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="grid grid-cols-5">
                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Scholarship Batches</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <p class="text-4xl font-semibold font-kanit">{{ props.batches.length }}</p>
                                    <button class="px-3 bg-blue-400 text-white rounded-full text-sm">2 new
                                        Batch</button>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Verified Scholars</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <p class="text-4xl font-semibold font-kanit">{{verified_scholars}}</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Unverified Scholars</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">{{ unverified_scholars }}</p>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'user-clock']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Submitted Requirements</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">2/{{ total_scholars }}</p>
                            </div>

                            <!-- <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Completed Scholars</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">2</p>
                            </div> -->
                        </div>

                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="flex flex-row justify-between items-center">
                            <span>List of Batches {{ props.selectedSem }} {{ schoolyear.year }}</span>

                            <button @click="toggleSendBatch"
                                class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                <span class="font-normal">Forward Completed Scholars</span>
                            </button>
                        </div>


                        <div v-for="batch in batches" :key="batch.id"
                            class="bg-gradient-to-r from-white to-[#D2CFFE] w-full rounded-lg p-5 shadow-sm hover:bg-lightblue">
                            <div @click="() => openBatch(batch.id)"
                                class="flex flex-row justify-between items-center cursor-pointer">
                                <span>Batch {{ batch.batch_no }}</span>
                                <div class="grid grid-cols-2">
                                    <div class="flex flex-col">
                                        <span>No of Scholars</span>
                                        <span>200</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span>No of Unverified Scholars</span>
                                        <span>200</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--ONE TIME PAYMENT PROCESS-->
                <div v-else>
                    <form @submit.prevent="submitForm">
                        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-4">
                            <!-- <div class="mx-auto max-w-8xl sm:px-6 lg:px-8 "> -->
                            <div class="w-full block bg-white p-5 flex-col items-center mx-auto sm:px-6 lg:px-8">
                                <span>
                                    Set up One-Time Payment Scholarship Details
                                </span>
                                <div class="mt-5 font-inter text-lg space-y-3">
                                    <div class="flex flex-row w-full gap-3">
                                        <div class="flex flex-col space-y-2 w-full">
                                            <label for="suffixName"
                                                class="text-sm font-medium text-gray-700">Scholarship Name</label>
                                            <input id="suffixName" :value="scholarship.name" readonly type="text"
                                                placeholder="Scholarship Name"
                                                class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                                        </div>

                                        <div class="flex flex-col space-y-2 w-full">
                                            <label for="suffixName"
                                                class="text-sm font-medium text-gray-700">Scholarship Type</label>
                                            <input id="suffixName" :value="scholarship.scholarshipType" readonly
                                                type="text" placeholder="Scholarship Type"
                                                class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                                        </div>
                                    </div>

                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <div class="flex flex-row w-6/12 gap-3 pr-2">
                                        <div id="date-range-picker" date-rangepicker
                                            class="flex items-center gap-4 w-full">
                                            <!-- Application Start Date -->
                                            <div class="flex flex-col w-full">
                                                <label for="datepicker-range-start"
                                                    class="text-sm font-medium text-gray-700 mb-1">Application
                                                    Start</label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                        </svg>
                                                    </div>
                                                    <input id="datepicker-range-start" name="start" type="text"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Select start date">
                                                </div>
                                            </div>

                                            <span class="text-gray-500">to</span>

                                            <!-- Application Deadline -->
                                            <div class="flex flex-col w-full">
                                                <label for="datepicker-range-end"
                                                    class="text-sm font-medium text-gray-700 mb-1">Application
                                                    Deadline</label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                        </svg>
                                                    </div>
                                                    <input id="datepicker-range-end" name="end" type="text"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Select end date">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <!-- Total Recipients Input -->
                                    <div class="flex w-6/12 pr-2 flex-col">
                                        <label for="totalRecipients" class="text-sm font-medium text-gray-700">
                                            Number of Recipients
                                        </label>
                                        <input id="totalRecipients" type="number" v-model="form.totalRecipients" min="1"
                                            placeholder="Enter total recipients"
                                            class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded-lg"
                                            @input="distributeRecipients" />
                                    </div>

                                    <!-- Campus Selection & Recipient Distribution -->
                                    <div class="grid grid-cols-2 w-full gap-3">
                                        <!-- Left Side: Campus Selection & Recipient Distribution -->
                                        <div class="flex flex-col space-y-3">
                                            <!-- Header with Label & Stats -->
                                            <div class="flex flex-row justify-between items-center py-2">
                                                <div class="flex flex-col space-y-2">
                                                    <label class="text-sm font-medium">Distribute Recipients per
                                                        Selected Campus</label>
                                                    <div class="flex flex-row text-sm gap-4">
                                                        <div>Allocated: {{ allocatedRecipients }} of {{
                                                            form.totalRecipients
                                                            }}</div>
                                                        <div v-if="allocatedRecipients !== parseInt(form.totalRecipients)"
                                                            class="text-red-500 font-medium">
                                                            *{{ parseInt(form.totalRecipients) - allocatedRecipients }}
                                                            recipients still need to be allocated
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col text-sm">
                                                    <!-- Stats & Reset Button -->
                                                    <div class="flex items-center text-sm text-gray-600 space-x-3">
                                                        <!-- Reset to Auto-Distribution Button -->
                                                        <button @click="distributeRecipients"
                                                            class="px-2 text-gray-700 flex items-center space-x-1 hover:text-blue-600">
                                                            <span
                                                                class="material-symbols-rounded text-base">restart_alt</span>
                                                            <span>Reset to Auto Distribution</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Campus Selection List -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div v-for="campus in campusesData" :key="campus.id"
                                                    class="flex flex-row items-center space-x-2">
                                                    <!-- Checkbox to select campus -->
                                                    <input type="checkbox" :id="`campus-${campus.id}`"
                                                        v-model="campus.selected" @change="distributeRecipients"
                                                        class="rounded" />
                                                    <label :for="`campus-${campus.id}`"
                                                        class="text-sm font-medium leading-none cursor-pointer">
                                                        {{ campus.name }}
                                                    </label>

                                                    <!-- Recipients per campus input -->
                                                    <input type="number" v-model="campus.recipients"
                                                        :readonly="!campus.selected" :class="`w-20 px-2 py-1 border rounded-md text-center ${!campus.selected ? 'bg-gray-100 border-gray-300' : 'bg-white border-blue-300'
                                                            }`" min="0" :max="form.totalRecipients"
                                                        @input="onRecipientManualChange(campus.id)" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Side: Course List Display Grouped by Campus -->
                                        <div class="flex flex-col space-y-4">
                                            <div v-for="campus in selectedCampuses" :key="campus.id"
                                                class="py-3 px-4 bg-gray-50 border rounded-md">
                                                <!-- Selected Campus Name -->
                                                <div class="text-sm font-semibold text-gray-700 mb-2">
                                                    {{ campus.name }}
                                                </div>

                                                <!-- Courses Offered for this Campus -->
                                                <div v-for="course in campus.courses" :key="course" class="mt-1">
                                                    <input type="checkbox" :id="`course-${campus.id}-${course}`"
                                                        v-model="selectedCoursesMap[course]" class="rounded" />
                                                    <label :for="`course-${campus.id}-${course}`"
                                                        class="text-sm ml-2 cursor-pointer">{{ course }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="w-full border-t border-gray-200 my-4"></div>


                                    <div class="flex flex-row space-x-2">
                                        <div class="w-full">
                                            <label for="totalRecipients" class="text-sm font-medium text-gray-700">
                                                List Criteria and Eligibility
                                            </label>
                                            <!-- Campus Selection List -->
                                            <div class="grid grid-cols-1 md:grid-cols-1 gap-3 mt-2">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Checkbox to accept terms and conditions -->
                                                    <input id="accept-terms" type="checkbox"
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <label for="accept-terms"
                                                        class="text-sm font-medium text-gray-700 cursor-pointer">
                                                        First Condition
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="w-full">
                                            <label for="totalRecipients" class="text-sm font-medium text-gray-700">
                                                List Requirements
                                            </label>
                                            <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                                <div class="flex items-center mb-4 w-full">
                                                    <form @submit.prevent="addCriteria"
                                                        class="flex items-center w-full">
                                                        <input v-model="newCriteria" type="text"
                                                            placeholder="Enter an item"
                                                            class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                                        <button type="submit"
                                                            class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                            Add
                                                        </button>
                                                    </form>
                                                </div>

                                                <form @submit.prevent="removeCriteria">
                                                    <div class="flex flex-col gap-2">
                                                        <div v-for="(criteria, index) in criteria" :key="index"
                                                            class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                                            <span>{{ criteria }}</span>
                                                            <button @click="removeItem(index)"
                                                                class="flex items-center text-red-500 hover:text-red-700">
                                                                <span class="material-symbols-rounded text-red-600">
                                                                    delete
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                        <div class="flex justify-end mt-8">
                            <button type="submit" @click="submitForm"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                {{ isSubmitting ? 'Creating...' : 'Create Scholarship' }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Simplified forwarding batch list modal -->
        <div v-if="ForwardBatchList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                            Forwarding Batch List
                        </h2>
                    </span>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- body -->
                <div class="py-4 px-8 flex flex-col gap-3">
                    <div class="mb-4">
                        <label for="batchSelection"
                            class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                            Select a Batch to Forward:
                        </label>
                        <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">
                            <!-- Application Start Date -->
                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input :value="selectedStart" @input="selectedStart = $event.target.value"
                                        id="datepicker-range-start" name="start" type="text" autocomplete="off"
                                        lang="en"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Submission Start Date">
                                </div>
                            </div>

                            <span class="text-gray-500">to</span>

                            <!-- Application Deadline -->
                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input :value="selectedEnd" @input="selectedEnd = $event.target.value"
                                        id="datepicker-range-end" name="end" type="text" autocomplete="off" lang="en"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Submission Deadline">
                                </div>
                            </div>
                        </div>
                    </div>

                    <label for="batchSelection" class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                        Select a Batch to Forward:
                    </label>

                    <!-- Loading indicator -->
                    <div v-if="isLoading" class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                    </div>

                    <!-- Checkbox List -->
                    <div v-if="!isLoading" class="flex flex-col gap-2">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" value="all" v-model="selectedBatches" @change="selectAllBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Send All Batch List</span>
                        </label>

                        <label v-for="batch in batchesWithScholars" :key="batch.id" class="flex items-center space-x-2">
                            <input type="checkbox" :value="batch.id" v-model="selectedBatches"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-gray-900 dark:text-white">Batch {{ batch.batch_no }}</span>
                            <span class="text-sm text-gray-500">({{ batch.scholar_count }} scholars)</span>
                        </label>
                    </div>

                    <!-- Forward Button -->
                    <div class="mt-4">
                        <button :disabled="isSubmitting || selectedBatches.length === 0" @click="forwardBatches"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ isSubmitting ? 'Processing...' : 'Forward' }}
                        </button>
                    </div>
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
import { defineProps, ref, watchEffect, onBeforeMount, reactive, onMounted, watch, computed } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Checkbox } from '@/Components/ui/checkbox'
import { Input } from '@/Components/ui/input'
import { initFlowbite } from 'flowbite';
import { Tooltip } from 'primevue';


// Define props to include scholars data
const props = defineProps({
    batches: Array,
    scholarship: Object,
    schoolyear: Object,
    selectedSem: String,
    scholars: Array, // Add scholars prop
    campuses: Array,
    courses: Array,
    students: Array,
    total_scholars: Array,
    requirements: Array,
});

const directives = {
    Tooltip,
};

// Forward batch modal state
const ForwardBatchList = ref(false);
const selectedBatches = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const batchesWithScholars = ref([]);

const selectedStart = ref(""); // Stores the selected start date
const selectedEnd = ref("");   // Stores the selected end date

// Count scholars with "Verified" status
const verified_scholars = computed(() => {
    return props.total_scholars.filter(scholar => scholar.status === "Verified").length;
});

// Count scholars with "Unverified" status
const unverified_scholars = computed(() => {
    return props.total_scholars.filter(scholar => scholar.status === "Unverified").length;
});

const total_scholars = computed(() => {
    return props.total_scholars.filter(scholar => {
        // Add your conditions here, for example:
        // return scholar.isActive === true;
        return true; // Count all scholars by default
    }).length;
});


const toggleSendBatch = async () => {
    ForwardBatchList.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const loadBatchesData = async () => {
    isLoading.value = true;

    try {
        // Calculate scholar counts for each batch using the scholars prop
        setTimeout(() => {
            // Group scholars by batch_id and count them
            const scholarCountsByBatch = props.scholars.reduce((counts, scholar) => {
                if (scholar.batch_id) {
                    counts[scholar.batch_id] = (counts[scholar.batch_id] || 0) + 1;
                }
                return counts;
            }, {});

            // Map batches with their scholar counts
            batchesWithScholars.value = props.batches.map(batch => {
                return {
                    ...batch,
                    scholar_count: scholarCountsByBatch[batch.id] || 0
                };
            });

            // Automatically select all batches when data is loaded
            selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];

            isLoading.value = false;
        }, 300);
    } catch (error) {
        console.error('Error loading batch data:', error);
        toastMessage.value = 'Failed to load batch data';
        toastVisible.value = true;
        isLoading.value = false;
    }
};

const selectAllBatches = () => {
    if (selectedBatches.value.includes('all')) {
        // If 'all' is selected, select all batch IDs
        selectedBatches.value = ['all', ...batchesWithScholars.value.map(batch => batch.id)];
    } else {
        // If 'all' is unselected, clear all selections
        selectedBatches.value = [];
    }
};

// Form data
const form = ref({
    name: '',
    scholarshipType: '',
    totalRecipients: 0,
    reqs: [],
    criteria: [],
    amount: 0,
});


const newReq = ref("");
const reqs = ref([]);

const newCriteria = ref("");
const criteria = ref([]);

const addReq = () => {
    if (newReq.value.trim() !== "") {
        reqs.value.push(newReq.value.trim());
        newReq.value = ""; // Clear input after adding
    }
};

const addCriteria = () => {
    if (newCriteria.value.trim() !== "") {
        criteria.value.push(newCriteria.value.trim());
        newCriteria.value = ""; // Clear input after adding
    }
};

// Separate remove functions
const removeReq = (index) => {
    reqs.value.splice(index, 1);
};

const removeCriteria = (index) => {
    criteria.value.splice(index, 1);
};


// Create reactive campus array from props with selection state
const campusesData = ref([]);

// Initialize campus data from props
onMounted(() => {
    // Transform props.campuses into the format we need
    if (props.campuses && props.campuses.length > 0) {
        campusesData.value = props.campuses.map(campus => ({
            id: campus.id,
            name: campus.name,
            selected: false,
            recipients: 0,
            // Get courses associated with this campus
            courses: props.courses
                ? props.courses.filter(course => course.campus_id === campus.id)
                    .map(course => course.name)
                : []
        }));
    }

    if (props.batches && props.batches.length > 0) {
        expandedBatches.value = props.batches[0].id;
    }

    // Initialize Flowbite Datepicker
    const dateInput = document.getElementById("datepicker-autohide");
    if (dateInput) {
        const datepicker = new Datepicker(dateInput, {
            autohide: true,
            format: "yyyy-mm-dd", // Adjust format as needed
        });

        dateInput.addEventListener("changeDate", (event) => {
            form.value.birthdate = event.target.value;
        });
    }

    const startInput = document.getElementById("datepicker-range-start");
    if (startInput) {
        startInput.value = selectedStart.value; // Keep the previous value
        startInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value);
            form.value.application = date.toISOString().split("T")[0];
            console.log("Application:", form.value.application);
            selectedStart.value = event.target.value;
        });
    }


    const endInput = document.getElementById("datepicker-range-end");
    if (endInput) {
        endInput.value = selectedEnd.value; // Keep the previous value
        endInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value);
            form.value.deadline = date.toISOString().split("T")[0];
            selectedEnd.value = event.target.value;
        });
    }

    // Initial distribution
    distributeRecipients();
    initFlowbite();
});


// Ensure selected values persist
watch(selectedStart, (newVal) => {
    document.getElementById("datepicker-range-start").value = newVal;
});

watch(selectedEnd, (newVal) => {
    document.getElementById("datepicker-range-end").value = newVal;
});

watch(ForwardBatchList, (newValue) => {
    if (newValue) {
        setTimeout(() => {
            initFlowbite(); // Initialize the modal components
        }, 200);
    }
});

// Compute selected campuses dynamically
const selectedCampuses = computed(() =>
    campusesData.value.filter(campus => campus.selected)
);

// Calculate total allocated recipients
const allocatedRecipients = computed(() => {
    return campusesData.value.reduce(
        (sum, campus) => sum + parseInt(campus.recipients || 0), 0
    );
});

// Helper to access the total recipients value
const totalRecipients = computed(() => parseInt(form.value.totalRecipients) || 0);

// Function to distribute recipients equally when checking/unchecking a campus
const distributeRecipients = () => {
    const selectedCount = selectedCampuses.value.length;

    if (selectedCount === 0 || totalRecipients.value === 0) {
        campusesData.value.forEach(campus => campus.recipients = 0);
        return;
    }

    const share = Math.floor(totalRecipients.value / selectedCount);
    const remainder = totalRecipients.value % selectedCount;

    campusesData.value.forEach(campus => {
        if (!campus.selected) {
            campus.recipients = 0;
            return;
        }

        // Find the index in the selected campuses array
        const index = selectedCampuses.value.findIndex(c => c.id === campus.id);
        campus.recipients = share + (index < remainder ? 1 : 0);
    });
};

// Handle manual change to a campus's recipients
const onRecipientManualChange = (changedCampusId) => {
    const changedCampus = campusesData.value.find(c => c.id === changedCampusId);

    // Ensure value is a valid number and not less than 0
    changedCampus.recipients = Math.max(0, parseInt(changedCampus.recipients) || 0);

    // If changing this would exceed total, cap it
    if (allocatedRecipients.value > totalRecipients.value) {
        changedCampus.recipients = Math.max(0,
            parseInt(changedCampus.recipients) - (allocatedRecipients.value - totalRecipients.value)
        );
    }
};

// Watch total recipients and automatically redistribute
watch(() => form.value.totalRecipients, distributeRecipients);

// Initial distribution
distributeRecipients();

// Store selected courses
const selectedCoursesMap = ref({});

// Compute selected courses dynamically based on checked campuses
const selectedCourses = computed(() => {
    let courses = [];
    campusesData.value.forEach((campus) => {
        if (campus.selected) {
            courses = [...new Set([...courses, ...campus.courses])]; // Remove duplicates
        }
    });

    // Sync the selected courses in the map
    selectedCoursesMap.value = courses.reduce((acc, course) => {
        acc[course] = selectedCoursesMap.value[course] || false;
        return acc;
    }, {});

    return courses;
});

// Update selected courses whenever a campus is checked/unchecked
const updateSelectedCourses = () => {
    selectedCourses.value; // Triggers computed property update
};

const submitForm = () => {

    // Prepare campus recipients data for the backend
    const campusRecipients = selectedCampuses.value.map(campus => ({
        campus_id: campus.id,
        slots: parseInt(campus.recipients),
        remaining_slots: parseInt(campus.recipients),
        selected_campus: JSON.stringify(
            campus.courses
                .filter(course => selectedCoursesMap.value[course])
                .map(course => ({ course }))
        ),
    }));

    // Create the payload
    const payload = {
        // name: form.value.name,
        // scholarship_type: form.value.scholarshipType,
        total_recipients: form.value.totalRecipients,
        // requirements: form.value.reqs,
        // criteria: form.value.criteria,
        amount: form.value.scholarshipType === 'One-Time' ? form.value.amount : null,
        campus_recipients: campusRecipients,
    };

    // Submit the form to the backend
    router.post(`/sholarships/${props.scholarship.id}/one-time-payment`, payload, {
        onSuccess: () => {
            showToast('Success', 'Scholarship created successfully');
            setTimeout(() => {
                router.visit('/scholarships');
            }, 1500);
        },
        onError: (errors) => {
            showToast('Error', 'There was an error creating the scholarship');
            errors.value = errors;
            isSubmitting.value = false;
        },
    });

}


// Watch for changes in individual batch selections
watchEffect(() => {
    // If any individual batch is unselected and 'all' was selected, unselect 'all'
    const allBatchIds = batchesWithScholars.value.map(batch => batch.id);

    if (selectedBatches.value.includes('all') &&
        !allBatchIds.every(id => selectedBatches.value.includes(id))) {
        selectedBatches.value = selectedBatches.value.filter(id => id !== 'all');
    }

    // If all individual batches are selected, also select 'all'
    if (allBatchIds.length > 0 &&
        allBatchIds.every(id => selectedBatches.value.includes(id)) &&
        !selectedBatches.value.includes('all')) {
        selectedBatches.value.push('all');
    }
});

const forwardBatches = async () => {
    isSubmitting.value = true;

    try {
        // Prepare data for submission
        const batchesToForward = selectedBatches.value.includes('all')
            ? batchesWithScholars.value.map(batch => batch.id)
            : selectedBatches.value;

        // Create payload with selected batches
        const payload = {
            scholarship_id: props.scholarship.id,
            scholars: batchesWithScholars.value.reduce((scholars, batch) => {
                if (batchesToForward.includes(batch.id)) {
                    scholars.push(...props.scholars.filter(s => s.batch_id === batch.id));
                }
                return scholars;
            }, []),
            batch_ids: batchesToForward
        };

        await router.post(`/scholarship/forward-batches`, payload);

        // In a real implementation, you would submit to the backend
        setTimeout(() => {
            // Simulate successful submission
            const totalScholars = batchesToForward.reduce((total, batchId) => {
                const batch = batchesWithScholars.value.find(b => b.id === batchId);
                return total + (batch ? batch.scholar_count : 0);
            }, 0);

            toastMessage.value = `Successfully forwarded ${totalScholars} scholars from ${batchesToForward.length} batch(es)`;
            toastVisible.value = true;

            // Close the modal and reset form
            closeModal();

            isSubmitting.value = false;
        }, 1000);

        // In a real implementation, you would use fetch or Inertia.js
    } catch (error) {
        console.error('Error forwarding batches:', error);
        toastMessage.value = error.message || 'Failed to forward batches';
        toastVisible.value = true;
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    ForwardBatchList.value = false;
    resetForm();
};

const resetForm = () => {
    selectedBatches.value = [];
    batchesWithScholars.value = [];
};

// Existing code remains the same
const addVisible = ref(false);
const List = ref(true);

const toggleAdd = () => {
    addVisible.value = true;
    List.value = false;
};

const toggleList = () => {
    List.value = true;
    addVisible.value = false;
};

// Reactive variables to track which tab is open
const activeTab = ref("scholars"); // Default to Scholars

// Functions to toggle the active tab
const toggleScholars = () => {
    activeTab.value = "scholars";
};

const toggleReqs = () => {
    activeTab.value = "requirements";
};

const toggleMonitoring = () => {
    activeTab.value = "monitoring";
};

const openBatch = (batchId) => {
    router.visit(`/scholarships/${props.scholarship.id}/batch/${batchId}`, {
        data: {
            scholarship: props.scholarship.id,
            selectedYear: props.schoolyear.id,
            selectedSem: props.selectedSem
        },
        preserveState: true
    });
};

const expandedBatches = ref(new Set([props.batches?.[0]?.id])) // First batch expanded by default


const selectedSem = ref("");

selectedSem.value = props.selectedSem;

const openScholarship = () => {
    router.visit(`/scholarships/${props.scholarship.id}/adding-scholars`, {
        data: { selectedYear: props.schoolyear.id, selectedSem: props.selectedSem },
        preserveState: true
    });
};

const formData = ref({
    file: null,
    // other form fields...
});

const updateFile = (file) => {
    formData.value.file = file;
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
</script>

<style scoped>
/* override the prime vue componentss */
:root {
  --p-tooltip-background: #D97706 !important; /* Yellow warning color */
}

.p-tooltip-text {
  font-size: 12px !important;
  color: white !important;
}

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