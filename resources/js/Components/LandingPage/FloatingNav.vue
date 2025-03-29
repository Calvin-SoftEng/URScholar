<template>
    <nav class="flex items-center shadow-md bg-[#003366] mx-[200px] sticky top-3 rounded-lg z-50 px-5 py-2.5">
        <!-- Logo (Left-Aligned) -->
        <!-- <div class="flex items-center p-2">
            <img src="../../../assets/images/logo-hori-white.png" alt="Logo" class="h-12" />
        </div> -->
            <div class="flex flex-row  items-center justify-center gap-2">
                <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">

                <span class="font-poppins text-3xl font-bold text-white tracking-tight">URScholar</span>
            </div>

        <!-- Centered Menu -->
        <div class="absolute left-1/2 -translate-x-1/2">
            <ul class="flex space-x-12 font-quicksand font-semibold">
                <li><a href="#" class="text-white hover:text-primary-foreground transition">Home</a></li>
                <li><a href="#" class="text-white hover:text-primary-foreground transition">About</a></li>
                <li><a href="#" class="text-white hover:text-primary-foreground transition">Contact</a></li>
            </ul>
        </div>

        <!-- Login / Register (Right-Aligned) -->
        <div class="flex items-center space-x-4 ml-auto font-poppins">
            <Link :href="route('login')" @mouseover="isHovered = true" @mouseleave="isHovered = false"
                class="font-quicksand font-semibold group flex items-center rounded-md py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                <span class="transition-transform duration-300 ease-in-out group-hover:-translate-x-1">Log in</span>
                <!-- <box-icon name="log-in-circle" type="solid" class=""></box-icon> -->
                <span class="material-symbols-rounded fill-white opacity-0 translate-x-2 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-x-0 ml-1">
                arrow_right_alt
                </span>
            </Link>

            <Link :href="route('register')"
                class="font-quicksand font-semibold bg-dsecondary rounded-md px-5 py-2 text-white ring-transparent transition hover:bg-dtext hover:text-dprimary focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Register
            </Link>
        </div>
    </nav>


</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const isHovered = ref(false);

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>
