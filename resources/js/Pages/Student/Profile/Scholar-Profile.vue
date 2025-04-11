<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <form @submit.prevent="submit" class="w-full h-full overflow-hidden">
            <div class="w-full bg-dirtywhite shadow-sm justify-between flex flex-row">
                <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3 md:mx-20">
                    My Profile
                </h1>
                <!-- Web Version -->
                <div class="hidden lg:flex space-x-4 p-3 mx-20">
                    <!-- Edit Button -->
                    <button v-if="!EditProfileWeb" type="button" @click="enableWebEdit"
                        class="text-primary font-medium text-sm">
                        Edit Profile
                    </button>

                    <!-- Save Button -->
                    <button v-else type="submit" class="text-primary font-medium text-sm">
                        Save Updates
                    </button>

                    <!-- Cancel Button -->
                    <button v-if="EditProfileWeb" type="button" @click="cancelWebEdit"
                        class="text-gray-500 font-medium text-sm">
                        Cancel
                    </button>
                </div>

                <!-- Mobile Version -->
                <div class="flex lg:hidden space-x-4 p-3">
                    <!-- Edit Button -->
                    <button v-if="!EditProfileMobile" type="button" @click="enableMobileEdit"
                        class="text-primary font-medium text-xs">
                        Edit Profile
                    </button>

                    <!-- Save Button -->
                    <button v-else type="submit" class="text-primary font-medium text-xs">
                        Save Updates
                    </button>

                    <!-- Cancel Button -->
                    <button v-if="EditProfileMobile" type="button" @click="cancelMobileEdit"
                        class="text-gray-500 font-medium text-xs">
                        Cancel
                    </button>
                </div>
            </div>
            <div
                class="pt-3 sm:pb-5 lg:pb-24 overflow-auto h-full scroll-py-2 bg-gradient-to-b from-[#E9F4FF] via-white to-white">
                <div class="mx-auto sm:w-11/12 lg:w-7/12 sm:px-1 lg:px-8 ">

                    <!-- Mobile Display------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <!-- Mobile Wrapper: Visible on mobile screens -->
                    <div class="block md:hidden">
                        <!-- Content for Mobile -->
                        <div v-if="!EditProfileMobile"
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                                <!-- Profile Picture -->
                                <div class="border w-40 h-40 rounded-lg overflow-hidden">
                                    <img :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                        alt="Profile Picture" class="w-full h-full object-cover">
                                </div>

                                <div class="w-full text-center h-12/12">
                                    <span class="font-italic font-sora text-3xl font-bold uppercase">{{
                                        student.last_name
                                        }},
                                        {{ student.first_name }}</span>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.course.name
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
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.campus.name
                                        }}, Campus</span>
                                    </div>
                                </div>

                                <!-- education -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                                    <h1 class="text-base">Education</h1>

                                    <div v-if="latestgrade" class="space-y-2">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight mb-2">
                                            General Weighted Average
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                latestgrade.school_year.year }} {{ latestgrade.semester }}
                                                Semester</span>
                                            <div class="flex flex-col items-end">
                                                <span class="text-gray-700 text-base font-medium leading-tight">
                                                    {{ latestgrade ? latestgrade.grade : 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <button class="text-sm bg-primary w-full text-center rounded-lg"
                                            @click="toggleCheck">
                                            View Certificate of Grades
                                        </button>
                                        <hr class="border-gray-300">
                                    </div>
                                    <div v-else>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            General Weighted Average
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">Must upload
                                                <b>{{ semesterGrade }} Semester
                                                    {{ schoolyear_grade.year }}</b>
                                            </span>
                                            <div class="flex flex-col items-end">
                                                <button class="text-sm" @click="toggleCheck">
                                                    View
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="border-gray-300 my-4">
                                    </div>
                                    <!-- Notification Message for Stakeholder -->
                                    <div v-if="notify && !latestgrade"
                                        class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mt-4 flex flex-col items-left">
                                        <span class="space-x-2">
                                            <font-awesome-icon :icon="['fas', 'circle-exclamation']"
                                                class="text-yellow-800" />
                                            <span class="font-semibold">Reminder:</span>
                                        </span>
                                        Your grades need to be updated. Please upload your latest available Copy of
                                        Grades and General
                                        Weighted Average (GWA).
                                    </div>

                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Elementary
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                elementary.name
                                            }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                elementary.years
                                            }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Junior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                junior.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                junior.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Senior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                senior.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                senior.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            College
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                college.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                college.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <!-- Vocational Section -->
                                    <div v-if="vocational.name !== 'N/A' && vocational.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Vocational
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.name
                                            }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.years
                                            }}</span>
                                        </div>
                                    </div>

                                    <!-- Post Graduate Section -->
                                    <div v-if="postgrad.name !== 'N/A' && postgrad.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Post Graduate
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.years
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full h-1/12 flex flex-col items-start gap-1 pb-4 border-b-2">
                                        <span class="text-gray-500 text-sm">Permanent Address</span>
                                        <span class="text-gray-900 text-base font-semibold leading-tight">
                                            {{ student.address }}
                                        </span>

                                        <!-- Age and Date of Birth Section -->
                                        <div class="w-full flex flex-col sm:flex-row gap-2 py-2">
                                            <div class="w-full sm:w-[40%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Age</span>
                                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                    student.age }}</span>
                                            </div>
                                            <div class="w-full sm:w-[60%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Date of Birth</span>
                                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                    formattedDate }}</span>
                                            </div>
                                        </div>

                                        <!-- Civil Status and Place of Birth Section -->
                                        <div class="w-full flex flex-col sm:flex-row gap-2 py-2">
                                            <div class="w-full sm:w-[40%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Civil Status</span>
                                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                    student.civil }}</span>
                                            </div>
                                            <div class="w-full sm:w-[60%] flex flex-col items-start gap-1">
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
                                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                    student.gender
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
                                        <!-- Icon -->
                                        <span
                                            class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>

                                        <!-- Email Container -->
                                        <div class="flex-1 min-w-0">
                                            <span
                                                class="block pl-2 text-gray-900 text-base font-bold break-words leading-tight">
                                                {{ $page.props.auth.user.email }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- family -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                                    <h1 class="cols-span-2 text-base">Family</h1>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <div class="w-full flex flex-row items-center gap-2 py-2">
                                                <font-awesome-icon :icon="['fas', 'person-dress']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />

                                                <div v-if="mother.first_name === 'n\/a'"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">Deceased</span>
                                                </div>
                                                <div v-else class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        mother.first_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        mother.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-row items-center gap-2 py-2">
                                                <font-awesome-icon :icon="['fas', 'person']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div v-if="father.first_name === 'n\/a'"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">Deceased</span>
                                                </div>
                                                <div v-else class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        father.first_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        father.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="w-full flex flex-row items-center gap-2 py-2">
                                                <font-awesome-icon :icon="['fas', 'people-roof']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div v-if="siblings.length === 0"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">N/A</span>
                                                </div>
                                                <div v-else v-for="sibling in siblings" :key="sibling.id"
                                                    class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        sibling.first_name }} {{ sibling.last_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        sibling.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left ">
                                                <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly
                                                    Family
                                                    Income</span>
                                                <span
                                                    class="text-gray-900 text-3xl font-semibold leading-tight">100,000</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left gap-1 py-1">
                                                <span class="text-gray-500 text-base font-semibold leading-tight">Family
                                                    Housing
                                                    Type</span>
                                                <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                                    family.family_housing }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left gap-1 py-1">
                                                <span class="text-gray-500 text-base font-semibold leading-tight">Other
                                                    Sources
                                                    of Income</span>
                                                <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                                    family.other_income }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- qr -->
                                <div
                                    class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                                    <div v-if="scholar.qr_code" class="w-20 h-20">
                                        <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code"
                                            class="w-full h-full">
                                    </div>
                                    <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon :icon="['fas', 'qrcode']" class="text-gray-400 text-3xl" />
                                    </div>
                                    <button @click="openQRModal()"
                                        class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition">
                                        Download your QR Code
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- Mobile Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                        <div v-if="EditProfileMobile"
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                                <!-- Profile Picture -->
                                <div class="w-full sm:w-[30%] flex flex-col items-center gap-1.5">
                                    <label for="dropzone-img"
                                        class="flex flex-col items-center justify-center w-44 h-44 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100"
                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                        @dragover.prevent="handleImgDragOver" @dragleave="handleImgDragLeave"
                                        @drop.prevent="handleImgDrop">

                                        <!-- Show Current or New Preview -->
                                        <div v-if="!form.imgPreview"
                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB-4MB)</p>
                                        </div>

                                        <!-- Show New Uploaded Image -->
                                        <div v-else class="flex flex-col items-center justify-center">
                                            <img :src="form.imgPreview" alt="Uploaded Preview"
                                                class="max-h-56 mb-2 rounded-lg" />
                                        </div>

                                        <input id="dropzone-img" type="file" class="hidden" accept=".png, .jpg, .jpeg"
                                            @change="handleImgChange" />
                                    </label>
                                </div>

                                <div class="w-full text-center h-12/12">
                                    <span class="font-italic font-sora text-3xl font-bold uppercase">{{
                                        student.last_name
                                        }},
                                        {{ student.first_name }}</span>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.course.name
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
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.campus.name
                                        }}, Campus</span>
                                    </div>
                                </div>

                                <!-- education -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                                    <h1 class="text-base">Education</h1>

                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Elementary
                                        </h3>
                                        <div class="w-full flex flex-col justify-between items-center gap-3">
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                                }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.elementary.name" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                            }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.elementary.years" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Junior High School
                                        </h3>
                                        <div class="w-full flex flex-col justify-between items-center gap-3">
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                                }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.junior.name" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                            }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.junior.years" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Senior High School
                                        </h3>
                                        <div class="w-full flex flex-col justify-between items-center gap-3">
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                                }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.senior.name" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                            }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.senior.years" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            College
                                        </h3>
                                        <div class="w-full flex flex-col justify-between items-center gap-3">
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                                }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.college.name" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                            <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                            }}</span> -->
                                            <div class="relative w-full">
                                                <input v-model="form.education.college.years" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Vocational Section -->
                                    <div v-if="vocational.name !== 'N/A' && vocational.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Vocational
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.name
                                            }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.years
                                            }}</span>
                                        </div>
                                    </div>

                                    <!-- Post Graduate Section -->
                                    <div v-if="postgrad.name !== 'N/A' && postgrad.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Post Graduate
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.years
                                            }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full h-1/12 flex flex-col items-start gap-1 pb-4 border-b-2">
                                        <span class="text-gray-500 text-sm">Permanent Address</span>
                                        <span class="text-gray-500 text-xs">Street</span>
                                        <div class="relative w-full">
                                            <input v-model="form.street" type="text" placeholder="Address"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <span class="text-gray-500 text-sm">Municipality</span>
                                        <div class="relative w-full">
                                            <input v-model="form.municipality" type="text" placeholder="Address"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <span class="text-gray-500 text-sm">Province</span>
                                        <div class="relative w-full">
                                            <input v-model="form.province" type="text" placeholder="Address"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>

                                        <!-- Age and Date of Birth Section -->
                                        <div class="w-full flex flex-col sm:flex-row gap-2 py-2">
                                            <div class="w-full sm:w-[40%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Age</span>
                                                <div class="relative w-full">
                                                    <input v-model="form.age" type="text" placeholder="Address"
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                    <!-- Icon inside input -->
                                                    <font-awesome-icon :icon="['fas', 'pen']"
                                                        class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-[60%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Date of Birth</span>
                                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                    formattedDate }}</span>
                                            </div>
                                        </div>

                                        <!-- Civil Status and Place of Birth Section -->
                                        <div class="w-full flex flex-col sm:flex-row gap-2 py-2">
                                            <div class="w-full sm:w-[40%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Civil Status</span>
                                                <div class="relative w-full">
                                                    <input v-model="form.civil" type="text" placeholder="Address"
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                    <!-- Icon inside input -->
                                                    <font-awesome-icon :icon="['fas', 'pen']"
                                                        class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                                </div>
                                            </div>
                                            <div class="w-full sm:w-[60%] flex flex-col items-start gap-1">
                                                <span class="text-gray-500 text-sm">Place of Birth</span>
                                                <div class="relative w-full">
                                                    <input v-model="form.birthplace" type="text" placeholder="Address"
                                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                    <!-- Icon inside input -->
                                                    <font-awesome-icon :icon="['fas', 'pen']"
                                                        class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                                        <div class="w-full flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Gender</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                student.gender
                                            }}</span>
                                        </div>
                                        <div class="w-full flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Religion</span>
                                            <div class="relative w-full">
                                                <input v-model="form.religion" type="text" placeholder="Address"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- gmail -->
                                    <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                                        <!-- Icon -->
                                        <span
                                            class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>

                                        <!-- Email Container -->
                                        <div class="flex-1 min-w-0">
                                            <div class="relative w-full">
                                                <input v-model="form.age" type="text" placeholder="Address"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- family -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument flex flex-col items-left space-y-2 gap-2 py-5 px-5">
                                    <h1 class="cols-span-2 text-base">Family</h1>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div
                                            class="space-y-2">
                                            <div class="col-span-4 grid w-full items-center gap-1.5">
                                                <!-- Edit Sibling Entries -->
                                                <div v-if="form.mother.first_name === 'n/a'"
                                                    class="border border-gray-100 px-5 py-3">
                                                    <div class="w-full flex flex-row items-center mb-2">
                                                        <font-awesome-icon :icon="['fas', 'people-roof']"
                                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                        <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                            <div
                                                                class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                                <span class="text-gray-700">Mother</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-for="(entry, index) in form.siblings" :key="index"
                                                        class="col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                        <!-- First Name -->
                                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                                            <Label :for="'edit_first_name_' + index">First Name</Label>
                                                            <Input :id="'edit_first_name_' + index" type="text"
                                                                placeholder="First Name" v-model="entry.first_name"
                                                                class="w-full border border-gray-200" />
                                                        </div>

                                                        <!-- Last Name -->
                                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                                            <Label :for="'edit_last_name_' + index">Last Name</Label>
                                                            <Input :id="'edit_last_name_' + index" type="text"
                                                                placeholder="Last Name" v-model="entry.last_name"
                                                                class="w-full border-gray-200" />
                                                        </div>

                                                        <!-- Middle Name -->
                                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                                            <Label :for="'edit_middle_name_' + index">Middle
                                                                Name</Label>
                                                            <Input :id="'edit_middle_name_' + index" type="text"
                                                                placeholder="Middle Name" v-model="entry.middle_name"
                                                                class="w-full border-gray-200" />
                                                        </div>

                                                        <!-- Age -->
                                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                                            <Label :for="'edit_age_' + index">Age</Label>
                                                            <Input :id="'edit_age_' + index" type="number"
                                                                placeholder="Age" v-model="entry.age"
                                                                class="w-full border-gray-200" />
                                                        </div>

                                                        <!-- Occupation -->
                                                        <div class="grid w-full max-w-sm items-center gap-1.5">
                                                            <Label :for="'edit_occupation_' + index">Occupation</Label>
                                                            <Input :id="'edit_occupation_' + index" type="text"
                                                                placeholder="Occupation" v-model="entry.occupation"
                                                                class="w-full border-gray-200" />
                                                        </div>

                                                        <!-- NO REMOVE BUTTON -->
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit father Entries -->
                                            <div v-if="form.father.first_name === 'n/a'"
                                                class="border border-gray-100 px-5 py-3">
                                                <div class="w-full flex flex-row items-center mb-2">
                                                    <font-awesome-icon :icon="['fas', 'people-roof']"
                                                        class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                    <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                        <div
                                                            class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                            <span class="text-gray-700">Father</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                    <!-- First Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label>First Name</Label>
                                                        <Input type="text" placeholder="First Name"
                                                            v-model="form.father.first_name"
                                                            class="w-full border border-gray-200" />
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label>Last Name</Label>
                                                        <Input type="text" placeholder="Last Name"
                                                            v-model="form.father.last_name"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Middle Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label>Middle Name</Label>
                                                        <Input type="text" placeholder="Middle Name"
                                                            v-model="form.father.middle_name"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Age -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label>Age</Label>
                                                        <Input type="number" placeholder="Age" v-model="form.father.age"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Occupation -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label>Occupation</Label>
                                                        <Input type="text" placeholder="Occupation"
                                                            v-model="form.father.occupation"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- NO REMOVE BUTTON -->
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="siblings.length > 0"
                                            class="w-full h-1/12 font-instrument flex flex-col items-left border border-gray-100 px-5 py-3">
                                            <div class="w-full flex flex-row items-center">
                                                <font-awesome-icon :icon="['fas', 'people-roof']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                    <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                        <span class="text-gray-700">Siblings</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-for="(entry, index) in form.siblings" :key="index"
                                                class="entry py-2 col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                <!-- First Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'edit_first_name_' + index">First Name</Label>
                                                    <Input :id="'edit_first_name_' + index" type="text"
                                                        placeholder="First Name" v-model="entry.first_name"
                                                        class="w-full border border-gray-200" />
                                                </div>

                                                <!-- Last Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'edit_last_name_' + index">Last Name</Label>
                                                    <Input :id="'edit_last_name_' + index" type="text"
                                                        placeholder="Last Name" v-model="entry.last_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Middle Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'edit_middle_name_' + index">Middle Name</Label>
                                                    <Input :id="'edit_middle_name_' + index" type="text"
                                                        placeholder="Middle Name" v-model="entry.middle_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Age -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'edit_age_' + index">Age</Label>
                                                    <Input :id="'edit_age_' + index" type="number" placeholder="Age"
                                                        v-model="entry.age" class="w-full border-gray-200" />
                                                </div>

                                                <!-- Occupation -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label :for="'edit_occupation_' + index">Occupation</Label>
                                                    <Input :id="'edit_occupation_' + index" type="text"
                                                        placeholder="Occupation" v-model="entry.occupation"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- NO REMOVE BUTTON -->
                                            </div>
                                        </div>

                                        <div class="w-full flex flex-col items-left ">
                                            <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly
                                                Family
                                                Income</span>
                                            <div
                                                class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                                <RadioGroup default-value="comfortable"
                                                class="grid sm:grid-cols-1 md:grid-cols-2 gap-2"
                                                v-model="form.monthly_income">
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i1" value="10,000 and below" />
                                                    <Label for="i1">10,000 and below</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i3" value="10,001 - 20,000" />
                                                    <Label for="i3">10,001 - 20,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i2" value="20,001 - 30,000" />
                                                    <Label for="i2">20,001 - 30,000</Label>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <RadioGroupItem id="i4" value="30,001 and above" />
                                                    <Label for="i4">30,001 and above</Label>
                                                </div>
                                            </RadioGroup>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left gap-1 py-1">
                                                <span class="text-gray-500 text-base font-semibold leading-tight">Family
                                                    Housing
                                                    Type</span>
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

                                        <div class="w-full flex flex-col items-left gap-1 py-1">
                                            <span class="text-gray-500 text-base font-semibold leading-tight">Other
                                                Sources
                                                of Income</span>
                                            <div class="relative w-full">
                                                <input v-model="family.other_income" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm" />
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Web------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <!-- Web Wrapper: Visible on larger screens (md and up) -->
                    <div class="hidden md:block">
                        <!-- Content for Web -->
                        <div v-if="!EditProfileWeb" class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                                <!-- pic -->
                                <div class="border w-80 h-80 rounded-lg overflow-hidden">
                                    <img :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                        alt="Profile Picture" class="w-full h-full object-cover">
                                </div>

                                <!-- info -->
                                <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                                    <span class="text-gray-500 text-sm">Permanent Address</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">
                                        {{ student.address }}
                                    </span>
                                    <div class="w-full flex flex-row gap-2 py-2">
                                        <div class="w-[40%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Age</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                student.age
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
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                student.civil
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
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                student.gender
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
                                    <!-- Icon -->
                                    <span
                                        class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>

                                    <!-- Email Container -->
                                    <div class="flex-1 min-w-0">
                                        <span
                                            class="block pl-2 text-gray-900 text-base font-bold break-words leading-tight">
                                            {{ $page.props.auth.user.email }}
                                        </span>
                                    </div>
                                </div>

                                <!-- qr -->
                                <div v-if="!EditProfile"
                                    class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                                    <div v-if="scholar">
                                        <div v-if="scholar.qr_code"
                                            class="w-40 h-40 flex items-center justify-center mx-auto">
                                            <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code"
                                                class="w-full h-full object-contain" />
                                        </div>

                                        <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                            <font-awesome-icon :icon="['fas', 'qrcode']"
                                                class="text-gray-400 text-3xl" />
                                        </div>
                                        <button @click="openQRModal"
                                            class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition">
                                            View & Download QR Code
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div
                                class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                                <div class="w-full h-1/12">
                                    <span class="font-italic font-sora text-3xl font-bold uppercase">{{
                                        student.last_name
                                        }},
                                        {{ student.first_name }}</span>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.course.name
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
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            scholar.campus.name
                                        }}, Campus</span>
                                    </div>
                                </div>

                                <!-- education -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                                    <h1 class="text-base">Education</h1>

                                    <div v-if="latestgrade">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            General Weighted Average
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                latestgrade.school_year.year }} {{ latestgrade.semester }}
                                                Semester</span>
                                            <div class="flex flex-col items-end">
                                                <span class="text-gray-700 text-base font-medium leading-tight">
                                                    {{ latestgrade ? latestgrade.grade : 'N/A' }}
                                                </span>
                                                <button class="text-sm" @click="toggleCheck">
                                                    View Certificate of Grades
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="border-gray-300">
                                    </div>
                                    <div v-else>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            General Weighted Average
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">Must upload
                                                <b>{{ semesterGrade }} Semester
                                                    {{ schoolyear_grade.year }}</b>
                                            </span>
                                            <div class="flex flex-col items-end">
                                                <button class="text-sm" @click="toggleCheck">
                                                    View
                                                </button>
                                            </div>
                                        </div>
                                        <hr class="border-gray-300 my-4">
                                    </div>
                                    <!-- Notification Message for Stakeholder -->
                                    <div v-if="notify && !latestgrade"
                                        class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mt-4 flex flex-col items-left">
                                        <span class="space-x-2">
                                            <font-awesome-icon :icon="['fas', 'circle-exclamation']"
                                                class="text-yellow-800" />
                                            <span class="font-semibold">Reminder:</span>
                                        </span>
                                        Your grades need to be updated. Please upload your latest available Copy of
                                        Grades and General
                                        Weighted Average (GWA).
                                    </div>

                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Elementary
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                elementary.name
                                            }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                elementary.years
                                            }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Junior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                junior.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                junior.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Senior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                senior.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                senior.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            College
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                college.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                college.years
                                                }}</span>
                                        </div>
                                    </div>
                                    <!-- Vocational Section -->
                                    <div v-if="vocational.name !== 'N/A' && vocational.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Vocational
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.name
                                            }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                vocational.years
                                            }}</span>
                                        </div>
                                    </div>

                                    <!-- Post Graduate Section -->
                                    <div v-if="postgrad.name !== 'N/A' && postgrad.years !== 'N/A'">
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Post Graduate
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.name
                                                }}</span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">{{
                                                postgrad.years
                                            }}</span>
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

                                                <div v-if="mother.first_name === 'n\/a'"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">Deceased</span>
                                                </div>
                                                <div v-else class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        mother.first_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        mother.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left ">
                                                <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly
                                                    Family
                                                    Income</span>
                                                <span
                                                    class="text-gray-900 text-3xl font-semibold leading-tight">100,000</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-row items-center gap-2 py-2">
                                                <font-awesome-icon :icon="['fas', 'person']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div v-if="father.first_name === 'n\/a'"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">Deceased</span>
                                                </div>
                                                <div v-else class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        father.first_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        father.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left gap-1 py-1">
                                                <span class="text-gray-500 text-base font-semibold leading-tight">Family
                                                    Housing
                                                    Type</span>
                                                <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                                    family.family_housing }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-row items-center gap-2 py-2">
                                                <font-awesome-icon :icon="['fas', 'people-roof']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div v-if="siblings.length === 0"
                                                    class="flex flex-col items-left gap-1">
                                                    <span
                                                        class="text-gray-900 text-base font-semibold leading-tight">N/A</span>
                                                </div>
                                                <div v-else v-for="sibling in siblings" :key="sibling.id"
                                                    class="flex flex-col items-left gap-1">
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        sibling.first_name }} {{ sibling.last_name }}</span>
                                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                        sibling.occupation }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="w-full flex flex-col items-left gap-1 py-1">
                                                <span class="text-gray-500 text-base font-semibold leading-tight">Other
                                                    Sources
                                                    of Income</span>
                                                <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                                    family.other_income }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Web Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <div v-if="EditProfileWeb" class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                            <!-- Upload & Preview Section -->
                            <div class="w-full sm:w-[30%] flex flex-col items-center gap-1.5">
                                <label for="dropzone-img"
                                    class="flex flex-col items-center justify-center w-80 h-80 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100"
                                    :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                    @dragover.prevent="handleImgDragOver" @dragleave="handleImgDragLeave"
                                    @drop.prevent="handleImgDrop">

                                    <!-- Show Current or New Preview -->
                                    <div v-if="!form.imgPreview"
                                        class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG (MAX. 2MB-4MB)</p>
                                    </div>

                                    <!-- Show New Uploaded Image -->
                                    <div v-else class="flex flex-col items-center justify-center">
                                        <img :src="form.imgPreview" alt="Uploaded Preview"
                                            class="max-h-56 mb-2 rounded-lg" />
                                    </div>

                                    <input id="dropzone-img" type="file" class="hidden" accept=".png, .jpg, .jpeg"
                                        @change="handleImgChange" />
                                </label>
                            </div>

                            <!-- info -->
                            <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                                <span class="text-gray-500 text-sm">Permanent Address</span>
                                <span class="text-gray-900 text-base leading-tight">
                                    <span class="text-gray-500 text-xs">Street</span>
                                    <div class="relative w-full">
                                        <input v-model="form.street" type="text" placeholder="Address"
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <!-- Icon inside input -->
                                        <font-awesome-icon :icon="['fas', 'pen']"
                                            class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                    </div>
                                    <span class="text-gray-500 text-sm">Municipality</span>
                                    <div class="relative w-full">
                                        <input v-model="form.municipality" type="text" placeholder="Address"
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <!-- Icon inside input -->
                                        <font-awesome-icon :icon="['fas', 'pen']"
                                            class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                    </div>
                                    <span class="text-gray-500 text-sm">Province</span>
                                    <div class="relative w-full">
                                        <input v-model="form.province" type="text" placeholder="Address"
                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <!-- Icon inside input -->
                                        <font-awesome-icon :icon="['fas', 'pen']"
                                            class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                    </div>
                                </span>
                                <div class="w-full flex flex-col gap-2 py-2">
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Age</span>
                                        <div class="relative w-full">
                                            <input v-model="form.age" type="text" placeholder="Age"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.age
                                        }}</span> -->
                                    </div>
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Date of Birth</span>
                                        <div class="relative max-w-sm">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                </svg>
                                            </div>
                                            <input :value="form.birthdate" id="datepicker-autohide" type="text"
                                                autocomplete="off"
                                                class="bg-white border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Select Birthdate" />

                                            <!-- <InputError v-if="errors?.birthdate" :message="errors.birthdate"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" /> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col gap-2 py-2">
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Civil Status</span>
                                        <div class="relative w-full">
                                            <Select v-model="form.civil_status">
                                                <SelectTrigger id="civil_status" class="w-full border pr-10">
                                                    <SelectValue placeholder="Select Civil Status" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem value="Single">
                                                            Single
                                                        </SelectItem>
                                                        <SelectItem value="Married">
                                                            Married
                                                        </SelectItem>
                                                        <SelectItem value="widowed">
                                                            Widowed
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>

                                            <!-- <InputError v-if="errors?.civil_status" :message="errors.civil_status"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" /> -->
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Place of Birth</span>
                                        <div class="relative w-full">
                                            <input v-model="form.birthplace" type="text" placeholder="Age"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                                <div class="w-full flex flex-col gap-2">
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Gender</span>
                                        <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                            student.gender
                                        }}</span>
                                    </div>
                                    <div class="w-full flex flex-col items-left gap-1">
                                        <span class="text-gray-500 text-sm">Religion</span>
                                        <div class="relative w-full">
                                            <input v-model="form.religion" type="text" placeholder="Age"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- gmail -->
                            <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                                <span
                                    class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                                <!-- <span class="pl-2 text-gray-900 text-base font-bold">{{ $page.props.auth.user.email
                                    }}</span> -->
                                <div class="relative w-full">
                                    <input type="text" placeholder="Email" v-model="form.email"
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <!-- Icon inside input -->
                                    <font-awesome-icon :icon="['fas', 'pen']"
                                        class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                </div>
                            </div>
                            <!-- qr -->
                            <div
                                class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                                <div v-if="scholar.qr_code" class="w-20 h-20">
                                    <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code"
                                        class="w-full h-full">
                                </div>
                                <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                    <font-awesome-icon :icon="['fas', 'qrcode']" class="text-gray-400 text-3xl" />
                                </div>
                                <button @click="openQRModal()"
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
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        scholar.course.name
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
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        scholar.campus.name
                                    }}, Campus</span>
                                </div>
                            </div>

                            <!-- education -->
                            <div
                                class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-2 gap-2 py-5 px-10">
                                <h1 class="text-base">Education</h1>

                                <div>
                                    <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                        Elementary
                                    </h3>
                                    <div class="w-full flex flex-row justify-between items-center gap-3">
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                            }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="form.education.elementary.name" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="form.education.elementary.years" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
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
                                            <input v-model="form.education.junior.name" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="form.education.junior.years" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
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
                                            <input v-model="form.education.senior.name" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="form.education.senior.years" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
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
                                            <input v-model="form.education.college.name" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="form.education.college.years" type="text"
                                                placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
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
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="vocational.years" type="text" placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
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
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                        <!-- <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                        }}</span> -->
                                        <div class="relative w-full">
                                            <input v-model="postgrad.years" type="text" placeholder="Enter User ID"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            <!-- Icon inside input -->
                                            <font-awesome-icon :icon="['fas', 'pen']"
                                                class="absolute right-3 bottom-1 text-gray-400 text-sm bg-gray-50 pl-2 py-2" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- family -->
                            <div
                                class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                                <h1 class="cols-span-2 text-base">Family</h1>
                                <div class="flex flex-col gap-4">
                                    <div 
                                        class="space-y-2">
                                        <div class="col-span-4 grid w-full items-center gap-1.5">
                                            <!-- Edit Sibling Entries -->
                                            <div v-if="form.mother.first_name === 'n/a'">
                                                <div class="w-full flex flex-row items-center mb-2">
                                                    <font-awesome-icon :icon="['fas', 'people-roof']"
                                                        class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                    <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                        <div
                                                            class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                            <span class="text-gray-700">Mother</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-for="(entry, index) in form.siblings" :key="index"
                                                    class="entry border border-gray-200 p-3 col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                    <!-- First Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label :for="'edit_first_name_' + index">First Name</Label>
                                                        <Input :id="'edit_first_name_' + index" type="text"
                                                            placeholder="First Name" v-model="entry.first_name"
                                                            class="w-full border border-gray-200" />
                                                    </div>

                                                    <!-- Last Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label :for="'edit_last_name_' + index">Last Name</Label>
                                                        <Input :id="'edit_last_name_' + index" type="text"
                                                            placeholder="Last Name" v-model="entry.last_name"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Middle Name -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label :for="'edit_middle_name_' + index">Middle Name</Label>
                                                        <Input :id="'edit_middle_name_' + index" type="text"
                                                            placeholder="Middle Name" v-model="entry.middle_name"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Age -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label :for="'edit_age_' + index">Age</Label>
                                                        <Input :id="'edit_age_' + index" type="number" placeholder="Age"
                                                            v-model="entry.age" class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- Occupation -->
                                                    <div class="grid w-full max-w-sm items-center gap-1.5">
                                                        <Label :for="'edit_occupation_' + index">Occupation</Label>
                                                        <Input :id="'edit_occupation_' + index" type="text"
                                                            placeholder="Occupation" v-model="entry.occupation"
                                                            class="w-full border-gray-200" />
                                                    </div>

                                                    <!-- NO REMOVE BUTTON -->
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit father Entries -->
                                        <div v-if="form.father.first_name === 'n/a'">
                                            <div class="w-full flex flex-row items-center mb-2">
                                                <font-awesome-icon :icon="['fas', 'people-roof']"
                                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                                <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                    <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                        <span class="text-gray-700">Father</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="entry border border-gray-200 p-3 col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                                <!-- First Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label>First Name</Label>
                                                    <Input type="text" placeholder="First Name"
                                                        v-model="form.father.first_name"
                                                        class="w-full border border-gray-200" />
                                                </div>

                                                <!-- Last Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label>Last Name</Label>
                                                    <Input type="text" placeholder="Last Name"
                                                        v-model="form.father.last_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Middle Name -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label>Middle Name</Label>
                                                    <Input type="text" placeholder="Middle Name"
                                                        v-model="form.father.middle_name"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Age -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label>Age</Label>
                                                    <Input type="number" placeholder="Age" v-model="form.father.age"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- Occupation -->
                                                <div class="grid w-full max-w-sm items-center gap-1.5">
                                                    <Label>Occupation</Label>
                                                    <Input type="text" placeholder="Occupation"
                                                        v-model="form.father.occupation"
                                                        class="w-full border-gray-200" />
                                                </div>

                                                <!-- NO REMOVE BUTTON -->
                                            </div>
                                        </div>

                                    </div>


                                    <!-- <div>
                                        <div class="w-full flex flex-row items-center gap-2 py-2">
                                            <font-awesome-icon :icon="['fas', 'people-roof']"
                                                class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                            <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                   
                                                    <span class="text-gray-700">Siblings</span>

                                                    
                                                    <div class="relative w-full">
                                                        <input v-model="father.first_name" type="text"
                                                            placeholder="Enter User ID"
                                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                                                        
                                                        <font-awesome-icon :icon="['fas', 'pen']"
                                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm" />
                                                    </div>
                                                </div>
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    
                                                    <span class="text-gray-700">Occupation</span>

                                                   
                                                    <div class="relative w-full">
                                                        <input v-model="father.occupation" type="text"
                                                            placeholder="Enter User ID"
                                                            class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                                                       
                                                        <font-awesome-icon :icon="['fas', 'pen']"
                                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div v-if="siblings > 0" class="col-span-4 grid w-full items-center gap-1.5">
                                        <!-- Edit Sibling Entries -->
                                        <div class="w-full flex flex-row items-center">
                                            <font-awesome-icon :icon="['fas', 'people-roof']"
                                                class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                            <div class="w-full flex flex-col items-left gap-1 px-2 ">
                                                <div class="relative grid grid-cols-[30%_70%] items-center w-full">
                                                    <span class="text-gray-700">Siblings</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-for="(entry, index) in form.siblings" :key="index"
                                            class="entry border border-gray-200 p-3 col-span-4 grid sm:grid-cols-1 md:grid-cols-3 w-full items-end gap-3 justify-end">

                                            <!-- First Name -->
                                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                                <Label :for="'edit_first_name_' + index">First Name</Label>
                                                <Input :id="'edit_first_name_' + index" type="text"
                                                    placeholder="First Name" v-model="entry.first_name"
                                                    class="w-full border border-gray-200" />
                                            </div>

                                            <!-- Last Name -->
                                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                                <Label :for="'edit_last_name_' + index">Last Name</Label>
                                                <Input :id="'edit_last_name_' + index" type="text"
                                                    placeholder="Last Name" v-model="entry.last_name"
                                                    class="w-full border-gray-200" />
                                            </div>

                                            <!-- Middle Name -->
                                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                                <Label :for="'edit_middle_name_' + index">Middle Name</Label>
                                                <Input :id="'edit_middle_name_' + index" type="text"
                                                    placeholder="Middle Name" v-model="entry.middle_name"
                                                    class="w-full border-gray-200" />
                                            </div>

                                            <!-- Age -->
                                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                                <Label :for="'edit_age_' + index">Age</Label>
                                                <Input :id="'edit_age_' + index" type="number" placeholder="Age"
                                                    v-model="entry.age" class="w-full border-gray-200" />
                                            </div>

                                            <!-- Occupation -->
                                            <div class="grid w-full max-w-sm items-center gap-1.5">
                                                <Label :for="'edit_occupation_' + index">Occupation</Label>
                                                <Input :id="'edit_occupation_' + index" type="text"
                                                    placeholder="Occupation" v-model="entry.occupation"
                                                    class="w-full border-gray-200" />
                                            </div>

                                            <!-- NO REMOVE BUTTON -->
                                        </div>
                                    </div>

                                    <div>
                                        <div class="w-full flex flex-col items-left ">
                                            <span class="text-gray-500 text-sm font-semibold leading-tight mb-2">Monthly
                                                Family
                                                Income</span>
                                            <div
                                                class="col-span-4 sm:col-span-4 xl:col-span-2 grid w-full items-center gap-1.5">
                                                <RadioGroup default-value="comfortable"
                                                    class="grid sm:grid-cols-1 md:grid-cols-2 gap-2"
                                                    v-model="form.monthly_income">
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem id="i1" value="10,000 and below" />
                                                        <Label for="i1">10,000 and below</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem id="i3" value="10,001 - 20,000" />
                                                        <Label for="i3">10,001 - 20,000</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem id="i2" value="20,001 - 30,000" />
                                                        <Label for="i2">20,001 - 30,000</Label>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <RadioGroupItem id="i4" value="30,001 and above" />
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
                                            <span class="text-gray-500 text-base font-semibold leading-tight">Other
                                                Sources
                                                of Income</span>
                                            <!-- Input Container (70%) -->
                                            <div class="relative w-full">
                                                <input v-model="family.other_income" type="text"
                                                    placeholder="Enter User ID"
                                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                                                <!-- Icon inside input -->
                                                <font-awesome-icon :icon="['fas', 'pen']"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- closing form ------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

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
                    <a @click="downloadQRCode" :disabled="!qrCodeUrl || loading"
                        class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 disabled:opacity-50">
                        Download
                    </a>
                </div>
            </div>
        </div>

        <div v-if="View_Grades"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300 dark:bg-primary dark:bg-opacity-50 z-50">
            <!-- Modal Container -->
            <div
                class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-lg shadow-xl overflow-hidden max-h-screen overflow-y-auto">
                <!-- Close Button (Positioned at Top Right) -->
                <button @click="closeModal"
                    class="absolute top-3 right-3 text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-full p-2 transition-all">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>

                <!-- Modal Header -->
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-4 p-4">List of Grades</h2>

                <div class="px-5 gap-2 relative w-full flex items-center mt-4 mb-2 whitespace-nowrap">
                    <h3 class="font-semibold text-[12px] text-blue-900 dark:text-white">
                        Last Available School Year and Semester
                    </h3>
                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                </div>
                <!-- Upload Section (Hidden by Default) -->
                <div class="p-5">
                    <form @submit.prevent="submitGrade">
                        <div
                            class="col-span-1 md:col-span-2 lg:col-span-3 w-full flex flex-col md:flex-row md:items-center gap-4">
                            <!-- GWA Input -->
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-2/3 flex flex-col gap-1.5">
                                <Label for="gwa">Enter General Weighted Average
                                </Label>
                                <input id="gwa" v-model="formGrade.grade" type="text"
                                    placeholder="Enter your GWA (e.g., 2.0)"
                                    class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200" />
                            </div>

                            <!-- File Upload -->
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 w-full md:w-1/3 flex flex-col gap-1.5">
                                <Label for="file_upload">Upload Certificate of Grade</Label>
                                <input id="file_upload" type="file" @change="handleFile"
                                    class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            </div>
                        </div>
                        <div class="pl-5 mt-4 justify-end flex items-end">
                            <button type="submit"
                                class="text-white font-sans w-6/12 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm py-2.5 text-center mb-2 ">
                                Add Grade</button>
                        </div>
                    </form>
                    <hr class="border-gray-300 my-4">
                </div>

                <div v-if="grades.length > 0">
                    <!-- Document Viewer -->
                    <div v-for="grade in grades" :key="grade.id"
                        class="mb-4 overflow-y-auto max-h-[70vh] p-6 space-y-10">
                        <!-- Added scrollable body with max height -->
                        <div>
                            <!-- Year and Semester -->
                            <div class="flex items-center justify-between mb-2 font-poppins">
                                <div class="text-lg font-normal text-gray-900 dark:text-white">Year: {{
                                    grade.school_year.year }}
                                </div>
                                <div class="text-lg font-normal text-gray-900 dark:text-white">Semester: {{
                                    grade.semester }}
                                </div>
                            </div>

                            <!-- Accordion for GWA and File -->
                            <details class="mb-4">
                                <summary
                                    class="text-lg font-normal text-gray-900 dark:text-white cursor-pointer flex flex-row justify-between font-poppins">
                                    <div class="text-gray-900 dark:text-white">GWA: <span class="font-semibold">{{
                                        grade.grade }}</span></div>
                                    <div class="text-gray-900 dark:text-white flex items-center">
                                        <a v-tooltip.left="'Click to download'" :href="'/storage/' + grade.path"
                                            download class="hover:text-primary transition-colors">
                                            <font-awesome-icon :icon="['fas', 'file']" class="text-primary mr-1" />{{
                                                grade.cog }}
                                        </a>
                                    </div>
                                </summary>
                            </details>
                            <!-- Divider -->
                            <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>
                        </div>
                    </div>
                </div>
                <div v-else class="p-2">
                    <!-- Empty State Message -->
                    <div
                        class="flex items-center justify-center py-12 px-8 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl text-center">
                        <div class="text-gray-500">
                            <h2 class="text-2xl font-semibold mb-4">No Grade Uploaded Yet</h2>
                            <p class="text-lg">It seems there are no grades available at the moment. Please submit
                                grades as soon as available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed, watch, nextTick } from 'vue';
