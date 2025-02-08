<!-- <script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

defineProps({
    users: Array,
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white"> -->
                <!-- Primary Navigation Menu -->
                <!-- <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex"> -->
                            <!-- Logo -->
                            <!-- <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div> -->

                            <!-- Navigation Links -->
                            <!-- <div v-if="$page.props.auth.user.role == 'coordinator'"
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('coordinator.dashboard')" :active="route().current('coordinator.dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('scholarships.index')" :active="route().current('scholarships.index')">
                                    Scholarships
                                </NavLink> -->
                                <!-- <NavLink :href="route('scholars.index')" :active="route().current('scholars.index')">
                                    Scholars
                                </NavLink> -->
                            <!-- </div>
                            <div v-else
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('student.dashboard')" :active="route().current('student.dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('available.index')" :active="route().current('available.index')">
                                    Scholarships
                                </NavLink>
                            </div>
                        </div> -->

                        <!-- <div class="hidden sm:ms-6 sm:flex sm:items-center"> -->
                            <!-- Settings Dropdown -->
                            <!-- <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template> -->

                                    <!-- <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div> -->

                        <!-- Hamburger -->
                        <!-- <div class="-me-2 flex items-center sm:hidden">
                            <button @click="
                                showingNavigationDropdown =
                                !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div> -->

                <!-- Responsive Navigation Menu -->
                <!-- <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div> -->

                    <!-- Responsive Settings Options -->
                    <!-- <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav> -->

            <!-- Page Heading -->
            <!-- <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Page Content -->
            <!-- <main>
                <slot />
            </main>
        </div>
    </div>
</template> -->

<template>
    <div v-if="$page.props.auth.user.role == 'coordinator'">
        <div class="w-full h-screen flex p-2 overflow-hidden">
            <div class="max-h-screen rounded-md bg-blue-900 flex flex-col" id="side-bar" :class="dataOpenSideBar == true ? 'side-bar-visible' : 'side-bar-close'">
                <div class="bg-blue-900 flex justify-center items-center py-2">
                    <div class="text-xl font-bold text-center flex items-center justify-center text-white" v-show="dataOpenSideBar"></div>
                    <img src="../../assets/images/ursportallogoonly.png" v-show="!dataOpenSideBar" class="p-1 w-8 h-8 ring-gray-100 dark:ring-gray-500 " alt="Avatar" />
                </div>
                <div class="flex flex-col justify-between h-full bg-blue-900">
                    <div class="menu-man text-left px-1 whitespace-nowrap ">
                    <div class="profile flex justify-center items-center text-center px-5 pb-5 pt-0">
                        <div class="text-center text-white" v-show="dataOpenSideBar">
                        <div class="flex justify-center items-center">
                            <img src="../../assets/images/ursportallogoonly.png" class="p-1 w-24 h-24 ring-gray-300 dark:ring-gray-500 mb-4" alt="Avatar" />
                        </div>
                        <h5 class="text-[30px] font-large leading-tight mb-3 font-cocogoose">URScholar</h5>
                        </div>
                    </div>
                    <div class="py-3 rounded-sm cursor-pointer text-gray-300 hover:text-white">
                        <router-link to="/" class="flex space-x-2 font-poppins pl-2">
                            <!-- <box-icon type='solid' name='dashboard' color="#f8f8fa" class="icon-size" 
                            v-tooltip.right="!dataOpenSideBar ? 'Dashboard' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon> -->
                            <span class="material-symbols-rounded" style="color: white;" v-tooltip.right="!dataOpenSideBar ? 'Dashboard' : ''">
                            dashboard
                            </span>
                        <span v-show="dataOpenSideBar">Dashboard</span>
                        </router-link>
                    </div>
            
                    <!-- cascading scholarship menu -->
                    <div class="relative py-3">
                        <!-- Main menu item -->
                        <div 
                        class="flex items-center w-full justify-between font-poppins cursor-pointer text-gray-300 hover:text-white pl-0"
                        @click="toggleScholarshipMenu"
                        >
                        <div class="flex items-center space-x-2">
                            <span v-tooltip.right="'Scholarships'"></span> 
                            <!-- <box-icon type='solid' name='graduation' color="#4a5568" class="icon-size"
                            v-tooltip.right="!dataOpenSideBar ? 'Scholarship' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon> -->
                            <span class="material-symbols-rounded" style="color: white;" v-tooltip.right="!dataOpenSideBar ? 'Scholarships' : ''">
                            checkbook
                            </span>
                        <span v-show="dataOpenSideBar">Scholarship</span>
                        </div>
                        <font-awesome-icon 
                            icon="fa-solid fa-chevron-right" 
                            v-show="dataOpenSideBar" 
                            :class="{'rotate-180': isScholarshipMenuOpen} "
                            class="mr-2 transition-transform duration-200"
                        />
                        </div>
            
                        <!-- Submenu -->
                        <Transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 -translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-x-0"
                        leave-to-class="opacity-0 -translate-x-2"
                        >
                        <div 
                            v-show="isScholarshipMenuOpen" 
                            class="fixed top-0 bg-[#003366] w-48 z-50"
                            :style="{ top: `${menuPosition}px`, left: `${sidebarWidth + 15}px` }"
                        >
                            <ul class="space-y-1">
                            <li v-for="(item, index) in ScholarshipItems" :key="index">
                                <router-link 
                                :to="item.route" 
                                class="block px-4 py-2 text-md font-poppins text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-200"
                                >
                                {{ item.label }}
                                </router-link>
                            </li>
                            </ul>
                        </div>
                        </Transition>
                    </div>
            
                    <!-- cascading scholars menu -->
                    <div class="relative py-3">
                        <!-- Main menu item -->
                        <div 
                        class="flex items-center w-full justify-between font-poppins cursor-pointer text-gray-300 hover:text-white pl-0"
                        @click="toggleScholarsMenu"
                        >
                        <div class="flex items-center space-x-2">
                            <span v-tooltip.right="'Scholars'"></span> 
                            <!-- <font-awesome-icon color="#f8f8fa" icon="fas fa-user-graduate" class="icon-size" 
                            v-tooltip.right="!dataOpenSideBar ? 'Scholars' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </font-awesome-icon> -->
                            <span class="material-symbols-rounded" style="color: white;" v-tooltip.right="!dataOpenSideBar ? 'Scholars' : ''">
                            school
                            </span>
                        <span v-show="dataOpenSideBar">Scholars</span>
                        </div>
                        <font-awesome-icon 
                            icon="fa-solid fa-chevron-right" 
                            v-show="dataOpenSideBar" 
                            :class="{'rotate-180': isScholarsMenuOpen} "
                            class="mr-2 transition-transform duration-200"
                        />
                        </div>
            
                        <!-- Submenu -->
                        <Transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 -translate-x-2"
                        enter-to-class="opacity-100 translate-x-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-x-0"
                        leave-to-class="opacity-0 -translate-x-2"
                        >
                        <div v-show="isScholarsMenuOpen" 
                            class="fixed top-0 bg-[#003366] w-48 z-50"
                            :style="{ top: `${menuPosition}px`, left: `${sidebarWidth + 15}px` }">
                            <ul class="space-y-1">
                            <li v-for="(item, index) in ScholarItems" :key="item.label">
                                <router-link 
                                :to="item.route" 
                                class="block px-4 py-2 text-md font-poppins text-gray-300 hover:text-white hover:bg-gray-700 transition-colors duration-200"
                                >
                                {{ item.label }}
                                </router-link>
                            </li>
                            </ul>
                        </div>
                        </Transition>
                    </div>
            
            
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <router-link to="/contact" class="flex space-x-2 font-poppins pl-2">
                        <box-icon type='solid' name='message-dots' color="#f8f8fa" class="icon-size"
                        v-tooltip.right="!dataOpenSideBar ? 'Messaging' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon>
                        <span v-show="dataOpenSideBar">Messaging</span>
                        </router-link>
                    </div>
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <router-link to="/contact" class="flex space-x-2 font-poppins pl-2">
                        <box-icon type='solid' name='book-content' color="#f8f8fa" class="icon-size"
                        v-tooltip.right="!dataOpenSideBar ? 'Feed' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon>
                        <span v-show="dataOpenSideBar">Feed</span>
                        </router-link>
                    </div>
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <router-link to="/customer" class="flex space-x-2 font-poppins pl-2">
                        <!-- <font-awesome-icon 
                            color="#f8f8fa" 
                            icon="fa-solid fa-hand-holding-dollar" 
                            class="icon-size" 
                            v-tooltip.right="!dataOpenSideBar ? 'Payouts' : ''"
                        /> -->
                        <span class="material-symbols-rounded" style="color: white;" v-tooltip.right="!dataOpenSideBar ? 'Payouts' : ''">
                            price_check
                            </span>
                        <span v-show="dataOpenSideBar">Payouts</span>
                        </router-link>
                    </div>
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <router-link to="/activation" class="flex space-x-2 font-poppins pl-2">
                        <box-icon type='solid' name='report' color="#f8f8fa" class="icon-size"
                        v-tooltip.right="!dataOpenSideBar ? 'Reports' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon>
                            <!-- <span class="material-symbols-rounded" style="color: white;" v-tooltip.right="!dataOpenSideBar ? 'Reports' : ''">
                            request_quote
                            </span> -->
                        <span v-show="dataOpenSideBar">Reports</span>
                        </router-link>
                    </div>
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <div class="flex space-x-2 font-poppins pl-2">
                        <box-icon type='solid' name='archive' color="#f8f8fa" class="icon-size"
                        v-tooltip.right="!dataOpenSideBar ? 'Archives' : ''">
                            <span v-show="dataOpenSideBar"></span>
                            </box-icon>
                        <span v-show="dataOpenSideBar">Archives</span>
                        </div>
                    </div>
                    </div>
                    <!-- <div class="menu-man text-left px-2 justify-self-end whitespace-nowrap">
                    <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
                        <a href="https://website-nuxt-front.vercel.app/" target="_blank" class="px-2 flex space-x-2"><span class="pi pi-question" v-tooltip.right="'About Me'"></span> <span v-show="dataOpenSideBar">About Me</span></a>
                    </div>
                    </div> -->
                    <!-- account -->
                    <div class="menu-man pb-5 w-full text-left px-2 justify-self-end">
                    <!-- Dropdown Toggle Button -->
                    <button id="dropdownTopButton" data-dropdown-toggle="dropdownTop" data-dropdown-placement="top" type="button"
                        v-show="dataOpenSideBar" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-2 py-2.5 inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 justify-between">
                        <div class="w-full flex px-1 items-center justify-between">
                        <!-- Avatar (displayed only when !dataOpenSideBar) -->
                        <div class="avatar" v-show="dataOpenSideBar">
                            <div class="w-8 h-8 rounded-full ring ring-offset-2">
                            <img
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                                alt="Avatar"
                            />
                            </div>
                        </div>
                            <!-- Text (displayed only when dataOpenSideBar) -->
                        <div class="flex flex-col items-start">
                            <span v-show="dataOpenSideBar">Account Name</span>
                            <span v-show="dataOpenSideBar">Admin</span>
                        </div>
                        <div>
                            <svg
                            class="w-2.5 h-2.5 ms-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 10 6"
                            >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5 5 1 1 5"
                            />
                            </svg>
                        </div>
                        </div>
                        
                    </button>
            
                    <!-- Dropdown Menu -->
                    <div id="dropdownTop" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownTopButton">
                        <li class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <span class="material-symbols-rounded" style="color: black;">
                            person
                            </span>
                            <a href="#" class="font-poppins">
                            Profile
                            </a>
                        </li>
                        <li class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <span class="material-symbols-rounded" style="color: black;">
                            logout
                            </span>
                            <form method="POST" :action="route('logout')" class="inline">
                            <!-- @csrf -->
                            <button type="submit" class="font-poppins">
                                Logout
                            </button>
                            </form>
                        </li>
                        </ul>
                    </div>
            
                        <!-- Dropdown Toggle Button if collapsed-->
                        <button id="dropdownTopButton" data-dropdown-toggle="dropdownTopcollapsed" data-dropdown-placement="top" type="button"
                        v-show="!dataOpenSideBar" class="flex justify-center w-full">
                        <div class="w-full flex px-1 items-center justify-between">
                            <!-- Avatar (displayed only when !dataOpenSideBar) -->
                            <div class="avatar" v-show="!dataOpenSideBar">
                            <div class="w-6.5 h-6.5 rounded-full ring ring-offset-2">
                                <img
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                                alt="Avatar"
                                />
                            </div>
                            </div>
                        </div>
                        </button>
            
                        <!-- Dropdown Menu if collapsed -->
                        <div id="dropdownTopcollapsed" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-10 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownTopButton">
                            <li class="flex items-center space-x-1 px-2 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <span class="material-symbols-rounded" style="color: black;" v-tooltip.right="!dataOpenSideBar ? 'Account' : ''">
                                person
                            </span>
                            </li>
                            <li :href="route('logout')" method="post" as="button" class="flex items-center space-x-1 px-2 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <span class="material-symbols-rounded" style="color: black;" v-tooltip.right="!dataOpenSideBar ? 'Logout' : ''">
                                logout
                            </span>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                </div>
            <div class="w-full h-full">
                <headerTop :dataOpenSideBar="openSidebar" @click="toggleSidebar" />
                
                <div class="w-full h-[calc(100vh-50px)]">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// import router from '../../router';
import headerTop from '../Components/Side_and_Head/Header.vue'
// import sidebar from '../Components/Sidebar.vue'
// import { ref } from 'vue';
import { ref, onMounted } from 'vue'
import { Tooltip } from 'primevue';
import { Link } from '@inertiajs/vue3';
import { data } from 'autoprefixer';

const openSidebar = ref(true);

const defineProps = {
    users: Array,
    dataOpenSideBar: Boolean,
};
const methods = {
    toggleSidebar() {
        openSidebar.value = !openSidebar.value;
    },
};

const mounted = () => {
    window.initFlowbite();
};

const directives = {
    tooltip: Tooltip
};

    const isScholarshipMenuOpen = ref(false)
    const isScholarsMenuOpen = ref(false)
    const menuPosition = ref(0)
    const sidebarWidth = ref(0)

    const ScholarshipItems = [
      { label: 'View Scholarships', route: '/scholarships/view' },
      { label: 'Add Scholarships', route: '/scholarships/add' }
    ];

    const ScholarItems = [
      { label: 'View Scholars', route: '/scholars/view' },
      { label: 'Add Scholars', route: '/scholars/add' }
    ];

    const toggleScholarshipMenu = (event) => {
      // Calculate position relative to viewport
      const rect = event.currentTarget.getBoundingClientRect()
      menuPosition.value = rect.top
      sidebarWidth.value = rect.width // Adjust this value based on the actual width of the sidebar
      isScholarshipMenuOpen.value = !isScholarshipMenuOpen.value
    };

    const toggleScholarsMenu = (event) => {
      // Calculate position relative to viewport
      const rect = event.currentTarget.getBoundingClientRect()
      menuPosition.value = rect.top
      sidebarWidth.value = rect.width // Adjust this value based on the actual width of the sidebar
      isScholarsMenuOpen.value = !isScholarsMenuOpen.value
    };

    // Close menu when clicking outside
    onMounted(() => {
      document.addEventListener('click', (event) => {
      const target = event.target
      if (!target.closest('.relative')) {
        isScholarshipMenuOpen.value = false
        isScholarsMenuOpen.value = false
      }
      });
    });

    // const toggleSidebar = () => {
    //         openSidebar.value = !openSidebar.value;
    // };

    // data() {
    //     return {
    //         openSidebar,
    //         isScholarshipMenuOpen,
    //         isScholarsMenuOpen,
    //         menuPosition,
    //         sidebarWidth,
    //         ScholarshipItems,
    //         ScholarItems,
    //     };
    // }
// export default {
    // defineProps: {
    //     users: Array,
    // },
    // components: { headerTop, sidebar },
    // data() {
    //     return {
    //         openSidebar: true
    //     }
    // },
    // methods: {
    //     toggleSidebar() {
    //         this.openSidebar = !this.openSidebar
    //     }
    // }
// }
</script>



<style scoped>
:root {
  --sidebar-width: 240px; /* Adjust this value to match your sidebar width */
}

.side-bar-visible {
  width: var(--sidebar-width) !important;
}

.p-tooltip-text {
  font-size: 10px !important;
}

#side-bar {
  overflow: hidden;
  transition: 300ms;
}

.side-bar-close {
  width: 50px !important;
}

.icon-size {
  width: 22px;
  height: 22px;
}

/* header */
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


