<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-[#e8f0f9] shadow-sm justify-between flex flex-row px-10">
            <h1 class="text-3xl font-bold font-sora text-left p-3">My Profile</h1>
             <!-- Toggle Button -->
            <button @click="EditProfile = !EditProfile" class="text-sm font-normal text-primary">
            {{ EditProfile ? 'Save Updates' : 'Edit Profile' }}
            </button>
        </div>
        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2 bg-gradient-to-b from-[#E9F4FF] via-white to-white">
            <div class="mx-auto w-7/12 sm:px-6 lg:px-8 ">
                
                <div v-if="!EditProfile"
                class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- pic -->
                        <div class="border w-80 h-80 rounded-lg overflow-hidden">
                            <img :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="Profile Picture"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- info -->
                        <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                            <span class="text-gray-500 text-sm">Permanent Address</span>
                            <span class="text-gray-900 text-base font-semibold leading-tight"></span>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Age</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.age
                                        }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Date of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        formattedDate }}</span>
                                </div>
                            </div>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Civil Status</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.civil
                                        }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Place of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.placebirth }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                            <div class="w-full flex flex-row gap-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Gender</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.gender
                                        }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Religion</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.religion }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- gmail -->
                        <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                            <span class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                            <span class="pl-2 text-gray-900 text-base font-bold">{{ $page.props.auth.user.email
                            }}</span>
                        </div>
                        <!-- qr -->
                        <div v-if="!EditProfile"  
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                            <div v-if="scholar.qr_code" class="w-20 h-20">
                                <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-full h-full">
                            </div>
                            <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                <font-awesome-icon :icon="['fas', 'qrcode']" class="text-gray-400 text-3xl" />
                            </div>
                            <button @click="openQRModal"
                                class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition">
                                View & Download QR Code
                            </button>
                        </div>
                    </div>
                    <div class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                        <div class="w-full h-1/12">
                            <span class="font-italic font-sora text-3xl font-bold uppercase">{{ student.last_name
                            }},
                                {{ student.first_name }}</span>
                        </div>

                        <div
                            class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.course.name
                                    }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'id-card-clip']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                    scholar.urscholar_id }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'school']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.campus.name
                                    }}, Campus</span>
                            </div>
                        </div>

                        <!-- education -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                            <h1 class="text-base">Education</h1>
                            
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    General Weighted Average
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">Year and Semester</span>
                                    <div class="flex flex-col items-end">
                                        <span class="text-gray-700 text-base font-medium leading-tight">{{ grade ? grade.grade : 'N/A'
                                            }}</span>
                                        <button class="text-sm" @click="toggleCheck">
                                            View Certificate of Grade
                                        </button>
                                    </div>
                                </div>

                                <hr class="border-gray-300 my-4">
                            </div>

                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Elementary
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Junior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ junior.name
                                        }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ junior.years
                                        }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Senior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ senior.name
                                        }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ senior.years
                                        }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    College
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ college.name
                                        }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ college.years
                                        }}</span>
                                </div>
                            </div>
                            <!-- Vocational Section -->
                            <div v-if="vocational.name !== 'N/A' && vocational.years !== 'N/A'">
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Vocational
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ vocational.name }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ vocational.years }}</span>
                                </div>
                            </div>

                            <!-- Post Graduate Section -->
                            <div v-if="postgrad.name !== 'N/A' && postgrad.years !== 'N/A'">
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Post Graduate
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ postgrad.name }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ postgrad.years }}</span>
                                </div>
                            </div>

                        </div>

                        <!-- family -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="cols-span-2 text-base">Family</h1>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person-dress']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                mother.first_name }}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                mother.occupation }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left ">
                                        <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly Family
                                            Income</span>
                                        <span class="text-gray-900 text-3xl font-semibold leading-tight">100,000</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                father.first_name }}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                father.occupation }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Family Housing
                                            Type</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                            family.family_housing }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'people-roof']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Kapatid1,
                                                Kapatid2,</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Trabaho1,
                                                Trabaho2</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Other Sources
                                            of Income</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                            family.other_income }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>


                <div v-if="EditProfile"  
                class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- Upload & Preview Section -->
                        <div class="w-full sm:w-[30%] flex flex-col items-center gap-1.5">
                        <label for="dropzone-img"
                            class="flex flex-col items-center justify-center w-80 h-80 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100"
                            :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                            @dragover.prevent="handleImgDragOver"
                            @dragleave="handleImgDragLeave"
                            @drop.prevent="handleImgDrop">
                            
                            <!-- Show Current or New Preview -->
                            <div v-if="!form.imgPreview" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB-4MB)</p>
                            </div>

                            <!-- Show New Uploaded Image -->
                            <div v-else class="flex flex-col items-center justify-center">
                            <img :src="form.imgPreview" alt="Uploaded Preview" class="max-h-56 mb-2 rounded-lg" />
                            </div>

                            <input id="dropzone-img" type="file" class="hidden" accept=".png, .jpg, .jpeg" @change="handleImgChange" />
                        </label>
                        </div>

                        <!-- info -->
                        <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                            <span class="text-gray-500 text-sm">Permanent Address</span>
                            <span class="text-gray-900 text-base font-semibold leading-tight"></span>
                            <div class="w-full flex flex-col gap-2 py-2">
                                <div class="w-full flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Age</span>
                                    <div class="relative w-full">
                                    <input v-model="student.age" type="text" placeholder="Enter Email" 
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                        <!-- Icon inside input -->
                                        <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                    </div>
                                    <!-- <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.age
                                    }}</span> -->
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Date of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        formattedDate }}</span>
                                </div>
                            </div>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Civil Status</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.civil
                                    }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Place of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.placebirth }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                            <div class="w-full flex flex-row gap-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Gender</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.gender
                                    }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Religion</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.religion }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- gmail -->
                        <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                            <span class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                            <!-- <span class="pl-2 text-gray-900 text-base font-bold">{{ $page.props.auth.user.email
                                }}</span> -->
                                <div class="relative w-full">
                                <input  type="text" placeholder="Enter Email" 
                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    <!-- Icon inside input -->
                                    <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                </div>
                        </div>
                        <!-- qr -->
                        <div
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                            <div v-if="scholar.qr_code" class="w-20 h-20">
                                <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-full h-full">
                            </div>
                            <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                <font-awesome-icon :icon="['fas', 'qrcode']" class="text-gray-400 text-3xl" />
                            </div>
                            <button @click="openQRModal"
                                class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition">
                                View & Download QR Code
                            </button>
                        </div>
                    </div>
                    <div class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                        <div class="w-full h-1/12">
                            <span class="font-italic font-sora text-3xl font-bold uppercase">{{ student.last_name
                                }},
                                    {{ student.first_name }}</span>
                        </div>

                        <div
                            class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.course.name }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'id-card-clip']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                    scholar.urscholar_id }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'school']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.campus.name }}, Campus</span>
                            </div>
                        </div>

                        <!-- education -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                            <h1 class="text-base">Education</h1>
                            
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    General Weighted Average
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <div class="flex flex-col space-y-2">
                                        <span class="text-gray-700 text-base font-medium leading-tight">Update Grade and Certificate of Grade</span>
                                        <span class="text-gray-600 text-base leading-tight">Last Update: Noon pa</span>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <!-- gwa input -->
                                        <div class="relative pl-1">
                                        <input v-model="grade.grade" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>

                                        <!-- File Input -->
                                        <div class="">
                                        <input 
                                            type="file" 
                                            @change="handleFileUpload"
                                            class="w-full border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        />
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-gray-300 my-4">
                            </div>
                            
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Elementary
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="elementary.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="elementary.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Junior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="junior.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="junior.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Senior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="senior.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="senior.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    College
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="college.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="college.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>
                            <!-- Vocational Section -->
                            <div v-if="vocational.name !== 'N/A' && vocational.years !== 'N/A'">
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Vocational
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="vocational.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="vocational.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>

                            <!-- Post Graduate Section -->
                            <div v-if="postgrad.name !== 'N/A' && postgrad.years !== 'N/A'">
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Post Graduate
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center gap-3">
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="postgrad.name" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                    <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span> -->
                                        <div class="relative w-full">
                                        <input v-model="postgrad.years" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2"/>
                                        </div>
                                </div>
                            </div>

                        </div>

                        <!-- family -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="cols-span-2 text-base">Family</h1>
                            <div class="flex flex-col gap-4">
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person-dress']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Mothers Name</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="mother.first_name" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Occupation</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="mother.occupation" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Fathers Name</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="father.first_name" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Occupation</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="father.occupation" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'people-roof']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Siblings</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="father.first_name" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <!-- Label (30%) -->
                                                    <span class="text-gray-700">Occupation</span>

                                                    <!-- Input Container (70%) -->
                                                    <div class="relative w-full">
                                                        <input v-model="father.occupation" type="text" placeholder="Enter User ID" 
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                                        
                                                        <!-- Icon inside input -->
                                                        <font-awesome-icon :icon="['fas', 'pen']" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="w-full flex flex-col items-left ">
                                        <span class="text-gray-500 text-sm font-semibold leading-tight mb-2">Monthly Family
                                            Income</span>
                                            <div
                                            class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                            <RadioGroup default-value="comfortable"
                                                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2"
                                                v-model="form.monthly_income">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i1" value="below" />
                                                    <Label for="i1">10,000 and below</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i2" value="mid" />
                                                    <Label for="i2">20,001 - 30,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i3" value="average" />
                                                    <Label for="i3">10,001 - 20,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i4" value="above" />
                                                    <Label for="i4">30,001 and above</Label>
                                                </div>
                                            </RadioGroup>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-sm font-semibold leading-tight mb-2">Family
                                            Housing Type</span>
                                            <div
                                            class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                            <RadioGroup default-value="comfortable"
                                                class="flex sm:flex-col md:flex-row gap-2"
                                                v-model="form.family_housing">

                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h1" value="owned" />
                                                    <Label for="h1">Owned</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h2" value="settler" />
                                                    <Label for="h2">Settler</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h3" value="rental" />
                                                    <Label for="h3">Rental</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="h4" value="other" />
                                                    <Label for="h4">Others, please specify:</Label>
                                                    <input v-if="form.family_housing === 'other'" type="text"
                                                        v-model="otherText" placeholder="Type here..."
                                                        class="border border-gray-200 focus:outline-none w-32 px-2 text-sm" />
                                                </div>

                                            </RadioGroup>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Other Sources
                                            of Income</span>
                                        <!-- Input Container (70%) -->
                                        <div class="relative w-full">
                                            <input v-model="family.other_income" type="text" placeholder="Enter User ID" 
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- QR Code Modal -->
        <div v-if="isQRModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeQRModal">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Your QR Code</h3>
                    <button @click="closeQRModal" class="text-gray-400 hover:text-gray-500">
                        <font-awesome-icon :icon="['fas', 'times']" class="w-5 h-5" />
                    </button>
                </div>

                <div class="border border-gray-300 p-4 aspect-square flex items-center justify-center w-full">
                    <!-- Show loading spinner while generating -->
                    <div v-if="loading" class="flex items-center justify-center">
                        <font-awesome-icon :icon="['fas', 'circle-notch']"
                            class="w-10 h-10 animate-spin text-primary" />
                    </div>

                    <!-- Show error message if there is one -->
                    <div v-else-if="error" class="text-red-500 text-center">
                        {{ error }}
                    </div>

                    <!-- Show QR code if available -->
                    <img v-else-if="qrCodeUrl" :src="`/storage/qr_codes/${qrCodeUrl}`" alt="QR Code"
                        class="max-w-full max-h-full" />

                    <!-- Show placeholder if no QR code yet -->
                    <div v-else class="flex items-center justify-center">
                        <font-awesome-icon :icon="['fas', 'qrcode']" class="w-16 h-16 text-gray-300" />
                    </div>
                </div>

                <div class="text-center py-4 gap-2 flex items-center justify-center">
                    <!-- <button @click="forceRegenerateQRCode" :disabled="loading"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 disabled:opacity-50">
                        {{ loading ? 'Generating...' : 'Regenerate' }}
                    </button> -->
                    <button @click="downloadQRCode" :disabled="!qrCodeUrl || loading"
                        class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 disabled:opacity-50">
                        Download
                    </button>
                </div>
            </div>
        </div>

        <div v-if="View_Grades" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300 dark:bg-primary dark:bg-opacity-50 z-50">
            <!-- Modal Container -->
            <div class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 overflow-hidden">
                <!-- Close Button (Positioned at Top Right) -->
                <button 
                    @click="closeModal"
                    class="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-full p-2 transition-all"
                >
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>

                <!-- Modal Header -->
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-4">Viewing Document</h2>

                <!-- Document Viewer -->
                <div class="w-full h-[80vh]">
                    <iframe :src="documentUrl" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'

