<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10 h-full overflow-auto">
        <!-- <div class="w-full bg-[#e8f0f9] shadow-sm ">
            <h1 class="text-3xl text-primary font-bold font-sora text-left p-3 mx-10">My Scholarship</h1>
        </div> -->
        <div
            class="pt-3 pb-3 sm:overflow-auto sm:max-h-full sm:scroll-py-2 overflow-auto h-full scroll-py-4 bg-gradient-to-b from-[#E9F4FF] via-white to-white dark:bg-gradient-to-b dark:from-[#1C2541] dark:via-[#0B132B] dark:to-[#0B132B]">
            <div class="mx-auto sm:w-full lg:w-10/12 lg:px-8 h-full">
                <div class="flex flex-col md:grid md:grid-cols-2 lg:grid-cols-3 gap-3 h-full">
                    <!-- Greeting and Scholarship Section -->
                    <div class="w-full flex flex-col items-center space-y-3 sm:space-y-0 gap-3 sm:px-3">

                        <!-- Greeting -->
                        <div
                            class="bg-[#003366] dark:bg-dprimary w-full text-white text-base sm:text-xl lg:text-3xl font-sans font-bold p-5 sm:p-7 rounded-lg text-center">
                            Greetings! {{ $page.props.auth.user.name }}
                        </div>

                        <!-- Scholarships -->
                        <div v-show="!scholar"
                            class="w-full bg-white shadow-lg rounded-lg flex items-center gap-3 py-4 px-4 sm:px-6 transition-all duration-300 hover:shadow-xl hover:bg-gray-100 cursor-pointer">
                            <box-icon name="bell-ring" type="solid" class="w-6 h-6 text-primary"></box-icon>
                            <span class="text-base sm:text-lg font-semibold text-gray-800">View Available
                                Scholarships</span>
                        </div>

                        <!-- Recent Activities Section (Hidden on Mobile) -->
                        <div class="hidden sm:block w-full space-y-3 bg-white dark:bg-dcontainer shadow-lg rounded-lg p-3">
                            <span class="pl-2 font-semibold text-gray-700 dark:text-dtext">Recent Activities</span>

                            <!-- Scrollable Activity Log -->
                            <div class="w-full space-y-3 flex flex-col max-h-64 overflow-y-auto pr-2">
                                <div v-for="(log, index) in activity" :key="index"
                                    class="w-full text-dtext bg-dsecondary dark:bg-dprimary rounded-lg flex items-center gap-2 p-3">
                                    {{ log.description }} {{ new Date(log.created_at).toLocaleDateString('en-US', {
                                        month: 'long', day: 'numeric', year: 'numeric' }) }}
                                </div>
                                <div v-if="activity.length === 0"
                                    class="w-full text-dtext bg-dsecondary dark:bg-dprimary rounded-lg flex items-center gap-2 p-3">
                                    No recent activities found.
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Section When Scholar Exists -->
                    <div v-if="grantee" class="w-full md:col-span-2">
                        <div
                            class="w-full h-full bg-white dark:bg-dprimary rounded-lg shadow-md sm:p-5 lg:py-10 flex-col items-center mx-auto max-w-8xl sm:px-2 lg:px-8">
                            <ScholarGrant :reqDeadline="reqDeadline" :payout_schedule="payout_schedule"
                                :scholar="scholar" :schoolyears="schoolyears" :scholarship="scholarship"
                                :submitReq="submitReq" :submitPending="submitPending" :historygrantee="historygrantee"
                                :disbursement="disbursement" :grantee="grantee" :oldestGrantee="oldestGrantee"
                                :submitApproved="submitApproved" :total_subreq="total_subreq"
                                :academic_year="academic_year" />
                        </div>
                    </div>

                    <!-- Section When No Scholarship Exists -->
                    <div v-else
                        class="md:col-span-2 w-full h-full block border-l border-gray-200 p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8">
                        <div v-if="!applicant"
                            class="w-full h-full bg-white dark:bg-dprimary rounded-lg shadow-md sm:p-5 lg:py-10 flex-col items-center mx-auto max-w-8xl sm:px-2 lg:px-8">
                            <Scholarships :sponsors="sponsors" :scholarships="scholarships" :schoolyears="schoolyears"
                                :scholar="scholar" :grade="grade" />
                        </div>
                        <div v-else
                            class="w-full h-full bg-white dark:bg-dprimary rounded-lg shadow-md sm:p-5 lg:py-10 flex-col items-center mx-auto max-w-8xl sm:px-2 lg:px-8">
                            <ScholarAppliant :reqDeadline="reqDeadline" :payout_schedule="payout_schedule"
                                :scholar="scholar" :schoolyears="schoolyears" :scholarship="scholarship"
                                :submitReq="submitReq" :submitPending="submitPending" :applicant="applicant"
                                :submitApproved="submitApproved" :total_subreq="total_subreq"
                                :academic_year="academic_year" />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- qrcode -->
        <div v-if="isQrCodeVisible"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity duration-300">

            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-900 dark:border-gray-700 rounded-lg shadow-xl w-full max-w-lg">

                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">QR Code Download</h2>
                    <button @click="isQrCodeVisible = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg w-8 h-8 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 flex flex-col items-center">
                    <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code"
                        class="w-40 h-40 object-cover rounded-lg shadow-md">
                    <p class="text-gray-700 dark:text-gray-300 mt-4 text-sm">Scan the QR code or click below to download
                        it.</p>
                    <a :href="qrCodeImage" download="QR_Code.png"
                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        Download QR Code
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ScholarGrant from './Scholar/ScholarGrant.vue';
import Scholarships from './Non_Scholar/Scholarships.vue';
import ScholarAppliant from './Non_Scholar/NonScholarDashboard.vue';

const props = defineProps({
    //For scholars only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,
    submitPending: Array,
    submitApproved: Array,
    historygrantee: Array,
    total_subreq: Array,
    disbursement: Object,
    grantee: Object,
    oldestGrantee: Object,
    payout_schedule: Object,
    reqDeadline: Object,
    academic_year: Object,
    activity: Array,

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
    applicant: Object,
    grade: Object,
});

const isQrCodeVisible = ref(false);

const qrCodeImage = ref(new URL("@/assets/images/qrcodesample.png", import.meta.url).href);
</script>
