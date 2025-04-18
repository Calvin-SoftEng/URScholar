<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="justify-between flex flex-row items-start">
                    <div class="breadcrumbs text-sm text-gray-400">
                        <ul>
                            <li class="hover:text-gray-600">
                                Home
                            </li>
                            <li>
                                <span class="text-blue-400 font-semibold dark:text-gray-300">My Profile</span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2">
                    <div class="mx-auto w-7/12 sm:px-6 lg:px-8">
                        <form @submit.prevent="">
                            <div class="relative w-full">
                                <!-- Background Image -->
                                <img src="../../../assets/images/profile_bg.jpg" alt="Profile Background"
                                    class="w-full h-48 object-cover rounded-t-lg" />

                                <!-- Profile Section -->
                                <!-- Profile Section Update -->
                                <div
                                    class="absolute left-1/2 -bottom-14 transform -translate-x-1/2 w-[95%] bg-white/50 backdrop-blur-md border border-white/30 shadow-sm rounded-xl px-5 py-5">
                                    <div class="flex items-center gap-4 relative">
                                        <!-- Profile Picture Wrapper -->
                                        <div class="relative w-20 h-20">
                                            <img :src="form.filePreview || '../../../assets/images/no_userpic.png'"
                                                alt="Profile Picture"
                                                class="w-full h-full object-cover rounded-xl border-2 border-white shadow-sm" />

                                            <!-- Upload Icon positioned inside profile image -->
                                            <button @click="toggleChangeDP" for="profile-pic"
                                                class="absolute bottom-0 right-[-5px] bg-white rounded-full px-1 shadow-md cursor-pointer hover:bg-gray-100 transition"
                                                title="Change Profile Picture">
                                                <span class="material-symbols-rounded text-primary text-base">
                                                    add_photo_alternate
                                                </span>
                                            </button>
                                        </div>

                                        <!-- Name and Role -->
                                        <div class="flex flex-col">
                                            <span class="text-lg font-semibold text-primary drop-shadow-md">
                                                {{ $page.props.auth.user.last_name || '' }}, {{
                                                    $page.props.auth.user.first_name || '' }}
                                                {{ $page.props.auth.user.suffix_name ? $page.props.auth.user.suffix_name
                                                    : '' }}
                                            </span>
                                            <span class="text-base font-medium text-primary opacity-50">
                                                {{ getUserRole() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div
                                class="relative w-full mx-auto mt-20 p-6 border border-gray-100 rounded-lg shadow-sm bg-white dark:bg-gray-800">

                                <!-- Header -->
                                <div class="flex gap-2 justify-end">
                                    <button
                                        class="text-white bg-blue-600 hover:bg-blue-800 rounded-md px-5 py-2 font-medium text-sm"
                                        @click="toggleEdit">
                                        {{ isEditing ? 'Save' : 'Edit Profile' }}
                                    </button>

                                    <button v-if="isEditing"
                                        class="text-white bg-gray-400 hover:bg-gray-600 rounded-md px-5 py-2 font-medium text-sm"
                                        @click="cancelEdit">
                                        Cancel
                                    </button>
                                </div>

                                <!-- DISPLAY -->
                                <div v-if="!isEditing" class="flex flex-row w-full">
                                    <div class="w-1/2">
                                        <!-- First Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">First
                                                Name</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.first_name }}</span>
                                        </div>

                                        <!-- Middle Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle
                                                Name</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.middle_name }}</span>
                                        </div>

                                        <!-- Last Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last
                                                Name</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.last_name }}</span>
                                        </div>
                                    </div>

                                    <div class="w-1/2">
                                        <!-- First Name -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.age }}</span>
                                        </div>

                                        <!-- Middle Name -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Address</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.address }}</span>
                                        </div>

                                        <!-- Last Name -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Contact</label>
                                            <span class="text-lg font-semibold text-gray-800 dark:text-white">{{
                                                user.contact }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Form Section Update -->
                                <div v-if="isEditing" class="flex flex-row w-full gap-5">
                                    <div class="w-1/2">
                                        <!-- First Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">First
                                                Name</label>
                                            <input type="text" v-model="form.first_name"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>

                                        <!-- Middle Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Middle
                                                Name</label>
                                            <input type="text" v-model="form.middle_name"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>

                                        <!-- Last Name -->
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-primary opacity-45 mb-1">Last
                                                Name</label>
                                            <input type="text" v-model="form.last_name"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>

                                        <!-- Suffix Name -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Suffix</label>
                                            <input type="text" v-model="form.suffix_name"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>
                                    </div>

                                    <div class="w-1/2">
                                        <!-- Age -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Age</label>
                                            <input type="text" v-model="form.age"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>

                                        <!-- Address -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Address</label>
                                            <input type="text" v-model="form.address"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>

                                        <!-- Contact -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-primary opacity-45 mb-1">Contact</label>
                                            <input type="text" v-model="form.contact"
                                                class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div
                            class="relative w-full mx-auto mt-5 p-6 border border-gray-100 rounded-lg shadow-sm bg-white dark:bg-gray-800">

                            <!-- Header -->
                            <h1 class="text-xl font-semibold text-primary mb-6">Account Information</h1>

                            <!-- Grid Layout -->
                            <div class="grid grid-cols-2 gap-5">

                                <!-- Email Section -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-primary opacity-60 mb-1">Email</label>
                                    <input type="email" placeholder="Enter your email" v-model="form.email"
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <!-- Email Action -->
                                <div class="flex items-end justify-start max-w-2xs">
                                    <button @click="toggleChangeEmail"
                                        class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Change Email
                                    </button>
                                </div>

                                <!-- Password Section -->
                                <div class="space-y-2">
                                    <label
                                        class="block text-sm font-medium text-primary opacity-60 mb-1">Password</label>
                                    <input type="password" placeholder="••••••••"
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                                </div>

                                <!-- Password Action -->
                                <div class="flex items-end justify-start max-w-2xs">
                                    <button @click="toggleChangePassword"
                                        class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Change Password
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Change Modal -->
        <div v-if="changeEmail"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'envelope']" class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Change Email
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ emailStep === 1 ? 'Verify your current email first' : 'Enter new email and verification code' }}
                            </span>
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

                <!-- Step 1: Verify Current Email -->
                <form v-if="emailStep === 1" @submit.prevent="sendOldEmailVerification" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="current_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Current Email
                        </label>
                        <input type="email" id="current_email" v-model="form.email" disabled
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <div v-if="oldEmailCodeSent">
                        <label for="verification_code"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Verification Code
                        </label>
                        <input type="text" id="verification_code" v-model="form.oldEmailCode"
                            placeholder="Enter the code sent to your email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ oldEmailCodeSent ? 'Verify Code' : 'Send Verification Code' }}
                        </button>
                    </div>
                </form>

                <!-- Step 2: Enter New Email -->
                <form v-if="emailStep === 2" @submit.prevent="sendNewEmailVerification" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="new_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            New Email
                        </label>
                        <input type="email" id="new_email" v-model="form.newEmail"
                            placeholder="Enter your new email address"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div v-if="newEmailCodeSent">
                        <label for="new_verification_code"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Verification Code
                        </label>
                        <input type="text" id="new_verification_code" v-model="form.newEmailCode"
                            placeholder="Enter the code sent to your new email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ newEmailCodeSent ? 'Complete Change' : 'Send Verification Code' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Change Modal -->
        <div v-if="changePassword"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'lock']" class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Update Password
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ passwordStep === 1 ? 'Verify your current password' : 'Enter your new password' }}
                            </span>
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

                <!-- Step 1: Verify Current Password -->
                <form v-if="passwordStep === 1" @submit.prevent="verifyCurrentPassword" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="current_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Current Password
                        </label>
                        <input type="password" id="current_password" v-model="form.currentPassword"
                            placeholder="Enter your current password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div v-if="passwordCodeSent">
                        <label for="password_verification_code"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Verification Code
                        </label>
                        <input type="text" id="password_verification_code" v-model="form.passwordVerificationCode"
                            placeholder="Enter the code sent to your email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            {{ passwordCodeSent ? 'Verify Code' : 'Send Verification Code' }}
                        </button>
                    </div>
                </form>

                <!-- Step 2: Enter New Password -->
                <form v-if="passwordStep === 2" @submit.prevent="updatePassword" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            New Password
                        </label>
                        <input type="password" id="new_password" v-model="form.newPassword"
                            placeholder="Enter your new password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div>
                        <label for="confirm_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Confirm Password
                        </label>
                        <input type="password" id="confirm_password" v-model="form.confirmPassword"
                            placeholder="Confirm your new password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- adding course -->
        <div v-if="changeDP"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300 ">
            <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-3/12">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <div class="flex items-center gap-3">
                        <!-- Icon -->
                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                            class="text-blue-600 text-2xl flex-shrink-0" />

                        <!-- Title and Description -->
                        <div class="flex flex-col">
                            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                                Upload New Profile Picture
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <label for="dropzone-image"
                        class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                        :class="{ 'border-blue-500 bg-blue-50': isImgDragging }" @dragover.prevent="handleImgDragOver"
                        @dragleave="handleImgDragLeave" @drop.prevent="handleImgDrop">
                        <div v-if="!form.file" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG (Max 2MB-4MB)</p>
                        </div>

                        <!-- Preview Image (Optional) -->
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <img :src="form.filePreview" alt="Preview" class="max-h-full object-contain rounded-lg" />
                        </div>

                        <input id="dropzone-image" type="file" class="hidden" accept=".jpg, .jpeg, .png, .svg"
                            @change="previewImg" />
                    </label>

                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Save
                        </button>
                    </div>
                </form>

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
import { ref, watchEffect, onMounted } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'

