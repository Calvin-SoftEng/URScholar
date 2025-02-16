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
                        <a href="{{ route('download.qr') }}" download="QRCode.png"
                            class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3 cursor-pointer hover:bg-gray-100">
                            <img src="../../../../assets/images/qrcodesample.png" alt="QR Code" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </a>
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
                                    <font-awesome-icon :icon="['fas', 'check']" class="text-white" /></div>
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
                                    <div class="mb-4">
                                        <h3 class="font-medium text-gray-900">{{ req.requirement }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.return_message }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <input type="file" @change="(e) => handleFile(e, req.id, req.requirement)"
                                            :id="'file_' + req.id" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100" />

                                        <div v-if="form.files[req.id]"
                                            class="flex items-center gap-2 text-sm text-gray-600">
                                            <font-awesome-icon :icon="['fas', 'file']" />
                                            <span>{{ form.files[req.id].name }}</span>
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

                                <div class="flex justify-end">
                                    <button type="submit" :disabled="form.processing" 
                                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
                                                    focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
                                                    transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed
                                                    flex items-center justify-center gap-2">
                                            <span class="material-symbols-rounded">outbox</span>
                                            <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                                        </button>
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
import { computed } from 'vue';

const props = defineProps({
    scholar: Object,
    scholarship: Object,
    requirements: Object,
    submitReq: Array,
});

// Rename for clarity
const returnedRequirements = computed(() => props.submitReq || []);

const form = useForm({
    files: {},
    requirement_files: []
});


const handleFile = (event, reqId, requirementName) => {
    const file = event.target.files[0];
    if (file) {
        form.files[reqId] = file;
        form.requirement_files.push({
            requirement_id: reqId,
            requirement_name: requirementName,
            file: file
        });
    }
};

const removeFile = (reqId) => {
    delete form.files[reqId];
    form.requirement_files = form.requirement_files.filter(
        item => item.requirement_id !== reqId
    );
};

const submitRequirements = async () => {
    try {
        const formData = new FormData();

        form.requirement_files.forEach(item => {
            formData.append(`files[${item.requirement_id}]`, item.file);
            formData.append(`requirements[${item.requirement_id}]`, item.requirement_name);
        });

        form.post('/student/application/re-upload', {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                // Show success message or redirect
            },
            onError: (errors) => {
                console.error('Upload errors:', errors);
            }
        });
    } catch (error) {
        console.error('Form submission error:', error);
    }
};
</script>