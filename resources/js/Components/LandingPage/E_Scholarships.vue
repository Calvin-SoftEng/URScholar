<template>


    <section class="relative py-16 px-6 sm:px-3 min-h-screen items-center bg-white">
        <div class="flex flex-col text-center space-y-8 items-center justify-start h-full">
            <!-- Header Section -->
            <div class="sm:text-left lg:text-center flex flex-col space-y-5">
                <span
                    class="bg-gradient-to-r from-[#0D3B80] to-[#296fd6] bg-clip-text text-transparent font-sora sm:text-lg lg:text-2xl font-bold">
                    Find Available and Ongoing Scholarships, Check Eligibility, and Apply
                </span>
                <p class="text-base sm:text-sm text-primary max-w-4xl font-medium font-albert sm:text-left lg:text-center">
                    Browse Scholarship Programs offered by the Nation’s Government and Local Governments.
                    Have a Financial Assistance Grant and aid your tuition fees and school fees.
                </p>
            </div>

            <div class="w-full h-[1px] bg-gray-200"></div>

            <!-- Scholarships List -->
            <div class="w-full flex flex-col items-center space-y-4">
                <template v-if="scholarships.length > 0">
                    <div v-for="scholarship in scholarships" :key="scholarship.id"
                        class="p-4 sm:p-6 w-full max-w-7xl bg-white">
                        <div v-if="scholarship.status === 'Active'"
                            class="flex flex-col md:flex-row gap-6 items-center justify-between bg-white p-4 sm:p-6 rounded-lg shadow-md">
                            
                            <!-- Scholarship Image -->
                            <img :src="`/storage/sponsor/logo/${getSponsorDetails(scholarship.sponsor_id).logo}`"
                                alt="logo" class="sm:w-40 sm:h-40 rounded-lg object-cover flex-shrink-0" />

                            <!-- Scholarship Details -->
                            <div class="flex flex-col flex-grow space-y-2 lg:items-start sm:text-center lg:text-left">
                                <span class="font-semibold text-xl sm:text-2xl sm:text-center sm:justify-center text-gray-800">{{ scholarship.name }}</span>
                                <span class="text-sm text-gray-600">
                                    Funded by <span class="font-medium text-gray-800">{{
                                        getSponsorDetails(scholarship.sponsor_id).name }}</span>
                                    <span class="text-gray-500"> Since <span class="font-medium text-gray-800">
                                        {{ getSponsorDetails(scholarship.sponsor_id).since }}</span>
                                    </span>
                                </span>
                                <p class="text-sm text-gray-700 leading-relaxed pt-2 sm:pt-4 lg:text-left">
                                    {{ getSponsorDetails(scholarship.sponsor_id).description }}
                                </p>

                                <!-- Scholarship Info -->
                                <div
                                    class="flex flex-col sm:flex-col lg:flex-row lg:flex-wrap gap-6 pt-4 sm:items-center sm:text-center lg:text-left lg:items-start">

                                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                                        <span class="text-gray-500 text-sm">Scholarship for</span>
                                        <span
                                        class="font-medium text-gray-800 truncate overflow-hidden whitespace-nowrap max-w-[200px]">
                                        All Courses, All Courses, All Courses, All Courses
                                        </span>
                                    </div>

                                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                                        <span class="text-gray-500 text-sm">Application</span>
                                        <span class="font-medium text-green-600">Ongoing</span>
                                    </div>

                                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                                        <span class="text-gray-500 text-sm">Deadline</span>
                                        <span class="font-medium text-red-500">April 29, 2025</span>
                                    </div>
                                </div>


                            </div>

                            <!-- Divider (Hide on small screens) -->
                            <div class="hidden md:block w-[10px] h-full bg-gray-400 mx-6"></div>

                            <!-- Apply Button -->
                            <div class="w-full md:w-auto flex justify-center md:justify-end">
                                <Link :href="`/applying-scholarship/${scholarship.id}`">
                                    <button
                                        class="w-full md:w-auto text-sm sm:text-base whitespace-nowrap bg-primary text-white px-6 py-2 rounded-lg shadow-md hover:bg-primary-dark transition duration-200">
                                        View More
                                    </button>
                                </Link>
                            </div>
                        </div>

                        <!-- Inactive Scholarship -->
                        <div v-else
                            class="flex flex-col items-center justify-center p-6 text-center bg-white border border-gray-200 rounded-xl shadow-md">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l-2-2m0 0l-2-2m2 2h8m4 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-700">No scholarships available</span>
                            <p class="text-gray-500 text-sm mt-2">Check back later for new opportunities.</p>
                        </div>
                    </div>
                </template>

                <!-- No Scholarships at All -->
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
    </section>


</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
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
    }
});

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};

const toggleSpecification = (Scholarship) => {
    ScholarshipSpecification.value = !ScholarshipSpecification.value;

    if (ScholarshipSpecification.value) {
        selectedScholarship.value = Scholarship;
    }
};

const openScholarship = () => {

    router.visit(`/scholarships/${selectedScholarship.value.id}`, {
        preserveState: true
    });
};

</script>