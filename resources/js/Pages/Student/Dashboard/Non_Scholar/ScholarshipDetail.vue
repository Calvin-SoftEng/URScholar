<template>
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
                            <h2 class="text-lg font-semibold">Applicant for this scholarship must:</h2>
                            <p>Details about the eligibility criteria go here.</p>
                            <p>Grade: {{ grade.grade }}</p>
                            <div v-for="criteria in criterias" :key="criteria.id">

                                <h3 class="text-lg font-semibold">{{ criteria.scholarship_form_data.name }}</h3>
                            </div>
                        </div>
                        <div v-if="activeTab === 'requirements'">
                            <h2 class="text-lg font-semibold">Scholarship recipients are selected on the basis of:
                            </h2>
                            <p>Details about the required documents go here.</p>
                            <div v-for="requirement in requirements" :key="requirement.id">

                                <h3 class="text-lg font-semibold">{{ requirement.requirements }}</h3>
                            </div>
                        </div>
                        <div v-if="activeTab === 'awards'">
                            <h2 class="text-lg font-semibold">As part of your application, you must upload the
                                following:</h2>
                            <p>Details about the awards and benefits go here.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-1/4 bg-white flex flex-col gap-4 rounded-lg shadow-md h-fit p-4 border border-gray-50">
            <!-- 25% width -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Apply
                Now</button>
            <div class="flex flex-col">
                <span class="text-gray-500 text-sm">Application Deadline</span>
                <span class="font-medium text-xl">{{ formattedDate }}</span>
            </div>
            <div class="flex flex-col">
                <span class="text-gray-500 text-sm">Scholarship For</span>
                <div class="font-medium text-xl">
                    <!-- Replace the single line with this loop -->
                    <template v-if="Array.isArray(parsedCourses) && parsedCourses.length">
                        <div v-for="(course, index) in parsedCourses" :key="index">
                            {{ course.course }}
                        </div>
                    </template>
                    <div v-else>
                        {{ fallbackCourseDisplay }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
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
    }
});

const getSponsorDetails = (sponsorId) => {
    return props.sponsors.find(s => s.id === sponsorId) || { name: 'Unknown Sponsor' };
};

</script>