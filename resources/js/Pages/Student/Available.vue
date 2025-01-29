<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Available Scholarships</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-5xl">
      <div
        v-for="scholarship in scholarships"
        :key="scholarship.id"
        class="bg-white shadow-md rounded-lg p-4 text-center border border-gray-200"
      >
        <h2 class="text-xl font-semibold mb-2">{{ scholarship.name }}</h2>
        <p class="text-gray-600 mb-4">{{ scholarship.description }}</p>
        <button
          @click="openApplicationForm(scholarship.id)"
          class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        >
          Apply
        </button>
      </div>
    </div>

    <div v-if="showForm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Apply for Scholarship</h3>
        <form @submit.prevent="submitApplication">
          <div class="mb-4">
            <label class="block text-gray-700">First Name</label>
            <input
              v-model="form.first_name"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-2"
              required
            />
          </div>

          <!-- Last Name -->
          <div class="mb-4">
            <label class="block text-gray-700">Last Name</label>
            <input
              v-model="form.last_name"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-2"
              required
            />
          </div>

          <!-- Email -->
          <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full border border-gray-300 rounded-lg p-2"
              required
            />
          </div>

          <!-- Course -->
          <div class="mb-4">
            <label class="block text-gray-700">Course</label>
            <input
              v-model="form.course"
              type="text"
              class="w-full border border-gray-300 rounded-lg p-2"
              required
            />
          </div>

          <!-- File Upload Section (Only for eligible users) -->
          <div v-if="isEligible" class="mb-4">
            <label class="block text-gray-700">Upload Requirements</label>
            <input
              type="file"
              multiple
              @change="handleFileUpload"
              class="w-full border border-gray-300 rounded-lg p-2"
              required
            />
          </div>

          <!-- Eligibility Message -->
          <div v-if="!isEligible && isEligibilityChecked" class="mb-4 text-red-500">
            You are not eligible for this scholarship.
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end">
            <button
              type="button"
              @click="closeForm"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              :disabled="isEligibilityChecked && !isEligible"
            >
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3"; // Use Inertia's useForm for handling form data

// Props from backend
defineProps({
  scholarships: Array,
});

// Local states
const showForm = ref(false);
const isEligible = ref(false);
const selectedScholarshipId = ref(null);
const files = ref([]);
const isEligibilityChecked = ref(false);

// Form state using useForm
const { data: form, setData, post } = useForm({
  first_name: "",
  last_name: "",
  email: "",
  course: "",
});

// Open Application Form
const openApplicationForm = (scholarshipId) => {
  showForm.value = true;
  selectedScholarshipId.value = scholarshipId;
};

// Close Application Form
const closeForm = () => {
  showForm.value = false;
  setData({
    first_name: "",
    last_name: "",
    email: "",
    course: "",
  });
  files.value = [];
  isEligible.value = false;
  isEligibilityChecked.value = false;
};

// Handle File Upload
const handleFileUpload = (event) => {
  files.value = event.target.files;
};

// Check Eligibility using Inertia POST request
const checkEligibility = async () => {
  // Send a POST request to check eligibility
  const response = await post("/check-eligibility", {
    first_name: form.first_name,
    last_name: form.last_name,
    email: form.email,
    course: form.course,
  });

  isEligibilityChecked.value = true; // Mark eligibility as checked
  isEligible.value = response.props.isEligible; // Set eligibility based on response
};

// Submit Application
const submitApplication = async () => {
  // First, check eligibility
  await checkEligibility();

  if (isEligible.value) {
    // Prepare FormData
    const formData = new FormData();
    formData.append("first_name", form.first_name);
    formData.append("last_name", form.last_name);
    formData.append("email", form.email);
    formData.append("course", form.course);
    formData.append("scholarship_id", selectedScholarshipId.value);

    // Append files
    Array.from(files.value).forEach((file, index) => {
      formData.append(`files[${index}]`, file);
    });

    // Submit application via Inertia POST
    await post("/apply", formData, {
      onSuccess: () => {
        alert("Application submitted successfully!");
        closeForm();
      },
      onError: (errors) => {
        console.error(errors);
      },
    });
  } else {
    alert("You are not eligible for this scholarship.");
  }
};
</script>