const props = defineProps({
    user: Object,
});

// Get the authenticated user from page props
const user = usePage().props.auth.user;

// Initialize form with actual user data
const form = useForm({
    first_name: props.user.first_name || '',
    middle_name: props.user.middle_name || '',
    last_name: props.user.last_name || '',
    suffix_name: props.user.suffix_name || '',
    contact: props.user.contact || '',
    address: props.user.address || '',
    age: props.user.age || '',
    email: props.user.email || '',
    currentPassword: '',
    passwordVerificationCode: '',
    newPassword: '',
    confirmPassword: '',
    file: null,
    filePreview: props.user.picture ? `/storage/${user.picture}` : null,

    // New fields for email verification
    oldEmailCode: '',
    newEmail: '',
    newEmailCode: '',
});

// Modal states
const changeEmail = ref(false);
const changePassword = ref(false);
const changeDP = ref(false);
const isEditing = ref(false);
const isImgDragging = ref(false);

function cancelEdit() {
    isEditing.value = false
    // Optional: revert form fields here
    console.log("Canceled editing.")
}
// Toast notification
const toastVisible = ref(false);
const toastMessage = ref("");

// Modal toggle functions
const toggleChangeEmail = () => {
    changeEmail.value = !changeEmail.value;
};

const toggleChangePassword = () => {
    changePassword.value = !changePassword.value;
};

