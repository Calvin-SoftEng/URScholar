<template>
    <AuthenticatedLayout>
        <div class="flex flex-col items-center max-w-5xl mx-auto p-5 rounded-xl">
            <h1 class="text-3xl font-bold">Available Scholarships</h1>
            <div class="container mx-auto py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="scholarship in scholarships" :key="scholarship.id" class="p-4 border rounded shadow">
                        <h2 class="text-xl font-semibold">{{ scholarship.name }}</h2>
                        <p>{{ scholarship.description }}</p>
                        <button @click="openApplyForm(scholarship)"
                            class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">
                            Apply
                        </button>
                    </div>
                </div>

                <div v-if="showForm" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded shadow w-96">
                        <h2 class="text-xl font-bold">Apply for {{ scholarships.find(s => s.id ===
                            form.scholarship_id)?.name }}
                        </h2>
                        <form @submit.prevent="submitApplication">
                            <div class="mb-4">
                                <label class="block mb-2">First Name</label>
                                <input v-model="form.first_name" type="text" class="border px-3 py-2 w-full" required />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2">Last Name</label>
                                <input v-model="form.last_name" type="text" class="border px-3 py-2 w-full" required />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2">Course</label>
                                <input v-model="form.course" type="text" class="border px-3 py-2 w-full" required />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2">Email</label>
                                <input v-model="form.email" type="email" class="border px-3 py-2 w-full" disabled />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2">Upload File</label>
                                <input type="file" @change="handleFileUpload" class="border px-3 py-2 w-full"
                                    required />
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="showForm = false"
                                    class="bg-gray-400 px-4 py-2 rounded text-white mr-2">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 px-4 py-2 rounded text-white">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const page = usePage();

// Scholarships and User Email from Props
const scholarships = page.props.scholarships || [];
const email = page.props.userEmail;

// Modal and form data
const showForm = ref(false);
const selectedScholarship = ref(null);
const form = useForm({
    first_name: '',
    last_name: '',
    email: page.props.userEmail || '', // Set default email from user
    course: '',
    file: null,
    scholarship_id: null, // Placeholder for scholarship ID
});
// Open the form modal
const openApplyForm = (scholarship) => {
    form.scholarship_id = scholarship.id; // Assign selected scholarship's ID
    showForm.value = true; // Open the form modal
};

// Close the form modal
const closeForm = () => {
    showForm.value = false;
    form.value = { first_name: '', last_name: '', email: '', course: '', file: null };
};

// Handle file input
const handleFileUpload = (event) => {
    form.file = event.target.files[0];
};


// Submit the application
const submitApplication = () => {
    form.post('/applications', {
        onSuccess: () => {
            alert('Application submitted successfully!');
            form.reset(); // Clear the form after submission
            showForm.value = false; // Close the modal
        },
        onError: (errors) => {
            alert('Submission failed. Please check the errors and try again.');
            console.error(errors); // Log validation errors
        },
    });
};
</script>
