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
                            <span class="text-blue-400 font-semibold dark:text-gray-300">View Sponsors</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between items-center mb-4">
                    
                    <h1 class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                        <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>URS Partnered Sponsors
                    </h1>

                </div>

                <!-- List of Scholarships -->
                <div v-if="!Showcase">
                    <div class="py-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div v-for="sponsor in sponsors" :key="sponsor.id"
                                class="relative bg-white border border-gray-300 rounded-lg shadow-lg hover:shadow-xl hover:border-gray-400 
                                    dark:bg-dcontainer dark:border-gray-600 dark:hover:border-gray-400 
                                    before:absolute before:top-0 before:left-0 before:right-0 before:h-10 before:rounded-t-lg before:bg-white 
                                    dark:before:bg-dcontainer dark:before:border-dsecondary flex flex-col min-h-[400px]">

                                <!-- Logo Positioned Above Card with Border Around It -->
                                <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-white dark:bg-dcontainer 
                                            p-1 rounded-full border border-gray-300 dark:border-dsecondary shadow-md">
                                    <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo"
                                        class="w-24 h-24 rounded-full" />
                                </div>

                                <!-- Card Content -->
                                <div class="mt-12 pt-12 p-3 flex flex-col flex-grow">
                                    <!-- Sponsor Name & Info -->
                                    <div class="flex flex-col items-center text-center gap-1">
                                        <span class="text-3xl font-semibold text-gray-800 dark:text-dtext">
                                            {{ sponsor.name }} 
                                            <span class="text-gray-500 dark:text-gray-400">({{ sponsor.abbreviation }})</span>
                                        </span>
                                        <span class="inline-flex bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-dsecondary dark:text-blue-400 border border-blue-400 dark:border-gray-600">
                                            Sponsoring Since: {{ sponsor.since }}
                                        </span>
                                    </div>


                                    <!-- Active Scholarships -->
                                    <div class="flex flex-col flex-grow mt-4 justify-end">
                                        <p class="text-sm text-gray-400 mb-2">Active Scholarships:</p>

                                        <div class="max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer flex flex-col gap-2">
                                            <!-- No Scholarships Available -->
                                            <div v-if="sponsor.scholarship.length === 0" 
                                                class="p-3 text-gray-500 text-center bg-gray-100 rounded-lg border border-gray-300 shadow-sm 
                                                    dark:bg-dsecondary dark:text-gray-400 dark:border-gray-600 flex items-center justify-center h-full">
                                                No scholarships available.
                                            </div>

                                            <!-- Scholarships List -->
                                            <div v-else v-for="scholarship in sponsor.scholarship" :key="scholarship.id">
                                                <div class="flex flex-row p-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 border border-blue-400 shadow-lg 
                                                    hover:shadow-xl transition-all duration-300 justify-between items-center text-white 
                                                    dark:from-dsecondary dark:to-dprimary dark:border-gray-600">
                                                
                                                    <!-- Scholarship Name -->
                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-lg">
                                                            {{ scholarship.name }}
                                                        </span>
                                                        <span class="font-normal text-sm">
                                                            {{ scholarship.scholarshipType }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-row items-center gap-2">
                                                        <!-- Green Active Indicator -->
                                                        <span v-if="scholarship.status === 'Active'" 
                                                            class="w-3 h-3 bg-green-500 rounded-full shadow-md">
                                                        </span>

                                                        <!-- Status Text -->
                                                        <span class="text-sm font-medium">
                                                            {{ scholarship.status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sticky Button at the Bottom -->
                                <div class="p-3 mt-auto flex justify-end">
                                    <button @click="toggleCreate(sponsor.id)">
                                        <div class="text-sm text-gray-500 cursor-pointer"
                                            v-tooltip="'Create Scholarship'">
                                            <span
                                                class="material-symbols-rounded text-blue-900 dark:text-dtext bg-blue-100 hover:bg-gray-200 p-3 border rounded-lg dark:bg-dsecondary dark:border-gray-600 dark:hover:border-gray-300 dark:hover:bg-dsecondary">
                                                open_in_browser
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!-- creating a sponsor --> 
        <div v-if="isCreating"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-6/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Create a New Scholarship
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Provide the necessary details to set up a scholarship.
                            </span>
                        </div>
                    </div>


                    <!-- Close Button -->
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-10">
                    
                    <!-- Page 1: Basic Information -->
                    <div v-if="currentPage === 1">
                        <div class="flex flex-row gap-3">
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Name</h3>
                                <input v-model="form.name" type="text" id="name" placeholder="Enter Scholarship Name"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                            </div>
                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship Type</h3>
                                <select v-model="form.scholarshipType" id="scholarshipType"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                    <option value="" disabled>Select Scholarship Type</option>
                                    <option value="Grant-Based">Grant-Based</option>
                                    <option value="Academic Scholarship">Academic Scholarship</option>
                                    <option value="One-time Payment">One-time Payment</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Set Scholarship
                                Timeline</h3>
                                <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">
                                <!-- Application Start Date -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                            </svg>
                                        </div>
                                            <input v-model="selectedStart" id="datepicker-range-start" name="start" type="text" autocomplete="off" lang="en"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                            placeholder="Submission Start Date">
                                    </div>
                                </div>

                                <span class="text-gray-500">to</span>

                                <!-- Application Deadline -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                            </svg>
                                        </div>
                                            <input v-model="selectedEnd" id="datepicker-range-end" name="end" type="text" autocomplete="off" lang="en"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                            placeholder="Submission Start Date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page 2: recipients -->
                    <div v-if="currentPage === 2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Left Side: Number of Recipients & Campus Selection -->
                            <div class="space-y-4">
                                <!-- Total Recipients Input -->
                                <div class="flex flex-col">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Number of Recipients</h3>
                                    <input 
                                        id="totalRecipients" 
                                        type="number" 
                                        v-model="form.totalRecipients" 
                                        min="1"
                                        placeholder="Enter total recipients"
                                        class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded-lg"
                                        @input="distributeRecipients" 
                                    />
                                </div>

                                <!-- Campus Selection & Recipient Distribution -->
                                <div class="space-y-3">
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium">
                                            Distribute Recipients per Selected Campus
                                        </label>
                                        <div class="flex justify-between text-sm">
                                            <span>Allocated: {{ allocatedRecipients }} of {{ form.totalRecipients }}</span>
                                            <span v-if="allocatedRecipients !== parseInt(form.totalRecipients)" class="text-red-500 font-medium">
                                                *{{ parseInt(form.totalRecipients) - allocatedRecipients }} recipients still need to be allocated
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Campus Selection List (Expands Downward) -->
                                    <div class="space-y-2">
                                        <div 
                                            v-for="campus in campusesData" 
                                            :key="campus.id" 
                                            class="flex items-center justify-between border p-2 rounded-md"
                                        >
                                            <!-- Checkbox -->
                                            <input 
                                                type="checkbox" 
                                                :id="`campus-${campus.id}`" 
                                                v-model="campus.selected" 
                                                @change="distributeRecipients"
                                                class="w-5 h-5 rounded border-gray-300 cursor-pointer"
                                            />

                                            <!-- Campus Name -->
                                            <label 
                                                :for="`campus-${campus.id}`" 
                                                class="text-sm font-medium leading-none cursor-pointer text-gray-700 flex-grow pl-2">
                                                {{ campus.name }}
                                            </label>

                                            <!-- Recipients Input -->
                                            <input 
                                                type="number" 
                                                v-model="campus.recipients" 
                                                :readonly="!campus.selected" 
                                                class="w-16 px-2 py-1 border rounded-md text-center text-gray-700 disabled:bg-gray-100"
                                                min="0" 
                                                :max="form.totalRecipients"
                                                @input="onRecipientManualChange(campus.id)"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Scrollable Course Selection -->
                            <div class="flex flex-col space-y-2 p-2 overflow-hidden">
                                <div 
                                    class="flex flex-col space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer"
                                    style="max-height: min(100%, 50vh);" 
                                >
                                    <div 
                                        v-for="campus in selectedCampuses" 
                                        :key="campus.id" 
                                        class="p-3 border rounded-lg bg-white shadow-sm"
                                    >
                                        <!-- Selected Campus Name -->
                                        <div class="text-sm font-semibold text-gray-700 mb-2">
                                            {{ campus.name }}
                                        </div>

                                        <!-- Courses (Grow naturally, scroll if too many) -->
                                        <div class="space-y-1">
                                            <div v-for="course in campus.courses" :key="course">
                                                <input 
                                                    type="checkbox" 
                                                    :id="`course-${campus.id}-${course}`"
                                                    v-model="selectedCoursesMap[course]" 
                                                    class="rounded cursor-pointer"
                                                />
                                                <label 
                                                    :for="`course-${campus.id}-${course}`" 
                                                    class="text-sm ml-2 cursor-pointer">
                                                    {{ course }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- Page 3: Criteria -->
                    <div v-if="currentPage === 3">
                        <div class="w-full space-y-5">
                            <div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Grade Criteria</h3>

                                    <!-- Input Box for Grade Criteria -->
                                    <input v-model="form.name" type="text" id="name" placeholder="Enter Grade Criteria (e.g., GWA 95 1.1)" 
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                                    <!-- <input 
                                        type="text" 
                                        v-model="gradeCriteria" 
                                        placeholder="Enter Grade Criteria (e.g., GWA 95 1.1)" 
                                        class="w-full px-3 py-2 border rounded-md text-gray-700 
                                            focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                                    /> -->
                                </div>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">List Criteria and Eligibility</h3>
                                <!-- Campus Selection List -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-3 mt-2">
                                    <div class="flex items-center space-x-2">
                                        <!-- Checkbox to accept terms and conditions -->
                                        <input id="accept-terms" type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="accept-terms"
                                            class="text-sm font-medium text-gray-700 cursor-pointer">
                                            Dapat First Honor
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div v-if="currentPage === 4">
                        <div class="w-full space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">List Requirements</h3>
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

                    <!-- Buttons -->
                    <div class="mt-2 flex justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span v-for="index in totalPages" :key="index"
                                class="h-2 rounded-full transition-all duration-300"
                                :class="isActive(index).value ? 'bg-blue-500 w-7' : 'bg-gray-300 w-2'">
                            </span>
                        </div>

                        <div class="flex justify-end gap-2">
                            <!-- Cancel Button (Always Visible) -->
                            <button type="button" @click="cancel"
                                class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-400 shadow-sm rounded-lg text-sm px-5 py-2.5">
                                Cancel
                            </button>

                            <!-- Previous Button (Only Show from Page 2 Onwards) -->
                            <button v-if="currentPage > 1" type="button" @click="prevPage"
                                class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-400 shadow-sm rounded-lg text-sm px-5 py-2.5">
                                Previous
                            </button>

                            <!-- Next Button: Only Show if Scholarship Type is Selected & Not Grant-Based -->
                            <template v-if="form.scholarshipType && form.scholarshipType !== 'Grant-Based'">
                                <button v-if="currentPage < totalPages" type="button" @click="nextPage"
                                    class="text-white bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-7 py-2.5">
                                    Next
                                </button>

                                <button v-if="currentPage === totalPages" type="submit"
                                    class="text-white bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-5 py-2.5">
                                    Create Scholarship
                                </button>
                            </template>

                            <!-- Direct "Create Scholarship" Button if Grant-Based is Selected -->
                            <button v-if="form.scholarshipType === 'Grant-Based'" type="submit"
                                class="text-white bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:ring-blue-300 shadow-lg rounded-lg text-sm px-5 py-2.5">
                                Create Scholarship
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>








        <!-- <div v-if="none"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-9/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <span class="text-xl font-semibold text-gray-900 dark:text-white">
                        <h2 class="text-2xl font-bold">
                        Add New Scholarship
                        </h2>
                    </span>
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">

                    <div class="flex flex-row gap-3">
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                                Name</h3>
                            <input v-model="form.name" type="text" id="name"
                                placeholder="Enter Scholarship Name"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                                Type</h3>
                            <select v-model="form.scholarshipType" id="scholarshipType"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600">
                                <option value="" disabled>Select Scholarship Type</option>
                                <option value="Grant-Based">Grant-Based</option>
                                <option value="Academic Scholarship">Academic Scholarship</option>
                                <option value="One-time Payment">One-time Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-6/12 pr-2">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Set Scholarship
                            Timeline</h3>
                            <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">

                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                        <input v-model="selectedStart" id="datepicker-range-start" name="start" type="text" autocomplete="off" lang="en"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                        placeholder="Submission Start Date">
                                </div>
                            </div>

                            <span class="text-gray-500">to</span>


                            <div class="flex flex-col w-full">
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </div>
                                        <input v-model="selectedEnd" id="datepicker-range-end" name="end" type="text" autocomplete="off" lang="en"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                        placeholder="Submission Start Date">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div v-if="form.scholarshipType === 'Academic Scholarship' || form.scholarshipType === 'One-time Payment'">
                        <div
                            class="col-span-4 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                            <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                                Scholarship Setup
                            </h3>
                            <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                        </div>

                        <div class="w-full flex flex-col space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Scholarship
                                Blahblah</h3>
                            <input v-model="form.name" type="text" id="name"
                                placeholder="Enter Scholarship Name"
                                class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />
                        </div>
                    </div>
                    

                    <div class="mt-2 flex items-center justify-end space-x-3">
                        <button type="button" @click="closeModal"
                            class="text-gray-700 font-sans w-auto bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-400 shadow-sm font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Cancel
                        </button>
                        
                        <button type="submit"
                            class="text-white font-sans w-auto bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-16 py-2.5 text-center">
                            Create Scholarship
                        </button>
                    </div>

                </form>
            </div>
        </div> -->
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


const props = defineProps({
    sponsors: Array,
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

    // pang indication lngs
    currentPage: Number,
    totalPages: {
        type: Number,
        default: 4
    }
});

const directives = {
    Tooltip,
    DatePicker,
};

const isCreating = ref(false);
const isEditing = ref(false);
const Showcase = ref(false);
const sponsorid = ref(null);

const selectedStart = ref(''); // Stores the selected start date
const selectedEnd = ref('');   // Stores the selected end date


const form = ref({
    sponsor_id: null,
    name: null,
    scholarshipType: null,
    school_year: null,
    semester: null,
    application: '',
    deadline: '',
    name: '',
    scholarshipType: '',
    totalRecipients: 0,
    reqs: [],
    criteria: [],
    amount: 0,
});

// const form = ref({
//     name: '',
//     scholarshipType: '',
//     totalRecipients: 0,
//     reqs: [],
//     criteria: [],
//     amount: 0,
// });


// const toggleCreate = (sponsorID) => {
//     isCreating.value = !isCreating.value;
//     if (isCreating.value) {
//         sponsorid.value = sponsorID;
//         form.value.sponsor_id = sponsorID;
//     }
// };

const toggleCreate = (sponsorID) => {
    isCreating.value = isCreating.value === sponsorID ? null : sponsorID;
    if (isCreating.value) {
        sponsorid.value = sponsorID;
        form.value.sponsor_id = sponsorID;
    }
    initFlowbite(); // Initialize Flowbite first
};

const currentPage = ref(1);
const totalPages = 4; // Adjust this based on actual pages

const nextPage = () => {
    if (currentPage.value < totalPages) currentPage.value++;
};
const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
};

// Computed binding for the page indicator
const isActive = (index) => computed(() => index === currentPage.value);

const cancel = () => {
    isCreating.value = false; // Close modal
    currentPage.value = 1; // Reset to first page
    form.name = '';
    form.scholarshipType = '';
    selectedStart.value = '';
    selectedEnd.value = '';
};

// Create reactive campus array from props with selection state
const campusesData = ref([]);

// Compute selected campuses dynamically
const selectedCampuses = computed(() =>
    campusesData.value.filter(campus => campus.selected)
);

onMounted(() => {
    initFlowbite(); // Initialize Flowbite globally

    watch(isCreating, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                initFlowbite(); // Initialize Flowbite when modal is accessed
                
                const startInput = document.getElementById("datepicker-range-start");
                if (startInput) {
                    startInput.value = selectedStart.value; // Keep the previous value
                    startInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.application = date.toISOString().split("T")[0]; 
                        console.log("Application:", form.value.application);
                        selectedStart.value = event.target.value; 
                    });
                } else {
                    console.warn("Start datepicker not found.");
                }

                const endInput = document.getElementById("datepicker-range-end");
                if (endInput) {
                    endInput.value = selectedEnd.value; // Keep the previous value
                    endInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.deadline = date.toISOString().split("T")[0]; 
                        selectedEnd.value = event.target.value; 
                    });
                } else {
                    console.warn("End datepicker not found.");
                }

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
                // Initial distribution
                distributeRecipients();

            }, 200); // Small delay to ensure modal is in the DOM
        }
    });

});

