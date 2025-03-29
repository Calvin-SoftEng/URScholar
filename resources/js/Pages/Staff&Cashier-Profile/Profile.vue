<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
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
                    <button @click="toggleEdit">{{ isEditing ? "Save" : "Edit Profile" }}</button>
                </div>
                <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2">
                    <div v-if="!isEditing" class="mx-auto w-7/12 sm:px-6 lg:px-8">
                        <div class="relative w-full">
                            <!-- Background Image -->
                            <img
                            src="../../../assets/images/profile_bg.jpg"
                            alt="Profile Background"
                            class="w-full h-48 object-cover rounded-t-lg"
                            />

                            <!-- Profile Section -->
                            <div class="absolute left-1/2 -bottom-14 transform -translate-x-1/2 w-[95%] bg-white/50 backdrop-blur-md border border-white/30 shadow-sm rounded-xl px-5 py-5">
                                <div class="gap-4 flex flex-row items-center">
                                    <!-- Profile Picture -->
                                    <img
                                    src=""
                                    alt="Profile Picture"
                                    class="w-20 h-20 rounded-xl border-2 border-white shadow-sm"
                                    />

                                    <!-- Name -->
                                    <div class="flex flex-col">
                                        <span class="text-lg font-semibold text-primary drop-shadow-md">{{$page.props.auth.user.last_name}}, {{$page.props.auth.user.first_name}}</span>
                                        <span class="text-base font-medium text-primary opacity-50">{{$page.props.auth.user.usertype}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full flex justify-center mt-20 flex-col">

                            <!-- Student ID -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">User ID</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- First Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">First Name</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Middle Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Middle Name</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Last Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Last Name</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Campus -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Campus</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Age -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Age</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Role</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Address</label>
                                <span class="my-3 flex">User ID</span>
                            </div>

                        </div>
                    </div>

                        <!-- Edit Profile Section -->
                        <div v-if="isEditing" class="mx-auto w-7/12 sm:px-6 lg:px-8">
                        <div class="relative w-full">
                            <img
                            src="../../../assets/images/profile_bg.jpg"
                            alt="Profile Background"
                            class="w-full h-48 object-cover rounded-t-lg"
                            />

                            <div class="absolute left-1/2 -bottom-14 transform -translate-x-1/2 w-[95%] bg-white/50 backdrop-blur-md border border-white/30 shadow-sm rounded-xl px-5 py-5">
                                <div class="gap-4 flex flex-row items-center">
                                    <div class="relative w-20 h-20">
                                        <img src="" alt="Profile Picture" class="w-full h-full rounded-xl border-2 border-white shadow-sm object-cover" />
                                        
                                        <!-- Upload Icon at Bottom-Right -->
                                        <label for="profile-pic" class="absolute -bottom-1 -right-1 rounded-full shadow cursor-pointer">
                                            <span class="material-symbols-rounded text-primary text-lg bg-white px-1.5 py-0.5 rounded-full">
                                                add_photo_alternate
                                            </span>
                                        </label>
                                        
                                        <input type="file" id="profile-pic" class="hidden">
                                    </div>

                                    <div class="flex flex-col">
                                        <span contenteditable="true" 
                                            class="text-lg font-semibold text-primary drop-shadow-sm border-b-2 border-gray-300 focus:border-gray-400 outline-none">
                                            Name
                                        </span>

                                        <span contenteditable="true" class="text-base font-medium text-primary opacity-50 border-b border-transparent focus:border-gray-400 outline-none">
                                            Coordinator
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full flex justify-center mt-20 flex-col">

                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">User ID</label>
                                <input type="text" placeholder="Enter User ID" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- First Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">First Name</label>
                                <input type="text" placeholder="Enter First Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Middle Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Middle Name</label>
                                <input type="text" placeholder="Enter Middle Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Last Name -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Last Name</label>
                                <input type="text" placeholder="Enter Last Name" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Campus -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Campus</label>
                                <select class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Select Campus</option>
                                    <option>Campus A</option>
                                    <option>Campus B</option>
                                    <option>Campus C</option>
                                </select>
                            </div>

                            <!-- Age -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Age</label>
                                <input type="number" placeholder="Enter Age" class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Role</label>
                                <select class="w-full h-[43px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Select Role</option>
                                    <option>Student</option>
                                    <option>Faculty</option>
                                    <option>Admin</option>
                                </select>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-primary font-medium mb-1">Address</label>
                                <textarea placeholder="Enter Address" class="w-full h-[80px] bg-gray-50 border border-gray-300 rounded-md px-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <!-- Save & Cancel Buttons -->
                            <div class="flex justify-between">
                            <button
                                @click="toggleEdit"
                                class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                            >
                                Save
                            </button>
                            <button
                                @click="toggleEdit"
                                class="mt-4 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition"
                            >
                                Cancel
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ToastProvider>
            <ToastRoot 
                v-if="toastVisible" 
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full"
            >
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
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue'

import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue,} from '@/Components/ui/select'

import Profile from '@/Components/Profile/Profile.vue';
import EditProfile from '@/Components/Profile/EditProfile.vue';

// components

const components = {
    Button,
};

const isEditing = ref(false);

const toggleEdit = () => {
    isEditing.value = !isEditing.value;
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