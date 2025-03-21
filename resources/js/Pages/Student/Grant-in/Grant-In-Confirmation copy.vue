<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-dirtywhite shadow-sm ">
            <h1 class="text-3xl font-bold font-sora text-left p-3 mx-10">Confirmation</h1>
        </div>
        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-4">
            <!-- <div class="mx-auto max-w-8xl sm:px-6 lg:px-8 "> -->
            <div class="w-full block bg-white shadow-md p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8">
                <!-- Image Container (Centered) -->
                <div class="w-44 h-44 flex items-center justify-center mx-auto">
                    <img src="../../../../assets/images/qualifiedvector.png" alt="Congrats">
                </div>
                <h1 class="font-bold text-2xl font-albert text-center mt-4">
                    Congratulations! You are qualified for the {{ scholarship.name }} Scholarship!
                </h1>
                <div class="p-24 font-inter text-lg">
                    <p>Dear, {{ scholar.first_name + ' ' + scholar.last_name }}</p>
                    <br>
                    <p class="leading-loose indent-6">We are thrilled to inform you that you have been officially
                        qualified for a scholarship under {{ scholarship.name }} in the URScholar System! Your
                        dedication and hard work have truly paid off, and we are excited to support you on your academic
                        journey.</p>
                    <br>
                    <h1 class="font-bold">Next Steps: Submission of Requirements</h1>
                    <br>
                    <p class="leading-loose indent-6">To complete your scholarship application and ensure your
                        eligibility for the grant, please submit the following required documents no later than
                        {{ requirements.deadline }}:</p>
                    <br>

                    <div>
                        <form @submit.prevent="submitRequirements" enctype="multipart/form-data">
                            <div class="p-3 w-full bg-dtext rounded-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h1 class="text-lg font-inter font-bold text-dsecondary text-left">
                                        Uploading Documents
                                    </h1>
                                </div>

                                <!-- Requirements upload section -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div v-for="(requirement, index) in requirements" :key="requirement.id"
                                        class="border rounded-md shadow p-4 bg-white">
                                        <div class="mb-2">
                                            <span class="font-medium text-gray-700">{{ requirement.requirements
                                                }}</span>
                                        </div>

                                        <div class="space-y-2">
                                            <input type="file"
                                                @change="(e) => handleFile(e, requirement.id, requirement.requirements)"
                                                :id="'file_input_' + requirement.id"
                                                class="block w-full text-sm text-blue-900 border border-gray-300 rounded-lg cursor-pointer bg-white 
                                            dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />

                                            <!-- File preview -->
                                            <div v-if="form.files[requirement.id]" class="text-sm text-gray-600">
                                                Selected file: {{ form.files[requirement.id].name }}
                                                <button type="button" @click="removeFile(requirement.id)"
                                                    class="ml-2 text-red-600 hover:text-red-800">
                                                    Remove
                                                </button>
                                            </div>

                                            <div v-if="form.errors[`files.${requirement.id}`]"
                                                class="text-red-500 text-sm">
                                                {{ form.errors[`files.${requirement.id}`] }}
                                            </div>
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

                    <h1 class="font-bold">Important Reminders:</h1>
                    <ul class="list-disc ml-6 text-left">
                        <li>Late or incomplete submissions may result in forfeiture of your scholarship.</li>
                        <li>If you have any questions or need assistance, please contact [Scholarship Coordinator’s
                            Name] at [Coordinator’s Email/Phone].</li>
                        <li>Keep an eye on your URScholar notifications for further updates.</li>
                    </ul>
                    <br>

                    <h1 class="font-bold">How to Submit:</h1>
                    <ul class="list-disc ml-6 text-left">
                        <li>Log in to your URScholar account at [System URL] and navigate to the “Scholarship
                            Requirements” section.</li>
                        <li>Upload the required documents in the designated fields.</li>
                        <li>Ensure all files are clear and readable before submission.</li>
                        <li>Keep an eye on your URScholar notifications for further updates.</li>
                    </ul>

                    <br>
                    <p>Once again, congratulations! We are proud of you and look forward to supporting your academic
                        success.</p>
                    <br>

                    <p class="font-bold">Best regards,<br>
                        [Your Scholarship Office Name]<br>
                        University of Rizal System | URScholar Team<br>
                        [Scholarship Office Email] | [Contact Number]</p>
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