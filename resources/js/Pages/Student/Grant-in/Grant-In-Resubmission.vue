<template>


    <AuthenticatedLayout class="shadow-md z-10 min-h-screen overflow-auto">
        <div class="w-full bg-dirtywhite shadow-sm">
            <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3 md:mx-20">
                Resubmission
            </h1>
        </div>
        <div class="pt-3 pb-24 sm:overflow-auto sm:max-h-[90vh] sm:scroll-py-2">
            <div class="w-full bg-white shadow-md p-10 sm:p-5 flex flex-col items-center mx-auto max-w-5xl sm:px-6 lg:px-8">


                <!-- Image Container (Centered) -->
                <div class="w-44 h-44 sm:w-20 sm:h-40 md:w-42 md:h-44 lg:w-44 lg:h-34 flex justify-center mx-auto">
                    <img 
                        src="../../../../assets/images/validation.png" 
                        alt="Congrats" 
                    >
                </div>
                <h1 class="font-bold text-2xl font-albert text-center mt-4">
                    Data Validation and Document Submission for the {{ scholarship.name }} Scholarship!
                </h1>
                <div class="p-24 sm:p-3 sm:mt-10 font-inter text-lg">
                    <p>Dear {{ scholar.first_name + ' ' + scholar.last_name }},</p>
                        <br>
                        <p class="leading-loose indent-6">
                            This is a notice regarding your current status as a grantee of the {{ scholarship.name }} Scholarship through the URScholar System. Please be informed that new or updated document requirements have been issued for existing grantees.
                        </p>
                        <br>
                        <h1 class="font-bold">Action Required: Resubmission of Updated Requirements</h1>
                        <br>
                        <p class="leading-loose indent-6">
                            To maintain your scholarship status and ensure compliance with the updated guidelines, you are required to submit the newly listed documents no later than <strong>{{ requirements.deadline }}</strong>.
                        </p>
                        <br>
                        <p class="leading-loose indent-6">
                            Please log in to your URScholar account to review the updated list of required documents and complete the resubmission process. Failure to comply by the deadline may result in delays or impact your scholarship status.
                        </p>
                        <br>


                    <div>
                        <form @submit.prevent="submitRequirements" enctype="multipart/form-data">
                            <div class="p-3 w-full bg-gray-100 rounded-lg space-y-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h1 class="text-lg font-inter font-bold text-dsecondary text-left">
                                        Uploading New Issued Documents
                                    </h1>
                                </div>

                                <div class="col-span-1 flex flex-col space-y-3">
                                    <div v-for="(requirement, index) in requirements" :key="requirement.id"
                                        class="border rounded-lg p-3 bg-white shadow-sm w-full max-w-xl">
                                        <!-- Header -->
                                        <div class="flex justify-between items-center gap-5">
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="bg-yellow-400 text-black font-bold px-2 py-1 rounded">{{
                                                    String.fromCharCode(65 + index) }}</span>
                                                <span class="font-semibold text-gray-800">{{
                                                    requirement.requirements
                                                }}</span>
                                            </div>
                                            <label
                                                class="bg-blue-900 text-white px-3 py-1 rounded cursor-pointer text-sm">
                                                Add File
                                                <input type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png"
                                                @change="(e) => handleFile(e, requirement.id, requirement.requirements)"
                                                :id="'file_input_' + requirement.id" />
                                            </label>
                                        </div>

                                        <!-- Uploaded File Preview -->
                                        <div v-if="form.files[requirement.id]"
                                            class="border border-dashed border-purple-400 rounded-lg p-2 mt-2 flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <img src="https://img.icons8.com/ios-filled/50/000000/pdf.png"
                                                    class="w-6 h-6" alt="PDF Icon">
                                                <div>
                                                    <p class="text-sm font-medium">{{
                                                        form.files[requirement.id].name }}</p>
                                                    <p class="text-xs text-gray-500">{{
                                                        form.files[requirement.id].size }}</p>
                                                </div>
                                            </div>
                                            <button type="button" @click="removeFile(requirement.id)"
                                                class="ml-2 text-red-600 hover:text-red-800">
                                                Remove
                                            </button>

                                            <div v-if="form.errors[`files.${requirement.id}`]"
                                                class="text-red-500 text-sm">
                                                {{ form.errors[`files.${requirement.id}`] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col space-y-3 pt-5">
                                    <span v-if="templates.length > 0" class="text-lg font-inter font-normal text-dsecondary text-left">For the other documents, you can download them below</span>

                                    <div class="col-span-1 flex flex-col space-y-3">
                                        <div v-for="(template, index) in templates" :key="template.id"
                                            class="border rounded-lg p-3 bg-white shadow-sm w-full max-w-xl">
                                            
                                            <!-- Header -->
                                            <div class="flex justify-between items-center gap-5">
                                                <div class="flex items-center space-x-2">
                                                    <span class="bg-green-400 text-black font-bold px-2 py-1 rounded">
                                                        {{ String.fromCharCode(65 + index) }}
                                                    </span>
                                                    <span class="font-semibold text-gray-800">
                                                        {{ template.filename }}
                                                    </span>
                                                </div>
                                                <a :href="`/storage/scholarship_templates/${template.requirement_id}/${template.filename}`" target="_blank"
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



                                <div class="items-right flex w-full justify-end mt-4">
                                    <button type="submit" :disabled="form.processing || !isFormValid" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
                                            focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
                                            transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed
                                            flex items-center justify-center gap-2">
                                        <span class="material-symbols-rounded">outbox</span>
                                        <span>{{ form.processing ? 'Submitting...' : 'Submit' }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br>

                    <!-- <h1 class="font-bold">How to Submit:</h1>
                    <ul class="list-disc ml-6 text-left">
                        <li>Log in to your URScholar account at [System URL] and navigate to the “Scholarship
                            Requirements” section.</li>
                        <li>Upload the required documents in the designated fields.</li>
                        <li>Ensure all files are clear and readable before submission.</li>
                        <li>Keep an eye on your URScholar notifications for further updates.</li>
                    </ul> -->

                    <h1 class="font-bold">Important Reminders:</h1>
                    <ul class="list-disc ml-6 text-left">
                        <li>If you have any questions or need assistance, please reach out to the Scholarship and Financial Assistance Unit at your campus.</li>
                        <li>Stay updated with any notifications from URScholar for further instructions or changes.</li>
                    </ul>
                    <br>

                    <br>
                    <p>Once again, congratulations on reaching this stage! We look forward to finalizing your scholarship eligibility and supporting your academic journey.</p>
                    <br>

                    <p class="font-bold">Best regards,<br>
                        Scholarship and Financial Assistance Unit<br>
                        University of Rizal System | URScholar Team<br>
                    </p>

                </div>
            </div>
            <!-- </div> -->
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    scholar: Object,
    scholarship: Object,
    requirements: Array,
    templates: Array,
});

const form = useForm({
    files: {},
    requirements: []
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

        // Append requirements data
        formData.append('requirements', JSON.stringify(form.requirements));

        form.post('/student/application/upload', {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                form.files = {};
                form.requirements = [];
            },
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>