<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/LoginLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Tooltip } from 'primevue';

const directives = {
    Tooltip
};

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);
const failedAttempts = ref(parseInt(localStorage.getItem('failedAttempts')) || 0);
const isBlocked = ref(false);
const errorMessage = ref(localStorage.getItem('errorMessage') || '');
const blockEndTime = ref(parseInt(localStorage.getItem('blockEndTime')) || 0);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Check if user is still blocked on page load
onMounted(() => {
    const now = Date.now();
    if (blockEndTime.value > now) {
        isBlocked.value = true;
        setTimeout(unblockUser, blockEndTime.value - now);
    } else {
        localStorage.removeItem('failedAttempts');
        localStorage.removeItem('blockEndTime');
        localStorage.removeItem('errorMessage');
    }
});

const submit = () => {
    if (isBlocked.value) return;

    form.post(route('login'), {
        onError: () => {
            failedAttempts.value++;
            localStorage.setItem('failedAttempts', failedAttempts.value);

            if (failedAttempts.value >= 3) {
                blockUser();
            } else {
                errorMessage.value = 'Invalid email or password. Please try again.';
                localStorage.setItem('errorMessage', errorMessage.value);
            }
        },
        onFinish: () => {
            form.reset('password');
        },
        onSuccess: () => {
            failedAttempts.value = 0;
            localStorage.removeItem('failedAttempts');
            localStorage.removeItem('blockEndTime');
            localStorage.removeItem('errorMessage');
        },
    });
};

const blockUser = () => {
    isBlocked.value = true;
    errorMessage.value = 'Too many failed attempts. Try again in 3 minutes.';
    const unblockTime = Date.now() + 180000; // 3 minutes

    localStorage.setItem('blockEndTime', unblockTime);
    localStorage.setItem('errorMessage', errorMessage.value);
    setTimeout(unblockUser, 180000);
};

const unblockUser = () => {
    isBlocked.value = false;
    failedAttempts.value = 0;
    errorMessage.value = '';
    
    localStorage.removeItem('failedAttempts');
    localStorage.removeItem('blockEndTime');
    localStorage.removeItem('errorMessage');
};
</script>

<style >
:root {
  /* Adjust this value to match your sidebar width */
  --p-tooltip-background: white !important;
}

.p-tooltip-text {
  /* margin-left: 10px !important; */
  font-size: 12px !important;
  color: #003366 !important;
}

</style>

<template>
    <GuestLayout>
        <form @submit.prevent="submit" class="fit-content flex flex-col items-center justify-center">
            <!-- Login Header -->
            
            <div class="relative flex items-center justify-center">
                <Link :href="(route('welcome'))" class="absolute z-10 flex justify-center items-center w-full h-full" >
                    <!-- logo and branding -->
                    <div class="flex flex-row gap-2 justify-center items-center px-2" v-tooltip="'Back to Home Page'">
                        <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
                        <span class="font-poppins text-3xl font-bold text-white tracking-tight">URScholar</span>
                    </div>
                </Link>
                <!-- background -->
                <img src="../../../assets/images/login-bg.png" alt="Login Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-blue-950 opacity-65"></div>
            </div>


            <!-- Form -->
            <div class="w-full fit-content relative flex flex-col gap-1 px-10 py-9">
                <div class="flex flex-col items-start justify-start mb-8">
                    <p class="font-extrabold font-sora text-2xl">Login to your Account</p>
                    <span class="max-w-[90%] text-sm">Enter the details sent by the Scholarship Office</span>
                </div>

                <!-- Email Input -->
                <div>
                    <InputLabel for="email" value="Email" class="font-poppins font-semibold text-md mb-2" />
                    <input 
                        placeholder="name@gmail.com" 
                        name="email" 
                        type="email" 
                        id="email" 
                        v-model="form.email" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full h-12 bg-[#f1f1f1] border-0 border-b-2 border-gray-400 focus:outline-none focus:border-blue-500"
                    />
                </div>

                <!-- Password Input -->
                <div>
                    <InputLabel for="password" value="Password" class="font-poppins font-semibold text-md mt-6 mb-2"/>
                    <div class="relative w-full">
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            placeholder="Password" 
                            name="password" 
                            id="password"
                            v-model="form.password" 
                            required 
                            autocomplete="current-password"
                            class="w-full h-12 bg-[#f1f1f1] border-0 border-b-2 border-gray-400 focus:outline-none focus:border-blue-500"
                        />
                        <font-awesome-icon
                            v-if="form.password"
                            :icon="showPassword ? ['far', 'eye-slash'] : ['far', 'eye']"
                            @click="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-blue-900"
                        />
                    </div>
                </div>

                <!-- Error Message -->
                <p v-if="errorMessage" class="text-red-600 text-sm mt-2">
                    {{ errorMessage }}
                </p>

                <!-- Login Button -->
                <button 
                    class="bg-gradient-to-b from-blue-800 to-blue-900 text-white text-sm font-semibold w-full h-12 flex items-center justify-center rounded-md drop-shadow-sm cursor-pointer mt-5 transition 
                        hover:bg-gray-100 hover:bg-none hover:text-blue-900 hover:border-2 hover:border-blue-900"
                    :class="{ 'opacity-50 cursor-not-allowed': isBlocked || form.processing }"
                    :disabled="isBlocked || form.processing"
                >
                    LOGIN NOW
                </button>


                <!-- Divider -->
                <div class="flex items-center w-full my-2">
                    <hr class="flex-grow border-gray-300">
                    <span class="px-4 text-gray-400 text-xs font-normal">Don't have an account?</span>
                    <hr class="flex-grow border-gray-300">
                </div>

                <!-- Register Button -->
                <Link :href="route('register')">
                    <button 
                        class="bg-transparent text-blue-900 text-sm font-semibold w-full h-12 flex items-center justify-center rounded-md drop-shadow-sm cursor-pointer border-2 border-blue-900 transition 
                            hover:bg-gradient-to-b hover:from-blue-800 hover:to-blue-950 hover:text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': isBlocked || form.processing }"
                        :disabled="isBlocked || form.processing"
                    >
                        REGISTER NOW
                    </button>
                </Link>

                <!-- Register Link -->
                <div class="flex flex-col justify-center mt-4">
                    <p class="text-sm text-gray-600">Quit Login? 
                        <Link :href="route('welcome')" class="text-primary font-semibold hover:underline">Return to Home</Link>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-10 mb-3 font-poppins text-sm text-gray-500">
                URScholar 2024. All rights reserved 
            </div>
        </form>

    </GuestLayout>
</template>