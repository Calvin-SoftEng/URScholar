<template>
    <!-- Desktop Navbar -->
    <nav class="hidden lg:flex items-center shadow-md bg-[#003366] mx-auto sticky top-3 rounded-lg z-50 px-5 py-2.5 max-w-screen-xl">
        <!-- Logo and Title -->
        <div class="flex flex-row items-center justify-center gap-2">
            <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
            <span class="font-poppins text-3xl font-bold text-white tracking-tight">URScholar</span>
        </div>

        <!-- Login / Register (Right-Aligned) -->
        <div class="flex items-center space-x-4 ml-auto font-poppins">
            <Link :href="route('login')" @mouseover="isHovered = true" @mouseleave="isHovered = false"
                class="font-quicksand font-semibold group flex items-center rounded-md py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                <span class="transition-transform duration-300 ease-in-out group-hover:-translate-x-1">Log in</span>
                <span class="material-symbols-rounded fill-white opacity-0 translate-x-2 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:translate-x-0 ml-1">
                arrow_right_alt
                </span>
            </Link>

            <Link :href="route('register')"
                class="font-quicksand font-semibold bg-dsecondary rounded-md px-5 py-2 text-white ring-transparent transition hover:bg-dtext hover:text-dprimary focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Register
            </Link>
        </div>
    </nav>

    <!-- Mobile Navbar -->
    
        <!-- Mobile Navbar -->
        <nav class="lg:hidden border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-screen-lg flex items-center justify-between mx-auto h-[50px] p-2">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-4">
            <div class="pl-3">
                <div class="flex flex-row items-center justify-center gap-2">
                <img src="../../../assets/images/main_logo.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
                <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] hidden dark:block">
                <span class="font-poppins text-3xl font-bold text-navy tracking-tight dark:text-white">URScholar</span>
                </div>
            </div>
            </div>
            <!-- Hamburger Button -->
            <button id="menu-button" @click="toggleMenu" aria-expanded="isOpen" aria-label="Toggle navigation menu"
            class="inline-flex items-center justify-center p-2 w-8 h-8 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Toggle menu</span>
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            </button>
        </div>
        <!-- Mobile Menu Content -->
        <transition enter-active-class="transition-opacity duration-300 ease-out" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isOpen" id="menu-content"
                class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen max-w-screen-md mx-auto bg-primary bg-opacity-95 z-50 flex flex-col items-center justify-between pt-16 overflow-auto">
                <div class="absolute top-0 flex items-center justify-between w-full px-2 py-2">
                    <!-- Avatar (Left) -->
                    <div class="flex items-center gap-3 pl-3">
                        <img src="../../../assets/images/main_logo_white.png" alt="User Avatar" class="w-10 h-10">
                    </div>
                    <!-- Close Button (Right) -->
                    <button @click="toggleMenu" class="text-white p-2 rounded-full hover:bg-gray-800 transition-colors"
                            aria-label="Close menu">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
               <!-- Menu Links with animation -->
                <ul class="text-white h-full text-2xl font-medium space-y-6 text-center justify-center font-poppins w-full my-auto flex flex-col items-center">
                    <li class="transform transition-transform duration-300 hover:scale-110">
                        <Link :href="route('login')" class="block py-2 px-6 hover:text-gray-300 transition-colors">Login</Link>
                    </li>
                    <li class="transform transition-transform duration-300 hover:scale-110">
                        <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Register</a>
                    </li>
                    <!-- <li class="transform transition-transform duration-300 hover:scale-110">
                    <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Profile</a>
                    </li> -->
                </ul>

            </div>

        </transition>
        </nav>

</template>



<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';

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

// State to track dropdown visibility
const isDropdownOpen = ref(false);

// Toggle dropdown visibility
const toggleUserDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

// Close dropdown when clicking outside
const closeDropdown = (event) => {
  const dropdown = document.getElementById('userDropdown');
  const avatar = document.getElementById('avatarButton');

  if (isDropdownOpen.value &&
    dropdown &&
    avatar &&
    !dropdown.contains(event.target) &&
    !avatar.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

// Add click listener to document to close dropdown when clicking outside
onMounted(() => {
  initFlowbite();

  document.addEventListener('click', closeDropdown);
});

// Clean up event listener when component is unmounted
onUnmounted(() => {
  initFlowbite();

  document.removeEventListener('click', closeDropdown);
});


const isOpen = ref(false);

const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};
</script>
