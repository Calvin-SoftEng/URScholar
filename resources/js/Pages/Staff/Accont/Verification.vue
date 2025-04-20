<template>

    <Head title="Verification" />
    <div class="w-full h-screen box-border bg-gray-100">

        <!-- Reminder Dialog -->
        <div v-if="showDialog" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
            <div class="bg-primary p-6 rounded-lg shadow-xl max-w-md w-full animate-fade-in">

                <!-- Branding inside dialog -->
                <div class="w-full flex items-center justify-center gap-2 mb-4">
                    <img src="../../../../assets/images/main_logo_white.png" alt="URScholar Logo"
                        class="w-12 h-12 dark:hidden">
                    <span class="font-poppins text-3xl font-bold text-white tracking-tight">URScholar</span>
                </div>

                <h2 class="text-lg font-semibold text-white mb-3">Student Reminders</h2>
                <ul class="text-white list-disc list-inside space-y-3">
                    <li v-for="(reminder, index) in reminders" :key="index">{{ reminder }}</li>
                </ul>

                <div class="mt-5 flex flex-col gap-2">
                    <button @click="closeDialog"
                        class="w-full bg-white text-primary font-semibold py-2 rounded-lg hover:bg-gray-200 transition">
                        Got it!
                    </button>
                    <button @click="dontShowAgain" class="w-full text-white text-sm underline">
                        Don't show again
                    </button>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="w-full flex flex-row justify-between bg-white shadow-sm items-center px-10 sm:px-5">
                <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3">Set up your Profile
                </h1>
                <div class="flex flex-row gap-2">
                    <Link :href="route('logout')" method="post" as="button">
                    <button class="bg-gray-400 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded 
                    sm:py-1 sm:px-3 sm:text-xs">
                        Exit
                    </button>
                    </Link>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded 
                    sm:text-xs sm:py-1
                    xl:px-5">
                        Set Up
                    </button>
                </div>
            </div>
            <div class="py-3 h-full box-border bg-gray-100">
                <div class="mx-auto h-full max-w-5xl sm:px-3 lg:px-8 ">
                    <div class="flex flex-col space-y-5">
                        <div class="bg-primary font-sans font-bold rounded-lg p-7 
                                    2xl:text-3xl 2xl:text-white
                                    xl:text-3xl xl:text-red-500
                                    lg:p-6 lg:text-2xl lg:text-green-500
                                    md:p-5 md:text-2xl md:text-blue-500
                                    sm:p-4 sm:text-base sm:text-yellow-500">
                            Greetings! {{ user.first_name }} {{ user.last_name }}!<br>
                        </div>
                    </div>
                </div>

                <div class="box-border h-full mx-auto max-w-7xl sm:px-3 lg:px-8 overflow-auto">
                    <div class="h-full flex flex-col space-y-5 mt-5 xl:mt-10">

                        <div class="stepper-container max-w-full">
                            <!-- Step Content -->
                            <div class="flex-grow">
                                <div class="bg-white border-box overflow-x-auto grid grid-cols-5 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-6 xl:grid-cols-6
                                    gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                    <div class="sm:col-span-6 md:col-span-2 lg:col-span-6 xl:col-span-6">
                                        <h3
                                            class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                            Account Setup</h3>
                                        <p
                                            class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                            Please input, change and update your account before proceeding</p>
                                    </div>

                                    <div class="col-span-2 flex flex-col sm:flex-row px-4 sm:px-24 gap-6">

                                        <!-- Image Upload Column -->
                                        <div class="w-full sm:w-[30%] flex flex-col items-center gap-1.5">
                                            <Label for="pic">Insert Profile Picture</Label>
                                            <InputError v-if="errors?.img" :message="errors.img"
                                                class="items-center flex text-xs" />
                                            <label for="dropzone-img"
                                                class="flex flex-col items-center justify-center w-64 h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                @dragover.prevent="handleImgDragOver" @dragleave="handleImgDragLeave"
                                                @drop.prevent="handleImgDrop">
                                                <div v-if="!form.img"
                                                    class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 20 16">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-semibold">Click to upload</span> or
                                                        drag and drop
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG
                                                        (MAX. 800x400px - 2MB-4MB)</p>
                                                </div>
                                                <div v-else class="flex flex-col items-center justify-center">
                                                    <img :src="form.imgPreview" alt="Uploaded Preview"
                                                        class="max-h-56 mb-2 rounded-lg" />
                                                </div>
                                                <input id="dropzone-img" type="file" class="hidden"
                                                    accept=".svg, .png, .jpg, .jpeg"
                                                    @change="(e) => handleImg(e.target.files[0])" />
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-span-4 flex flex-col gap-6">
                                        <div class="grid grid-cols-4 gap-2">
                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="first_name" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>First Name
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="first_name" type="text" placeholder="First Name"
                                                        v-model="form.first_name" @focus="errors.first_name = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.first_name" :message="errors.first_name"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="last_name" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Last Name
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="last_name" type="text" placeholder="Last Name"
                                                        v-model="form.last_name" @focus="errors.last_name = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.last_name" :message="errors.last_name"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="middle_name" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Middle Name
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="middle_name" type="text" placeholder="Middle Name"
                                                        v-model="form.middle_name" @focus="errors.middle_name = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.middle_name" :message="errors.middle_name"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="age" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Age
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="age" type="text" placeholder="Age" v-model="form.age"
                                                        @focus="errors.age = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.age" :message="errors.age"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="address" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Address
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="address" type="text" placeholder="Street Address"
                                                        v-model="form.address" @focus="errors.address = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.address" :message="errors.address"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="contact" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Contact
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="contact" type="text" placeholder="Contact"
                                                        v-model="form.contact" @focus="errors.contact = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.contact" :message="errors.contact"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="email" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Email
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <InputError v-if="errors?.email" :message="errors.email"
                                                        class="items-center flex text-xs" />
                                                    <Input id="email" type="text" placeholder="Email" readonly
                                                        v-model="form.email" class="w-full border border-gray-200" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="password" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Password
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="password" type="text" placeholder="Enter Password"
                                                        v-model="form.password" @focus="errors.password = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.password" :message="errors.password"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
                                            </div>

                                            <div class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                <div class="flex flex-row items-center gap-2">
                                                    <Label for="password_confirmation" class="items-center flex mb-1">
                                                        <span class="text-red-900 font-bold mr-1">*</span>Confirm
                                                        Password
                                                    </Label>
                                                </div>

                                                <div class="relative w-full">
                                                    <Input id="password_confirmation" type="text"
                                                        placeholder="Confirm Password"
                                                        v-model="form.password_confirmation"
                                                        @focus="errors.password_confirmation = null"
                                                        class="w-full border border-gray-200 pr-10" />
                                                    <InputError v-if="errors?.password_confirmation"
                                                        :message="errors.password_confirmation"
                                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                </div>
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
    </div>
