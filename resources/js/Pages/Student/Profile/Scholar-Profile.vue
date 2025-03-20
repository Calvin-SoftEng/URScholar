<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-dirtywhite shadow-sm justify-between flex flex-row px-10">
            <h1 class="text-3xl font-bold font-sora text-left p-3">My Profile</h1>
            <button class="text-sm font-semibold text-black">Edit Profile</button>
        </div>
        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-2">
            <div class="mx-auto w-7/12 sm:px-6 lg:px-8 ">
                <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- pic -->
                        <div class="border w-80 h-80 rounded-lg overflow-hidden">
                            <img :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="Profile Picture" class="w-full h-full object-cover">
                        </div>

                        <!-- info -->
                        <div class="w-full h-1/12 flex flex-col items-left gap-1 pb-4 border-b-2">
                            <span class="text-gray-500 text-sm">Permanent Address</span>
                            <span class="text-gray-900 text-base font-semibold leading-tight"></span>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Age</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.age
                                    }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Date of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.placebirth }}</span>
                                </div>
                            </div>
                            <div class="w-full flex flex-row gap-2 py-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Civil Status</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.civil
                                    }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Place of Birth</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.placebirth }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-1/12 flex flex-col items-left gap-2 pb-4 border-b-2">
                            <div class="w-full flex flex-row gap-2">
                                <div class="w-[40%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Gender</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{ student.gender
                                    }}</span>
                                </div>
                                <div class="w-[60%] flex flex-col items-left gap-1">
                                    <span class="text-gray-500 text-sm">Religion</span>
                                    <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                        student.religion }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- gmail -->
                        <div class="w-full h-1/12 flex items-center gap-2 p-1 pb-4 border-b-2">
                            <span class="p-2 bg-primary rounded-md text-2xl text-white font-albert font-bold">@</span>
                            <span class="pl-2 text-gray-900 text-base font-bold">{{ $page.props.auth.user.email
                                }}</span>
                        </div>
                        <!-- qr -->
                        <div
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex flex-col flex-grow items-center justify-center gap-2 p-3">
                            <div v-if="scholar.qr_code" class="w-20 h-20">
                                <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-full h-full">
                            </div>
                            <div v-else class="w-20 h-20 bg-gray-200 flex items-center justify-center">
                                <font-awesome-icon :icon="['fas', 'qrcode']" class="text-gray-400 text-3xl" />
                            </div>
                            <button @click="openQRModal"
                                class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition">
                                View & Download QR Code
                            </button>
                        </div>
                    </div>
                    <div class="w-full h-full col-span-2 block flex-col items-center mx-auto max-w-8xl space-y-3">
                        <div class="w-full h-1/12">
                            <span class="font-italic font-sora text-3xl font-bold uppercase">{{ student.last_name
                                }},
                                    {{ student.first_name }}</span>
                        </div>

                        <div
                            class="w-full h-1/12 bg-white shadow-md rounded-lg flex flex-col items-center space-y-2 gap-2 py-5 px-10">
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'graduation-cap']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.course.name }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'id-card-clip']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                    scholar.urscholar_id }}</span>
                            </div>
                            <div class="w-full flex flex-row items-center gap-2">
                                <font-awesome-icon :icon="['fas', 'school']"
                                    class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                <span class="text-gray-900 text-base font-semibold leading-tight">{{ scholar.campus.name }}, Campus</span>
                            </div>
                        </div>

                        <!-- education -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="text-base">Education</h1>
                            
                            <div class="w-full flex flex-col space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-900 text-lg font-semibold leading-tight">Current GWA:</span>
                                    <span class="text-gray-800 text-base font-semibold">1.1</span>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Elementary
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.name
                                        }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ elementary.years
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Junior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ junior.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ junior.years
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Senior High School
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ senior.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ senior.years
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    College
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ college.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ college.years
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Vocational
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ vocational.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ vocational.years
                                    }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-gray-900 text-lg font-semibold leading-tight">
                                    Post Graduate
                                </h3>
                                <div class="w-full flex flex-row justify-between items-center space-y-3">
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ postgrad.name
                                    }}</span>
                                    <span class="text-gray-700 text-base font-medium leading-tight">{{ postgrad.years
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- family -->
                        <div
                            class="w-full h-1/12 bg-white font-instrument shadow-md rounded-lg flex flex-col items-left space-y-3 gap-2 py-5 px-10">
                            <h1 class="cols-span-2 text-base">Family</h1>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person-dress']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                mother.first_name }}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                mother.occupation }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left ">
                                        <span class="text-gray-500 text-sm font-semibold leading-tight">Monthly Family
                                            Income</span>
                                        <span class="text-gray-900 text-3xl font-semibold leading-tight">100,000</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'person']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                father.first_name }}</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">{{
                                                father.occupation }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Family Housing
                                            Type</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                            family.family_housing }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-row items-center gap-2 py-2">
                                        <font-awesome-icon :icon="['fas', 'people-roof']"
                                            class="p-2 w-7 h-7 bg-primary rounded-md text-white" />
                                        <div class="flex flex-col items-left gap-1">
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Kapatid1,
                                                Kapatid2,</span>
                                            <span class="text-gray-900 text-base font-semibold leading-tight">Trabaho1,
                                                Trabaho2</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="w-full flex flex-col items-left gap-1 py-1">
                                        <span class="text-gray-500 text-base font-semibold leading-tight">Other Sources
                                            of Income</span>
                                        <span class="text-gray-900 text-lg font-semibold leading-tight">{{
                                            family.other_income }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- QR Code Modal -->
        <div v-if="isQRModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeQRModal">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Your QR Code</h3>
                    <button @click="closeQRModal" class="text-gray-400 hover:text-gray-500">
                        <font-awesome-icon :icon="['fas', 'times']" class="w-5 h-5" />
                    </button>
                </div>

                <div class="border border-gray-300 p-4 aspect-square flex items-center justify-center w-full">
                    <!-- Show loading spinner while generating -->
                    <div v-if="loading" class="flex items-center justify-center">
                        <font-awesome-icon :icon="['fas', 'circle-notch']"
                            class="w-10 h-10 animate-spin text-primary" />
                    </div>

                    <!-- Show error message if there is one -->
                    <div v-else-if="error" class="text-red-500 text-center">
                        {{ error }}
                    </div>

                    <!-- Show QR code if available -->
                    <img v-else-if="qrCodeUrl" :src="`/storage/qr_codes/${qrCodeUrl}`" alt="QR Code"
                        class="max-w-full max-h-full" />

                    <!-- Show placeholder if no QR code yet -->
                    <div v-else class="flex items-center justify-center">
                        <font-awesome-icon :icon="['fas', 'qrcode']" class="w-16 h-16 text-gray-300" />
                    </div>
                </div>

                <div class="text-center py-4 gap-2 flex items-center justify-center">
                    <!-- <button @click="forceRegenerateQRCode" :disabled="loading"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 disabled:opacity-50">
                        {{ loading ? 'Generating...' : 'Regenerate' }}
                    </button> -->
                    <button @click="downloadQRCode" :disabled="!qrCodeUrl || loading"
                        class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 disabled:opacity-50">
                        Download
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    student: Object,
    education: Object,
    family: Object,
    scholar: Object,
});

