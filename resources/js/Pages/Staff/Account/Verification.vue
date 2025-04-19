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
                            Greetings! {{ user.name }}
                        </div>
                    </div>
                </div>

                <div class="box-border h-full mx-auto max-w-7xl sm:px-3 lg:px-8 overflow-auto">
                    <div class="h-full flex flex-col space-y-5 mt-5 xl:mt-10">

                        <div class="stepper-container max-w-full">
                            <!-- Step Content -->
                            <div class="flex-grow">
                                <form @submit.prevent="submit">
                                    <div class="bg-white border-box overflow-x-auto grid grid-cols-5 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-6 xl:grid-cols-6
                                        gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10">

                                        <div class="sm:col-span-6 md:col-span-2 lg:col-span-6 xl:col-span-6">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Account Setup</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Please input, change and update you account before proceeding</p>
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
                                                    @dragover.prevent="handleImgDragOver"
                                                    @dragleave="handleImgDragLeave" @drop.prevent="handleImgDrop">
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
                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
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

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
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

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
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

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="birthdate" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Birthdate
                                                        </Label>
                                                    </div>

                                                    <div class="relative max-w-sm">
                                                        <div
                                                            class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                            </svg>
                                                        </div>
                                                        <input v-model="form.birthdate" id="datepicker-autohide" type="text"
                                                            autocomplete="off"
                                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            placeholder="Select Birthdate" />

                                                        <InputError v-if="errors?.birthdate" :message="errors.birthdate"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
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

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="first_name" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Address
                                                        </Label>
                                                    </div>

                                                    <div class="relative w-full">
                                                        <Input id="first_name" type="text" placeholder="Street Address" 
                                                            v-model="form.address" @focus="errors.address = null"
                                                            class="w-full border border-gray-200 pr-10" />
                                                        <InputError v-if="errors?.address" :message="errors.address"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="first_name" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Contact
                                                        </Label>
                                                    </div>

                                                    <div class="relative w-full">
                                                        <Input id="first_name" type="text" placeholder="Street Address" 
                                                            v-model="form.address" @focus="errors.address = null"
                                                            class="w-full border border-gray-200 pr-10" />
                                                        <InputError v-if="errors?.address" :message="errors.address"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="first_name" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Email
                                                        </Label>
                                                    </div>

                                                    <div class="relative w-full">
                                                        <InputError v-if="errors?.email" :message="errors.email"
                                                            class="items-center flex text-xs" />
                                                        <Input id="first_name" type="text" placeholder="Email"
                                                            v-model="form.email"
                                                            class="w-full border border-gray-200" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="first_name" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Password
                                                        </Label>
                                                    </div>

                                                    <div class="relative w-full">
                                                        <Input id="middle_name" type="text"
                                                            placeholder="Enter Password" v-model="form.password"
                                                            @focus="errors.password = null"
                                                            class="w-full border border-gray-200 pr-10" />
                                                        <InputError v-if="errors?.password"
                                                            :message="errors.password"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full max-w-sm items-center gap-1.5 col-span-2">
                                                    <div class="flex flex-row items-center gap-2">
                                                        <Label for="first_name" class="items-center flex mb-1">
                                                            <span class="text-red-900 font-bold mr-1">*</span>Confirm Password
                                                        </Label>
                                                    </div>

                                                    <div class="relative w-full">
                                                        <Input id="middle_name" type="text"
                                                            placeholder="Confirm Password"
                                                            v-model="form.confirm_password"
                                                            @focus="errors.confirm_password = null"
                                                            class="w-full border border-gray-200 pr-10" />
                                                        <InputError v-if="errors?.confirm_password"
                                                            :message="errors.confirm_password"
                                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xs text-red-500" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>
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
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Calendar } from '@/Components/ui/calendar'
import InputError from '@/Components/InputError.vue';

import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover'
import { cn } from '@/lib/utils'
import { DateFormatter, getLocalTimeZone, } from '@internationalized/date'
import { Calendar as CalendarIcon } from 'lucide-vue-next'

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { initFlowbite } from 'flowbite';


const user = usePage().props.auth.user;

const props = defineProps({
    errors: Object,
    flash: Object,
    user: Object,
    scholar: Object,
    batch: Object,
    batch_semester: Object,
    school_year: Object,
    studentData: Object,
    scholarship_form: Array,
    scholarship_form_data: Array,
});

const getFormData = (formId) => {
    return props.scholarship_form_data.filter(data => data.scholarship_form_id === formId);
};