const props = defineProps({
    student: Object,
    education: Object,
    family: Object,
    scholar: Object,
    grade: Object,
});

const EditProfile = ref(false);  // Toggle state

// QR Code state management
const isQRModalOpen = ref(false);
const qrCodeUrl = ref(null);
const loading = ref(false);
const error = ref(null);

// Watch for qrCodeUrl in page props (for when returning from generation)
watch(() => props.flash?.qrCodeUrl, (newUrl) => {
    if (newUrl) {
        qrCodeUrl.value = newUrl;
    }
});

const openQRModal = () => {
    isQRModalOpen.value = true;

    // Check if the scholar has an existing QR code
    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    } else {
        // If no QR code exists, generate one
        generateQRCode();
    }
};

const closeQRModal = () => {
    isQRModalOpen.value = false;
};

const generateQRCode = () => {
    loading.value = true;
    error.value = null;

    // Make sure we have a valid scholar ID
    if (!props.scholar || !props.scholar.urscholar_id) {
        error.value = 'Scholar information not available.';
        loading.value = false;
        return;
    }

    router.visit(`/myProfile/generate/${props.scholar.urscholar_id}`, {
        method: 'get',
        preserveState: true,
        onSuccess: (page) => {
            if (page.props.flash?.qrCodeUrl) {
                qrCodeUrl.value = page.props.flash.qrCodeUrl;
            } else {
                error.value = 'QR code generation failed. Please try again.';
            }
            loading.value = false;
        },
        onError: (errors) => {
            error.value = 'Failed to generate QR code. Please try again.';
            console.error(errors);
            loading.value = false;
        }
    });
};

