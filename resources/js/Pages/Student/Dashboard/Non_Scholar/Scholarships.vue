<template>
    <div class="flex flex-col text-center space-y-8 items-center justify-start h-full">
        <!-- Header content - keeping the same -->
        <div class="text-left flex flex-col space-y-5">
            <span
                class="bg-gradient-to-r from-[#0D3B80] to-[#296fd6] bg-clip-text text-transparent font-sora text-2xl font-bold">Find
                Available and Ongoing Scholarships, Check Eligibility, and Apply <br></span>
            <p class="text-lg text-primary max-w-4xl font-medium font-albert text-center">Browse
                Scholarship
                Programs offered by the Nation's Government and Local Governments.
                Have a Financial Assistance Grant and aid your tuition fees and school fees</p>
        </div>

        <!-- Search box - keeping the same -->
        <div class="flex items-center border rounded-md overflow-hidden shadow-sm w-8/12">
            <span class="bg-white px-3 py-2 border-r flex items-center">
                <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="text-blue-500 text-lg" />
            </span>
            <input type="text" placeholder="Search..." class="w-full px-4 py-2 border-none focus:ring-0" />
        </div>

        <div class="w-full h-[1px] bg-gray-200"></div>

        <div class="w-full flex flex-col items-center space-y-4">
            <!-- Check if scholarships exist -->
            <template v-if="scholarships && scholarships.length > 0">
                <div v-if="grade">
                    <div v-for="scholarship in scholarships" :key="scholarship.id"
                        class="p-6 w-full min-w-xl bg-white shadow-lg rounded-lg mb-4">
                        <div v-if="scholarship.status == 'Active'">
                            <div class="flex flex-row items-center gap-6 justify-between">
                                <!-- Scholarship Image -->
                                <img :src="`/storage/sponsor/logo/${getSponsorDetails(scholarship.sponsor_id).logo}`"
                                    alt="logo" class="w-40 h-40 rounded-lg object-cover">

                                <!-- Scholarship Details -->
                                <div class="flex flex-col flex-grow space-y-1 items-start">
                                    <span class="font-semibold text-2xl text-gray-800">{{ scholarship.name }}</span>
                                    <span class="text-sm text-gray-600 space-x-2">
                                        Funded by <span class="font-medium text-gray-800">{{
                                            getSponsorDetails(scholarship.sponsor_id).name }}</span>
                                        <span class="text-gray-500">Since <span class="font-medium text-gray-800">{{
                                            getSponsorDetails(scholarship.sponsor_id).since }}</span></span>
                                    </span>
                                    <p class="text-sm text-gray-700 leading-relaxed mt-2">
                                        {{ getSponsorDetails(scholarship.sponsor_id).description }}
                                    </p>

                                    <!-- Scholarship Info -->
                                    <div class="flex gap-6 mt-5">
                                        <div class="flex flex-col items-start">
                                            <span class="text-gray-500 text-sm">Scholarship for</span>
                                            <span class="font-medium text-gray-800">All Courses</span>
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="text-gray-500 text-sm">Application</span>
                                            <span class="font-medium text-green-600">Ongoing</span>
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="text-gray-500 text-sm">Deadline</span>
                                            <span class="font-medium text-red-500">{{ new Date(scholarship.requirements[0].date_end).toLocaleDateString('en-US', {
                                                    year:
                                                        'numeric', month: 'long', day: 'numeric'
                                                }) }}</span>
                                        </div>
                                        <!-- Grade requirement display -->
                                        <div class="flex flex-col items-start">
                                            <span class="text-gray-500 text-sm">Min. Grade Required</span>
                                            <span class="font-medium"
                                                :class="meetsGradeRequirement(scholarship) ? 'text-green-600' : 'text-red-500'">
                                                {{ getRequiredGrade(scholarship) }}
                                            </span>
                                        </div>
                                        <!-- Campus requirement display -->
                                        <div class="flex flex-col items-start">
                                            <span class="text-gray-500 text-sm">Campus Eligibility</span>
                                            <span class="font-medium"
                                                :class="meetsCampusRequirement(scholarship) ? 'text-green-600' : 'text-red-500'">
                                                {{ getCampusEligibilityLabel(scholarship) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vertical Divider -->
                                <div class="w-[10px] h-full bg-gray-400 mx-6"></div>

                                <!-- Apply Button -->
                                <div class="flex h-full items-center justify-center">
                                    <Link v-if="isEligible(scholarship)"
                                        :href="`/student/applying-scholarship/${scholarship.id}`">
                                    <button
                                        class="bg-primary text-white px-10 py-2 rounded-lg shadow-md hover:bg-primary-dark transition duration-200">
                                        Apply Now
                                    </button>
                                    </Link>
                                    <button v-else
                                        class="bg-gray-400 text-white px-10 py-2 rounded-lg shadow-md cursor-not-allowed">
                                        Not Eligible
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else
                    class="max-w-4xl flex flex-col items-center justify-center p-10 text-center bg-white border border-gray-200 rounded-xl shadow-md">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l-2-2m0 0l-2-2m2 2h8m4 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-lg font-semibold text-gray-700">Need to upload grade</span>
                    <p class="text-gray-500 text-sm mt-2">Upload your grade to check scholarship eligibility.</p>
                </div>
            </template>

            <!-- If No Scholarships Available -->
            <div v-else
                class="max-w-4xl flex flex-col items-center justify-center p-10 text-center bg-white border border-gray-200 rounded-xl shadow-md">
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 14l-2-2m0 0l-2-2m2 2h8m4 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-lg font-semibold text-gray-700">No scholarships available</span>
                <p class="text-gray-500 text-sm mt-2">Check back later for new opportunities.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    //For scholars only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,

    //For non-scholars only
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    },
    schoolyears: {
        type: Array,
        required: true
    },
    campuses: {
        type: Array,
        required: false,
        default: () => []
    },
    courses: {
        type: Array,
        required: false,
        default: () => []
    },
    applicant: Object,
    grade: Object,
});

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};