// 🎯 Sync Input Values
watch(() => selectedStart.value, (newVal) => {
    const input = document.getElementById("datepicker-range-start");
    if (input) input.value = newVal;
});

watch(() => selectedEnd.value, (newVal) => {
    const input = document.getElementById("datepicker-range-end");
    if (input) input.value = newVal;
});

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




const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const editScholarship = (scholarship) => {
    isEditing.value = true;
    isCreating.value = false;
    form.value = { ...scholarship };
};


const resetForm = () => {
    form.value = { id: null, name: '', description: '', scholarshipType: '', school_year: '', semester: '', application: '', deadline: '' };
};


const submitForm = async () => {
    try {
        router.post(`/sponsors/create-scholarship`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
        closeModal();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const isPublishing = ref(false);

const toggleSetActive = () => {
    isPublishing.value = !isPublishing.value;
    currentPage.value = 1;
    if (isPublishing.value) {
        resetForm();
    }
};

// dynamic requirements
// const newItem = ref('');
// const items = ref([]);

// const addItem = () => {
//     if (newItem.value.trim() !== '') {
//         items.value.push(newItem.value.trim());
//         form.value.requirements = items.value;
//         newItem.value = '';

        
//     }
// };


// const addRequirement = () => {
//   if (newRequirement.value.name.trim()) {
//     form.requirements[newRequirement.value.name.trim()] = {
//       type: newRequirement.value.type,
//       required: true
//     };
//     newRequirement.value.name = '';
//   }
// };

// const removeItem = (index) => {
//     items.value = items.value.filter((_, i) => i !== index);
// };

// const currentPage = ref(1);

// const nextPage = () => {
//     if (currentPage.value < 3) {
//         currentPage.value++;
//     }
// };

// const prevPage = () => {
//     if (currentPage.value > 1) {
//         currentPage.value--;
//     }
// };

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


const df = new DateFormatter('en-US', {
    dateStyle: 'long',
})

const formatDate = (date) => {
    if (!date) return "Pick a date";
    return new Intl.DateTimeFormat("en-US", { dateStyle: "medium" }).format(new Date(date));
};

</script>

<style scoped>
.test {
    z-index: 9999;
}
</style>