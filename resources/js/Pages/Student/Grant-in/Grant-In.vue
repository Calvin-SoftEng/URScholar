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
                        <div class="w-full h-1/12 bg-white shadow-lg rounded-lg flex items-center gap-2 p-3">
                            <img src="../../../../assets/images/qrcodesample.png" alt="" class="w-20 h-20">
                            <span class="pl-2">Download your QR Code</span>
                        </div>
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
                    <div class="w-full h-full col-span-2 block bg-white shadow-md p-10 flex-col items-center mx-auto max-w-8xl sm:px-6 lg:px-8">
                        <div class="w-full h-1/12 bg-black">
                            faegrgrg
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