const toggleChangeDP = () => {
    changeDP.value = !changeDP.value;
};

// const closeModal = () => {
//     changeEmail.value = false;
//     changePassword.value = false;
//     changeDP.value = false;
// };

const toggleEdit = () => {
    isEditing.value = !isEditing.value;

    // Submit form if we're saving changes
    if (!isEditing.value) {
        submitProfileChanges();
    }
};

// // Submit profile changes
// const submitProfileChanges = () => {
//     form.post(route('profile.update'), {
//         onSuccess: () => {
//             showToast('Profile updated successfully!');
//         },
//     });
// };

// Email change
const submitEmailChange = () => {
    form.post(route('profile.update.email'), {
        onSuccess: () => {
            closeModal();
            showToast('Email update request sent! Check your new email for verification.');
        },
    });
};

// Password change
const submitPasswordChange = () => {
    if (form.newpassword !== form.confirmpassword) {
        showToast('Passwords do not match!', 'error');
        return;
    }

    form.post(route('profile.update.password'), {
        onSuccess: () => {
            closeModal();
            form.newpassword = '';
            form.confirmpassword = '';
            form.password = '';
            showToast('Password updated successfully!');
        },
    });
};

// // Profile picture change
// const submitProfilePicture = () => {
//     if (!form.file) {
//         closeModal();
//         return;
//     }

