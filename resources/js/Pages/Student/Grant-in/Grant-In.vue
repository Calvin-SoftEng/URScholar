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
                            Greetings! {{$page.props.auth.user.name}}
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
                        <a href="{{ route('download.qr') }}" download="QRCode.png" class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3 cursor-pointer hover:bg-gray-100">
                                <img src="../../../../assets/images/qrcodesample.png" alt="QR Code" class="w-20 h-20">
                                <span class="pl-2">Download your QR Code</span>
                        </a>
                        <!-- tracker -->
                        <div class="w-full h-1/12 space-y-3 bg-white shadow-lg rounded-lg items-center p-3">
                            <span class="pl-2">Recent Activities</span>
                            <div class="w-full h-full space-y-3 flex flex-col items-center">
                                <div class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    feafafaefaef
                                </div>
                                <div class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    fbeapnfpaeinf
                                </div>
                                <div class="w-full h-1/12 text-dtext bg-dsecondary rounded-lg flex items-center gap-2 p-3">
                                    daefefafefa
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="w-full h-full col-span-2 block bg-white shadow-md p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8 rounded-lg">
                        <div class="flex w-full h-1/12 justify-center items-center">
                            <span>Call mo yung scholarship here</span>
                        </div>
                        <div class="flex items-center justify-center my-5">
                            <!-- Step 1 -->
                            <div class="relative flex flex-col items-center">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg"><font-awesome-icon :icon="['fas', 'check']" class="text-white" /></div>
                                <span class="mt-2 text-sm text-gray-700">Application</span>
                            </div>

                            <!-- Line -->
                            <div class="w-16 h-1 bg-primary relative -top-4"></div>

                            <!-- Step 2 -->
                            <div class="relative flex flex-col items-center">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">2</div>
                                <span class="mt-2 text-sm text-gray-700">Under Review</span>
                            </div>

                            <!-- Line -->
                            <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

                            <!-- Step 3 -->
                            <div class="relative flex flex-col items-center">
                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">3</div>
                                <span class="mt-2 text-sm text-gray-700">Approved</span>
                            </div>
                        </div>
                        <div class="bg-dirtywhite w-full p-3 flex flex-col font-quicksand">
                            <span>From Maam Anorn</span>
                            <span>Message</span>
                            <p>It is noted, however, that among the requirements you have submittted to DBP, the following documents must be 
                                resubmitted.</p>
                                <br>
                                <span>Message nung nasa return: Ex anlabo pre</span>
                                <br>
                            <span>Deadline</span>
                            <br>
                            <form @submit.prevent="submitRequirements" enctype="multipart/form-data">
                                <div class="p-3 w-full bg-white rounded-lg">

                                    <!-- Requirements upload section -->
                                    <div class="flex flex-wrap w-full gap-1">
                                        <div class="border rounded-md shadow flex flex-col w-1/2 p-3 mb-4">
                                            <!-- Requirement name -->
                                            <div class="mb-2">
                                                <input type="hidden">
                                                <span class="font-medium text-gray-700" ></span>
                                            </div>

                                            <!-- File input with preview -->
                                            <div class="space-y-2">
                                                <input
                                                    type="file"
                                                    @change="(e) => handleFile(e, index, req)"
                                                    :id="'file_input_' + index"
                                                    class="block w-full text-sm text-blue-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                                    dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                />
                                                
                                                <!-- File preview -->
                                                <div v-if="form.files[index]" class="text-sm text-gray-600">
                                                    Selected file: {{ form.files[index].name }}
                                                    <button
                                                        type="button"
                                                        @click="() => {
                                                            form.files[index] = null;
                                                            form.requirement_files = form.requirement_files.filter(r => r.file_index !== index);
                                                        }"
                                                        class="ml-2 text-red-600 hover:text-red-800"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>

                                                <!-- Error message -->
                                                <div v-if="form.errors[`files.${index}`]" class="text-red-500 text-sm">
                                                    {{ form.errors[`files.${index}`] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="items-right flex w-full justify-end">
                                        <button type="submit" :disabled="form.processing" 
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
                                                    focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
                                                    transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed
                                                    flex items-center justify-center gap-2">
                                            <span class="material-symbols-rounded">outbox</span>
                                            <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                                        </button>
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';


const props = defineProps({
    scholar: Object,
    scholarship: Object,
    requirements: Object,
    submitRequirements: Object,
});

// Form to handle multiple files for different requirements
const form = useForm({
    files: {},
    requirement_files: [] // Array to store requirement-file mappings
});

// Handle file selection for specific requirement
const handleFile = (event, reqIndex, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        // Store the file with its requirement info
        form.files[reqIndex] = file;
        
        // Store the mapping of requirement to file
        form.requirement_files.push({
            requirement_name: requirementName,
            file_index: reqIndex
        });
    }
};

// Submit form with files
const submitRequirements = async () => {
    try {
        form.post('/student/application/upload', {
            forceFormData: true,
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                // Clear files after successful upload
                form.files = {};
                form.requirement_files = [];
            },
        });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>