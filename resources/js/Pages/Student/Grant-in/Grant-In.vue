<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <div class="w-full bg-dirtywhite shadow-sm ">
            <h1 class="text-3xl font-bold font-sora text-left p-3 mx-10">My Scholarship</h1>
        </div>
        <div class="pt-3 pb-24 overflow-auto h-full scroll-py-4">
            <div class="mx-auto w-10/12 sm:px-6 lg:px-8 ">
                <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                        <!-- greeting -->
                        <div class="bg-primary w-full text-white text-3xl font-sans font-bold p-7 rounded-lg">
                            Greetings! {{ $page.props.auth.user.name }}
                        </div>
                        <!-- notifications -->
                        <div class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3">
                            <box-icon name="bell-ring" type="solid"></box-icon>
                            <span>You have (0) feed updates</span>
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

                        <button @click="isQrCodeVisible = true"
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3 cursor-pointer hover:bg-gray-100">
                            <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </button>
                        <!-- tracker -->
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
                    <div
                        class="w-full h-full col-span-2 block bg-white shadow-md p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8 rounded-lg">
                        <div class="flex w-full h-1/12 justify-center items-center">
                            <span>Call mo yung scholarship here</span>
                        </div>
                        <div class="flex items-center justify-center my-5">
                            <!-- Step 1 -->
                            <div class="relative flex flex-col items-center">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
                                </div>
                                <span class="mt-2 text-sm text-gray-700">Application</span>
                            </div>

                            <!-- Line -->
                            <div class="w-16 h-1 bg-primary relative -top-4"></div>

                            <!-- Step 2 -->
                            <div class="relative flex flex-col items-center">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                                    2</div>
                                <span class="mt-2 text-sm text-gray-700">Under Review</span>
                            </div>

                            <!-- Line -->
                            <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

                            <!-- Step 3 -->
                            <div class="relative flex flex-col items-center">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                                    3</div>
                                <span class="mt-2 text-sm text-gray-700">Approved</span>
                            </div>
                        </div>
                        <div class="bg-dirtywhite w-full p-3 flex flex-col font-quicksand">
                            <span>From Maam Anorn</span>
                            <span>Message</span>
                            <p>It is noted, however, that among the requirements you have submittted to DBP, the
                                following documents must be
                                resubmitted.</p>
                            <br>
                            <span>Message nung nasa return: Ex anlabo pre</span>
                            <br>
                            <span>Deadline</span>
                            <br>
                            <form @submit.prevent="submitRequirements" class="space-y-6">
                                <div v-for="req in returnedRequirements" :key="req.id"
                                    class="bg-white border rounded-lg shadow-sm p-4">
                                    <h3 class="font-medium text-gray-900">{{ req.requirement_name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.return_message }}</p>

                                    <div class="mt-3">
                                        <input type="file" @change="(e) => handleFile(e, req.id, req.requirement_name)"
                                            :id="'file_' + req.id" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100" />

                                        <div v-if="selectedFiles[req.id]"
                                            class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                                            <font-awesome-icon :icon="['fas', 'file']" />
                                            <span>{{ selectedFiles[req.id] }}</span>
                                            <button type="button" @click="removeFile(req.id)"
                                                class="text-red-600 hover:text-red-800">
                                                <font-awesome-icon :icon="['fas', 'times']" />
                                            </button>
                                        </div>

                                        <p v-if="form.errors[`files.${req.id}`]" class="mt-1 text-sm text-red-600">
                                            {{ form.errors[`files.${req.id}`] }}
                                        </p>
                                    </div>
                                </div>

                                <div v-if="returnedRequirements.length === 0" class="text-center py-8">
                                    <p class="text-gray-500">No returned requirements to resubmit.</p>
                                </div>

                                <div v-if="returnedRequirements.length > 0" class="flex justify-end">
                                    <button type="submit"
                                        :disabled="form.processing || Object.keys(form.files).length === 0"
                                        class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
                            focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
                            transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                                    </button>
                                </div>
                            </form>
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
                    <img :src="`/storage/qr_codes/${scholar.qr_code}`" alt="QR Code" class="w-40 h-40 object-cover rounded-lg shadow-md">
                    <p class="text-gray-700 dark:text-gray-300 mt-4 text-sm">Scan the QR code or click below to download it.</p>
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

const props = defineProps({
    scholar: Object,
    scholarship: Object,
    submitReq: Array,
});

// List of returned requirements
const returnedRequirements = computed(() => props.submitReq || []);

// Form state
const form = useForm({
    files: {},
    requirements: []
});

// Track selected files
const selectedFiles = ref({});

const handleFile = (event, reqId, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        // Store file
        form.files[reqId] = file;
        selectedFiles.value[reqId] = file.name;

        // Add requirement if not exists
        const existingIndex = form.requirements.findIndex(r => r.id === reqId);
        if (existingIndex === -1) {
            form.requirements.push({
                id: reqId,
                requirement: requirementName
            });
        }
    }
};

const removeFile = (reqId) => {
    // Remove file
    delete form.files[reqId];
    delete selectedFiles.value[reqId];

    // Remove requirement from array
    form.requirements = form.requirements.filter(r => r.id !== reqId);

    // Reset file input
    const fileInput = document.getElementById(`file_${reqId}`);
    if (fileInput) {
        fileInput.value = '';
    }
};

const isQrCodeVisible = ref(false);

const qrCodeImage = ref(new URL("@/assets/images/qrcodesample.png", import.meta.url).href);


const closeModal = () => {
    isCreating.value = false;
    isEditing.value = false;
    resetForm();
};

const submitRequirements = () => {
    if (Object.keys(form.files).length === 0) {
        alert('Please select at least one file before submitting.');
        return;
    }

    if (!confirm('Are you sure you want to resubmit these requirements?')) {
        return;
    }

    form.post('/student/application/re-upload', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            alert('Requirements resubmitted successfully!');
            form.reset();
            selectedFiles.value = {};
            window.location.reload();
        },
        onError: (errors) => {
            console.error('Upload errors:', errors);
            alert('There was an issue with the upload. Please try again.');
        }
    });
};
</script>
