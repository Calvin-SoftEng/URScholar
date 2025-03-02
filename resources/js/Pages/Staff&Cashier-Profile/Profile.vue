<template>
    <AuthenticatedLayout>
        <div class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="w-full bg-dirtywhite shadow-sm justify-between flex flex-row px-10">
                    <h1 class="text-3xl font-bold font-sora text-left p-3">My Profile</h1>
                    <button class="text-sm font-semibold text-black">Edit Profile</button>
                </div>
                <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2">
                    <div class="mx-auto w-7/12 sm:px-6 lg:px-8 ">
                        <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                                <!-- pic -->
                                <div class="border w-80 h-80 rounded-lg">
                                    <img src="" alt="">
                                </div>
                                <!-- info -->
                                <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                                    <span class="text-gray-500 text-sm">Permanent Address</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight"></span>
                                    <div class="w-full flex flex-row gap-2 py-2">
                                        <div class="w-[40%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Age</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                                feafeafaefe
                                            </span>
                                        </div>
                                        <div class="w-[60%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Date of Birth</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                                fefaefaef
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-row gap-2 py-2">
                                        <div class="w-[40%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Civil Status</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                            feafaefef
                                            </span>
                                        </div>
                                        <div class="w-[60%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Place of Birth</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                                fefefefa
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                                    <div class="w-full flex flex-row gap-2">
                                        <div class="w-[40%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Gender</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                                faefaefea
                                            </span>
                                        </div>
                                        <div class="w-[60%] flex flex-col items-left gap-1">
                                            <span class="text-gray-500 text-sm">Religion</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">
                                                faefaefe
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- gmail -->
                                <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                                    <span class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                                    <span class="pl-2 text-gray-900 text-base font-bold">
                                    fefafefa
                                    </span>
                                </div>
                            </div>
                            <div class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                                <div class="w-full h-1/12">
                                    <span class="font-italic font-sora text-3xl font-bold uppercase">Pangalan</span>
                                </div>

                                <div
                                    class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">
                                            feafaefefaef
                                        </span>
                                    </div>
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'id-card-clip']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">
                                            feafaefefae
                                        </span>
                                    </div>
                                    <div class="w-full flex flex-row items-center gap-2">
                                        <font-awesome-icon :icon="['fas', 'school']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <span class="text-gray-900 text-base font-semibold leading-tight">Lagay mo dito</span>
                                    </div>
                                </div>

                                <!-- education -->
                                <div
                                    class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                                    <h1 class="text-base">Education</h1>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Elementary
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefef
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefaefa
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Junior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefaefaef
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                \feaffeafaef
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Senior High School
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                fefaefefaef
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefafaef
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            College
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                faefaefaefe
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                fefaefaefaf
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Vocational
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafafefaefe
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                fefaefaefa
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                            Post Graduate
                                        </h3>
                                        <div class="w-full flex flex-row justify-between items-center space-y-3">
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefeafeaf
                                            </span>
                                            <span class="text-gray-700 text-base font-medium leading-tight">
                                                feafaefaefaef
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

// components

const components = {
    Button,
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