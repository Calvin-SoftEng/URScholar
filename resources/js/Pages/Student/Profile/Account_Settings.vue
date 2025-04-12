<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout class="shadow-md z-10">
        <form @submit.prevent="submit" class="w-full h-full overflow-hidden">
            <div class="w-full bg-dirtywhite shadow-sm justify-between flex flex-row">
                <h1 class="xl:text-2xl sm:text-sm font-bold font-sora text-left p-3 mx-10 sm:mx-3 md:mx-20">
                    My Account
                </h1>
                <!-- Web Version -->
                <div class="hidden lg:flex space-x-4 p-3 mx-20">
                    <!-- Edit Button -->
                    <button v-if="!EditProfileWeb" type="button" @click="enableWebEdit"
                        class="text-primary font-medium text-sm">
                        Edit Account
                    </button>

                    <!-- Save Button -->
                    <button v-else type="submit" class="text-primary font-medium text-sm">
                        Save Updates
                    </button>

                    <!-- Cancel Button -->
                    <button v-if="EditProfileWeb" type="button" @click="cancelWebEdit"
                        class="text-gray-500 font-medium text-sm">
                        Cancel
                    </button>
                </div>

                <!-- Mobile Version -->
                <div class="flex lg:hidden space-x-4 p-3">
                    <!-- Edit Button -->
                    <button v-if="!EditProfileMobile" type="button" @click="enableMobileEdit"
                        class="text-primary font-medium text-xs">
                        Edit Account
                    </button>

                    <!-- Save Button -->
                    <button v-else type="submit" class="text-primary font-medium text-xs">
                        Save Updates
                    </button>

                    <!-- Cancel Button -->
                    <button v-if="EditProfileMobile" type="button" @click="cancelMobileEdit"
                        class="text-gray-500 font-medium text-xs">
                        Cancel
                    </button>
                </div>
            </div>
            <div
                class="pt-3 sm:pb-5 lg:pb-24 overflow-auto h-full scroll-py-2 bg-gradient-to-b from-[#E9F4FF] via-white to-white">
                <div class="mx-auto sm:w-11/12 lg:w-7/12 sm:px-1 lg:px-8 ">

                    <!-- Mobile Display------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <div class="block md:hidden">
                        <!-- Content for Mobile -->
                        <div v-if="!EditProfileMobile"
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div class="w-full h-full col-span-1 space-y-3 flex flex-col items-center">
                                
                                    <!-- Email Field -->
                                <div class="w-full">
                                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-white">Email</label>
                                    <div class="flex items-center gap-3 p-3 border-b-2 border-gray-200 dark:border-gray-600 w-full">
                                        
                                        <!-- Icon -->
                                        <span class="p-2 bg-primary rounded-md text-white text-lg font-bold">@</span>

                                        <!-- Email Value -->
                                        <span class="text-gray-900 dark:text-white text-base font-semibold break-all">
                                        <!-- {{ $page.props.auth.user.email }} --> feafafe@example.com
                                        </span>
                                    </div>
                                    </div>

                                    <!-- Password Field -->
                                    <div class="w-full">
                                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-white">Password</label>
                                    <div class="flex items-center gap-3 p-3 border-b-2 border-gray-200 dark:border-gray-600 w-full">
                                        
                                        <!-- Icon -->
                                        <span class="p-2 bg-primary rounded-md text-white text-lg font-bold">🔒</span>

                                        <!-- Hidden Password -->
                                        <span class="text-gray-900 dark:text-white text-base font-semibold tracking-widest">
                                        ••••••••
                                        </span>
                                    </div>
                                    </div>

                            </div>
                        </div>

                        <div v-if="EditProfileMobile" class="mx-auto max-w-xl h-full justify-center items-center flex flex-col gap-3">
                            <div class="w-full h-full col-span-1 space-y-4 flex flex-col items-center">

                                <!-- Email -->
                                <div class="w-full">
                                <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Change Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter your email"
                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                </div>

                                <!-- New Password -->
                                <div class="w-full">
                                <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">New Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                </div>
                                </div>

                        </div>
                    </div>
                    

                        <!-- Mobile Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->


                    <!-- Web------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <div class="hidden md:block">
                        <!-- Content for Web -->
                        <div v-if="!EditProfileWeb" class="mx-auto max-w-xl h-full justify-center items-center flex flex-col gap-3">
                            <div class="w-full space-y-6">

                                <!-- Email Field -->
                                <div class="w-full">
                                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-white">Email</label>
                                <div class="flex items-center gap-3 p-3 border-b-2 border-gray-200 dark:border-gray-600 w-full">
                                    
                                    <!-- Icon -->
                                    <span class="p-2 bg-primary rounded-md text-white text-lg font-bold">@</span>

                                    <!-- Email Value -->
                                    <span class="text-gray-900 dark:text-white text-base font-semibold break-all">
                                    <!-- {{ $page.props.auth.user.email }} --> feafafe@example.com
                                    </span>
                                </div>
                                </div>

                                <!-- Password Field -->
                                <div class="w-full">
                                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-white">Password</label>
                                <div class="flex items-center gap-3 p-3 border-b-2 border-gray-200 dark:border-gray-600 w-full">
                                    
                                    <!-- Icon -->
                                    <span class="p-2 bg-primary rounded-md text-white text-lg font-bold">🔒</span>

                                    <!-- Hidden Password -->
                                    <span class="text-gray-900 dark:text-white text-base font-semibold tracking-widest">
                                    ••••••••
                                    </span>
                                </div>
                                </div>

                                </div>
                        </div>
                    </div>
                    

                    <!-- Web Update------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

                    <div class="hidden md:block">
                        <!-- Content for Web -->
                        <div v-if="EditProfileWeb" class="mx-auto max-w-xl h-full justify-center items-center flex flex-col gap-3">
                            <div class="w-full h-full col-span-1 space-y-4 flex flex-col items-center">

                                <!-- Email -->
                                <div class="w-full">
                                <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">Change Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    placeholder="Enter your email"
                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                </div>

                                <!-- New Password -->
                                <div class="w-full">
                                <label class="block mb-1 text-sm font-semibold text-gray-700 dark:text-white">New Password</label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full h-[35px] bg-gray-50 border border-gray-300 rounded-md px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                </div>
                                </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </form>



    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed, watch, nextTick } from 'vue';