//     form.post(route('profile.update.picture'), {
//         onSuccess: () => {
//             closeModal();
//             showToast('Profile picture updated successfully!');
//         },
//     });
// };

// Show toast message
const showToast = (message, type = 'success') => {
    toastMessage.value = message;
    toastVisible.value = true;

    setTimeout(() => {
        toastVisible.value = false;
    }, 3000);
};

// Image handling functions
const previewImg = (event) => {
    const img = event.target.files[0];
    handleImg(img);
};

const handleImgDragOver = () => {
    isImgDragging.value = true;
};

const handleImgDragLeave = () => {
    isImgDragging.value = false;
};

const handleImgDrop = (event) => {
    isImgDragging.value = false;
    const img = event.dataTransfer.files[0];
    handleImg(img);
};

const handleImg = (img) => {
    if (img && img.type.startsWith('image/')) {
        form.file = img;

        const reader = new FileReader();
        reader.onload = (e) => {
            form.filePreview = e.target.result;
        };
        reader.readAsDataURL(img);
    }
};

// Submit profile changes - make this function work correctly
const submitProfileChanges = () => {
    form.post(route('profile.update'), {
        onSuccess: () => {
            showToast('Profile updated successfully!');
        },
        onError: (errors) => {
            // Display errors to the user
            const errorMessage = Object.values(errors).join('\n');
            showToast(errorMessage, 'error');
        }
    });
};

// Profile picture change - fix this function
const submitProfilePicture = () => {
    if (!form.file) {
        closeModal();
        return;
    }

    form.post(route('profile.update.picture'), {
        onSuccess: () => {
            closeModal();
            showToast('Profile picture updated successfully!');
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors).join('\n');
            showToast(errorMessage, 'error');
        }
    });
};

// Create a new submit form function to be used by the profile picture modal
const submitForm = () => {
    submitProfilePicture();
};


// Add these to your existing script section
const emailStep = ref(1); // Step 1: Verify old email, Step 2: Set new email
const oldEmailCodeSent = ref(false);
const newEmailCodeSent = ref(false);


// Step 1: Send verification code to old email
const sendOldEmailVerification = () => {
    if (oldEmailCodeSent.value) {
        // Verify the code
        axios.post(route('profile.verify.old.email'), {
            verification_code: form.oldEmailCode
        })
            .then(response => {
                if (response.data.success) {
                    // Move to step 2
                    emailStep.value = 2;
                    showToast('Current email verified successfully!');
                } else {
                    showToast('Invalid verification code. Please try again.', 'error');
                }
            })
            .catch(error => {
                showToast('Error verifying code: ' + error.response.data.message, 'error');
            });
    } else {
        // Send verification code to current email
        axios.post(route('profile.send.old.email.code'))
            .then(response => {
                oldEmailCodeSent.value = true;
                showToast('Verification code sent to your current email!');
            })
            .catch(error => {
                showToast('Error sending verification code: ' + error.response.data.message, 'error');
            });
    }
};

