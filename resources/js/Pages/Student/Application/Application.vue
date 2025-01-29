<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    scholars: Object,
    scholarships: Object,
    requirements: Object,
});

// Form to handle multiple files for different requirements
const form = useForm({
    files: {},
    requirement_files: [] // Array to store requirement-file mappings
});

// Handle file selection for specific requirement
const handleFile = (event, reqIndex, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        // Store the file with its requirement info
        form.files[reqIndex] = file;
        
        // Store the mapping of requirement to file
        form.requirement_files.push({
            requirement_name: requirementName,
            file_index: reqIndex
        });
    }
};

// Submit form with files
const submitRequirements = async () => {
    try {
        form.post('/student/application/upload', {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                // Clear files after successful upload
                form.files = {};
                form.requirement_files = [];
            },
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full bg-white shadow-sm">
            <h1 class="text-3xl font-bold font-soratext-left p-3 mx-10">My Feed</h1>
        </div>
        <div class="p-10 overflow-auto h-full space-y-3">
            <!-- Scholar info section -->
            <div class="p-10 w-full h-1/12 bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h1 v-for="scholar in scholars" :key="scholar.id"
                        class="text-3xl font-poppins font-extrabold text-[darkblue] text-left">
                        You are qualified for the {{ scholar.scholarship.name }}
                    </h1>
                </div>
            </div>

            <!-- Upload form section -->
            <form @submit.prevent="submitRequirements" enctype="multipart/form-data">
                <div class="p-10 w-full bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-3xl font-poppins font-extrabold text-[darkblue] text-left">
                            Uploading Documents
                        </h1>
                        <button class="btn" type="submit" :disabled="form.processing">
                            <span class="material-symbols-rounded">outbox</span>
                            {{ form.processing ? 'Submitting...' : 'Submit' }}
                        </button>
                    </div>

                    <!-- Requirements upload section -->
                    <div class="flex flex-wrap w-full gap-1" v-for="requirement in requirements" :key="requirement.id">
                        <div class="border rounded-md shadow flex flex-col w-1/2 p-3 mb-4"
                            v-for="(req, index) in requirement.requirements" :key="index">
                            <!-- Requirement name -->
                            <div class="mb-2">
                                <span class="font-medium text-gray-700">{{ req }}</span>
                            </div>

                            <!-- File input with preview -->
                            <div class="space-y-2">
                                <input
                                    type="file"
                                    @change="(e) => handleFile(e, index, req)"
                                    :id="'file_input_' + index"
                                    class="block w-full text-sm text-blue-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                    dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                />
                                
                                <!-- File preview -->
                                <div v-if="form.files[index]" class="text-sm text-gray-600">
                                    Selected file: {{ form.files[index].name }}
                                    <button
                                        type="button"
                                        @click="() => {
                                            form.files[index] = null;
                                            form.requirement_files = form.requirement_files.filter(r => r.file_index !== index);
                                        }"
                                        class="ml-2 text-red-600 hover:text-red-800"
                                    >
                                        Remove
                                    </button>
                                </div>

                                <!-- Error message -->
                                <div v-if="form.errors[`files.${index}`]" class="text-red-500 text-sm">
                                    {{ form.errors[`files.${index}`] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>