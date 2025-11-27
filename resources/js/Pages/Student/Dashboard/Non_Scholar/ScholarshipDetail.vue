<template>
    <AuthenticatedLayout>
        <div
            class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B] space-y-3 overflow-auto">
            <div class="flex w-full mt-10 my-auto max-w-8xl mx-auto gap-3">
                <div class="w-3/4 p-4 flex flex-col space-y-4"> <!-- 75% width -->
                    <div>
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-white text-primary rounded-md shadow-md hover:bg-gray-100 transition">
                            <span class="material-symbols-rounded text-lg">arrow_back_ios</span>
                            <span>Back</span>
                        </button>
                    </div>
                    <span class="text-4xl font-semibold font-sora">{{ scholarship.name }}</span>
                    <div class="flex flex-col">
                        <span>Funded By</span>
                        <span class="font-albert font-medium text-2xl">{{ sponsor.name }}</span>
                    </div>

                    <div class="flex items-center justify-center bg-white border border-gray-50 p-5">
                        <img :src="`/storage/sponsor/logo/${sponsor.logo}`" alt="logo"
                            class="w-80 h-80 rounded-md object-cover">
                    </div>

                    <div>
                        <div class="rounded-lg bg-white w-full mx-auto">
                            <!-- Tabs -->
                            <div class="flex">
                                <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                                    :class="['flex-1 text-center py-2 px-4 font-semibold', activeTab === tab.key ? 'border border-gray-400 text-blue-600' : 'text-gray-500 hover:text-gray-700']">
                                    {{ tab.label }}
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div class="p-4 border border-gray-400">
                                <div v-if="activeTab === 'eligibility'">
                                    <h2 class="text-lg font-semibold mb-3">Scholarship recipients are selected on the
                                        basis of:</h2>

                                    <!-- Grade criteria -->
                                    <p v-if="props.criterias.find(c => c.grade)">
                                        Student General Weighted Average must be at least
                                        <span class="font-semibold">{{props.criterias.find(c => c.grade).grade
                                        }}</span>
                                    </p>

                                    <!-- Income criteria -->
                                    <p v-if="props.criterias.find(c => c.scholarship_form_data)">
                                        Family income must range between
                                        <span v-for="criteria in criterias" :key="criteria.id" class="font-semibold">
                                            {{ criteria.scholarship_form_data.name }}/
                                        </span>
                                    </p>

                                    <!-- Other eligibility criteria -->
                                    <div v-for="eligible in eligibles" :key="eligible.id">
                                        <p><span class="font-semibold">{{ eligible.condition.name }}</span></p>
                                    </div>
                                </div>
                                <div v-if="activeTab === 'requirements'">
                                    <h2 class="text-lg font-semibold mb-3">Applicant for this scholarship must provide
                                        the following:
                                    </h2>
                                    <div v-for="requirement in requirements" :key="requirement.id">
                                        <p>A copy of <span class="font-semibold">{{ requirement.requirements }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div v-if="activeTab === 'awards'">
                                    <h2 class="text-lg font-semibold">Announcement of qualified applicants will be
                                        available to your Dashboard once announced</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="w-1/4 bg-white flex flex-col gap-4 rounded-lg shadow-md h-fit p-4 border border-gray-50">
                    <!-- 25% width -->
                    <Link v-if="isEligible(scholarship)"
                        :href="`/student/applying-scholarship/${scholarship.id}/application`">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Apply
                        Now</button>
                    </Link>
                    <Link v-else>
                    <button class="bg-gray-400 text-white px-10 py-2 rounded-lg shadow-md cursor-not-allowed">Not
                        Eligible</button>
                    </Link>

                    <div v-if="!isEligible(scholarship)"
                        class="bg-red-100 border-l-4 border-red-500 text-red-900 p-4 shadow-sm">
                        <h2 class="text-lg font-semibold">Reasons you are not Eligible</h2>
                        <ul class="list-disc pl-5 mt-2 text-base">
                            <li v-if="!meetsGradeRequirement(scholarship)">
                                Your {{ props.grade?.grade }} GWA does not meet the required minimum of
                                {{props.criterias.find(c => c.grade)?.grade || 'N/A'}}.
                            </li>
                            <li v-if="!meetsCriteria(scholarship)">
                                Your family income does not match the required range of
                                {{props.criterias.find(c => c.scholarship_form_data)?.scholarship_form_data?.name ||
                                    'N/A'}}.
                            </li>
                            <li v-if="!meetsCampusRequirement(scholarship)">
                                Your course is not eligible for this scholarship.
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">Application Deadline</span>
                        <span class="font-medium text-xl">{{ formattedDate }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-gray-500 text-sm">Scholarship For</span>
                        <div class="font-medium text-lg">
                            <!-- Replace the single line with this loop -->
                            <template v-if="Array.isArray(parsedCourses) && parsedCourses.length">
                                <ul class="list-disc pl-5">
                                    <li v-for="(course, index) in parsedCourses" :key="index">
                                        
                                    </li>
                                </ul>
                            </template>

                            <div v-else>
                                {{ fallbackCourseDisplay }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, watchEffect, watch, computed } from 'vue';

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
    eligibles: {
        type: Object,
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
    scholar: {
        type: Object,
        required: true
    }
});

const parsedCourses = computed(() => {
    try {
        const data = props.selectedCampus[0].selected_campus;
        if (typeof data === 'string') {
            return JSON.parse(data);
        } else if (Array.isArray(data)) {
            return data;
        } else if (typeof data === 'object') {
            return [data];
        }
        return [];
    } catch (e) {
        console.error('Error parsing course data:', e);
        return [];
    }
});

const fallbackCourseDisplay = computed(() => {
    // Fallback if parsing fails
    const data = props.selectedCampus[0].selected_campus;
    if (typeof data === 'string' && data.includes('Bachelor')) {
        return data;
    }
    return 'All Courses';
});

const formattedDate = new Date(props.deadline.date_end).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric"
});