// Step 2: Send verification to new email and complete change
const sendNewEmailVerification = () => {
    if (newEmailCodeSent.value) {
        // Complete the email change
        axios.post(route('profile.update.email'), {
            new_email: form.newEmail,
            verification_code: form.newEmailCode
        })
            .then(response => {
                closeModal();
                resetEmailVerification();
                form.email = form.newEmail; // Update the email in the form
                showToast('Email updated successfully!');
            })
            .catch(error => {
                showToast('Error updating email: ' + error.response.data.message, 'error');
            });
    } else {
        // Send verification code to new email
        axios.post(route('profile.send.new.email.code'), {
            new_email: form.newEmail
        })
            .then(response => {
                newEmailCodeSent.value = true;
                showToast('Verification code sent to your new email!');
            })
            .catch(error => {
                showToast('Error sending verification code: ' + error.response.data.message, 'error');
            });
    }
};


// For password change flow
const passwordStep = ref(1); // Step 1: Verify current password, Step 2: Enter new password
const passwordCodeSent = ref(false);

// Step 1: Verify current password and send verification code
const verifyCurrentPassword = () => {
    if (passwordCodeSent.value) {
        // Verify the code
        axios.post(route('profile.verify.password.code'), {
            verification_code: form.passwordVerificationCode
        })
            .then(response => {
                if (response.data.success) {
                    // Move to step 2
                    passwordStep.value = 2;
                    showToast('Verification successful! Now enter your new password.');
                } else {
                    showToast('Invalid verification code. Please try again.', 'error');
                }
            })
            .catch(error => {
                showToast('Error verifying code: ' + (error.response?.data?.message || 'Please try again'), 'error');
            });
    } else {
        // First verify the current password and send verification code
        axios.post(route('profile.send.password.code'), {
            current_password: form.currentPassword
        })
            .then(response => {
                passwordCodeSent.value = true;
                showToast('Password verification code sent to your email!');
            })
            .catch(error => {
                showToast('Error: ' + (error.response?.data?.message || 'Incorrect password'), 'error');
                form.currentPassword = ''; // Clear the password field for retry
            });
    }
};

// Step 2: Update the password
const updatePassword = () => {
    if (form.newPassword !== form.confirmPassword) {
        showToast('Passwords do not match!', 'error');
        return;
    }

    if (form.newPassword.length < 8) {
        showToast('Password must be at least 8 characters long!', 'error');
        return;
    }

    axios.post(route('profile.update.password'), {
        new_password: form.newPassword,
        password_confirmation: form.confirmPassword
    })
        .then(response => {
            closeModal();
            resetPasswordVerification();
            showToast('Password updated successfully!');
        })
        .catch(error => {
            showToast('Error updating password: ' + (error.response?.data?.message || 'Please try again'), 'error');
        });
};


// Reset the email verification flow
const resetEmailVerification = () => {
    emailStep.value = 1;
    oldEmailCodeSent.value = false;
    newEmailCodeSent.value = false;
    form.oldEmailCode = '';
    form.newEmail = '';
    form.newEmailCode = '';
};

// Reset the password verification flow
const resetPasswordVerification = () => {
    passwordStep.value = 1;
    passwordCodeSent.value = false;
    form.currentPassword = '';
    form.passwordVerificationCode = '';
    form.newPassword = '';
    form.confirmPassword = '';
};

// Update the closeModal function to reset password verification
const closeModal = () => {
    changeEmail.value = false;
    changePassword.value = false;
    changeDP.value = false;
    resetEmailVerification();
    resetPasswordVerification();
};

// Watch for flash messages
watchEffect(() => {
    const flashMessage = usePage().props.flash?.success;

    if (flashMessage) {
        toastMessage.value = flashMessage;
        toastVisible.value = true;

        setTimeout(() => {
            toastVisible.value = false;
        }, 3000);
    }
});

// Formatted user role
const getUserRole = () => {
    const roles = {
        'super_admin': 'Head Admin',
        'coordinator': 'Scholarship Coordinator',
        'cashier': 'Cashier',
        'head_cashier': 'Head Cashier',
        'student': 'Student',
        'system_admin': 'System Admin',
        'sponsor': 'Sponsor'
    };

    return roles[user.usertype] || user.usertype;
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
</style>