const form = ref({
    name: user.name,
    email: user.email,
    first_name: user.first_name,
    middle_name: usePage().props.scholar?.middle_name ?? '',
    last_name: user.last_name,
    student_number: props.scholar?.student_number ?? '',
    password: '',
    confirm_password: '',
    suffix: 'N/A',
    birthdate: usePage().props.scholar?.birthdate ?? '',
    birthplace: props.studentData?.birthplace ?? '',
    age: props.studentData?.age ?? '',
    gender: usePage().props.scholar?.sex ?? '',
    civil_status: props.studentData?.civil_status ?? '',
    street: usePage().props.scholar?.street ?? '',
    municipality: usePage().props.scholar?.municipality ?? '',
    province: usePage().props.scholar?.province ?? '',
    religion: props.studentData?.religion ?? '',
    guardian_name: '',
    relationship: '',
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
    education: {
        elementary: { name: '', years: '', honors: 'N/A' },
        junior: { name: '', years: '', honors: 'N/A' },
        senior: { name: '', years: '', honors: 'N/A' },
        college: { name: '', years: '', honors: 'N/A' },
        vocational: { name: 'N/A', years: 'N/A', honors: 'N/A' },
        postgrad: { name: 'N/A', years: 'N/A', honors: 'N/A' },
    },
    mother: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '', isDeceased: false },
    father: { first_name: '', last_name: '', middle_name: '', age: '', address: '', citizenship: '', occupation: '', education: '', batch: '', isDeceased: false },
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


const handleFile = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.cog = file;
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
// const submit = () => {
//     form.post(route('student.verify-account.verifying'), {
//         onFinish: () => form.reset(),
//     });
// };

const submit = async () => {

    try {
        if (props.scholar != null) {
            form.value.semester = props.batch_semester;
            form.value.school_year = props.school_year.id;
        }

        router.post(`/verify-account/verifying`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const finishStep = () => {
    alert('Step completed!');
};

const submitStep1 = () => {
    // Add your logic to submit the first step's form
    nextStep();
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

//adding siblings
const formEntries = ref([
    { first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }
]);

// Method to add a new entry
const addEntry = (event) => {
    event.preventDefault(); // Prevent form submission
    saveScrollPosition();
    form.value.siblings.push({ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' });
    restoreScrollPosition();
};

const removeEntry = (index) => {
    saveScrollPosition(); // Save scroll position before removing entry
    form.value.siblings.splice(index, 1);
    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
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

// This can be used to restore the scroll position when the page first loads
onMounted(() => {
    initFlowbite();
    initDatepicker();
    if (!localStorage.getItem('seenReminderDialog')) {
        showDialog.value = true;
    }

    // const datepickerEl = document.getElementById("datepicker-autohide");

    // if (datepickerEl) {
    //     const datepicker = new window.Datepicker(datepickerEl, {
    //         autohide: true,
    //         format: "yyyy-mm-dd", // Ensure YYYY-MM-DD format
    //     });

    //     datepickerEl.addEventListener("changeDate", (event) => {

    //         const selectedDate = datepicker.getDate();
    //         if (selectedDate) {

    //             const year = selectedDate.getFullYear();
    //             const month = String(selectedDate.getMonth() + 1).padStart(2, "0"); // Month is 0-based
    //             const day = String(selectedDate.getDate()).padStart(2, "0");

    //             form.value.birthdate = `${year}-${month}-${day}`;
    //         }
    //     });
    // }

    restoreScrollPosition();
});



const organizations = ref([
    { name: '', membership_dates: '', position: '' } // Initial entry
]);

// Add a new organization entry
const addOrganization = (event) => {
    event.preventDefault(); // Prevent form submission
    saveScrollPosition(); // Save scroll position before adding entry

    // Directly push into form.value.organizations instead of reassigning
    form.value.organizations.push({ name: '', membership_dates: '', position: '' });

    nextTick(() => restoreScrollPosition()); // Restore scroll position after DOM updates
};

// Remove an organization entry smoothly
const removeOrganization = (index) => {
    if (form.value.organizations.length > 1) {
        form.value.organizations.splice(index, 1);
    }
};


const isImgDragging = ref(false);
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
    if (img) {
        // Generate a unique filename using timestamp
        const timestamp = new Date().getTime();
        const uniqueFileName = `${timestamp}_${img.name}`;

        form.value.img = img;
        form.value.imgName = uniqueFileName; // Store the generated unique filename

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.imgPreview = e.target.result;
        };
        reader.readAsDataURL(img);
    }
};

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


/* form {
  display: flex;
  flex-direction: column;
}

form input {
  margin: 10px 0;
  padding: 10px;
  border: 1px solid #ccc;
}

form button {
  margin-top: 20px;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
} */
</style>