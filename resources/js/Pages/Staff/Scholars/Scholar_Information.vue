<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">
                            Home
                        </li>
                        <li class="hover:text-gray-600">
                            <span>Scholars</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Scholar Information</span>
                        </li>
                    </ul>
                </div>

                <div class="w-full h-full flex justify-center items-center dark:text-dprimary relative">
                    <!-- Close Button -->
                    <button @click="goBack" class="absolute top-4 right-20">
                        <span
                            class="material-symbols-rounded p-2 rounded-full bg-white dark:bg-dcontainer text-blue-900 dark:text-dprimary shadow-md hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            arrow_back
                        </span>
                    </button>
            
                        <!-- 25% Column -->
                        <div class="h-full flex flex-col w-7/12">
                            <div class="h-full rounded-xl p-3 shadow-md bg-white dark:bg-dcontainer flex flex-col space-y-5">
                            <div class="flex-1 flex flex-col space-y-5">
                                
                                <!-- Profile Image & Name -->
                                <div class="flex flex-col sm:flex-row items-center justify-center p-4 bg-white space-y-4 sm:space-y-0 sm:space-x-6">
                                    <!-- Image -->
                                    <div class="w-40 h-40 overflow-hidden rounded-xl shadow-md">
                                        <img src="../../../../assets/images/no_userpic.png" alt="Profile Picture" class="w-full h-full object-cover" />
                                    </div>

                                    <!-- Info -->
                                    <div class="flex flex-col items-center sm:items-start text-center sm:text-left">
                                        <span class="text-xl font-semibold text-gray-800">John Michael Doe</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full border">URS-20200</span>
                                        <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full border">URS Binangonan</span>
                                        <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full border">Active</span>
                                        <span class="px-3 py-1 text-sm bg-purple-100 text-purple-700 rounded-full border">BSIT</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="w-full h-0.5 bg-gray-100"></div>

                                <div class="space-y-2">
                                    <div v-for="(section, index) in sections" :key="index">
                                        <button
                                        class="w-full text-left px-5 py-4 bg-white hover:bg-gray-100 flex justify-between items-center rounded-xl shadow-sm border border-gray-200 transition-all duration-200 group"
                                        :class="{ 'bg-primary text-white': section.isOpen }"
                                        @click="toggleSection(index)"
                                        >
                                        <span class="font-semibold text-gray-800 text-base"
                                        :class="{ 'text-white': section.isOpen }">
                                            {{ section.title }}
                                        </span>
                                        <span
                                            class="material-symbols-rounded text-gray-500 text-2xl transition-transform duration-300 ease-in-out group-hover:text-gray-700"
                                            :class="{ 'rotate-180': section.isOpen }"
                                        >
                                            keyboard_arrow_down
                                        </span>
                                        </button>

                                            <!-- Personal Information -->
                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Personal Information'" class="p-4 bg-white">
                                                <div class="flex flex-row w-full">
                                                    <div class="w-1/2">
                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">First Name</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Middle Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle Name</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last Name</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Birth Date</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>
                                                    </div>

                                                    <div class="w-1/2">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Place of Birth</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div> 

                                                        
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Address</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div> 

                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Civil Status</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Middle Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Sex</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Religion</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </Transition>

                                            <!-- Education -->
                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Education'" class="p-4 bg-white ">
                                                <div class="flex flex-row w-full">
                                                    <div class="w-1/3">
                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">School Year and Sem</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">SY 2022-2033 - 1st Sem</span>
                                                        </div>

                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">General Weighted Average</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">1.2</span>
                                                        </div> 

                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Certifacate of Grades</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">file</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </Transition>

                                            <!-- Contact Information -->
                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Contact Information'" class="p-4 bg-white">
                                                <div class="flex flex-row w-full">
                                                    <div class="w-1/2">
                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Email</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">@gmail.cpm</span>
                                                        </div>

                                                    </div>

                                                    <div class="w-1/2">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Phone Number</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">00000</span>
                                                        </div> 

                                                    </div>
                                                </div>
                                            </Transition>

                                            <!-- Family Details -->
                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Family Details'" class="p-4 bg-white ">
                                                <div class="flex flex-col w-full gap-6">
                                                    <!-- Mother Column -->
                                                    <div class="w-full">
                                                        <h3 class="text-xl font-bold mb-4">Mother</h3>

                                                        <!-- Sibling Entry -->
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                            <!-- First Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">First Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                            </div>

                                                            <!-- Middle Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Sample Middle</span>
                                                            </div>

                                                            <!-- Last Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Sample Last</span>
                                                            </div>

                                                            <!-- Age -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">50</span>
                                                            </div>

                                                            <!-- Address -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Address</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">123 Sample Street</span>
                                                            </div>

                                                            <!-- Citizenship -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Citizenship</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Filipino</span>
                                                            </div>

                                                            <!-- Occupation -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Occupation</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Teacher</span>
                                                            </div>

                                                            <!-- Education -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Education</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">College Graduate</span>
                                                            </div>

                                                            <!-- Batch -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Batch</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">1995</span>
                                                            </div>

                                                            <!-- Is Deceased -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Is Deceased</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">No</span>
                                                            </div>
                                                        </div>

                                                        <h3 class="text-xl font-bold mb-4">Father</h3>

                                                        <!-- Sibling Entry -->
                                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                            <!-- First Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">First Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Maam Roslyn</span>
                                                            </div>

                                                            <!-- Middle Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Sample Middle</span>
                                                            </div>

                                                            <!-- Last Name -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last Name</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Sample Last</span>
                                                            </div>

                                                            <!-- Age -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">50</span>
                                                            </div>

                                                            <!-- Address -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Address</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">123 Sample Street</span>
                                                            </div>

                                                            <!-- Citizenship -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Citizenship</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Filipino</span>
                                                            </div>

                                                            <!-- Occupation -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Occupation</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">Teacher</span>
                                                            </div>

                                                            <!-- Education -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Education</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">College Graduate</span>
                                                            </div>

                                                            <!-- Batch -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Batch</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">1995</span>
                                                            </div>

                                                            <!-- Is Deceased -->
                                                            <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Is Deceased</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">No</span>
                                                            </div>
                                                        </div>

                                                        <!-- Siblings Section -->
                                                        <div class="w-full mt-6">
                                                            <h3 class="text-xl font-bold mb-4">Siblings</h3>

                                                            <!-- Sibling Entry -->
                                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                <div class="mb-4">
                                                                <label class="block text-sm font-medium text-primary opacity-45 mb-1">First Name</label>
                                                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Juan</span>
                                                                </div>
                                                                <div class="mb-4">
                                                                <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle Name</label>
                                                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Dela</span>
                                                                </div>
                                                                <div class="mb-4">
                                                                <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last Name</label>
                                                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Cruz</span>
                                                                </div>
                                                                <div class="mb-4">
                                                                <label class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                                                <span class="text-lg font-semibold text-gray-800 dark:text-white">22</span>
                                                                </div>
                                                                <div class="mb-4">
                                                                <label class="block text-sm font-medium text-primary opacity-45 mb-1">Occupation</label>
                                                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Student</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  

                                            </Transition>

                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Education History'" class="p-4 bg-white border-t border-gray-200">
                                                <div class="flex flex-row w-full">
                                                    <div class="w-1/3">
                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Elementary</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Elementry svhool</span>
                                                        </div>

                                                        <!-- Middle Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Junior High School</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">jhs</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Senior High School</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">shs</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">College</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">urs</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Post Graduate School</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">n/a</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Vocational</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">n/a</span>
                                                        </div>
                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div> 

                                                        
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div> 

                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div>

                                                        <!-- Middle Name -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Years Attended</label>
                                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">2023-2024</span>
                                                        </div>
                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 

                                                        
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 

                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 

                                                        <!-- Middle Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 

                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 

                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Honors/Awards Received</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">None</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </Transition>

                                            <!-- Extracurricular -->
                                            <Transition name="collapse" v-if="section.isOpen && section.title === 'Extracurricular'" class="p-4 bg-white border-t border-gray-200">
                                                <div class="flex flex-row w-full">
                                                    
                                                    <div class="w-1/3">
                                                        <!-- First Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Name of Organization</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">SY 2022-2033 - 1st Sem</span>
                                                        </div>

                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Inclusive Dates</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">1.2</span>
                                                        </div> 

                                                    </div>

                                                    <div class="w-1/3">
                                                        <!-- Last Name -->
                                                        <div class="mb-4">
                                                        <label class="block text-sm font-medium text-primary opacity-45 mb-1">Position Held</label>
                                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">PIO</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </Transition>

                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>


                </div>

                <!-- viewing docs -->
                <div v-if="Checking"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
                    <div
                        class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-10/12 max-h-[95vh] overflow-y-auto">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Call Document Name Here</h2>
                            <button type="button"
                                class="flex items-center gap-2 text-gray-600 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm px-3 py-1.5 dark:hover:bg-gray-600 dark:hover:text-white transition"
                                data-modal-hide="default-modal">
                                <span class="material-symbols-rounded text-lg">open_in_new</span>
                                <span class="font-medium">Open in New Tab</span>
                            </button>
                        </div>


                        <div class="p-4 flex flex-col gap-3">
                            <div class="w-full flex flex-col p-10">
                                <img src="../../../../assets/images/psa_sample.png" alt="">
                            </div>

                            <div class="w-full flex flex-col space-y-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white">*If Returning Requirement</h3>
                                <textarea id="return-requirement" placeholder="Add a message in returning"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 text-gray-900 text-sm w-full h-32 resize-none text-left dark:text-dtext dark:border dark:bg-dsecondary dark:border-gray-600"></textarea>
                            </div>


                            <!-- Close Button -->
                            <div class="mt-2 flex flex-row justify-end">
                                <button type="button" @click="closeModal"
                                    class="text-white font-sans w-4/12 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                    Save
                                </button>
                            </div>
                        </div>
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
import { defineProps, ref, watchEffect, onBeforeMount, reactive } from 'vue';
import { useForm, Link, usePage, router } from '@inertiajs/vue3';
import Papa from 'papaparse';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

import Adding from '../../../Components/Staff/ScholarsTabs/Adding.vue';

import ScholarList from '../../../Components/Staff/ScholarsTabs/ScholarList.vue';
import { Check } from 'lucide-vue-next';

// components

const props = defineProps({
    scholar: Object,
    scholarship: Object,
});

const components = {
    Button,
    Papa,
};

const goBack = () => {
    window.history.back();
};

const sections = ref([
  { title: 'Personal Information', isOpen: false },
  { title: 'Education', isOpen: false },
  { title: 'Contact Information', isOpen: false },
  { title: 'Family Details', isOpen: false },
  { title: 'Education History', isOpen: false },
  { title: 'Extracurricular', isOpen: false }
]);

const toggleSection = (index) => {
  sections.value[index].isOpen = !sections.value[index].isOpen;
};
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

.collapse-enter-active,
.collapse-leave-active {
  transition: all 0.3s ease;
}

.collapse-enter-from,
.collapse-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.collapse-enter-to,
.collapse-leave-from {
  opacity: 1;
  transform: translateY(0);
}

</style>