import axios from 'axios';
import { Input } from '@/Components/ui/input'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select'
import InputError from '@/Components/InputError.vue';
import { initFlowbite } from 'flowbite';


const props = defineProps({
    student: Object,
    education: Object,
    family: Object,
    siblings: Array,
    scholar: Object,
    grades: Array,
    latestgrade: Object,
    semesterGrade: Object,
    schoolyear_grade: Object,
    notify: Object,
});

const form = ref({
    email: '',
    password: '',
    confirm_password: '',
    suffix: 'N/A',
    birthdate: '',
    birthplace: '',
    age: '',
    gender: '',
    civil_status: '',
    municipality: '',
    province: '',
    street: '',
    religion: '',
    guardian_name: '',
    relationship: '',
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
    education: {
        elementary: { name: '', years: '' },
        junior: { name: '', years: '' },
        senior: { name: '', years: '' },
        college: { name: '', years: '' },
        vocational: { name: 'N/A', years: 'N/A' },
        postgrad: { name: 'N/A', years: 'N/A' },
    },
    mother: { first_name: '', last_name: '', middle_name: '', age: '', occupation: '', isDeceased: false },
    father: { first_name: '', last_name: '', middle_name: '', age: '', occupation: '', isDeceased: false },
    marital_status: '',
    monthly_income: '',
    other_income: 'N/A',
    family_housing: '',
    otherText: '',
    siblings: [{ first_name: '', last_name: '', middle_name: '', age: '', occupation: '' }],
    organizations: [{ name: '', membership_dates: '', position: '' }],
    img: null,
    imgName: null,
    imgPreview: null,
});


const formGrade = ref({
    grade: '',
    cog: '',
    semester: '',
    school_year: '',
});

const EditProfileMobile = ref(false);
const EditProfileWeb = ref(false);
const showUpload = ref(false);

// Web: Enable Edit Mode
const enableWebEdit = async () => {
    EditProfileWeb.value = true;
    EditProfileMobile.value = false;

    await nextTick();
    initFlowbite();
    initDatepicker();
};

// Web: Cancel Edit
const cancelWebEdit = () => {
    EditProfileWeb.value = false;
};

// Mobile: Enable Edit Mode
const enableMobileEdit = async () => {
    EditProfileMobile.value = true;
    EditProfileWeb.value = false;

    await nextTick();
    initFlowbite();
    initDatepicker();
};

// Mobile: Cancel Edit
const cancelMobileEdit = () => {
    EditProfileMobile.value = false;
};

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


const submit = async () => {
    try {

        router.post(`myProfile/update`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

// Handle Profile Picture Update
const updateProfilePicture = () => {
    if (!form.value.img) {
        alert("Please upload an image first.");
        return;
    }

    const formData = new FormData();
    formData.append('profile_picture', form.value.img);

    // Send FormData to backend (Example using fetch API)
    fetch('/api/update-profile-picture', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            alert('Profile updated successfully!');
        })
        .catch(error => {
            console.error('Error updating profile picture:', error);
        });
};


// Initialize QR code URL on component mount
onMounted(() => {
    initFlowbite();

    document.body.style.overflow = "hidden"; // Disable scrolling

    if (props.scholar && props.scholar.qr_code && props.scholar.qr_code !== 'NULL') {
        qrCodeUrl.value = props.scholar.qr_code;
    }

    // Also check if there's a flash message with a QR code URL
    if (props.flash?.qrCodeUrl) {
        qrCodeUrl.value = props.flash.qrCodeUrl;
    }
});

onUnmounted(() => {
    document.body.style.overflow = ""; // Reset when the component is unmounted
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
