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
    <div v-if="$page.props.auth.user.usertype == 'super_admin'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
        <!-- Header -->
        <headerTop class="w-full h-[50px]" />

            <!-- Content Area -->
            <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">
                <!-- Sidebar -->
                <sidebar 
                :dataOpenSideBar="openSidebar" 
                :clickHamburger="toggleSidebar" 
                class="lg:w-[250px] w-full lg:h-full h-auto dark:bg-dprimary dark:border-r dark:border-gray-600"
                />

                <!-- Main Content -->
                <div class="flex-1 lg:h-full h-auto lg:ml-0 dark:text-dprimary">
                <slot></slot>
                </div>
            </div>
        </div>
    </div>
    <div v-if="$page.props.auth.user.usertype == 'student'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
        <!-- Header -->
        <HeaderNav class="w-full h-[50px]" />

            <!-- Content Area -->
            <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">
                <!-- Sidebar -->
                <!-- <sidebar 
                :dataOpenSideBar="openSidebar" 
                :clickHamburger="toggleSidebar" 
                class="lg:w-[250px] w-full lg:h-full h-auto bg-white"
                /> -->

                <!-- Main Content -->
                <div class="flex-1 lg:h-full h-auto lg:ml-0 bg-gray-100">
                <slot></slot>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
// import router from '../../router';
import headerTop from '../Components/Header.vue'
import sidebar from '../Components/Sidebar.vue'

import HeaderNav from '../Components/Student/Header.vue'
import { ref } from 'vue';

</script>

<style>

</style>

