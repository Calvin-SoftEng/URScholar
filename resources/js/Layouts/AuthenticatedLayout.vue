<template>
    <div v-if="$page.props.auth.user.usertype == 'system_admin'">
        <div class="w-full h-screen flex flex-col overflow-hidden">
        <!-- Header -->
        <MIS_Header class="w-full h-[50px] z-50" />

            <!-- Content Area -->
            <div class="pl-96 bg-white flex flex-col lg:flex-row w-full h-[calc(100vh-50px)]">
                <!-- Sidebar --> 
                <MIS_Sidebar 
                class="lg:w-5/12 w-full  lg:h-full h-auto dark:bg-dprimary dark:border-r dark:border-gray-600"
                />

                <!-- Main Content -->
                <div class="pr-96 bg-dirtywhite flex-1 lg:h-full h-auto lg:ml-0 dark:text-dprimary">
                <slot></slot>
                </div>
            </div>
        </div>
    </div>
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

import MIS_Sidebar from '@/Components/MIS/MIS_Sidebar.vue';
import MIS_Header from '@/Components/MIS/MIS_Header.vue';


import { ref } from 'vue';


</script>

<style>

</style>

