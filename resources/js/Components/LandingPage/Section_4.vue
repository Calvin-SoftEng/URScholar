<template>
    <section class="relative py-16 px-40 min-h-screen items-center bg-white">

        <div class="flex flex-col text-center space-y-8 items-center justify-start h-full">
            <div class="text-left flex flex-col space-y-5">
                <span
                    class="bg-gradient-to-r from-[#0D3B80] to-[#296fd6] bg-clip-text text-transparent font-sora text-2xl font-bold">Find
                    Available and Ongoing Scholarships, Check Eligibility, and Apply <br></span>
                <p class="text-lg text-primary max-w-4xl font-medium font-albert text-center">Browse Scholarship
                    Programs offered by the Nation’s Government and Local Governments.
                    Have a Financial Assistance Grant and aid your tuition fees and school fees</p>
            </div>

            <div class="flex items-center border rounded-md overflow-hidden shadow-sm w-8/12">
                <span class="bg-white px-3 py-2 border-r flex items-center">
                    <font-awesome-icon :icon="['fas', 'magnifying-glass']" class="text-blue-500 text-lg" />
                </span>

                <input type="text" placeholder="Search..." class="w-full px-4 py-2 border-none focus:ring-0" />
            </div>


            <div class="w-full h-[1px] bg-gray-200"></div>

            <div class="flex flex-col space-y-4">
                <div  v-for="scholarship in scholarships" :key="scholarship.id" class="p-4 bg-white border border-gray-200 rounded-xl shadow-md">
                    <div class="flex text-left gap-4">
                        <!-- Scholarship Image -->
                        <img :src="`/storage/sponsor/logo/${getSponsorDetails(scholarship.sponsor_id).logo}`" alt="logo" class="w-16 h-16 rounded-md object-cover">

                        <!-- Scholarship Details -->
                        <div class="flex flex-col flex-grow space-y-2">
                            <span class="font-semibold text-lg">{{scholarship.name}}</span>
                            <span class="text-sm text-gray-600">Funded by {{ getSponsorDetails(scholarship.sponsor_id).name }} <span class="font-medium">Since {{ getSponsorDetails(scholarship.sponsor_id).since }}</span></span>
                            <p class="text-sm text-gray-700">
                                {{ getSponsorDetails(scholarship.sponsor_id).description }}
                            </p>

                            <!-- Scholarship Info -->
                            <div class="flex gap-6 mt-2">
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-sm">Scholarship for</span>
                                    <span class="font-medium">All Courses</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-sm">Application</span>
                                    <span class="font-medium text-green-600">Ongoing</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500 text-sm">Deadline</span>
                                    <span class="font-medium text-red-500">Bukas na</span>
                                </div>
                            </div>
                        </div>

                        <!-- Vertical Separator -->
                        <div class="w-[1px] bg-gray-300 self-stretch"></div>

                        <!-- Apply Button -->
                        <div class="flex items-center justify-center">
                            <Link :href="route('landing_page.schoalrship_apply_details')">
                            <button
                                class="bg-primary text-white px-10 py-2 rounded-lg shadow hover:bg-primary-dark transition whitespace-nowrap">
                                Apply Now
                            </button>
                            </Link>
                        </div>
                    </div>
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

</script>