<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};


</script>

<template>
    <GuestLayout>
        <form @submit.prevent="submit" class="fit-content flex flex-col items-center justify-center"> 
            <!-- inside here naka comment -->
             
            <!-- header login -->
            <div class="relative flex items-center justify-center">
                <!-- logo -->
                <div class="absolute z-10 w-[50%] h-full">
                    <img src="../../../assets/images/logo-hori-white_login.png" alt="">
                </div>
                <!-- bg -->
                <img src="../../../assets/images/login-bg.png" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-blue-950 opacity-65"></div>
            </div>
            <!-- form login -->
            <div class="w-full fit-content relative flex flex-col gap-1 px-10 py-9">
                <div class="flex flex-col items-start justify-start mb-8">
                    <p class="font-extrabold font-sora text-2xl">Register to URScholar</p>
                    <span class="max-w-[90%] text-sm">Enter the details sent by the Scholarship Office</span>
                </div>
                <div>
                    <InputLabel for="email" value="Email" class="font-poppins font-semibold text-md mb-2" />
                    <input placeholder="name@gmail.com" title="Input title" name="input-name" type="email" id="email" v-model="form.email" required autofocus autocomplete="username"
                    class="w-full h-12 bg-[#f1f1f1] border-0 border-b-2 border-[myblack] focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <InputLabel for="password" value="Password" class="font-poppins font-semibold text-md mt-6 mb-2"/>

                    <div class="relative w-full">
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Password" title="Input title" name="input-name" id="password"
                        v-model="form.password" required autofocus autocomplete="current-password"
                        class="w-full h-12 bg-[#f1f1f1] border-0 border-b-2 border-[myblack] focus:outline-none focus:border-blue-500"
                        />

                        <!-- Show eye icon only when input has a value -->
                        <font-awesome-icon
                        v-if="form.password"
                        :icon="showPassword ? ['far', 'eye-slash'] : ['far', 'eye']"
                        @click="togglePassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-blue-900"
                        />
                    </div>
                </div>
                <button class="bg-gradient-to-b from-blue-800 to-blue-900 text-white text-sm font-semibold w-full h-12 flex items-center justify-center rounded-md drop-shadow-sm cursor-pointer mt-5" :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">LOGIN NOW</button>
            </div>
            <div class="mt-10 mb-3 font-poppins text-sm text-gray-500">
                URScholar 2024. All rights reserved 
            </div>
        </form>
    </GuestLayout>
</template>
