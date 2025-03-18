<script setup>
import RegisterLayout from '@/Layouts/RegisterLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted, watchEffect, watch } from 'vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue, } from '@/Components/ui/select';
import { Input } from '@/Components/ui/input'
import { Tooltip } from 'primevue';

const props = defineProps({
    campus: Array,
    errors: Object,
    flash: Object,
});

const directives = {
    Tooltip
};

const form = ref({
    email: '',
    campus: '',
});

const isResending = ref(false);
const resendSuccess = ref(false);

const submit = async () => {
    try {
        router.post(`/register/checking`, form.value);
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

const resendEmail = async () => {
    // Validate form fields before sending
    if (!form.value.email || !form.value.campus) {
        return; // Don't proceed if email or campus is missing
    }

    try {
        isResending.value = true;

        await router.post(`/register/resend`, {
            email: form.value.email,
            campus: form.value.campus
        }, {
            onSuccess: () => {
                resendSuccess.value = true;
                setTimeout(() => {
                    resendSuccess.value = false;
                }, 5000); // Hide success message after 5 seconds
            },
            preserveScroll: true
        });
    } catch (error) {
        console.error('Error resending email:', error);
    } finally {
        isResending.value = false;
    }
};
</script>

<template>
    <RegisterLayout>
        <form @submit.prevent="submit" class="fit-content flex flex-col items-center justify-center">
            <!-- Logo adjusted to the left -->
            <div class="relative flex items-center justify-center w-full px-10 py-2">
                <!-- <Link :href="(route('welcome'))">
                    <div class="flex flex-row items-center justify-center gap-2 p-2" v-tooltip="'Back to Home Page'"">
                        <img src="../../../assets/images/main_logo.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">

                        <span class="font-poppins text-3xl font-bold text-navy tracking-tight">URScholar</span>
                    </div>
                </Link> -->
                
            </div>

            <div class="w-full fit-content relative flex flex-col gap-1 px-10 py-9">
                <div class="flex flex-col items-start justify-start mb-8">
                    <p class="font-extrabold font-sora text-2xl">Register to URScholar</p>
                    <span class="max-w-[90%] text-sm">Enter the needed details in order to create your account</span>
                </div>

                <div>
                    <InputLabel for="email" value="Email" class="font-poppins font-semibold text-md mb-2" />
                    <Input type="text" placeholder="Enter Email" v-model="form.email"
                        class="w-full h-[43px] bg-gray-50 border border-gray-300" />
                    <InputError v-if="errors?.email" :message="errors.email" class="mt-1" />
                </div>

                <div>
                    <InputLabel for="campus" value="Campus" class="font-poppins font-semibold text-md mb-2" />
                    <Select v-model="form.campus">
                        <SelectTrigger class="w-full h-[42px] bg-gray-50 border border-gray-300">
                            <SelectValue placeholder="Select Campus" class="text-black" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="campus in campus" :key="campus.id" :value="campus.name">
                                {{ campus.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError v-if="errors?.campus" :message="errors.campus" class="mt-1" />
                </div>

                <!-- Error message for credentials mismatch -->
                <div v-if="errors?.existing" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.existing }}</p>
                    <button type="button" @click="resendEmail" class="text-blue-600 text-xs mt-1 underline"
                        :disabled="isResending">
                        {{ isResending ? 'Sending email...' : 'Click here to resend email' }}
                    </button>
                </div>

                <div v-if="errors?.credentials" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.credentials }}</p>
                    <p class="text-gray-600 text-xs mt-1">If you believe this is an error, please contact your campus
                        scholarship coordinator.</p>
                </div>

                <div v-if="flash?.success || resendSuccess" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-green-600 text-sm">{{ flash?.success || 'Registration email sent successfully!' }}</p>
                    <p class="text-gray-600 text-xs mt-1">Please check your email inbox for login credentials.</p>
                </div>

                <button type="submit"
                    class="bg-gradient-to-b from-blue-800 to-blue-900 text-white text-sm font-semibold w-full h-12 flex items-center justify-center rounded-md drop-shadow-sm cursor-pointer mt-5"
                    :class="{ 'opacity-25': isResending }" :disabled="isResending">
                    REGISTER
                </button>

                <div class="mt-4 text-sm">
                    <span class="text-gray-600">Already have an account?</span>
                    <Link :href="route('login')" class="text-blue-600 font-semibold no-underline hover:underline"> Login</Link>
                </div>
            </div>

            <div class="mt-10 mb-3 font-poppins text-sm text-gray-500">
                URScholar 2024. All rights reserved
            </div>
        </form>

    </RegisterLayout>
</template>



<style>
:root {
  /* Adjust this value to match your sidebar width */
  --p-tooltip-background: #003366 !important;
}

.p-tooltip-text {
  /* margin-left: 10px !important; */
  font-size: 12px !important;
}

</style>