import axios from 'axios';
import { Input } from '@/Components/ui/input'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';


const props = defineProps({
    student: Object,
    education: Object,
    family: Object,
    siblings: Array,
    scholar: Object,
    grades: Array,
    latestgrade: Object,
    semesterGrade: Object,
    schoolyear_grade: Object,
    notify: Object,
});

const form = ref({
    email: '',
    password: '',
    confirm_password: '',
    suffix: 'N/A',
    birthdate: '',
    birthplace: '',
    age: '',
    gender: '',
    civil_status: '',
    municipality: '',
    province: '',
    street: '',
    religion: '',
    guardian_name: '',
    relationship: '',
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
    education: {
        elementary: { name: '', years: '' },
        junior: { name: '', years: '' },
        senior: { name: '', years: '' },
        college: { name: '', years: '' },
        vocational: { name: 'N/A', years: 'N/A' },
        postgrad: { name: 'N/A', years: 'N/A' },
    },
    mother: { first_name: '', last_name: '', middle_name: '', age: '', occupation: '', isDeceased: false },
    father: { first_name: '', last_name: '', middle_name: '', age: '', occupation: '', isDeceased: false },
    marital_status: '',
    monthly_income: '',
    other_income: 'N/A',
    family_housing: '',
    otherText: '',
    siblings: [{ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }],
    organizations: [{ name: '', membership_dates: '', position: '' }],
    img: null,
    imgName: null,
    imgPreview: null,
});