</template>

<script setup>
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, nextTick, onMounted, watch, reactive } from 'vue';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';

const user = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    flash: Object,
    user: Object,
});

// Initialize form with user data
const form = useForm({
    first_name: user.first_name || '',
    middle_name: user.middle_name || '',
    last_name: user.last_name || '',
    email: user.email || '',
    birthdate: '',
    age: '',
    address: '',
    contact: '',
    password: '',
    password_confirmation: '',
    img: null,
    imgName: null,
    imgPreview: null,
});

// Submit function using Inertia
const submit = () => {
    // Create FormData for file upload
    const formData = new FormData();

    // Add all form fields to FormData
    formData.append('first_name', form.first_name);
    formData.append('middle_name', form.middle_name);
    formData.append('last_name', form.last_name);
    formData.append('email', form.email);
    formData.append('birthdate', form.birthdate);
    formData.append('age', form.age);
    formData.append('address', form.address);
    formData.append('contact', form.contact);

    // Only append password fields if they're filled
    if (form.password) {
        formData.append('password', form.password);
        formData.append('password_confirmation', form.password_confirmation);
    }

    // Append image if selected
    if (form.img) {
        formData.append('img', form.img);
    }

    // Submit the form using Inertia with FormData
    form.post('/staff/verify-account/verifying', {
        forceFormData: true,
        onSuccess: () => {
            // Show success message or redirect
        },
    });
};

// Dialog state
const showDialog = ref(false);
const reminders = [
    "Complete your profile information.",
    "Check for new scholarship postings regularly.",
    "Ensure your documents are updated before applying.",
    "Monitor your application status on your dashboard."
];

// Close dialog
const closeDialog = () => {
    showDialog.value = false;
};

// Set "Don't Show Again" and save preference
const dontShowAgain = () => {
    localStorage.setItem('seenReminderDialog', 'true');
    closeDialog();
};

const scrollPosition = ref(0);

// Save the scroll position
const saveScrollPosition = () => {
    scrollPosition.value = window.scrollY;
};

// Restore the scroll position
const restoreScrollPosition = () => {
    window.scrollTo(0, scrollPosition.value);
};

// Image handling
const isDragging = ref(false);

const handleImgDragOver = () => {
    isDragging.value = true;
};

const handleImgDragLeave = () => {
    isDragging.value = false;
};

const handleImgDrop = (event) => {
    isDragging.value = false;
    const img = event.dataTransfer.files[0];
    handleImg(img);
};

const handleImg = (img) => {
    if (img) {
        form.img = img;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            form.imgPreview = e.target.result;
        };
        reader.readAsDataURL(img);
    }
};

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
                form.birthdate = datepickerEl.value;
            });

            datepickerEl.addEventListener("blur", () => {
                form.birthdate = datepickerEl.value;
            });
        }

        // Restore value manually when switching steps
        if (form.birthdate) {
            datepickerEl.value = form.birthdate;
        }
    }
};

onMounted(() => {
    initFlowbite();
    initDatepicker();

    // Show dialog if not seen before
    if (!localStorage.getItem('seenReminderDialog')) {
        showDialog.value = true;
    }

    // Restore scroll position
    restoreScrollPosition();
});
</script>

<style scoped>
.stepper-container {
    display: flex;
    flex-direction: column;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-title {
    margin-left: 10px;
}

.step.active .step-number {
    background-color: #4CAF50;
    color: white;
}

.step.completed .step-number {
    background-color: #2196F3;
    color: white;
}

.stepper-nav .step:hover {
    background-color: #f0f0f0;
}
</style>