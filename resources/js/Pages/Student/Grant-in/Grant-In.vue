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
                        <!-- notifications -->
                        <div class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 py-3 px-6">
                            <box-icon name="bell-ring" type="solid"></box-icon>
                            <span>You have (0) feed updates</span>
                        </div>
                        <!-- gc -->
                        <button @click="GroupChat = !GroupChat" 
                        class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-start gap-2 p-3">
                             <!-- Single Chat Item -->
                            <div class="w-full flex items-start gap-3 p-3  hover:bg-gray-100 cursor-pointer">
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                    <box-icon name="group" type="solid"></box-icon>
                                </div>
                                <div class="flex-1 items-start justify-start">
                                    <p class="font-semibold">Group Chat 1</p>
                                    <p class="text-sm text-gray-500 truncate">Hey everyone! Let's meet at 5 PM...</p>
                                </div>
                                <p class="text-xs text-gray-400">10:30 AM</p>
                            </div>
                        </button>
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

                    <div v-if="GroupChat" 
                    class="col-span-2 w-full h-full flex flex-col">
                        <div class="bg-white shadow-sm border-b border-gray-100 p-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-primary">Conversation</h3>
                            <!-- Three dots menu aligned with conversation text -->
                            <button class="text-gray-600 hover:text-primary transition-colors"
                                @click="showMemberList = !showMemberList">
                                <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" />
                            </button>
                        </div>
                        <!-- Main chat area -->
                        <div class="bg-white flex flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer">
                            <!-- Messages column -->
                            <div
                                class="flex-1 px-2 overflow-y-auto overscroll-contain inset-shadow-sm flex flex-col-reverse">
                                <!-- No group selected message -->
                                <div v-if="!selectedData || !selectedData.id"
                                    class="flex items-center justify-center h-full text-gray-500">
                                    <span class="text-lg font-semibold">Select a group</span>
                                </div>

                                <!-- Selected group but no messages -->
                                <div v-else-if="selectedData && selectedData.id && !messageData.length"
                                    class="flex items-center justify-center h-full text-gray-500">
                                    <span class="text-lg font-semibold">No messages available</span>
                                </div>

                                <!-- Message display when messages exist -->
                                <div v-else v-for="(message, index) in messageData" :key="message.id" :class="{
                                    'flex items-start justify-end gap-2.5': message.user.id === currentUser.id,
                                    'flex items-start justify-start gap-2.5': message.user.id !== currentUser.id
                                }">

                                    <!-- Other User's Message -->
                                    <template v-if="message.user.id !== currentUser.id">
                                        <!-- <img class="w-8 h-8 rounded-full mt-6 border"
                                            src="/docs/images/people/profile-picture-3.jpg" alt="User image"> -->
                                        <div v-if="$page.props.auth.user.picture">
                                            <img id="avatarButton" type="button"
                                                data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                                alt="picture">
                                        </div>
                                        <div v-else>
                                            <img id="avatarButton" type="button"
                                                data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/male.png`" alt="picture">
                                        </div>
                                        <div class="flex flex-col gap-1 w-full justify-start max-w-[320px] mb-3">
                                            <div
                                                class="flex justify-start items-center space-x-1 rtl:space-x-reverse">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ message.user.first_name }}
                                                </span>
                                                <span
                                                    class="text-sm font-semibold text-gray-400 dark:text-white">
                                                    {{ message.user.usertype }}
                                                </span> 
                                            </div>
                                            <div
                                                class="flex flex-col leading-1.5 p-4 bg-gray-100 text-gray-900 rounded-es-xl rounded-se-xl dark:bg-gray-700">
                                                <p class="text-sm font-normal">{{ message.content }}</p>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Current User's Message -->
                                    <template v-else>
                                        <div class="flex flex-col gap-1 w-full justify-end max-w-[320px]">
                                            <div
                                                class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ message.user.first_name }}
                                                </span>
                                            </div>
                                            <div
                                                class="flex flex-col leading-1.5 p-4 bg-primary text-white rounded-s-xl rounded-ee-xl dark:bg-gray-700">
                                                <p class="text-sm font-normal">{{ message.content }}</p>
                                            </div>
                                            <!-- Delivered message only for the latest message of the current user -->
                                            <div v-if="index === 0"
                                                class="flex justify-end items-center space-x-2 rtl:space-x-reverse">
                                                <span
                                                    class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span>
                                            </div>
                                        </div>
                                        <div v-if="$page.props.auth.user.picture">
                                            <img id="avatarButton" type="button"
                                                data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/${$page.props.auth.user.picture}`"
                                                alt="picture">
                                        </div>
                                        <div v-else>
                                            <img id="avatarButton" type="button"
                                                data-dropdown-toggle="userDropdown"
                                                data-dropdown-placement="bottom-start"
                                                class="w-8 h-8 rounded-full mt-6 border"
                                                :src="`/storage/user/profile/male.png`" alt="picture">
                                        </div>
                                        <!-- <img class="w-8 h-8 rounded-full mt-6 border"
                                            src="/docs/images/people/profile-picture-1.jpg"
                                            alt="Current user image"> -->
                                    </template>

                                </div>
                            </div>

                            <!-- Member list sidebar - conditionally shown -->
                            <div v-if="showMemberList" class="w-64 border-l overflow-y-auto">
                                <div class="p-4">
                                    <h4 class="font-bold text-primary mb-3">Members</h4>

                                    <!-- Administrator section -->
                                    <div class="mb-4">
                                        <h5 class="text-xs uppercase text-gray-500 font-semibold mb-2">
                                            Administrator</h5>
                                        <div
                                            class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded-lg">
                                            <div
                                                class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-500 font-semibold">
                                                A</div>
                                            <span class="text-sm font-medium">Admin Name</span>
                                        </div>
                                    </div>

                                    <!-- Coordinator section -->
                                    <div class="mb-4">
                                        <h5 class="text-xs uppercase text-gray-500 font-semibold mb-2">
                                            Coordinator</h5>
                                        <div
                                            class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded-lg">
                                            <div
                                                class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500 font-semibold">
                                                C</div>
                                            <span class="text-sm font-medium">Coordinator Name</span>
                                        </div>
                                    </div>

                                    <!-- Scholars section -->
                                    <div>
                                        <h5 class="text-xs uppercase text-gray-500 font-semibold mb-2">Scholars
                                        </h5>
                                        <div v-for="i in 5" :key="i"
                                            class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded-lg">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-semibold">
                                                S</div>
                                            <span class="text-sm font-medium">Scholar {{ i }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center box-border p-2 bg-white z-100 shadow-[0_-2px_5px_rgba(0,0,0,0.1)]">
                            <!-- For the circle-plus button -->
                            <button class="px-2" @click="sendMessage"
                                :disabled="!selectedData || !selectedData.id">
                                <font-awesome-icon :icon="['fas', 'circle-plus']" :class="[
                                    'w-6 h-6 transition',
                                    selectedData && selectedData.id ? 'text-primary hover:text-primary/80' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                            </button>

                            <!-- For the text input -->
                            <input type="text" placeholder="Type your message..."
                                class="flex-1 bg-transparent text-primary-foreground p-2 focus:outline-none focus:ring-0 border-none"
                                v-model="form.content" @keyup.enter="sendMessage"
                                :disabled="!selectedData || !selectedData.id" />

                            <!-- For the paper-plane button -->
                            <button class="px-2 transition duration-200 group" @click="sendMessage"
                                :disabled="!selectedData || !selectedData.id">
                                <font-awesome-icon :icon="['far', 'paper-plane']" :class="[
                                    'w-6 h-6',
                                    selectedData && selectedData.id ? 'text-primary group-hover:hidden' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                                <font-awesome-icon :icon="['fas', 'paper-plane']" :class="[
                                    'w-6 h-6 hidden group-hover:inline-block',
                                    selectedData && selectedData.id ? 'text-primary' : 'text-gray-400 cursor-not-allowed'
                                ]" />
                            </button>
                        </div>
                    </div>

                    <div v-if="!GroupChat"
                        class="w-full h-full col-span-2 block border-l border-gray-200 p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8">
                        
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

                            <div class="w-full flex flex-col items-center space-y-4">
                                <!-- Check if scholarships exist -->
                                <template>
                                    <div 
                                        class="p-6 w-full min-w-xl bg-white border border-gray-200 rounded-xl shadow-md">

                                        <div class="flex flex-row items-center gap-6 justify-between">
                                            <!-- Scholarship Image -->
                                            <!-- <img :src="`/storage/sponsor/logo/${getSponsorDetails(scholarship.sponsor_id).logo}`"
                                                alt="logo" class="w-40 h-40 rounded-lg object-cover"> -->

                                            <!-- Scholarship Details -->
                                            <div class="flex flex-col flex-grow space-y-1 items-start">
                                                <span class="font-semibold text-2xl text-gray-800">Isko</span>
                                                <!-- <span class="text-sm text-gray-600 space-x-2">
                                                    Funded by <span class="font-medium text-gray-800">{{
                                                        getSponsorDetails(scholarship.sponsor_id).name }}</span>
                                                    <span class="text-gray-500">Since <span class="font-medium text-gray-800">{{
                                                        getSponsorDetails(scholarship.sponsor_id).since }}</span></span>
                                                </span> -->
                                                <p class="text-sm text-gray-700 leading-relaxed mt-2">
                                                   feafeafaefeafafefafeafaef
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
                                                        <span class="font-medium text-red-500">Bukas na</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Vertical Divider (Thicker & More Visible) -->
                                            <div class="w-[10px] h-full bg-gray-400 mx-6"></div>

                                            <!-- Apply Button (Vertically Centered) -->
                                            <div class="flex h-full items-center justify-center">
                                                <!-- <Link :href="`/student/applying-scholarship/${scholarship.id}`"> -->
                                                <button
                                                    class="bg-primary text-white px-10 py-2 rounded-lg shadow-md hover:bg-primary-dark transition duration-200">
                                                    Apply Now
                                                </button>
                                                <!-- </Link> -->
                                            </div>
                                        </div>

                                    </div>
                                </template>

                                <!-- If No Scholarships Available -->
                                <!-- <div v-else class="max-w-4xl flex flex-col items-center justify-center p-10 text-center bg-white border border-gray-200 rounded-xl shadow-md">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l-2-2m0 0l-2-2m2 2h8m4 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-lg font-semibold text-gray-700">No scholarships available</span>
                                    <p class="text-gray-500 text-sm mt-2">Check back later for new opportunities.</p>
                                </div> -->
                            </div>


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

const GroupChat = ref(false); // Change to true to show group chat

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
