<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-white shadow-sm ">
            <h1 class="font-bold font-sora text-left p-3 mx-10
            2xl:text-3xl xl:text-3xl lg:text-2xl md:text-base sm:text-base">Application</h1>
        </div>
        <div
            class="w-full h-full flex flex-col bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <h1 class="text-2xl font-bold text-center text-gray-900 dark:text-white mt-3">
                {{ scholarship.name }}
            </h1>

            <div class="flex w-full mt-10 my-auto max-w-5xl mx-auto gap-3">
                <!-- Stepper Navigation -->
                <div class="flex flex-row items-center justify-center mt-5 relative w-full">
                    <!-- Background Gray Line (Full Width) -->
                    <div class="absolute top-[30%] left-0 w-full h-1 bg-[#E9F4FF] rounded-lg"></div>

                    <div v-for="(step, index) in steps" :key="index" class="relative flex flex-col items-center w-full"
                        @click="goToStep(index)">
                        <!-- Progress Line (Only for Steps > 0) -->
                        <div v-if="index !== 0" class="absolute top-[30%] left-[-50%] h-1 w-full transition-all" :class="{
                            'bg-blue-900': activeStep >= index,
                            'bg-gray-100': activeStep < index
                        }"></div>

                        <!-- Step Circle with Icon -->
                        <span
                            class="material-symbols-rounded text-base xl:text-3xl flex items-center justify-center w-8 h-8 xl:w-10 xl:h-10 rounded-full transition-all z-10"
                            :class="{
                                'text-white bg-blue-900': activeStep === index,
                                'text-yellow-600 bg-yellow-100': activeStep > index,
                                'text-gray-400 bg-gray-100': activeStep < index
                            }">
                            {{ step.icon }}
                        </span>

                        <!-- Step Label -->
                        <div class="mt-2 text-xs xl:text-sm font-medium"
                            :class="{ 'text-blue-900': activeStep >= index, 'text-gray-500': activeStep < index }">
                            {{ step.label }}
                        </div>

                    </div>
                </div>
            </div>

            <div class="box-border h-full mx-auto max-w-8xl sm:px-3 lg:px-8">
                <div class="h-full flex flex-col space-y-5 mt-5 xl:mt-10">
                    <form @submit.prevent="submitRequirements" enctype="multipart/form-data">
                        <div class="stepper-container max-w-full">
                            <!-- Step Content -->
                            <div class="flex-grow">
                                <div v-if="activeStep === 0">
                                    <div class="bg-white rounded-lg p-10 sm:p-5 xl:p-10 shadow-md">
                                        <!-- Header Section -->
                                        <h3
                                            class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4">
                                            Why are you Eligible?
                                        </h3>
                                        <p class="text-xs font-inter uppercase text-gray-500 dark:text-gray-300 mb-4">
                                            Please fill up missing required fields. Leave N/A if not applicable.
                                        </p>

                                        <!-- Two-Column Grid (Switches to Single Column on Small Screens) -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Scholarship Criteria -->
                                            <div>
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-3">
                                                    Scholarship Eligibility Criteria</h4>
                                                <ul class="space-y-3">
                                                    <li v-for="(criterion, index) in criteria" :key="index"
                                                        class="flex items-center space-x-2">
                                                        <span class="text-blue-600 font-medium">✔</span>
                                                        <span class="text-gray-700 dark:text-gray-300">{{ criterion
                                                            }}</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- User's Eligibility Status -->
                                            <div>
                                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-3">
                                                    Your
                                                    Eligibility Status</h4>
                                                <ul class="space-y-3">
                                                    <li v-for="(detail, index) in userDetails" :key="index"
                                                        class="flex items-center space-x-2">
                                                        <span class="text-green-600">✅</span>
                                                        <span class="text-gray-700 dark:text-gray-300">{{ detail
                                                            }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Navigation Button -->
                                        <div class="flex justify-end mt-6">
                                            <button type="submit" @click="nextStep" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br 
                                                focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 
                                                font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step Content -->
                                <div v-if="activeStep === 1">
                                    <div class="bg-white grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3
                                    gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10 ">

                                        <div class="sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Brief Explanation</h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Answer the following question briefly and concisely</p>
                                        </div>

                                        <div class="col-span-3 md:col-span-2">
                                            <label for="essay"
                                                class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                                Topic:
                                            </label>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                Describe yourself, your family, and your goals or aspirations in
                                                relation to
                                                the scholarship or financial assistance you are applying for.
                                            </p>
                                            <textarea id="essay" rows="6" v-model="form.essay"
                                                class="block w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:bg-gray-800 dark:border-gray-600 dark:text-white placeholder-gray-400"
                                                placeholder="Write your scholarship essay here..."></textarea>
                                        </div>

                                        <div class="col-span-3 flex justify-end mt-4">
                                            <button type="submit" @click="nextStep"
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step Content -->
                                <div v-if="activeStep === 2">
                                    <div class="bg-white grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3
                                    gap-6 rounded-lg h-1/2 items-center justify-start p-10 sm:p-5 xl:p-10 ">

                                        <div class="sm:col-span-3 md:col-span-2 lg:col-span-3 xl:col-span-3">
                                            <h3
                                                class="font-semibold text-gray-900 dark:text-white mb-2 py-1 pl-3 border-primary border-l-4 sm:text-white">
                                                Scholarship Requirements
                                            </h3>
                                            <p
                                                class="font-semibold text-[12px] font-inter uppercase text-gray-400 dark:text-white mb-4">
                                                Upload the needed file requirements
                                            </p>

                                        </div>

                                        <div class="col-span-2 md:col-span-2">
                                            <div v-for="(requirement, index) in requirements" :key="requirement.id"
                                                class="border rounded-lg p-4 bg-white shadow-sm mb-3 max-w-xl">
                                                <!-- Header -->
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="bg-yellow-400 text-black font-bold px-2 py-1 rounded">
                                                            {{ String.fromCharCode(65 + index) }}
                                                        </span>
                                                        <span class="font-semibold text-gray-800 min-w-72">{{
                                                            requirement.requirements }}</span>
                                                    </div>
                                                    <label
                                                        class="bg-blue-900 text-white px-3 py-1 rounded cursor-pointer text-sm">
                                                        Add File
                                                        <input type="file" class="hidden"
                                                            @change="(e) => handleFile(e, requirement.id, requirement.requirements)"
                                                            :id="'file_input_' + requirement.id" />
                                                    </label>
                                                </div>

                                                <!-- Uploaded File Preview -->
                                                <div v-if="form.files[requirement.id]"
                                                    class="border border-dashed border-purple-400 rounded-lg p-3 mt-2 flex items-center justify-between min-w-[250px]">
                                                    <!-- <div class="flex items-center space-x-3">
                                                        <img src="https://img.icons8.com/ios-filled/50/000000/pdf.png" class="w-8 h-8" alt="PDF Icon">
                                                        <div>
                                                            <p class="text-sm font-medium">{{ form.files[requirement.id].name }}</p>
                                                            <p class="text-xs text-gray-500">{{ form.files[requirement.id].size }}</p>
                                                        </div>
                                                    </div> -->

                                                    <div class="flex items-center space-x-3">
                                                        <img src="https://img.icons8.com/ios-filled/50/000000/pdf.png"
                                                            class="w-8 h-8" alt="PDF Icon">
                                                        <div>
                                                            <p class="text-sm font-medium truncate overflow-hidden max-w-xs"
                                                                title="{{ form.files[requirement.id].name }}">
                                                                {{ form.files[requirement.id].name.length > 30 ?
                                                                    form.files[requirement.id].name.substring(0, 30) + '...'
                                                                : form.files[requirement.id].name }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">{{
                                                                form.files[requirement.id].size }}</p>
                                                        </div>
                                                    </div>
                                                    <button type="button" @click="removeFile(requirement.id)"
                                                        class="ml-4 text-red-600 hover:text-red-800">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col space-y-3 pt-5">
                                            <span v-if="templates.length > 0"
                                                class="text-lg font-inter font-normal text-dsecondary text-left">For the
                                                other documents, you can download them below</span>

                                            <div class="col-span-1 flex flex-col space-y-3">
                                                <div v-for="(template, index) in templates" :key="template.id"
                                                    class="border rounded-lg p-3 bg-white shadow-sm w-full max-w-xl">

                                                    <!-- Header -->
                                                    <div class="flex justify-between items-center gap-5">
                                                        <div class="flex items-center space-x-2">
                                                            <span
                                                                class="bg-green-400 text-black font-bold px-2 py-1 rounded">
                                                                {{ String.fromCharCode(65 + index) }}
                                                            </span>
                                                            <span class="font-semibold text-gray-800">
                                                                {{ template.filename }}
                                                            </span>
                                                        </div>
                                                        <a :href="`/storage/scholarship_templates/${template.requirement_id}/${template.filename}`"
                                                            target="_blank"
                                                            class="bg-blue-900 text-white px-3 py-1 rounded cursor-pointer text-sm">
                                                            Download
                                                        </a>
                                                        <!-- <a target="_blank"
                                                    class="bg-blue-900 text-white px-3 py-1 rounded cursor-pointer text-sm">
                                                    Download
                                                </a> -->
                                                    </div>

                                                    <!-- Optional description or file info -->
                                                    <!-- <div v-if="template.description" class="mt-2 text-sm text-gray-600">
                                                {{ template.description }}
                                            </div> -->
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-span-3 flex justify-end mt-4">
                                            <button type="submit"
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                                Submit</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, watchEffect, watch, computed, nextTick } from 'vue';