const downloadQRCode = async () => {
    if (!qrCodeUrl.value) {
        error.value = 'No QR code available to download.';
        return;
    }

    try {
        loading.value = true;

        // Fetch the QR code image as a Blob
        const response = await fetch(qrCodeUrl.value);

        if (!response.ok) {
            throw new Error('Failed to fetch QR code.');
        }

        const blob = await response.blob();

        // Ensure it's a valid image
        if (blob.type !== 'image/png') {
            throw new Error('Invalid QR code file format.');
        }

        // Create a temporary download link
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${props.scholar.urscholar_id}.png`;

        // Append, click, and remove the link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        error.value = null; // Clear any errors

    } catch (err) {
        console.error("Download failed:", err);
        error.value = 'Failed to download QR code.';
    } finally {
        loading.value = false;
    }
};

const form = ref({
img: null,
imgPreview: null
});

const View_Grades = ref(false);

const toggleCheck = () => {
    View_Grades.value = !View_Grades.value;
    if (View_Grades.value) {
        resetForm();
    }
};

const closeModal = () => {
    View_Grades.value = false;
    resetForm();
};

const selectedFile = ref(null);

const handleFileUpload = (event) => {
  selectedFile.value = event.target.files[0];
  console.log("Selected file:", selectedFile.value);
};

const isDragging = ref(false);

// Handle Drag Over & Drag Leave
const handleImgDragOver = () => isDragging.value = true;
const handleImgDragLeave = () => isDragging.value = false;

// Handle File Upload
const handleImgChange = (event) => {
const file = event.target.files[0];
if (file) {
    form.value.img = file;
    const reader = new FileReader();
    reader.onload = (e) => {
    form.value.imgPreview = e.target.result; // Show preview before saving
    };
    reader.readAsDataURL(file);
}
};

// Handle Profile Picture Update
const updateProfilePicture = () => {
if (!form.value.img) {
    alert("Please upload an image first.");
    return;
}

const formData = new FormData();
formData.append('profile_picture', form.value.img);

// Send FormData to backend (Example using fetch API)
fetch('/api/update-profile-picture', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    alert('Profile updated successfully!');
})
.catch(error => {
    console.error('Error updating profile picture:', error);
});
};


const formattedDate = new Date(props.student.birthdate).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric"
});

// Initialize QR code URL on component mount
onMounted(() => {
    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    }

    // Also check if there's a flash message with a QR code URL
    if (props.flash?.qrCodeUrl) {
        qrCodeUrl.value = props.flash.qrCodeUrl;
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
