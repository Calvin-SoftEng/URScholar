<template>
    <div class="w-full h-full bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
        <div class="grid grid-cols-[30%_70%] sm:grid-cols-[30%_70%] gap-3 py-6 px-20 h-full">
            <!-- Left Column (30% width) -->
            <div class="flex flex-col gap-3">
                <!-- Small Card 1 -->
                <div class="h-full">
                    <ScholarsSupported />
                </div>

                <!-- Small Card 2 -->
                <div class="h-full">
                    <TotalScholarsSupported />
                </div>

                <!-- Disbursements Card -->
                <div class="bg-white shadow-md rounded-lg">
                    <Disbursements />
                </div>
            </div>

            <!-- Right Column (70% width) -->
            <div class="bg-white shadow-md p-6 rounded-lg flex flex-col justify-between">
                <Scholarships :sponsor="sponsor" :scholarships="scholarships" :schoolyears="schoolyears"/>
            </div>
        </div>



    </div>

</template>

<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { useRouter, useRoute } from 'vue-router'
import Scholarships from '@/Components/Sponsor/Dashboard/Scholarships.vue';
import ScholarsSupported from '@/Components/Sponsor/Dashboard/ScholarsSupported.vue';
import TotalScholarsSupported from '@/Components/Sponsor/Dashboard/TotalScholarsSupported.vue';
import Disbursements from '@/Components/Sponsor/Dashboard/Disbursements.vue';

const components = {
    Scholarships,
    ScholarsSupported,
    TotalScholarsSupported,
    Disbursements,
    // ActiveScholarship,
    // QuickPost,
    // Messages,
    // Calendar,
};

const props = defineProps({
    sponsor: Object,
    scholarships: Array,
    schoolyears: Array,
});

const grantBasedScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'Grant-Based')
);

const oneTimeScholarships = computed(() =>
    props.scholarships.filter(scholarship => scholarship.scholarshipType === 'One-time Payment')
);
</script>