// For grade requirement check
const meetsGradeRequirement = (scholarship) => {
    const gradeCriteria = props.criterias.find(criteria => criteria.grade);
    if (!gradeCriteria) return true;

    const requiredGrade = gradeCriteria.grade;
    const studentGrade = props.grade?.grade;

    if (requiredGrade <= 0) {
        return true
    }

    if (studentGrade === null || studentGrade === undefined) return false;

    return studentGrade <= requiredGrade;
};

const meetsCampusRequirement = (scholarship) => {
    // Check if selectedCampus array exists and has items
    if (!props.selectedCampus || props.selectedCampus.length === 0) {
        return false;
    }

    // Look for a matching campus in the selectedCampus array
    for (const campus of props.selectedCampus) {
        // Skip if campus_id doesn't match
        if (campus.campus_id !== props.scholar.campus_id) {
            continue;
        }

        // Check if selected_campus exists and is not null
        if (!campus.selected_campus) {
            continue;
        }

        // Parse selected_campus if it's a string
        let selectedCampusData;
        try {
            selectedCampusData = typeof campus.selected_campus === 'string' 
                ? JSON.parse(campus.selected_campus) 
                : campus.selected_campus;
        } catch (e) {
            console.error('Error parsing selected_campus:', e);
            continue;
        }

        // Handle if it's an array
        if (Array.isArray(selectedCampusData)) {
            // If array is empty or contains empty string, any course is eligible
            if (selectedCampusData.length === 0 || selectedCampusData.includes('')) {
                return true;
            }

            // Check if scholar's course is in the array
            if (selectedCampusData.some(item => 
                item === props.scholar.course.name || 
                (item.course && item.course === props.scholar.course.name)
            )) {
                return true;
            }
        }
        // Handle if it's a string
        else if (typeof selectedCampusData === 'string') {
            if (selectedCampusData === '' || selectedCampusData === props.scholar.course.name) {
                return true;
            }
        }
    }

    return false;
};

// For income criteria
const meetsCriteria = (scholarship) => {
    if (!props.criterias || props.criterias.length === 0) return true;

    const incomeCriteria = props.criterias.find(criteria =>
        criteria.scholarship_form_data && criteria.scholarship_form_data.name);

    if (!incomeCriteria) return true;

    return props.scholar.monthly_income === incomeCriteria.scholarship_form_data.name;
};

// Overall eligibility check
const isEligible = (scholarship) => {
    return meetsGradeRequirement(scholarship) && meetsCampusRequirement(scholarship) && meetsCriteria(scholarship);
};
</script>