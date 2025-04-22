<template>
    <!-- sys admin ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- system admin layout -->
    <div v-if="$page.props.auth.user.usertype == 'system_admin'">
        <div class="w-full h-screen flex flex-col">
            <!-- Header -->
            <MIS_Header :branding="branding" class="w-full h-[50px] flex-none" />

            <!-- Content: Sidebar + Main -->
            <div class="pl-96 flex-1 flex overflow-hidden bg-white dark:bg-dprimary">
                <!-- Sidebar -->
                <MIS_Sidebar
                    class="lg:w-5/12 flex-shrink-0 h-full overflow-y-auto dark:bg-dprimary dark:border-r dark:border-gray-600" />

                <!-- Main Content -->
                <div class="pr-96 flex-1 h-full overflow-y-auto bg-dirtywhite dark:bg-dprimary">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>


    <!-- super_admin ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <div v-if="$page.props.auth.user.usertype == 'super_admin'">
        <div class="w-full h-screen flex flex-col">

            <!-- Header -->
            <headerTop class="w-full h-[50px] flex-none" />

            <!-- Content Area: fills remaining height -->
            <div class="flex flex-1 flex-col lg:flex-row overflow-hidden">

                <!-- Sidebar -->
                <sidebar :dataOpenSideBar="openSidebar" :clickHamburger="toggleSidebar"
                    class="lg:w-[250px] w-full lg:h-full h-auto dark:bg-dprimary dark:border-r dark:border-gray-600 flex-none" />

                <!-- Main Content (scrollable) -->
                <div class="flex-1 h-full overflow-auto dark:text-dprimary">
                    <slot></slot>
                </div>

            </div>
        </div>
    </div>


    <!-- sponsor ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <div v-if="$page.props.auth.user.usertype == 'sponsor'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
            <!-- Header -->
            <SponsorHeaderNav class="w-full h-[50px]" />

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

    <!-- coordinator ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <div v-if="$page.props.auth.user.usertype == 'coordinator'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
            <!-- Header -->
            <headerTop class="w-full h-[50px]" />

            <!-- Content Area -->
            <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">
                <!-- Sidebar -->
                <coor_sidebar :dataOpenSideBar="openSidebar" :clickHamburger="toggleSidebar"
                    class="lg:w-[250px] w-full lg:h-full h-auto dark:bg-dprimary dark:border-r dark:border-gray-600" />

                <!-- Main Content -->
                <div class="flex-1 lg:h-full h-auto lg:ml-0 dark:text-dprimary overflow-auto">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>

    <!-- cashier ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <div v-if="$page.props.auth.user.usertype == 'cashier' || $page.props.auth.user.usertype == 'head_cashier'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
            <!-- Header -->
            <headerTop class="w-full h-[50px]" />

            <!-- Content Area -->
            <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">
                <!-- Sidebar -->
                <Cashier_Sidebar :dataOpenSideBar="openSidebar" :clickHamburger="toggleSidebar"
                    class="lg:w-[250px] w-full lg:h-full h-auto dark:bg-dprimary dark:border-r dark:border-gray-600" />

                <!-- Main Content -->
                <div class="flex-1 lg:h-full h-auto lg:ml-0 dark:text-dprimary">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>

    <!-- student ---------------------------------------------------------------------------------------------------------------------------------------- -->
    <div v-if="$page.props.auth.user.usertype == 'student'">
        <div class="w-full h-screen flex flex-col overflow-hidden max-w-full overflow-x-hidden">
            <!-- Header -->
            <ScholarHeaderNav class="w-full h-[50px]" />

            <div class="w-full max-w-full overflow-x-hidden">
                <div class="flex flex-col lg:flex-row w-full h-[calc(100vh-50px)] max-w-full">
                    <!-- Sidebar -->
                    <!-- <sidebar ... /> -->

                    <!-- Main Content -->
                    <div class="flex-1 h-auto lg:h-full ml-0 mr-0 px-0 bg-white max-w-full">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
// import router from '../../router';
import headerTop from '../Components/Side_and_Head/Header.vue'
import sidebar from '../Components/Side_and_Head/Sidebar.vue'
import coor_sidebar from '../Components/Side_and_Head/Coor_Sidebar.vue'
import Cashier_Sidebar from '@/Components/Cashier/Cashier_Sidebar.vue'

import ScholarHeaderNav from '../Components/Student/Header.vue'
import SponsorHeaderNav from '../Components/Sponsor/Header.vue'

import MIS_Sidebar from '@/Components/MIS/MIS_Sidebar.vue';
import MIS_Header from '@/Components/MIS/MIS_Header.vue';


import { ref, watch, provide } from 'vue';

const props = defineProps({

    branding: Object,
});


// Sidebar state
// Sidebar state
const dataOpenSideBar = ref(true);

// Toggle sidebar state
const toggleSidebar = () => {
    dataOpenSideBar.value = !dataOpenSideBar.value;
};

// Optionally, persist the sidebar state in localStorage for page reloads
const savedSidebarState = localStorage.getItem('sidebarState') === 'true';
if (savedSidebarState !== null) {
    dataOpenSideBar.value = savedSidebarState;
}

// Persist the sidebar state to localStorage when it changes
watch(dataOpenSideBar, (newState) => {
    localStorage.setItem('sidebarState', newState);
});
provide('dataOpenSideBar', dataOpenSideBar);



</script>

<style></style>
