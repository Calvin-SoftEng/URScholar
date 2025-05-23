<template>
    <div class="bg-white">
        <FloatingNav :branding="branding" />

        <div class="flex w-full mt-10 my-auto max-w-8xl mx-auto gap-3">
            <div class="w-3/4 p-4 flex flex-col space-y-4"> <!-- 75% width -->
                <div>
                    <button @click="goBack"
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
                                        {{ criteria.scholarship_form_data.name }}
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
                <Link :href="(route('register'))">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Apply
                    Now</button>
                </Link>

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

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FloatingNav from '@/Components/LandingPage/FloatingNav.vue';
import Section_1 from '@/Components/LandingPage/A_Title_Section.vue';
import Section_2 from '@/Components/LandingPage/C_Tags.vue';
import Section_3 from '@/Components/LandingPage/D_Sponsors.vue';
import Section_4 from '@/Components/LandingPage/E_Scholarships.vue';
import SectionFooter from '@/Components/LandingPage/Footer.vue';
import { onMounted, watchEffect, watch, computed } from 'vue';

const activeTab = ref("eligibility");

const tabs = [
    { key: "eligibility", label: "Eligibility" },
    { key: "requirements", label: "Requirements" },
    { key: "awards", label: "Awards" },
];

const goBack = () => {
    window.history.back();
};

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
    branding: {
        type: Object,
        required: true,
    },
});

const parsedCourses = computed(() => {
    try {
        const data = props.selectedCampus.selected_campus;
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
    const data = props.selectedCampus.selected_campus;
    if (typeof data === 'string' && data.includes('Bachelor')) {
        return data;
    }
    return 'Course information unavailable';
});

const formattedDate = new Date(props.deadline.date_end).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric"
});
</script>