const activeTab = ref("eligibility");

const tabs = [
    { key: "eligibility", label: "Eligibility" },
    { key: "requirements", label: "Requirements" },
    { key: "awards", label: "Awards" },
];

const props = defineProps({
    scholarship: {
        type: Object,
        required: true
    },
    sponsor: {
        type: Object,
        required: true
    },
    requirements: {
        type: Array,
        required: true
    },
    criterias: {
        type: Array,
        required: true
    },
    deadline: {
        type: Object,
        required: true
    },
    selectedCampus: {
        type: Object,
        required: true
    },
    grade: {
        type: Object,
        required: true
    },
    templates: {
        type: Array,
        required: true
    }
});

const form = useForm({
    essay: "",
    files: {},
    requirements: [],
    scholarship_id: props.scholarship.id,
});


const handleFile = (event, requirementId, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        form.files[requirementId] = file;

        // Update or add the requirement entry
        const existingIndex = form.requirements.findIndex(r => r.id === requirementId);
        if (existingIndex !== -1) {
            form.requirements[existingIndex] = { id: requirementId, name: requirementName };
        } else {
            form.requirements.push({ id: requirementId, name: requirementName });
        }
    }
};

const removeFile = (requirementId) => {
    delete form.files[requirementId];
    form.requirements = form.requirements.filter(r => r.id !== requirementId);
};

