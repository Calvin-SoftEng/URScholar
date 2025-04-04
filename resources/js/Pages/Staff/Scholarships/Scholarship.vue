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
                            <Link>
                            <span class="material-symbols-rounded mr-2 text-blue-400 tracking-[-.1rem]">
                                arrow_back_ios_new
                            </span>
                            </Link>
                            <span>{{ scholarship?.name }}</span>
                            <span>{{ scholarship?.type }}</span>
                        </h1>
                        <span class="text-xl dark:text-dtext">SY {{ schoolyear?.year || '2024' }} - {{ props.selectedSem || 'Semester'
                            }} Semester</span>
                    </div>
                    <!--Condition for scholarship type-->
                    <div v-if="scholarship.scholarshipType == 'Grant-Based'" class="flex gap-2">
                        <div v-if="students.length === 0" class="flex flex-row items-end gap-2">
                            <!-- Disabled Import Scholars Button -->
                            <button v-tooltip.left="'You need to add students before importing scholars'" disabled
                                class="px-4 py-2 text-sm text-primary dark:text-dtext bg-yellow-100 dark:bg-yellow-800 
                                    border border-yellow-300 dark:border-yellow-500 rounded-lg hover:bg-yellow-200 
                                    font-poppins flex items-center gap-2 dark:hover:bg-yellow-800">
                                <i class="pi pi-exclamation-triangle text-yellow-600 dark:text-yellow-300"></i>
                                <font-awesome-icon :icon="['fas', 'user-plus']" class="text-sm dark:text-dtext" />
                                <span class="dark:text-dtext">Import Scholars</span>
                            </button>

                            <!-- Disabled Send Email Button -->
                            <button v-tooltip.left="'You need to add scholars before sending emails'" disabled class="mt-2 px-4 py-2 text-sm text-primary dark:text-dtext bg-yellow-100 dark:bg-yellow-800 
                                    border border-yellow-300 dark:border-yellow-500 rounded-lg hover:bg-yellow-200 
                                    font-poppins flex items-center gap-2 dark:hover:bg-yellow-800">
                                <i class="pi pi-exclamation-triangle text-yellow-600 dark:text-yellow-300"></i>
                                <font-awesome-icon :icon="['far', 'envelope']" class="text-sm dark:text-dtext " />
                                <span class="dark:text-dtext ">Send Email</span>
                            </button>
                        </div>

                        <div v-else class="flex flex-row items-end gap-2">
                            <!-- Active Import Scholars Button -->
                            <button @click="openScholarship"
                                class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] 
                                    border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins flex items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'user-plus']" class="text-sm dark:text-dtext" />
                                <span>Import Scholars</span>
                            </button>

                            <!-- Active Send Email Button -->
                            <div v-if="batches.length === 0" class="flex flex-row items-end gap-2">
                                <button v-tooltip.left="'You need to add scholars before sending emails'" disabled
                                    class="mt-2 px-4 py-2 text-sm text-primary dark:text-dtext bg-yellow-100 dark:bg-yellow-800 
                                    border border-yellow-300 dark:border-yellow-500 rounded-lg hover:bg-yellow-200 
                                    font-poppins flex items-center gap-2">
                                    <i class="pi pi-exclamation-triangle text-yellow-600 dark:text-yellow-300"></i>
                                    <font-awesome-icon :icon="['far', 'envelope']" class="text-sm dark:text-dtext" />
                                    <span>Send Email</span>
                                </button>
                            </div>
                            <div v-else>
                                <button @click="openSendEmail"
                                    class="px-4 py-2 text-sm text-primary dark:text-dtext bg-dirtywhite dark:bg-[#3b5998] 
                                        border border-1-gray-100 rounded-lg hover:bg-gray-100 font-poppins flex items-center gap-2">
                                    <font-awesome-icon :icon="['far', 'envelope']" class="text-sm dark:text-dtext" />
                                    <span>Send Email</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- <ScholarList :scholarship="scholarship" :batches="batches" /> -->
                <!-- <Batches :scholarship="scholarship" :batches="batches" :schoolyear="schoolyear" :selectedSem="selectedSem" class="w-full h-full"/> -->

                <div v-if="scholarship.scholarshipType == 'Grant-Based'">
                    <div v-if="!batches || batches.length === 0"
                        class="flex flex-col w-full items-center justify-center mt-5">
                        <div class="bg-white w-full dark:bg-dsecondary p-6 rounded-lg text-center animate-fade-in">
                            <font-awesome-icon :icon="['fas', 'user-graduate']"
                                class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                No scholars added yet
                            </p>
                        </div>
                    </div>

                    <div v-else class="w-full mt-5 rounded-xl space-y-5">
                        <!-- Stats Section -->
                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="grid grid-cols-5">
                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Total Verified Scholars</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <p class="text-4xl font-semibold font-kanit">{{ verified_scholars }}</p>
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
                                    <p class="text-gray-500 text-sm">Approved Requirements</p>
                                </div>
                                <div class="grid grid-cols-3 items-center gap-3">
                                    <!-- Scholars Length -->
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ grantees.length }}
                                    </p>

                                    <!-- Divider -->
                                    <div class="h-5 w-[2px] bg-gray-400 mx-auto"></div>

                                    <!-- Total Scholars -->
                                    <p class="text-4xl font-semibold font-kanit text-center">
                                        {{ total_scholars }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10 border-r border-gray-300">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['fas', 'users']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Scholarship Batches</p>
                                </div>
                                <div class="w-full flex flex-row justify-between space-x-3 items-end">
                                    <div v-if="$page.props.auth.user.usertype == 'super_admin'"
                                        class="w-full flex flex-row justify-between items-end">
                                        <p class="text-4xl font-semibold font-kanit">{{ props.allBatches.length }}</p>
                                        <template
                                            v-if="props.allBatches.filter(batch => batch.read === 0 || batch.read === false).length > 0">
                                            <button
                                                class="h-5 px-3 py-1 bg-blue-400 text-white rounded-full text-sm inline-flex items-center justify-center">
                                                {{props.allBatches.filter(batch => batch.read === 0 || batch.read ===
                                                    false).length}} new
                                                {{props.allBatches.filter(batch => batch.read === 0 || batch.read ===
                                                    false).length === 1 ? 'Batch' : 'Batches'}}
                                            </button>
                                        </template>

                                    </div>
                                    <div v-else>
                                        <p class="text-4xl font-semibold font-kanit">{{ props.batches.length }}</p>
                                        <template v-if="filteredUnreadBatches.length > 0">
                                            <button class="px-3 bg-blue-400 text-white rounded-full text-sm">
                                                {{ filteredUnreadBatches.length }} new
                                                {{ filteredUnreadBatches.length === 1 ? 'Batch' : 'Batches' }}
                                            </button>
                                        </template>
                                    </div>


                                </div>
                            </div>

                            <div class="flex flex-col items-start py-4 px-10">
                                <div class="flex flex-row space-x-3 items-center">
                                    <font-awesome-icon :icon="['far', 'circle-check']" class="text-primary text-base" />
                                    <p class="text-gray-500 text-sm">Completed Batches</p>
                                </div>
                                <p class="text-4xl font-semibold font-kanit">{{ completedBatches ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="w-full h-[1px] bg-gray-200"></div>

                        <div class="flex flex-row justify-between items-center">
                            <!-- Dynamic Title -->
                            <h2 class="text-lg font-semibold text-gray-800 mt-4">
                                {{ showPayrolls ? 'List of Payrolls' : 'List of Scholars Batch list' }}
                            </h2>

                            <div class="flex flex-row space-x-3 items-center">
                                <!-- Campus Filter - Only shown for super_admin or if coordinator has multiple campuses -->
                                <template
                                    v-if="$page.props.auth.user.usertype === 'super_admin' || campuses.length > 1">
                                    <span class="font-poppins text-sm">Filter Campus</span>
                                    <select v-model="selectedCampus" @change="filterByCampus"
                                        class="p-2.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option v-if="$page.props.auth.user.usertype === 'super_admin'" value="">All
                                            Campuses</option>
                                        <option v-for="campus in campuses" :key="campus.id" :value="campus.id">
                                            {{ campus.name }}
                                        </option>
                                    </select>
                                </template>

                                <!-- Campus display for coordinators with single campus -->
                                <template
                                    v-else-if="$page.props.auth.user.usertype === 'coordinator' && campuses.length === 1">
                                    <span class="font-poppins text-sm">Campus:</span>
                                    <span class="font-poppins text-sm font-semibold">{{ campuses[0].name }}</span>
                                </template>

                                <div v-if="$page.props.auth.user.usertype === 'super_admin'">
                                    <div v-if="payouts">
                                    <button @click="toggleView"
                                        class="flex items-center gap-2 dark:text-dtext bg-white dark:bg-white 
                                        border border-green-300 dark:border-green-500 hover:bg-green-200 px-4 py-2 rounded-lg transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'receipt']" class="text-base" />
                                        <span class="font-normal">
                                            {{ showPayrolls ? 'View Scholar List' : 'View Payrolls' }}
                                        </span>
                                    </button>
                                    </div>
                                    <div v-if="!payouts">
                                    <button @click="toggleSendBatch"
                                        class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward Completed Scholars</span>
                                    </button>
                                    </div>
                                    <div v-else>
                                    <button v-tooltip.left="'Scholars already submitted to Cashier'" disabled
                                        class="flex items-center gap-2 dark:text-dtext bg-blue-100 dark:bg-blue-800 
                                    border border-blue-300 dark:border-blue-500  hover:bg-blue-200 px-4 py-2 rounded-lg  transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward Completed Scholars</span>
                                    </button>
                                    </div>
                                </div>
                                <div v-else>
                                    <div v-if="payouts">
                                    <button @click="toggleView"
                                        class="flex items-center gap-2 dark:text-dtext bg-white dark:bg-white 
                                        border border-green-300 dark:border-green-500 hover:bg-green-200 px-4 py-2 rounded-lg transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'receipt']" class="text-base" />
                                        <span class="font-normal">
                                            {{ showPayrolls ? 'View Scholar List' : 'View Payrolls' }}
                                        </span>
                                    </button>
                                    </div>
                                    <div v-if="!payouts">
                                    <button @click="toggleForwardCoor"
                                        class="flex items-center gap-2 bg-blue-600 font-poppins text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward Completed Scholars</span>
                                    </button>
                                    </div>
                                    <div v-else>
                                    <button v-tooltip.left="'Scholars already submitted to Cashier'" disabled
                                        class="flex items-center gap-2 dark:text-dtext bg-blue-100 dark:bg-blue-800 
                                    border border-blue-300 dark:border-blue-500  hover:bg-blue-200 px-4 py-2 rounded-lg  transition duration-200">
                                        <font-awesome-icon :icon="['fas', 'share-from-square']" class="text-base" />
                                        <span class="font-normal">Forward Completed Scholars</span>
                                    </button>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>

                        <!-- Scholar List -->
                        <div v-show="!showPayrolls">
        <!-- Loop through each campus in batchesByCampus -->
        <div v-for="(campusData, campusId) in batchesByCampus" :key="campusId" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-dtext flex items-center gap-3 before:flex-1 before:border-t before:border-gray-300 after:flex-1 after:border-t after:border-gray-300">
                {{ campusData.campus.name }}
            </h2>
            
            <!-- Loop through batches for this campus -->
            <div v-for="batch in campusData.batches" :key="batch.id"
                class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer mt-4">
                <div @click="() => openBatch(batch.id)" class="flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-xl font-semibold text-gray-800">Batch {{ batch.batch_no }}</span>
                        <span class="text-lg font-medium text-gray-600">
                            {{ schoolyear ? schoolyear.school_year : '' }} {{ batch.semester }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col items-center">
                            <span class="text-sm text-gray-600">No. of Scholars</span>
                            <span class="text-xl font-bold text-blue-600">{{ batch.grantees.length }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-sm text-gray-600">Unverified Scholars</span>
                            <span class="text-xl font-bold text-red-500">
                                {{ batch.grantees.filter(grantee => !grantee.scholar?.is_verified).length }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Show message if no batches for this campus -->
            <div v-if="campusData.batches.length === 0" class="mt-4 text-center text-gray-500">
                No batches available for this campus with the current filters.
            </div>
        </div>
        
        <!-- Show message if no campuses with batches -->
        <div v-if="Object.keys(batchesByCampus).length === 0" class="text-center text-gray-500 py-8">
            No batches available with the current filters.
        </div>
    </div>

                        <!-- Payrolls List -->
                        <div v-show="showPayrolls">
                            <div v-if="payouts">
                                <div v-if="payouts.status == 'Pending'">
                                    <div class="flex flex-col w-full items-center justify-center mt-5">
                                        <div
                                            class="bg-white w-full dark:bg-gray-800 p-6 rounded-lg text-center animate-fade-in">
                                            <font-awesome-icon :icon="['fas', 'user-graduate']"
                                                class="text-4xl text-gray-400 dark:text-gray-500 mb-4" />
                                            <p class="text-lg text-gray-700 dark:text-gray-300">
                                                Payout is still Ongoing
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                <div v-else>
                                    <div v-for="batch in batches" :key="batch.id"
                                        class="bg-gradient-to-r from-[#F8F9FC] to-[#D2CFFE] w-full rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer">
                                        <div @click="() => openPayroll(batch.id)"
                                            class="flex justify-between items-center">
                                            <span class="text-lg font-semibold text-gray-800">Batch {{ batch.batch_no
                                                }}</span>

                                            <div class="grid grid-cols-2">
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Completed Payouts</span>
                                                    <span class="text-xl font-bold text-blue-600">{{
                                                        batch.grantees.length
                                                        }}</span>
                                                </div>
                                                <div class="flex flex-col items-center">
                                                    <span class="text-sm text-gray-600">Missed Payouts</span>
                                                    <span class="text-xl font-bold text-red-500">
                                                        {{batch.grantees.filter(grantee =>
                                                            !grantee.scholar.is_verified).length
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--ONE TIME PAYMENT PROCESS-->
                <div v-else-if="scholarship.scholarshipType == 'One-time Payment'">
                    <form @submit.prevent="submitForm">
                        <div class="pt-3 pb-20 overflow-auto h-full scroll-py-4">
                            <!-- <div class="mx-auto max-w-8xl sm:px-6 lg:px-8 "> -->
                            <div class="w-full block bg-white dark:bg-dsecondary px-12 py-6 flex-col items-center mx-auto sm:px-6 lg:px-8">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-lg font-semibold text-gray-700 dark:text-dtext">
                                        Set up One-Time Payment Scholarship Details
                                    </span>
                                    <button type="submit" @click="submitForm"
                                        class="px-6 py-2 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all">
                                        {{ isSubmitting ? 'Creating...' : 'Create Scholarship' }}
                                    </button>
                                </div>
                                <div class="mt-5 font-inter text-lg space-y-3">
                                    <div class="flex flex-row w-full gap-3">
                                        <div class="flex flex-col space-y-2 w-full">
                                            <label for="suffixName"
                                                class="text-sm font-medium text-gray-700 dark:text-dtext">Scholarship Name</label>
                                            <input id="suffixName" :value="scholarship.name" readonly type="text"
                                                placeholder="Scholarship Name"
                                                class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                                        </div>

                                        <div class="flex flex-col space-y-2 w-full">
                                            <label for="suffixName"
                                                class="text-sm font-medium text-gray-700 dark:text-dtext">Scholarship Type</label>
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
                                                    class="text-sm font-medium text-gray-700 mb-1 dark:text-dtext">Application
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
                                                        autocomplete="off"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Select start date">
                                                </div>
                                            </div>

                                            <span class="text-gray-500">to</span>

                                            <!-- Application Deadline -->
                                            <div class="flex flex-col w-full">
                                                <label for="datepicker-range-end"
                                                    class="text-sm font-medium text-gray-700 mb-1 dark:text-dtext">Application
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
                                                        autocomplete="off"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="Select end date">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                                        <div>
                                            <!-- Total Recipients Input -->
                                            <div class="flex w-6/12 pr-2 flex-col">
                                                <label for="totalRecipients" class="text-sm font-medium text-gray-700 dark:text-dtext">
                                                    Number of Recipients
                                                </label>
                                                <input id="totalRecipients" type="number" v-model="form.totalRecipients"
                                                    min="1" placeholder="Enter total recipients"
                                                    class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded-lg"
                                                    @input="distributeRecipients" />
                                            </div>

                                            <!-- Left Side: Campus Selection & Recipient Distribution -->
                                            <div class="flex flex-col space-y-3">
                                                <!-- Header with Label & Stats -->
                                                <div class="flex flex-row justify-between items-center py-2">
                                                    <div class="flex flex-col space-y-2">
                                                        <label class="text-sm font-medium dark:text-dtext">Distribute Recipients per
                                                            Selected Campus</label>
                                                        <div class="flex flex-row text-sm gap-4 dark:text-dtext">
                                                            <div>Allocated: {{ allocatedRecipients }} of {{
                                                                form.totalRecipients
                                                            }}</div>
                                                            <div v-if="allocatedRecipients !== parseInt(form.totalRecipients)"
                                                                class="text-red-500 font-medium dark:text-dtext">
                                                                *{{ parseInt(form.totalRecipients) - allocatedRecipients
                                                                }}
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
                                                                    class="material-symbols-rounded text-base dark:text-dtext">restart_alt</span>
                                                                <span class="text-sm dark:text-dtext">Reset to Auto Distribution</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Campus Selection List -->
                                                <div class="space-y-2">
                                                    <div v-for="campus in campusesData" :key="campus.id"
                                                        class="flex items-center justify-between border p-2 rounded-md">

                                                        <input type="checkbox" :id="`campus-${campus.id}`"
                                                            v-model="campus.selected" @change="distributeRecipients"
                                                            class="w-5 h-5 rounded border-gray-300 cursor-pointer" />


                                                        <label :for="`campus-${campus.id}`"
                                                            class="text-base font-medium leading-none cursor-pointer text-gray-700 flex-grow pl-2 dark:text-dtext">
                                                            {{ campus.name }}
                                                        </label>


                                                        <input type="number" v-model="campus.recipients"
                                                            :readonly="!campus.selected"
                                                            class="w-16 px-2 py-1 border rounded-md text-center text-gray-700 disabled:bg-gray-100"
                                                            min="0" :max="form.totalRecipients"
                                                            @input="onRecipientManualChange(campus.id)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Side: Course List Display Grouped by Campus -->
                                        <div class="flex flex-col space-y-4">
                                            <div v-for="campus in selectedCampuses" :key="campus.id"
                                                class="py-3 px-4 bg-gray-50 dark:bg-dnavy border rounded-md">
                                                <!-- Selected Campus Name -->
                                                <div class="text-sm font-semibold text-gray-700 mb-2 dark:text-dtext">
                                                    {{ campus.name }}
                                                </div>

                                                <!-- Courses Offered for this Campus -->
                                                <div v-for="course in campus.courses" :key="course" class="mt-1">
                                                    <input type="checkbox" :id="`course-${campus.id}-${course}`"
                                                        v-model="selectedCoursesMap[course]" class="rounded" />
                                                    <label :for="`course-${campus.id}-${course}`"
                                                        class="text-sm ml-2 cursor-pointer dark:text-dtext">{{
                                                            course }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <h3 class="text-base font-medium text-black dark:text-dtext">List
                                        Criteria and Eligibility</h3>
                                    <div class="grid grid-cols-2 space-x-2">
                                        <div class="w-full flex flex-col p-2">

                                            <div class="space-y-4">
                                                <div class="flex flex-col justify-center items-start">
                                                    <span
                                                        class="text-sm font-medium text-black whitespace-nowrap mb-2 dark:text-dtext">General
                                                        Weighted Average must be:
                                                    </span>

                                                    <input v-model="form.grade" type="text" id="name"
                                                        placeholder="Enter Grade Criteria (e.g., GWA 95 1.1)"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-base w-full dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600" />

                                                </div>

                                                <div class="flex flex-col space-y-2 justify-center items-start">
                                                    <span class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                        Must be enrolled in:
                                                    </span>

                                                    <textarea v-model="selectedCoursesText" id="name" rows="3"
                                                        placeholder="Specific courses will appear here if there are any selected... If none, will show for All Courses"
                                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-base w-full resize-none dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"
                                                        readonly>
                                    </textarea>
                                                </div>

                                                <div v-for="eligiblity in eligibilities" :key="eligiblity.id"
                                                    class="flex flex-col justify-center space-y-2 items-start">
                                                    <span class="text-sm font-medium text-black whitespace-nowrap dark:text-dtext">
                                                        {{ eligiblity.name }}
                                                    </span>
                                                    <div v-for="conditions in getFormData(eligiblity.id)"
                                                        :key="conditions.id" class="flex items-center space-x-2 mb-1">
                                                        <input id="accept-terms-{{ conditions.id }}" type="checkbox"
                                                            class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                            :checked="conditionIncludes(conditions.id)"
                                                            @change="toggleCondition(conditions.id)">
                                                        <label
                                                            class="text-base font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                            {{ conditions.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                            <div class="p-4 ">
                                                <h4 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">
                                                    {{ scholarship_form.name }}</h4>

                                                <div v-for="data in scholarship_form_data" :key="data.id"
                                                    class="flex items-center space-x-2 mb-1">
                                                    <input id="accept-terms-{{ data.id }}" type="checkbox"
                                                        class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                        :checked="criteriaIncludes(data.id)"
                                                        @change="toggleCriteria(data.id)">
                                                    <label :for="'accept-terms-' + data.id"
                                                        class="text-sm font-medium text-gray-700 cursor-pointer dark:text-dtext">
                                                        {{ data.name }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full border-t border-gray-200 my-4"></div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="w-full">
                                            <label for="totalRecipients" class="text-sm font-medium text-gray-700 dark:text-dtext" >
                                                List Requirements
                                            </label>
                                            <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                                <div class="flex items-center mb-4 w-full">
                                                    <form @submit.prevent="addItem" class="flex items-center w-full">
                                                        <input v-model="newItem" type="text" placeholder="Enter an item"
                                                            class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                                        <button type="submit"
                                                            class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                            Add
                                                        </button>
                                                    </form>
                                                </div>

                                                <form @submit.prevent="removeItem">
                                                    <div class="flex flex-col gap-2">
                                                        <div v-for="(item, index) in items" :key="index"
                                                            class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                                            <span>{{ item }}</span>
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
                                        
                                        <div>
                                            <label for="totalRecipients" class="text-sm font-medium text-gray-700 dark:text-dtext">
                                                Upload multiple file templates
                                            </label>
                                            <!-- <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">
                                            Upload multiple file templates
                                            </label> -->
                                            <input ref="fileInput"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                                    dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                                            id="multiple_files" 
                                            type="file" 
                                            multiple 
                                            @change="handleFileChange"
                                            />

                                            <!-- Preview Selected Files -->
                                            <div v-if="selectedFiles.length" class="mt-3 p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">Selected Files:</p>
                                            <ul class="space-y-2">
                                                <li v-for="(file, index) in selectedFiles" :key="index" class="flex items-center justify-between text-sm bg-white dark:bg-gray-700 p-2 rounded-lg">
                                                <span class="text-gray-700 dark:text-gray-300 truncate max-w-xs">{{ file.name }}</span>
                                                <button @click="removeFile(index)" class="text-red-500 hover:text-red-700 text-xs">
                                                    ✖ Remove
                                                </button>
                                                </li>
                                            </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Forwarding Batch List</h2>
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

                <!-- Form -->
                <form @submit.prevent="forwardBatches">
                    <div class="py-4 px-8 flex flex-col gap-3">
                        <div class="mb-4">
                            <label for="batchSelection"
                                class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
                                Select a Date:
                            </label>

                            <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">
                                <!-- Application Start Date -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <InputError v-if="errors?.date_start" :message="errors.date_start"
                                            class=" text-red-500" />
                                        <input v-model="StartPayout" id="datepicker-range-start" name="start"
                                            type="text" autocomplete="off" lang="en"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Submission Start Date">
                                    </div>
                                </div>

                                <span class="text-gray-500">to</span>

                                <!-- Application Deadline -->
                                <div class="flex flex-col w-full">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                            </svg>
                                        </div>
                                        <InputError v-if="errors?.date_end" :message="errors.date_end"
                                            class=" text-red-500" />
                                        <input v-model="EndPayout" id="datepicker-range-end" name="end" type="text"
                                            autocomplete="off" lang="en"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Submission Start Date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <label for="batchSelection"
                            class="block mb-2 text-base font-medium text-gray-500 dark:text-white">
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

                            <label v-for="batch in batchesWithScholars" :key="batch.id"
                                class="flex items-center space-x-2">
                                <input type="checkbox" :value="batch.id" v-model="selectedBatches"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-gray-900 dark:text-white">Batch {{ batch.batch_no }}</span>
                                <span class="text-sm text-gray-500">({{ batch.scholar_count }} scholars)</span>
                            </label>
                        </div>

                        <!-- Forward Button -->
                        <div v-if="completedBatches === batches.length" class="mt-4">
                            <button type="submit" :disabled="isSubmitting || selectedBatches.length === 0"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isSubmitting ? 'Processing...' : 'Forward' }}
                            </button>
                        </div>
                        <div v-else class="mt-4">
                            <button v-tooltip.left="'Complete all batches'" disabled
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                Forward
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

                <!-- Simplified forwarding batch list modal -->
                <div v-if="ForwardCoorList"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Send Payroll
                            </h2>
                        </div>
                    </div>
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

                <div class="py-4 px-8 flex flex-col gap-4 bg-white shadow-md rounded-lg border border-gray-200">
                    <label class="block text-lg font-semibold text-gray-700 dark:text-white">
                        Completed Payout Batches
                    </label>

                    <!-- Loading Indicator -->
                    <div v-if="isLoading" class="flex justify-center items-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                    </div>

                    <!-- Batch List -->
                    <div v-if="ForwardCoorList"
                        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
                        <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12">
                            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <div class="flex items-center gap-3">
                                    <!-- Icon -->
                                    <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                        class="text-blue-600 text-2xl flex-shrink-0" />

                                    <!-- Title and Description -->
                                    <div class="flex flex-col">
                                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                            Send Payroll
                                        </h2>
                                    </div>
                                </div>
                                <button type="button" @click="closeModal"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="default-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                class="py-4 px-8 flex flex-col gap-4 bg-white shadow-md rounded-lg border border-gray-200">
                                <label class="block text-lg font-semibold text-gray-700 dark:text-white">
                                    Completed Payout Batches
                                </label>

                                <!-- Loading Indicator -->
                                <div v-if="isLoading" class="flex justify-center items-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Loading batches...</span>
                                </div>

                                <!-- Batch List -->
                                <!-- Batch List -->
                                <div v-else class="flex flex-col divide-y divide-gray-300">
                                    <div v-for="batch in batchesWithScholars" :key="batch.id"
                                        class="py-3 px-4 flex justify-between items-center">
                                        <div>
                                            <p class="text-base font-medium text-gray-900 dark:text-white">Batch {{
                                                batch.batch_no }}</p>
                                            <p class="text-sm text-gray-500">Includes {{ batch.claimed_count }} Claimed,
                                                {{ batch.not_claimed_count }} Not Claimed</p>
                                        </div>
                                        <span
                                            :class="`text-sm font-medium px-3 py-1 rounded-full ${batch.not_claimed_count === 0 ? 'text-green-700 bg-green-100' : 'text-yellow-700 bg-yellow-100'}`">
                                            {{ batch.not_claimed_count === 0 ? 'Ready to Send' : 'Incomplete' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Forward Button -->
                                <div class="mt-4">
                                    <button :disabled="isSubmitting" @click="forwardPayout"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ isSubmitting ? 'Processing...' : 'Forward' }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Forward Button -->
                    <!-- <div class="mt-4">
                        <button :disabled="isSubmitting" @click="forwardPayout"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ isSubmitting ? 'Processing...' : 'Forward' }}
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
import { defineProps, ref, watchEffect, onBeforeMount, reactive, onMounted, watch, computed, onUnmounted } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Checkbox } from '@/Components/ui/checkbox'
import { Input } from '@/Components/ui/input'
import { initFlowbite } from 'flowbite';
import { Tooltip } from 'primevue';
import InputError from '@/Components/InputError.vue';


// Define props to include scholars data
// Define props with the new batchesByCampus property
const props = defineProps({
    scholarship_form: Object,
    scholarship_form_data: Array,
    eligibilities: Array,
    conditions: Array,
    batches: Array,
    batchesByCampus: Object, // New prop with batches organized by campus
    scholarship: Object,
    schoolyear: Object,
    selectedYear: String,
    selectedSem: String,
    selectedCampus: String,
    grantees: Array,
    campuses: Array,
    courses: Array,
    students: Array,
    total_scholars: Array,
    requirements: Array,
    completedBatches: Array,
    errors: Object,
    userType: String,
    userCampusId: Number,
    allBatches: Array,
    payouts: Object,
});

// Initialize selectedCampus with the value from props
const selectedCampus = ref(props.selectedCampus || '');

// Computed property for unread batches count - using allBatches instead of filtered batches
const filteredUnreadBatches = computed(() => {
    // Start with all unread batches from the complete batches list
    let unreadBatches = props.allBatches.filter(batch => batch.read === 0 || batch.read === false);

    // If user is coordinator, only count batches from their campus
    if (props.userType === 'coordinator') {
        unreadBatches = unreadBatches.filter(batch => {
            // Check if any scholars in this batch belong to the coordinator's campus
            return batch.scholars.some(scholar => scholar.campus_id === props.userCampusId);
        });
    }

    return unreadBatches;
});

// Function to filter batches by campus
function filterByCampus() {
    router.get(route('scholarship.show', props.scholarship.id), {
        selectedYear: props.schoolyear.id,
        selectedSem: props.selectedSem,
        selectedCampus: selectedCampus.value
    }, {
        preserveState: true,
        replace: true
    });
}


const directives = {
    Tooltip,
};

const getFormData = (formId) => {
    return props.conditions.filter(data => data.eligibility_id === formId);
};

// Forward batch modal state
const ForwardBatchList = ref(false);
const ForwardCoorList = ref(false);
const selectedBatches = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const batchesWithScholars = ref([]);

const selectedStart = ref(""); // Stores the selected start date
const selectedEnd = ref("");   // Stores the selected end date

const StartPayout = ref(""); // Stores the selected start date
const EndPayout = ref("");   // Stores the selected end date

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


const requirementemplates = ref(false);
const selectedFiles = ref([]);
const fileInput = ref(null);
// Default to showing the Scholar List
const showPayrolls = ref(false);


const toggletemplates = () => {
  requirementemplates.value = !requirementemplates.value;
};

const handleFileChange = (event) => {
  selectedFiles.value = Array.from(event.target.files);
};

const removeFile = (index) => {
  selectedFiles.value.splice(index, 1); 

  // Reset the file input by clearing and re-adding remaining files
  const dataTransfer = new DataTransfer();
  selectedFiles.value.forEach(file => dataTransfer.items.add(file));
  
  if (fileInput.value) {
    fileInput.value.files = dataTransfer.files;
  }
};

if (fileInput.value) {
    fileInput.value.files = dataTransfer.files;
  }


const toggleView = () => {
    showPayrolls.value = !showPayrolls.value;
};

const toggleSendBatch = async () => {
    ForwardBatchList.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const toggleForwardCoor = async () => {
    ForwardCoorList.value = true;

    // Load batches with scholar counts
    await loadBatchesData();
};

const loadBatchesData = async () => {
    isLoading.value = true;

    try {
        // Calculate scholar counts for each batch using the scholars prop
        setTimeout(() => {
            // Group scholars by batch_id and count them
            const scholarCountsByBatch = props.grantees.reduce((counts, grant) => {
                if (grant.batch_id) {
                    counts[grant.batch_id] = (counts[grant.batch_id] || 0) + 1;
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
    requirements: [],
    criteria: [],
    conditions: [],
    grade: 0.0,
    amount: 0,
    application: '',
    deadline: '',
    payoutStartInput: '',
    payoutEndInput: '',
});

const clearForm = () => {
    form.value = {
        name: '',
        scholarshipType: '',
        totalRecipients: 0,
        requirements: [],
        criteria: [],
        grade: 0.0,
        amount: 0,
        application: '',
        deadline: '',
    };
};

// Safe check if criteria includes an ID
const conditionIncludes = (dataId) => {
    return form.value && form.value.conditions && Array.isArray(form.value.conditions)
        ? form.value.conditions.includes(dataId)
        : false;
};

// Handle criteria selection
const toggleCondition = (dataId) => {
    // Ensure criteria is initialized
    if (!form.value.conditions) {
        form.value.conditions = [];
    }

    const index = form.value.conditions.indexOf(dataId);
    if (index === -1) {
        // Add to criteria if not already present
        form.value.conditions.push(dataId);
    } else {
        // Remove from criteria if already present
        form.value.conditions.splice(index, 1);
    }
};

// Safe check if criteria includes an ID
const criteriaIncludes = (dataId) => {
    return form.value && form.value.criteria && Array.isArray(form.value.criteria)
        ? form.value.criteria.includes(dataId)
        : false;
};

// Handle criteria selection
const toggleCriteria = (dataId) => {
    // Ensure criteria is initialized
    if (!form.value.criteria) {
        form.value.criteria = [];
    }

    const index = form.value.criteria.indexOf(dataId);
    if (index === -1) {
        // Add to criteria if not already present
        form.value.criteria.push(dataId);
    } else {
        // Remove from criteria if already present
        form.value.criteria.splice(index, 1);
    }
};

const newReq = ref("");
const reqs = ref([]);

// dynamic requirements
const newItem = ref('');
const items = ref([]);

const addItem = () => {
    if (newItem.value.trim() !== '') {
        items.value.push(newItem.value.trim());
        form.value.requirements = items.value;
        newItem.value = '';
    }
};

const removeItem = (index) => {
    items.value = items.value.filter((_, i) => i !== index);
};


// Create reactive campus array from props with selection state
const campusesData = ref([]);

// Initialize campus data from props
onMounted(() => {
    // Make sure form.criteria is initialized
    if (!form.value.criteria) {
        form.value.criteria = [];
    }

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

            // Correct for time zone issues
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

            form.value.application = date.toISOString().split("T")[0]; // Keeps the correct local date
            console.log("Application:", form.value.application);
            selectedStart.value = event.target.value;
        });
    }

    const endInput = document.getElementById("datepicker-range-end");
    if (endInput) {
        endInput.value = selectedEnd.value; // Keep the previous value
        endInput.addEventListener("changeDate", (event) => {
            const date = new Date(event.target.value);

            // Correct for time zone issues
            date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

            form.value.deadline = date.toISOString().split("T")[0]; // Keeps the correct local date
            selectedEnd.value = event.target.value;
        });
    }

    watch(ForwardBatchList, (newValue) => {
        if (newValue) {
            setTimeout(() => {
                initFlowbite(); // Initialize Flowbite when modal is accessed

                const startInput = document.getElementById("datepicker-range-start");
                if (startInput) {
                    startInput.value = StartPayout.value; // Keep the previous value
                    startInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.payoutStartInput = date.toISOString().split("T")[0];
                        console.log("Application:", form.value.payoutStartInput);
                        StartPayout.value = event.target.value;
                    });
                } else {
                    console.warn("Start datepicker not found.");
                }

                const endInput = document.getElementById("datepicker-range-end");
                if (endInput) {
                    endInput.value = EndPayout.value; // Keep the previous value
                    endInput.addEventListener("changeDate", (event) => {
                        const date = new Date(event.target.value); // ✅ Get selected date
                        form.value.payoutEndInput = date.toISOString().split("T")[0];
                        EndPayout.value = event.target.value;
                    });
                } else {
                    console.warn("End datepicker not found.");
                }

                // Initial distribution
                distributeRecipients();

            }, 200); // Small delay to ensure modal is in the DOM
        }
    });


    // Initial distribution
    distributeRecipients();
    initFlowbite();
});

// Watch errors.date_start and open the modal if an error exists
watch(() => props.errors.date_start, (newError) => {
    if (newError) {
        ForwardBatchList.value = true; // Show modal
        setTimeout(() => initFlowbite(), 200); // Initialize Flowbite modal
    }
});

watch(selectedStart, (newVal) => {
    document.getElementById("datepicker-range-start").value = newVal;
});

watch(selectedEnd, (newVal) => {
    document.getElementById("datepicker-range-end").value = newVal;
});

// 🎯 Sync Input Values
watch(() => StartPayout.value, (newVal) => {
    const input = document.getElementById("datepicker-range-start");
    if (input) input.value = newVal;
});

watch(() => EndPayout.value, (newVal) => {
    const input = document.getElementById("datepicker-range-end");
    if (input) input.value = newVal;
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

// Compute selected courses dynamically based on checked checkboxes
const selectedCoursesText = computed(() => {
    return Object.keys(selectedCoursesMap.value)
        .filter(course => selectedCoursesMap.value[course]) // Get only checked courses
        .join(", "); // Convert to a comma-separated string
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
        requirements: form.value.requirements,
        criteria: form.value.criteria,
        conditions: form.value.conditions,
        grade: form.value.grade,
        application: form.value.application,
        deadline: form.value.deadline,
        amount: form.value.scholarshipType === 'One-Time' ? form.value.amount : null,
        campus_recipients: campusRecipients,
        semester: props.selectedSem,
        school_year: props.schoolyear.id
    };

    // Submit the form to the backend
    router.post(`/sholarships/${props.scholarship.id}/one-time-payment`, payload, {
        onSuccess: () => {
            showToast('Success', 'Scholarship created successfully');
            clearForm();
            setTimeout(() => {
                router.visit('/scholarships');
            }, 1500);
        },
        onError: (errors) => {
            // showToast('Error', 'There was an error creating the scholarship');
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
            grantees: batchesWithScholars.value.reduce((grantees, batch) => {
                if (batchesToForward.includes(batch.id)) {
                    // Filter the grantees based on the batch_id and map their scholar_ids
                    grantees.push(...props.grantees
                        .filter(s => s.batch_id === batch.id)
                        .map(grantee => ({
                            ...grantee,
                            scholar_id: grantee.scholar ? grantee.scholar.scholar_id : null // Ensure scholar_id is added
                        })));
                }
                return grantees;
            }, []),
            batch_ids: batchesToForward,
            date_start: form.value.payoutStartInput,
            date_end: form.value.payoutEndInput,
        };


        // Send the request and wait for response
        // const response = await router.post(`/scholarship/forward-batches`, payload);

        // // Only proceed if request was successful
        // const totalScholars = batchesToForward.reduce((total, batchId) => {
        //     const batch = batchesWithScholars.value.find(b => b.id === batchId);
        //     return total + (batch ? batch.scholar_count : 0);
        // }, 0);

        // toastMessage.value = `Successfully forwarded ${totalScholars} scholars from ${batchesToForward.length} batch(es)`;
        // toastVisible.value = true;

        // Close the modal only on success
        // closeModal();

        // Send the request only when user confirms
        router.post(`/scholarship/forward-batches`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                // Only proceed if request was successful
                const totalScholars = batchesToForward.reduce((total, batchId) => {
                    const batch = batchesWithScholars.value.find(b => b.id === batchId);
                    return total + (batch ? batch.scholar_count : 0);
                }, 0);

                toastMessage.value = `Successfully forwarded ${totalScholars} scholars from ${batchesToForward.length} batch(es)`;
                toastVisible.value = true;


            },
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            // Store the validation errors returned from the backend
            errors.value = error.response.data.errors;
        }

        console.error('Error forwarding batches:', error);
        toastMessage.value = error.message || 'Failed to forward batches';
        toastVisible.value = true;
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    ForwardBatchList.value = false;
    ForwardCoorList.value = false;
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

const openPayroll = (batchId) => {
    router.visit(`/scholarships/${props.scholarship.id}/batch/${batchId}/payroll`, {
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

const openSendEmail = () => {
    router.visit(`/scholarships/${props.scholarship.id}/send-access`, {
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

onMounted(() => {
    window.addEventListener('popstate', () => {
        window.location.reload();
    });
});

onUnmounted(() => {
    window.removeEventListener('popstate', () => {
        window.location.reload();
    });
});
</script>

<style scoped>
/* override the prime vue componentss */
:root {
    --p-tooltip-background: #D97706 !important;
    /* Yellow warning color */
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