<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full flex flex-col py-5 px-6 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-100 scrollbar-thumb-rounded">
            <div class="w-full mx-auto space-y-3">
                <div class="breadcrumbs text-sm text-gray-400 mb-2">
                    <ul>
                        <li class="hover:text-gray-600">Home</li>
                        <li class="hover:text-gray-600">
                            <span>Scholarships</span>
                        </li>
                        <li>
                            <span class="text-blue-400 font-semibold">Create Scholarship</span>
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <div class="text-3xl font-semibold text-gray-700">
                        <h1
                            class="text-4xl font-kanit uppercase font-extrabold text-[darkblue] dark:text-dtext text-left">
                            <span class="mr-2 font-kanit font-bold text-blue-400 tracking-[-.1rem]">\\</span>
                            <span>Create New Scholarship</span>
                        </h1>
                    </div>
                </div>

                <form @submit.prevent="submitForm">
                    <div class="w-full block bg-white p-5 rounded-lg shadow-sm mt-4">
                        <div class="text-xl font-semibold mb-4">Scholarship Information</div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="flex flex-col space-y-2">
                                <label for="name" class="text-sm font-medium text-gray-700">Scholarship Name</label>
                                <input id="name" v-model="form.name" type="text" placeholder="Scholarship Name"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none" />
                                <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <label for="type" class="text-sm font-medium text-gray-700">Scholarship Type</label>
                                <select id="type" v-model="form.scholarshipType"
                                    class="w-full h-[43px] px-4 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
                                    <option value="">Select Type</option>
                                    <option value="Need-Based">Need-Based</option>
                                    <option value="One-Time">One-Time</option>
                                    <option value="Merit-Based">Merit-Based</option>
                                </select>
                                <div v-if="errors.scholarshipType" class="text-red-500 text-sm mt-1">{{
                                    errors.scholarshipType }}</div>
                            </div>
                        </div>

                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <!-- Total Recipients Input -->
                        <div class="mb-4">
                            <label for="totalRecipients" class="text-sm font-medium text-gray-700">
                                Number of Recipients
                            </label>
                            <input id="totalRecipients" type="number" v-model="form.totalRecipients" min="1"
                                placeholder="Enter total recipients"
                                class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded"
                                @input="distributeRecipients" />
                            <div v-if="errors.totalRecipients" class="text-red-500 text-sm mt-1">{{
                                errors.totalRecipients }}</div>
                        </div>

                        <!-- Campus Selection & Recipient Distribution -->
                        <div class="grid grid-cols-2 w-full gap-6 mb-4">
                            <!-- Left Side: Campus Selection & Recipient Distribution -->
                            <div class="flex flex-col space-y-3">
                                <!-- Header with Label & Stats -->
                                <div class="flex flex-row justify-between items-center py-2">
                                    <div class="flex flex-col space-y-2">
                                        <label class="text-sm font-medium">Distribute Recipients per Selected
                                            Campus</label>
                                        <div class="flex flex-row text-sm gap-4">
                                            <div>Allocated: {{ allocatedRecipients }} of {{ form.totalRecipients }}
                                            </div>
                                            <div v-if="allocatedRecipients !== parseInt(form.totalRecipients)"
                                                class="text-red-500 font-medium">
                                                *{{ parseInt(form.totalRecipients) - allocatedRecipients }} recipients
                                                still need to be allocated
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col text-sm">
                                        <!-- Stats & Reset Button -->
                                        <div class="flex items-center text-sm text-gray-600 space-x-3">
                                            <!-- Reset to Auto-Distribution Button -->
                                            <button type="button" @click="distributeRecipients"
                                                class="px-2 text-gray-700 flex items-center space-x-1 hover:text-blue-600">
                                                <span class="material-symbols-rounded text-base">restart_alt</span>
                                                <span>Reset to Auto Distribution</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campus Selection List -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="campus in campusesData" :key="campus.id"
                                        class="flex flex-row items-center space-x-2">
                                        <!-- Checkbox to select campus -->
                                        <input type="checkbox" :id="`campus-${campus.id}`" v-model="campus.selected"
                                            @change="distributeRecipients" class="rounded" />
                                        <label :for="`campus-${campus.id}`"
                                            class="text-sm font-medium leading-none cursor-pointer">
                                            {{ campus.name }}
                                        </label>

                                        <!-- Recipients per campus input -->
                                        <input type="number" v-model="campus.recipients" :readonly="!campus.selected"
                                            :class="`w-20 px-2 py-1 border rounded-md text-center ${!campus.selected ? 'bg-gray-100 border-gray-300' : 'bg-white border-blue-300'
                                                }`" min="0" :max="form.totalRecipients" @input="onRecipientManualChange(campus.id)" />
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Course List Display Grouped by Campus -->
                            <div class="flex flex-col space-y-4">
                                <div v-for="campus in selectedCampuses" :key="campus.id"
                                    class="py-3 px-4 bg-gray-50 border rounded-md">
                                    <!-- Selected Campus Name -->
                                    <div class="text-sm font-semibold text-gray-700 mb-2">
                                        {{ campus.name }}
                                    </div>

                                    <!-- Courses Offered for this Campus -->
                                    <div v-for="course in campus.courses" :key="course" class="mt-1">
                                        <input type="checkbox" :id="`course-${campus.id}-${course}`"
                                            v-model="selectedCoursesMap[course]" class="rounded" />
                                        <label :for="`course-${campus.id}-${course}`"
                                            class="text-sm ml-2 cursor-pointer">{{ course }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full border-t border-gray-200 my-4"></div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="w-full">
                                <label for="criteria" class="text-sm font-medium text-gray-700">
                                    List Criteria and Eligibility
                                </label>
                                <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center mb-4 w-full">
                                        <div class="flex items-center w-full">
                                            <input v-model="newReq" type="text" placeholder="Enter an item"
                                                class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                            <button type="button" @click="addReq"
                                                class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                Add
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <div v-for="(req, index) in form.reqs" :key="index"
                                            class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                            <span>{{ req }}</span>
                                            <button type="button" @click="removeReq(index)"
                                                class="flex items-center text-red-500 hover:text-red-700">
                                                <span class="material-symbols-rounded text-red-600">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </ul>
                            </div>

                            <div class="w-full">
                                <label for="requirements" class="text-sm font-medium text-gray-700">
                                    List Requirements
                                </label>
                                <ul class="w-full text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center mb-4 w-full">
                                        <div class="flex items-center w-full">
                                            <input v-model="newCriteria" type="text" placeholder="Enter an item"
                                                class="border border-gray-300 rounded-lg px-4 py-2 flex-grow dark:bg-dsecondary" />
                                            <button type="button" @click="addCriteria"
                                                class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-lg hover:bg-blue-600">
                                                Add
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <div v-for="(criterion, index) in form.criteria" :key="index"
                                            class="flex items-center justify-between text-base bg-gray-100 px-4 py-2 mb-1 rounded-lg dark:bg-primary">
                                            <span>{{ criterion }}</span>
                                            <button type="button" @click="removeCriteria(index)"
                                                class="flex items-center text-red-500 hover:text-red-700">
                                                <span class="material-symbols-rounded text-red-600">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>

                        <!-- Scholarship amount for One-Time scholarships -->
                        <div v-if="form.scholarshipType === 'One-Time'" class="mt-6">
                            <div class="w-full border-t border-gray-200 my-4"></div>
                            <label for="amount" class="text-sm font-medium text-gray-700">Scholarship Amount
                                (PHP)</label>
                            <input id="amount" v-model="form.amount" type="number" min="0"
                                placeholder="Enter scholarship amount"
                                class="w-full h-10 bg-gray-50 border border-gray-300 px-4 py-2 mt-1 rounded" />
                            <div v-if="errors.amount" class="text-red-500 text-sm mt-1">{{ errors.amount }}</div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" @click="cancel"
                                class="px-4 py-2 mr-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" :disabled="isSubmitting"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                {{ isSubmitting ? 'Creating...' : 'Create Scholarship' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <ToastProvider>
            <ToastRoot v-if="toastVisible"
                class="fixed bottom-4 right-4 bg-primary text-white px-5 py-3 mb-5 mr-5 rounded-lg shadow-lg dark:bg-primary dark:text-dtext dark:border-gray-200 z-50 max-w-xs w-full">
                <ToastTitle class="font-semibold dark:text-dtext">{{ toastTitle }}</ToastTitle>
                <ToastDescription class="text-gray-100 dark:text-dtext">{{ toastMessage }}</ToastDescription>
            </ToastRoot>

            <ToastViewport class="fixed bottom-4 right-4" />
        </ToastProvider>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'radix-vue';

const props = defineProps({
    campuses: Array,
    courses: Array,
    schoolyear: Object,
    selectedSem: String,
});

// Form data
const form = ref({
    name: '',
    scholarshipType: '',
    totalRecipients: 40,
    reqs: [],
    criteria: [],
    amount: 0,
});

const errors = ref({});
const isSubmitting = ref(false);
const toastVisible = ref(false);
const toastMessage = ref('');
const toastTitle = ref('');

// Create reactive campus array from props with selection state
const campusesData = ref([]);

// Initialize campus data from props
onMounted(() => {
    // Transform props.campuses into the format we need
    if (props.campuses && props.campuses.length > 0) {
        campusesData.value = props.campuses.map(campus => ({
            id: campus.id,
            name: campus.name,
            selected: false,
            recipients: 0,
            // Get courses associated with this campus
            courses: props.courses
                ? props.courses.filter(course => course.campus_id === campus.id).map(course => course.name)
                : [],
        }));
    }
    // Initial distribution
    distributeRecipients();
});

// Compute selected campuses dynamically
const selectedCampuses = computed(() => campusesData.value.filter(campus => campus.selected));

// Calculate total allocated recipients
const allocatedRecipients = computed(() => {
    return campusesData.value.reduce((sum, campus) => sum + parseInt(campus.recipients || 0), 0);
});

// Function to distribute recipients equally when checking/unchecking a campus
const distributeRecipients = () => {
    const selectedCount = selectedCampuses.value.length;

    if (selectedCount === 0) {
        campusesData.value.forEach(campus => (campus.recipients = 0));
        return;
    }

    const share = Math.floor(form.value.totalRecipients / selectedCount);
    const remainder = form.value.totalRecipients % selectedCount;

    campusesData.value.forEach(campus => {
        if (!campus.selected) {
            campus.recipients = 0;
            return;
        }

        // Find the index in the selected campuses array
        const index = selectedCampuses.value.findIndex(c => c.id === campus.id);
        campus.recipients = share + (index < remainder ? 1 : 0);
    });
};

// Handle manual change to a campus's recipients
const onRecipientManualChange = (changedCampusId) => {
    const changedCampus = campusesData.value.find(c => c.id === changedCampusId);

    // Ensure value is a valid number and not less than 0
    changedCampus.recipients = Math.max(0, parseInt(changedCampus.recipients) || 0);

    // If changing this would exceed total, cap it
    if (allocatedRecipients.value > form.value.totalRecipients) {
        changedCampus.recipients = Math.max(
            0,
            parseInt(changedCampus.recipients) - (allocatedRecipients.value - form.value.totalRecipients)
        );
    }
};

// Watch total recipients and automatically redistribute
watch(() => form.value.totalRecipients, distributeRecipients);

// Store selected courses
const selectedCoursesMap = ref({});

// Compute selected courses dynamically based on checked campuses
const selectedCourses = computed(() => {
    let courses = [];
    campusesData.value.forEach(campus => {
        if (campus.selected) {
            courses = [...new Set([...courses, ...campus.courses])]; // Remove duplicates
        }
    });

    // Sync the selected courses in the map
    selectedCoursesMap.value = courses.reduce((acc, course) => {
        acc[course] = selectedCoursesMap.value[course] || false;
        return acc;
    }, {});

    return courses;
});

// New criteria and requirements
const newReq = ref('');
const newCriteria = ref('');

const addReq = () => {
    if (newReq.value.trim() !== '') {
        form.value.reqs.push(newReq.value.trim());
        newReq.value = ''; // Clear input after adding
    }
};

const addCriteria = () => {
    if (newCriteria.value.trim() !== '') {
        form.value.criteria.push(newCriteria.value.trim());
        newCriteria.value = ''; // Clear input after adding
    }
};

// Separate remove functions
const removeReq = (index) => {
    form.value.reqs.splice(index, 1);
};

const removeCriteria = (index) => {
    form.value.criteria.splice(index, 1);
};

// Form submission
const submitForm = () => {
    isSubmitting.value = true;
    errors.value = {};

    // Validate form
    if (!form.value.name) {
        errors.value.name = 'Scholarship name is required';
    }

    if (!form.value.scholarshipType) {
        errors.value.scholarshipType = 'Scholarship type is required';
    }

    if (!form.value.totalRecipients || form.value.totalRecipients <= 0) {
        errors.value.totalRecipients = 'Number of recipients must be greater than zero';
    }

    if (form.value.scholarshipType === 'One-Time' && (!form.value.amount || form.value.amount <= 0)) {
        errors.value.amount = 'Scholarship amount is required for one-time scholarships';
    }

    // Check if all campus allocations match the total
    if (allocatedRecipients.value !== parseInt(form.value.totalRecipients)) {
        errors.value.totalRecipients = 'All recipients must be allocated to campuses';
    }

    // Check if at least one campus is selected
    if (selectedCampuses.value.length === 0) {
        errors.value.campus = 'At least one campus must be selected';
        // Show error as toast since there's no specific field
        showToast('Validation Error', 'At least one campus must be selected');
    }

    if (Object.keys(errors.value).length > 0) {
        isSubmitting.value = false;
        return;
    }

    // Prepare campus recipients data for the backend
    const campusRecipients = selectedCampuses.value.map(campus => ({
        campus_id: campus.id,
        slots: parseInt(campus.recipients),
        remaining_slots: parseInt(campus.recipients),
        selected_campus: JSON.stringify(
            campus.courses
                .filter(course => selectedCoursesMap.value[course])
                .map(course => ({ course }))
        ),
    }));

    // Create the payload
    const payload = {
        name: form.value.name,
        scholarship_type: form.value.scholarshipType,
        total_recipients: form.value.totalRecipients,
        requirements: form.value.reqs,
        criteria: form.value.criteria,
        amount: form.value.scholarshipType === 'One-Time' ? form.value.amount : null,
        campus_recipients: campusRecipients,
        school_year_id: props.schoolyear.id,
        semester: props.selectedSem,
    };

    // Submit the form to the backend
    router.post('/scholarships', payload, {
        onSuccess: () => {
            showToast('Success', 'Scholarship created successfully');
            setTimeout(() => {
                router.visit('/scholarships');
            }, 1500);
        },
        onError: (errors) => {
            showToast('Error', 'There was an error creating the scholarship');
            errors.value = errors;
            isSubmitting.value = false;
        },
    });
};

const showToast = (title, message) => {
    toastTitle.value = title;
    toastMessage.value = message;
    toastVisible.value = true;

    setTimeout(() => {
        toastVisible.value = false;
    }, 3000);
};

const cancel = () => {
    router.visit('/scholarships');
};
</script>