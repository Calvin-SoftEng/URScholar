<template>
  <div class="w-full bg-[F8F8FA] shadow-lg">
    <!-- desktop -->
    <div class="hidden lg:flex justify-between items-center h-[50px] place-content-center px-10">
      <div class="flex items-center space-x-4">
          <div class="pl-3">
            <!-- <img src="../../../assets/images/logo-hori.png" alt="Light Mode Logo" class="w-[180px] h-[40px] dark:hidden"> -->

            <div class="flex flex-row  items-center justify-center gap-2">
              <img src="../../../assets/images/main_logo.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
              <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] hidden dark:block">

              <span class="font-poppins text-3xl font-bold text-navy tracking-tight dark:text-white">URScholar</span>
            </div>
            <!-- Dark Mode Logo -->
            <!-- <img src="../../../assets/images/logo-hori-white.png" alt="Dark Mode Logo"
              class="w-[180px] h-[40px] hidden dark:block"> -->
          </div>
        <ul class="flex pl-10 space-x-10 font-inter font-semibold text-navy">
          <!-- <li>
            <Link :href="(route('student.dashboard'))">
            <p class="text-primary-foreground hover:text-primary-foreground transition">Feed</p>
            </Link>
          </li> -->
          <li>
            <Link :href="(route('student.dashboard'))">
            <p class="text-primary-foreground hover:text-primary-foreground transition">My Scholarships</p>
            </Link>
          </li>
        </ul>
      </div>

      <!-- Theme and Notification -->
      <div class="flex items-center space-x-4 pr-5">
        <!-- Theme Toggle -->
        <div
          class="border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <label class="swap swap-rotate h-[25px] w-[25px]">
            <!-- Hidden Checkbox -->
            <input type="checkbox" class="theme-controller hidden" value="synthwave" @change="toggleTheme" />
            <!-- Sun Icon -->
            <svg class="swap-off h-full w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
            </svg>
            <!-- Moon Icon -->
            <svg class="swap-on h-full w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
            </svg>
          </label>
        </div>

        <!-- notification -->
        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification"
          class="relative border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 20">
            <path
              d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
          </svg>

          <div
            class="absolute block w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full -top-0.5 start-7 dark:border-gray-900">
          </div>
        </button>

        <!-- avatar -->
        <div v-if="$page.props.auth.user.picture">
          <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" @click.stop="toggleUserDropdown"
            data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
            :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="picture">
        </div>
        <div v-else>
          <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" @click.stop="toggleUserDropdown"
            data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
            :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="picture">
        </div>


        <!-- Notifs Dropdown menu -->
        <div id="dropdownNotification"
          class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-800 dark:divide-gray-700"
          aria-labelledby="dropdownNotificationButton">
          <div
            class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-800 dark:text-white">
            Notifications
          </div>
          <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
              <div class="flex-shrink-0">
                <img class="rounded-full w-11 h-11" src="" alt="Jese image">
                <div
                  class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full dark:border-gray-800">
                  <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 18 18">
                    <path
                      d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
                    <path
                      d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
                  </svg>
                </div>
              </div>
              <div class="w-full ps-3">
                <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">New message from <span
                    class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's up? All set for
                  the presentation?"</div>
                <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
              </div>
            </a>
          </div>
          <a href="#"
            class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white">
            <div class="inline-flex items-center ">
              <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                <path
                  d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
              </svg>
              View all
            </div>
          </a>
        </div>
      </div>
    </div>
    
    <!-- avatar dropdown -->
        <!-- Dropdown menu -->
        <div id="userDropdown" v-show="isDropdownOpen"
          class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
          <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
            <div>{{ $page.props.auth.user.name }}</div>
            <div class="font-medium truncate">{{ $page.props.auth.user.email }}</div>
          </div>
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
            <li>
              <Link :href="(route('student.profile'))"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</Link>
            </li>
          </ul>
          <div class="py-1 text-left">
            <Link :href="route('logout')" method="post" as="button"
              class="w-full px-4 items-start justify-start py-2 text-sm text-gray-700 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
            Sign out
            </Link>
          </div>
        </div>

    
      <!-- Mobile Navbar -->
      <nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto h-[50px] p-2">
          <div class="flex items-center space-x-4">
            <div class="pl-3">
              <div class="flex flex-row  items-center justify-center gap-2">
                <img src="../../../assets/images/main_logo.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
                <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo" class="w-[40px] h-[40px] hidden dark:block">

                <span class="font-poppins text-3xl font-bold text-navy tracking-tight dark:text-white">URScholar</span>
              </div>
            </div>
          </div>

          <!-- Hamburger Button -->
          <button 
            id="menu-button" 
            @click="toggleMenu"
            aria-expanded="isOpen"
            aria-label="Toggle navigation menu"
            class="inline-flex items-center justify-center p-2 w-10 h-10 text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          >
            <span class="sr-only">Toggle menu</span>
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>

        <transition 
          enter-active-class="transition-opacity duration-300 ease-out" 
          enter-from-class="opacity-0" 
          enter-to-class="opacity-100"
          leave-active-class="transition-opacity duration-200 ease-in" 
          leave-from-class="opacity-100" 
          leave-to-class="opacity-0"
        >
          <div v-if="isOpen" 
            id="menu-content"
            class="fixed inset-0 bg-primary bg-opacity-95 z-50 flex flex-col items-center justify-center overflow-hidden"
          >

            <div class="absolute top-0 flex items-center justify-between w-full px-2 py-2">
              <!-- Avatar (Left) -->
              <div class="flex items-center gap-3 pl-3">
                <img 
                  src="../../../assets/images/main_logo_white.png"
                  alt="User Avatar" 
                  class="w-10 h-10 "
                >
              </div>

              <!-- Close Button (Right) -->
              <button 
                @click="toggleMenu" 
                class="text-white p-2 rounded-full hover:bg-gray-800 transition-colors"
                aria-label="Close menu"
              >
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div v-if="$page.props.auth.user.picture" class="flex justify-center mb-5 px-4">
              <div class="bg-white shadow-lg rounded-xl px-10 py-5 w-full max-w-xs text-center">
                <!-- Avatar -->
                <img 
                  :src="`/storage/user/profile/${$page.props.auth.user.picture}`" 
                  alt="User Avatar" 
                  class="w-20 h-20 rounded-full border border-gray-300 dark:border-gray-600 mx-auto"
                >
                
                <!-- Name & Scholar ID -->
                <div class="mt-3">
                  <span class="block font-semibold text-gray-900 dark:text-gray-100">Name</span>
                  <span class="block text-gray-600 dark:text-gray-400">URScholar ID</span>
                </div>
              </div>
            </div>

            <!-- Menu Links with animation -->
            <ul class="text-white text-2xl font-medium space-y-8 text-center font-poppins">
              <li class="transform transition-transform duration-300 hover:scale-110">
                <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Feed</a>
              </li>
              <li class="transform transition-transform duration-300 hover:scale-110">
                <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Scholarship</a>
              </li>
              <li class="transform transition-transform duration-300 hover:scale-110">
                <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Profile</a>
              </li>
              <li class="transform transition-transform duration-300 hover:scale-110">
                <Link :href="route('logout')" method="post" as="button">
                <a href="#" class="block py-2 px-6 hover:text-gray-300 transition-colors">Logout</a>
                </Link>
              </li>
            </ul>
          </div>
        </transition>
      </nav>


    
  </div>
</template>

<script setup>
import { ref, defineProps, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { initFlowbite } from 'flowbite';

// Props
defineProps({
  dataOpenSideBar: Boolean,
  clickHamburger: Function
});


// Menu items
const items = ref([
  {
    label: 'Logout',
    icon: 'pi pi-refresh',
    command: () => {
      console.log('Logged out'); // Replace with actual logout logic
    }
  },
  {
    label: 'Change Password',
    icon: 'pi pi-times',
    command: () => {
      console.log('Change Password'); // Replace with actual password change logic
    }
  }
]);

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



<style scoped>
.swap-rotate .swap-on {
  display: none;
}

.swap-rotate .swap-off {
  display: block;
}

.swap-rotate:hover .swap-on {
  display: block;
}

.swap-rotate:hover .swap-off {
  display: none;
}
</style>