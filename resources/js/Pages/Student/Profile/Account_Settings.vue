<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <form @submit.prevent="submit" class="w-full h-full overflow-hidden">
            <div class="w-full bg-dirtywhite shadow-sm justify-between flex flex-row">
                <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3 md:mx-20">
                    My Account
                </h1>
            </div>
            <div
                class="pt-3 sm:pb-5 lg:pb-24 overflow-auto h-full scroll-py-2 bg-gradient-to-b from-[#E9F4FF] via-white to-white">
                <div class="mx-auto sm:w-11/12 lg:w-7/12 sm:px-1 lg:px-8 ">

                    <!-- Mobile Display------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    
                    

                        <!-- Mobile Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
                    <!-- Web Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <div class="hidden md:block">
                        <!-- Content for Web -->
                        <div  class="mx-auto max-w-xl h-full bg-white p-5 justify-center items-center flex flex-col gap-3">
                            <div class="w-full h-full col-span-1 space-y-4 flex flex-col items-center">

                                <div class="relative w-full mx-auto mt-5 p-6 border border-gray-100 rounded-lg shadow-sm bg-white dark:bg-gray-800">

                                    <!-- Grid Layout -->
                                    <div class="grid grid-cols-2 gap-5">

                                    <!-- Email Section -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-primary opacity-60 mb-1">Email</label>
                                        <input
                                        type="email"
                                        placeholder="Enter your email" v-model="form.email"
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        />
                                    </div>

                                    <!-- Email Action -->
                                    <div class="flex items-end justify-start max-w-2xs">
                                        <button @click="toggleChangeEmail" class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Change Email
                                        </button>
                                    </div>

                                    <!-- Password Section -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-primary opacity-60 mb-1">Password</label>
                                        <input
                                        type="password"
                                        placeholder="••••••••"
                                        class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        />
                                    </div>

                                    <!-- Password Action -->
                                    <div class="flex items-end justify-start max-w-2xs">
                                        <button @click="toggleChangePassword" class="w-full px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Change Password
                                        </button>
                                    </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </form>


        <!-- adding course -->
        <div v-if="changeEmail"
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
                                Change Email
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Enter new email and submit the code
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="course"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Email</label>
                        <input type="text" id="last_name" v-model="form.email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div>
                        <label for="abbreviation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Email</label>
                        <input type="text" id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div>
                        <label for="abbreviation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Code</label>
                        <input type="text" id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- adding course -->
        <div v-if="changePassword"
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
                                Update Password
                            </h2>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Enter current password and a new password
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

                <form @submit.prevent="submitForm" class="p-4 flex flex-col gap-3">
                    <div>
                        <label for="course"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Password</label>
                        <input type="text" id="last_name" v-model="form.password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div>
                        <label for="abbreviation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New Password</label>
                        <input type="text" id="last_name" v-model="form.newpassword"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div>
                        <label for="abbreviation"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="text" id="last_name" v-model="form.confirmpassword"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required />
                    </div>
                    <div class="mt-2">
                        <button type="submit"
                            class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                            Save</button>
                    </div>
                </form>
            </div>
        </div>



    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed, watch, nextTick } from 'vue';
import axios from 'axios';
import { Input } from '@/Components/ui/input'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';


const props = defineProps({
    student: Object,
    // education: Object,
    // family: Object,
    // siblings: Array,
    scholar: Object,
    // grades: Array,
    // latestgrade: Object,
    // semesterGrade: Object,
    // schoolyear_grade: Object,
    // notify: Object,
});

const form = useForm({
    email: "headadmin@gmail.com",
    password: "password",
})

const changeEmail = ref(false);
const changePassword = ref(false);
const changeDP = ref(false);

const toggleChangeEmail = () => {
    changeEmail.value = !changeEmail.value;
}

const toggleChangePassword = () => {
    changePassword.value = !changePassword.value;
}

const closeModal = () => {
    changeEmail.value = false;
    changePassword.value = false;
    changeDP.value = false;
}

const qrCodeUrl = ref(null);
const loading = ref(false);
const error = ref(null);

// Watch for qrCodeUrl in page props (for when returning from generation)
watch(() => props.flash?.qrCodeUrl, (newUrl) => {
    if (newUrl) {
        qrCodeUrl.value = newUrl;
    }
});


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


// Initialize QR code URL on component mount
onMounted(() => {
    initFlowbite();

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

</script>