// QR Code state management
const isQRModalOpen = ref(false);
const qrCodeUrl = ref(null);
const loading = ref(false);
const error = ref(null);

// Watch for qrCodeUrl in page props (for when returning from generation)
watch(() => props.flash?.qrCodeUrl, (newUrl) => {
    if (newUrl) {
        qrCodeUrl.value = newUrl;
    }
});

const openQRModal = () => {
    isQRModalOpen.value = true;

    // Check if the scholar has an existing QR code
    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    } else {
        // If no QR code exists, generate one
        generateQRCode();
    }
};

const closeQRModal = () => {
    isQRModalOpen.value = false;
};

const generateQRCode = () => {
    loading.value = true;
    error.value = null;

    // Make sure we have a valid scholar ID
    if (!props.scholar || !props.scholar.urscholar_id) {
        error.value = 'Scholar information not available.';
        loading.value = false;
        return;
    }

    router.visit(`/myProfile/generate/${props.scholar.urscholar_id}`, {
        method: 'get',
        preserveState: true,
        onSuccess: (page) => {
            if (page.props.flash?.qrCodeUrl) {
                qrCodeUrl.value = page.props.flash.qrCodeUrl;
            } else {
                error.value = 'QR code generation failed. Please try again.';
            }
            loading.value = false;
        },
        onError: (errors) => {
            error.value = 'Failed to generate QR code. Please try again.';
            console.error(errors);
            loading.value = false;
        }
    });
};

const downloadQRCode = async () => {
    if (!qrCodeUrl.value) {
        error.value = 'No QR code available to download.';
        return;
    }

    try {
        loading.value = true;

        // Fetch the QR code image as a Blob
        const response = await fetch(qrCodeUrl.value);

        if (!response.ok) {
            throw new Error('Failed to fetch QR code.');
        }

        const blob = await response.blob();

        // Ensure it's a valid image
        if (blob.type !== 'image/png') {
            throw new Error('Invalid QR code file format.');
        }

        // Create a temporary download link
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${props.scholar.urscholar_id}.png`;

        // Append, click, and remove the link
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        error.value = null; // Clear any errors

    } catch (err) {
        console.error("Download failed:", err);
        error.value = 'Failed to download QR code.';
    } finally {
        loading.value = false;
    }
};


// Initialize QR code URL on component mount
onMounted(() => {
    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    }

    // Also check if there's a flash message with a QR code URL
    if (props.flash?.qrCodeUrl) {
        qrCodeUrl.value = props.flash.qrCodeUrl;
    }
});

const elementary = computed(() => {
    try {
        return JSON.parse(props.education.elementary);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const junior = computed(() => {
    try {
        return JSON.parse(props.education.junior);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const senior = computed(() => {
    try {
        return JSON.parse(props.education.senior);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const college = computed(() => {
    try {
        return JSON.parse(props.education.college);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const vocational = computed(() => {
    try {
        return JSON.parse(props.education.vocational);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const postgrad = computed(() => {
    try {
        return JSON.parse(props.education.postgrad);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const mother = computed(() => {
    try {
        return JSON.parse(props.family.mother);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});

const father = computed(() => {
    try {
        return JSON.parse(props.family.father);
    } catch (error) {
        console.error("Invalid JSON format", error);
        return {}; // Return empty object if parsing fails
    }
});
</script>
