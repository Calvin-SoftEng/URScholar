<template>
    <div class="submenu-width lg:max-h-screen bg-white flex flex-col border-r transition-all duration-300"
        id="side-bar">
        <!-- desktop -->
        <div class="hidden lg:flex flex-col justify-between h-full bg-white">
            <div class="menu-man text-left whitespace-nowrap mt-5 p-2">

                <div class="py-3 rounded-md cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>User Management</span>
                    </router-link>
                </div>

                <!-- <Link :href="(route('coordinator.dashboard'))"> -->
                <div class="py-3 rounded-sm cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <div class="flex items-center space-x-2 font-quicksand font-semibold pl-2 text-[16px]">
                        <span>University Calendar</span>
                    </div>
                </div>
                <!-- </Link> -->

                <div class="py-3 rounded-md cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <router-link to="/contact" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>Mission and Vision</span>
                    </router-link>
                </div>

                <!-- <Link :href="route('messaging.index')"> -->
                <div class="py-3 rounded-md cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <div class="flex items-center space-x-2 font-quicksand font-semibold pl-2 text-[16px]">
                        <span>Archives</span>
                    </div>
                </div>
                <!-- </Link> -->

                <div class="py-3 rounded-md cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>University</span>
                    </router-link>
                </div>
                <div class="py-3 rounded-md cursor-pointer text-blue-900 hover:bg-gray-100 hover:rounded-md">
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>Courses</span>
                    </router-link>
                </div>
            </div>
            <!-- <div class="menu-man text-left px-2 justify-self-end whitespace-nowrap">
        <div class="py-3 rounded-md cursor-pointer text-gray-300 hover:text-white">
            <a href="https://website-nuxt-front.vercel.app/" target="_blank" class="px-2 flex space-x-2"><span class="pi pi-question" v-tooltip.right="'About Me'"></span> <span v-show="dataOpenSideBar">About Me</span></a>
        </div>
        </div> -->
        </div>
    </div>
    <!-- Mobile -->
    <div class="w-full lg:hidden flex items-center bg-white px-4 py-2 overflow-x-auto scrollbar-hidden">
        <!-- Sidebar and Navigation Section -->
        <div class="w-full flex flex-row items-center space-x-6">
            <!-- Dashboard Link -->
            <div class="flex items-center cursor-pointer">
                <Link 
                    class="flex items-center space-x-2 text-blue-900 font-quicksand font-semibold">
                <span class="material-symbols-rounded text-[24px]" style="color: #0D47A1;"
                    v-tooltip.right="'Dashboard'">
                    dashboard
                </span>
                <span>Dashboard</span>
                </Link>
            </div>

            <!-- Feed Link -->
            <div class="flex items-center cursor-pointer">
                <router-link to="/contact" class="flex space-x-2 font-quicksand font-semibold">
                    <box-icon type='solid' name='book-content' color="#0D47A1" class="icon-size"
                        v-tooltip.right="'Feed'"></box-icon>
                    <span>Feed</span>
                </router-link>
            </div>

            <!-- Messaging Link -->
            <div class="flex items-center cursor-pointer">
                <Link 
                    class="flex items-center space-x-2 text-blue-900 font-quicksand font-semibold">
                <span class="material-symbols-rounded text-[24px]" style="color: #0D47A1;"
                    v-tooltip.right="'Messaging'">
                    forum
                </span>
                <span>Messaging</span>
                </Link>
            </div>

            <!-- Payouts Link -->
            <div class="flex items-center cursor-pointer">
                <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold">
                    <span class="material-symbols-rounded" style="color: #0D47A1;" v-tooltip.right="'Payouts'">
                        price_check
                    </span>
                    <span>Payouts</span>
                </router-link>
            </div>

            <!-- Reports Link -->
            <div class="flex items-center cursor-pointer">
                <router-link to="/activation" class="flex space-x-2 font-quicksand font-semibold">
                    <box-icon type='solid' name='report' color="#0D47A1" class="icon-size"
                        v-tooltip.right="'Reports'"></box-icon>
                    <span>Reports</span>
                </router-link>
            </div>

            <!-- Archives Link -->
            <div class="flex items-center cursor-pointer">
                <div class="flex space-x-2 font-quicksand font-semibold">
                    <box-icon type='solid' name='archive' color="#0D47A1" class="icon-size"
                        v-tooltip.right="'Archives'"></box-icon>
                    <span>Archives</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Tooltip } from 'primevue';
import { Link } from '@inertiajs/vue3';

const components = {
    Link,
    Tooltip
}
</script>

<style scoped>
:root {
    --sidebar-width: 400px;
    /* Adjust this value to match your sidebar width */
}

.submenu-width {
    width: var(--sidebar-width) !important;
}

.p-tooltip-text {
    margin-left: 10px;
    font-size: 13px !important;
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
</style>