const isFormValid = computed(() => {
    return Object.keys(form.files).length === props.requirements.length;
});

const submitRequirements = async () => {
    try {
        const formData = new FormData();

        // Append each file with its requirement ID
        Object.entries(form.files).forEach(([requirementId, file]) => {
            formData.append(`files[${requirementId}]`, file);
        });

        // Append essay data
        formData.append('essay', form.essay);

        formData.append('scholarship_id', form.scholarship_id);

        // Add req array from requirements
        form.requirements.forEach((requirement, index) => {
            formData.append(`req[${index}]`, requirement.id);
        });

        form.post(`/student/applying-scholarship/${props.scholarship.id}/apply`, {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                form.files = {};
                form.requirements = [];
                form.essay = "";
            },
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};


const activeStep = ref(0);
const steps = ref([
    { label: 'Eligibility', icon: 'how_to_reg' },
    { label: 'Essay', icon: 'stylus_note' },
    { label: 'Upload', icon: 'upload_file' },
]);

const goToStep = (index) => {
    activeStep.value = index;
    nextTick(() => {
        initDatepicker(); // Reinitialize datepicker after the step changes
        restoreFileInput();
    });
};

const nextStep = () => {
    if (activeStep.value < steps.value.length - 1) {
        activeStep.value++;
        nextTick(() => {
            initDatepicker(); // Reinitialize datepicker after the step changes
            restoreFileInput();
        });
    }
};

const prevStep = () => {
    if (activeStep.value > 0) {
        activeStep.value--;
        nextTick(() => {
            initDatepicker(); // Reinitialize datepicker after the step changes
            restoreFileInput();
        });
    }
};

// Scholarship Criteria (Dummy Data)
const criteria = ref([
    "Must be a Filipino citizen",
    "Must have a General Weighted Average (GWA) of at least 2",
    "Must belong to a low-income household",
    "Must be enrolled in an accredited university"
]);

// User's Eligible Details (Dummy Data)
const userDetails = ref([
    "You are a Filipino citizen",
    "Your GWA is 1.2",
    "Your household income is within the required range",
    "You are enrolled at University of Rizal System"
]);

// const file = ref(null);

// const handleFileUpload = (event) => {
//   const uploadedFile = event.target.files[0];
//   if (uploadedFile) {
//     file.value = {
//       name: uploadedFile.name,
//       size: (uploadedFile.size / 1024).toFixed(2) + 'KB',
//     };
//   }
// };

// const removeFile = () => {
//   file.value = null;
// };




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
