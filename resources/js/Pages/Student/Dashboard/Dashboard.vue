<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <!-- <div class="w-full bg-[#e8f0f9] shadow-sm ">
            <h1 class="text-3xl text-primary font-bold font-sora text-left p-3 mx-10">My Scholarship</h1>
        </div> -->
        <div class="pt-3 pb-3 overflow-auto h-full scroll-py-4 bg-gradient-to-b from-[#E9F4FF] via-white to-white">
            <div class="mx-auto w-10/12 sm:px-6 lg:px-8 h-full ">
                <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3 h-full">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- greeting -->
                        <div class="bg-primary w-full text-white text-3xl font-sans font-bold p-7 rounded-lg">
                            Greetings! {{ $page.props.auth.user.name }}
                        </div>
                        <!-- scholarships -->
                        <div v-show="!scholar"
                            class="w-full bg-white shadow-lg rounded-lg flex items-center gap-3 py-4 px-6 transition-all duration-300 hover:shadow-xl hover:bg-gray-100 cursor-pointer">
                            <box-icon name="bell-ring" type="solid" class="w-6 h-6 text-primary"></box-icon>
                            <span class="text-lg font-semibold text-gray-800">View Available Scholarships</span>
                        </div>

                        <!-- qr code -->
                        <!-- <div class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3">
                            <img src="../../../../assets/images/qrcodesample.png" alt="" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </div> -->

                        <!-- <a href="{{ route('download.qr') }}" download="QRCode.png"
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3 cursor-pointer hover:bg-gray-100">
                            <img src="../../../../assets/images/qrcodesample.png" alt="QR Code" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </a> -->

                        <!-- <button @click="isQrCodeVisible = true"
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3 cursor-pointer hover:bg-gray-100">
                            <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </button> -->

                        <div class="w-full h-1/12 space-y-3 bg-white shadow-lg rounded-lg items-center p-3">
                            <span class="pl-2">Recent Activities</span>
                            <div class="w-full h-full space-y-3 flex flex-col items-center">
                                <div
                                    class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    feafafaefaef
                                </div>
                                <div
                                    class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    fbeapnfpaeinf
                                </div>
                                <div
                                    class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    daefefafefa
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="scholar" class="col-span-2">
                    <!-- kapag may scholarship -->
                        <div
                            class="w-full h-full col-span-2 block bg-white shadow-md p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8 rounded-lg">
                            <ScholarGrant :scholar="scholar" :schoolyears="schoolyears" :scholarship="scholarship"
                                :submitReq="submitReq" :submitPending="submitPending" :submitApproved="submitApproved"/>
                        </div>
                    </div>
                    
                    
                    <!-- kapag wala pang scholarship -->
                    <div v-else
                        class="w-full h-full col-span-2 block border-l border-gray-200 p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8">
                        <Scholarships :sponsors="sponsors" :scholarships="scholarships"
                            :schoolyears="schoolyears" />
                            <!-- <Scholarships  /> -->
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

const props = defineProps({
    //For scholars only
    scholar: Object,
    scholarship: Object,
    submitReq: Array,
    submitPending: Array,
    submitApproved: Array,

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

const isQrCodeVisible = ref(false);

const qrCodeImage = ref(new URL("@/assets/images/qrcodesample.png", import.meta.url).href);
</script>
