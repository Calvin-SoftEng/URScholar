<template>
    <div class="submenu-width lg:max-h-screen dark:bg-dprimary flex flex-col border-r transition-all duration-300"
        id="side-bar">
        <!-- desktop -->
        <div class="hidden lg:flex flex-col justify-between h-full bg-white dark:bg-dprimary">
            <div class="text-left whitespace-nowrap mt-9">

                <Link :href="(route('settings.index'))">
                <div class="py-3 px-3 cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer" :class="[{ 'active text-dtext dark:text-dtext dark:bg-primary font-semibold border-r-4 border-primary dark:border-dtext bg-gray-100': $page.url === '/settings/sponsors' }]">
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>Sponsors</span>
                    </router-link>
                </div>
                </Link>

                <Link :href="(route('settings.adding'))">
                <div class="py-3 px-3 cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer" :class="[{ 'active text-dtext dark:text-dtext dark:bg-primary font-semibold border-r-4 border-primary dark:border-dtext bg-gray-100': $page.url === '/settings/adding-students' }]">
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>University Students</span>
                    </router-link>
                </div>
                </Link>

                <Link :href="(route('settings.scholarship_forms'))">
                <div class="py-3 px-3 cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer" :class="[{ 'active text-dtext dark:text-dtext dark:bg-primary font-semibold border-r-4 border-primary dark:border-dtext bg-gray-100': $page.url === '/settings/scholarship-forms' }]" >
                    <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                        <span>Eligibility Standards</span>
                    </router-link>
                </div>
                </Link>

                <Link :href="route('settings.verification_forms')">
                    <div class="py-3 px-3 cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer" :class="[{ 'active text-dtext dark:text-dtext dark:bg-primary font-semibold border-r-4 border-primary dark:border-dtext bg-gray-100': $page.url === '/settings/verification-forms' }]" >
                        <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                            <span>Verification Forms</span>
                        </router-link>
                    </div>
                </Link>

                <!-- <Link :href="route('messaging.index')"> -->
                <div class="py-3 px-3 cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer">
                    <div class="flex items-center space-x-2 font-quicksand font-semibold pl-2 text-[16px]">
                        <span>Archives</span>
                    </div>
                </div>
                <!-- </Link> -->

            </div>
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