// Get the required grade for a scholarship
const getRequiredGrade = (scholarship) => {
    if (scholarship.criteriaData && scholarship.criteriaData.grade) {
        return scholarship.criteriaData.grade;
    }
    return 'None';
};

// Check if student meets grade requirements
const meetsGradeRequirement = (scholarship) => {
    // If no grade criteria is set, student is eligible
    if (!scholarship.criteriaData || !scholarship.criteriaData.grade) {
        return true;
    }

    // If no student grade available, not eligible
    if (!props.grade || !props.grade.grade) {
        return false;
    }

    // Compare student grade with required grade
    const studentGrade = parseFloat(props.grade.grade);
    const requiredGrade = parseFloat(scholarship.criteriaData.grade);

    return studentGrade <= requiredGrade;
};

// Check if student's campus is eligible
const meetsCampusRequirement = (scholarship) => {
    // If student has no campus/course info, not eligible
    if (props.scholar.campus_id === scholarship.campusRecipients[0].campus_id) {

        for (const recipient of scholarship.campusRecipients) {
            if (recipient.selected_campus.includes(props.scholar.course.name)) {
                console.log('meron siya');
                return true;
            }
        }
    }

    // Check if student's campus is in any of the scholarship's campus recipients
    // for (const recipient of scholarship.campusRecipients) {
    //     if (recipient.selected_campus.includes('Bachelor of Science in Information Technology')) {
    //         console.log('meron siya');
    //         return true;
    //     }
    // }

    return false;
};

// Get campus eligibility label
const getCampusEligibilityLabel = (scholarship) => {
    if (!scholarship.campusRecipients || scholarship.campusRecipients.length === 0) {
        return 'All Campuses';
    }

    if (meetsCampusRequirement(scholarship)) {
        return 'Eligible';
    }
    else {
        if (!props.scholar || !props.scholar.campus_id) {
            return 'Campus Info Missing';
        }

        return 'Not Eligible';
    }
};

// Overall eligibility check
const isEligible = (scholarship) => {
    return meetsGradeRequirement(scholarship) && meetsCampusRequirement(scholarship);
};
</script>