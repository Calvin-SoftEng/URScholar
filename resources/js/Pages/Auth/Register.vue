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

const props = defineProps({
    campus: Array,
    errors: Object,
    flash: Object,
});

const form = ref({
    email: '',
    campus: '',
});

const submit = async () => {
    try {
        router.post(`/register/checking`, form.value);
        //await useForm(form.value).post(`/sponsors/create-scholarship`);
        // await form.post(`/sponsors/${props.sponsor.id}/create`)
        // resetForm();
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>

<template>
    <RegisterLayout>
        <form @submit.prevent="submit" class="fit-content flex flex-col items-center justify-center">
            <!-- header login -->
            <div class="relative flex items-center justify-center">
                <div class="absolute z-10 w-[50%] h-[50%]">
                    <img src="../../../assets/images/logo-hori-white_login.png" alt="">
                </div>
                <img src="../../../assets/images/login-bg.png" alt="" class="w-full h-[50px] object-cover">
                <div class="absolute inset-0 bg-blue-950 opacity-65"></div>
            </div>
            <!-- form login -->
            <div class="w-full fit-content relative flex flex-col gap-1 px-10 py-9">
                <div class="flex flex-col items-start justify-start mb-8">
                    <p class="font-extrabold font-sora text-2xl">Register to URScholar</p>
                    <span class="max-w-[90%] text-sm">Enter the needed details in order to create your account </span>
                </div>
                <div>
                    <InputLabel for="email" value="Email" class="font-poppins font-semibold text-md mb-2" />
                    <Input type="text" placeholder="Enter Email" v-model="form.email"
                        class="w-full h-[43px] bg-gray-50 border border-gray-300" />
                    <InputError v-if="errors?.email" :message="errors.email" class="mt-1" />
                </div>
                <div>
                    <InputLabel for="email" value="Campus" class="font-poppins font-semibold text-md mb-2" />
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
                <div v-if="errors?.credentials" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ errors.credentials }}</p>
                    <p class="text-gray-600 text-xs mt-1">If you believe this is an error, please contact your campus
                        scholarship coordinator.</p>
                </div>
                <div v-if="flash?.success" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-red-600 text-sm">{{ flash.success }}</p>
                    <p class="text-gray-600 text-xs mt-1">If you believe this is an error, please contact your campus
                        scholarship coordinator.</p>
                </div>

                <button type="submit"
                    class="bg-gradient-to-b from-blue-800 to-blue-900 text-white text-sm font-semibold w-full h-12 flex items-center justify-center rounded-md drop-shadow-sm cursor-pointer mt-5"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">REGISTER</button>
            </div>
            <div class="mt-10 mb-3 font-poppins text-sm text-gray-500">
                URScholar 2024. All rights reserved
            </div>
        </form>
    </RegisterLayout>
</template>