const formGrade = ref({
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
});

const EditProfileMobile = ref(false);
const EditProfileWeb = ref(false);
const showUpload = ref(false);

// Web: Enable Edit Mode
const enableWebEdit = async () => {
    EditProfileWeb.value = true;
    EditProfileMobile.value = false;

    await nextTick();
    initFlowbite();
    initDatepicker();
};

// Web: Cancel Edit
const cancelWebEdit = () => {
    EditProfileWeb.value = false;
};

// Mobile: Enable Edit Mode
const enableMobileEdit = async () => {
    EditProfileMobile.value = true;
    EditProfileWeb.value = false;

    await nextTick();
    initFlowbite();
    initDatepicker();
};

// Mobile: Cancel Edit
const cancelMobileEdit = () => {
    EditProfileMobile.value = false;
};

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

const handleFile = (event) => {
    const file = event.target.files[0];
    if (file) {
        formGrade.value.cog = file;
    }
};

const submitGrade = async () => {

    try {
        if (props.scholar != null) {
            formGrade.value.semester = props.semesterGrade;
            formGrade.value.school_year = props.schoolyear_grade.id;
        }

        router.post(`/myProfile/${props.scholar.id}/upload-grade`, formGrade.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

// Re-attach the selected file when switching steps
const restoreFileInput = () => {
    const fileInput = document.getElementById("file_upload");
    if (fileInput && form.value.cog) {
        // Create a new FileList and assign it to the input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(form.value.cog);
        fileInput.files = dataTransfer.files;
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
    try {
        loading.value = true;

        // Option 1: Use direct download link
        const link = document.createElement('a');
        link.href = `/storage/qr_codes/${qrCodeUrl.value}`;
        link.download = qrCodeUrl.value;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);


    } catch (error) {
        console.error('Error downloading file:', error);
        alert('Failed to download file. Please try again.');
    } finally {
        loading.value = false;
    }
};

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

const submit = async () => {
    try {

        router.post(`myProfile/update`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
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

const initDatepicker = () => {
    const datepickerEl = document.getElementById("datepicker-autohide");

    if (datepickerEl) {
        if (!datepickerEl.dataset.initialized) {
            const datepicker = new window.Datepicker(datepickerEl, {
                autohide: true,
                format: "yyyy-mm-dd",
            });

            datepickerEl.dataset.initialized = "true";

            // Store selected date when user types or selects a date
            datepickerEl.addEventListener("input", () => {
                form.value.birthdate = datepickerEl.value;
            });

            datepickerEl.addEventListener("blur", () => {
                form.value.birthdate = datepickerEl.value;
            });
        }

        // 🔥 Restore value manually when switching steps
        if (form.value.birthdate) {
            datepickerEl.value = form.value.birthdate;
        }
    }
};


// Initialize QR code URL on component mount
onMounted(() => {
    initFlowbite();
    initDatepicker();
    document.body.style.overflow = "hidden"; // Disable scrolling

    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    }

    // Also check if there's a flash message with a QR code URL
    if (props.flash?.qrCodeUrl) {
        qrCodeUrl.value = props.flash.qrCodeUrl;
    }
});

onUnmounted(() => {
    document.body.style.overflow = ""; // Reset when the component is unmounted
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
