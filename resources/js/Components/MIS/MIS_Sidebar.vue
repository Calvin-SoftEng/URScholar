<template>
    <div class="lg:max-h-screen flex flex-col border-r border-gray-200 dark:border-gray-800 transition-all duration-300" id="side-bar"
    :class="{'side-bar-visible': dataOpenSideBar}">

      <!-- desktop -->
      <div class="hidden py-5 lg:flex flex-col justify-between h-full dark:bg-dprimary">
        <div class="space-y-1" :class="[ dataOpenSideBar ? 'px-1' : 'px-1']">
          <!-- Hamburger Icon -->
            
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-xs font-semibold py-2 px-3 w-full uppercase">System Configuration</div> 
            <!-- Portal Branding, Homepage Settings -->
            
            <Link :href="route('sa.portal_branding')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/univ-settings/portal-branding' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/univ-settings/portal-branding' }">Portal Branding</span>
                </div>
              </div>
            </Link>
<!-- 
            <Link>
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2">Landing Page Settings</span>
                </div>
              </div>
            </Link> -->


            <div class="h-0.5 bg-gray-100 dark:bg-gray-800 w-full"></div>
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-xs font-semibold py-2 px-3 w-full uppercase">User and Role Management</div>
            <!-- Manage Admin and Users, Role-Based Access Controls, User Activity Logs -->

            <Link :href="route('sa.users')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/user-settings/users' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/user-settings/users' }">Manage Admin and Users</span>
                </div>
              </div>
            </Link>

            <!-- <Link :href="route('sa.user_roles')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/user-settings/system-users_roles' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/user-settings/system-users_roles' }">User Roles Control</span>
                </div>
              </div>
            </Link> -->

            <Link :href="route('sa.activity_logs')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/user-settings/activity-logs' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/user-settings/activity-logs' }">User Activity Logs</span>
                </div>
              </div>
            </Link>


            <div class="h-0.5 bg-gray-100 dark:bg-gray-800 w-full"></div>
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-xs font-semibold py-2 px-3 w-full uppercase">University Settings</div>
            <!-- School Year and Term Management, Campus Management, Course and College Management -->

            <Link :href="route('sa.sy_term')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/univ-settings/schoolyear-term' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/univ-settings/schoolyear-term' }">School Year</span>
                </div>
              </div>
            </Link>

            <Link :href="route('sa.campuses')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/univ-settings/campuses' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url === '/system_admin/univ-settings/campuses' }">Campus Management</span>
                </div>
              </div>
            </Link>

            <Link :href="route('sa.courses')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url.startsWith('/system_admin/univ-settings/course') }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url.startsWith('/system_admin/univ-settings/course') }">Course Management</span>
                </div>
              </div>
            </Link>


            <div class="h-0.5 bg-gray-100 dark:bg-gray-800 w-full"></div>
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-xs font-semibold py-2 px-3 w-full uppercase">Security and Backup</div>
            <!-- System Logs and Audit Trail, Data Backup and Restore, User Access Restrictions -->

            <!-- <Link>
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2">System Logs and Audit Trail</span>
                </div>
              </div>
            </Link> -->

            <Link :href="route('sa.archives')">
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md" :class="{ 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/system_admin/security-and-backup/archives' }">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2" :class="{ 'active text-white': $page.url.startsWith('/system_admin/security-and-backup') }">Data Backup and Restore</span>
                </div>
              </div>
            </Link>

            <!-- <Link>
              <div class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <div class="flex space-x-2 font-quicksand text-[15px] font-semibold pl-1">
                  <span class="pl-2">User Access Restrictions</span>
                </div>
              </div>
            </Link> -->
        </div>

      </div>
    </div>
    
    <!-- Mobile -->
    <div class="w-full lg:hidden flex items-center bg-white px-4 py-2 overflow-x-auto scrollbar-hidden">
        <!-- Sidebar and Navigation Section -->
        <div class="w-full flex flex-row items-center space-x-6">
          <!-- Dashboard Link -->
          <div class="flex items-center cursor-pointer">
            <Link :href="route('staff.dashboard')" class="flex items-center space-x-2 text-blue-900 font-quicksand font-semibold">
              <span class="material-symbols-rounded text-[24px]" style="color: #0D47A1;" v-tooltip.right="'Dashboard'">
                dashboard
              </span>
              <span>Dashboard</span>
            </Link>
          </div>

          <!-- Feed Link -->
          <div class="flex items-center cursor-pointer">
            <router-link to="/contact" class="flex space-x-2 font-quicksand font-semibold">
              <box-icon type='solid' name='book-content' color="#0D47A1" class="icon-size" v-tooltip.right="'Feed'"></box-icon>
              <span>Feed</span>
            </router-link>
          </div>

          <!-- Messaging Link -->
          <div class="flex items-center cursor-pointer">
            <Link :href="route('messaging.index')" class="flex items-center space-x-2 text-blue-900 font-quicksand font-semibold">
              <span class="material-symbols-rounded text-[24px]" style="color: #0D47A1;" v-tooltip.right="'Messaging'">
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
              <box-icon type='solid' name='report' color="#0D47A1" class="icon-size" v-tooltip.right="'Reports'"></box-icon>
              <span>Reports</span>
            </router-link>
          </div>

          <!-- Archives Link -->
          <div class="flex items-center cursor-pointer">
            <div class="flex space-x-2 font-quicksand font-semibold">
              <box-icon type='solid' name='archive' color="#0D47A1" class="icon-size" v-tooltip.right="'Archives'"></box-icon>
              <span>Archives</span>
            </div>
          </div>
        </div>
      </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Tooltip } from 'primevue';
import { Link } from '@inertiajs/vue3';
import { data } from 'autoprefixer';


const components = {
  Link,
  Tooltip
}

const props = defineProps({
  clickHamburger: Function
})

const methods = {
  toggle(event) {
    this.$refs.menu.toggle(event);
  }
}

const initialSidebarState = localStorage.getItem('sidebarState') === 'true';

const dataOpenSideBar = ref(initialSidebarState);

const clickHamburger = (event) => {
  dataOpenSideBar.value = !dataOpenSideBar.value
  // localStorage.setItem('sidebarState', JSON.stringify(dataOpenSideBar.value)); // Persist the state
  localStorage.setItem('sidebarState', dataOpenSideBar.value);
}

    const isScholarshipMenuOpen = ref(false)
    const isScholarsMenuOpen = ref(false)
    const menuPosition = ref(0)
    const sidebarWidth = ref(0)

    const ScholarshipItems = [
      { label: 'Sponsors', route: { name: route('sponsor.index'), active: 'sponsor.index' }, },
      { label: 'Scholarships', route: { name: route('scholarships.index'), active: 'scholarships.index' }, }
    ]

    const ScholarItems = [
      { label: 'View Scholars', route: '/scholars/view' },
      { label: 'Add Scholars', route: '/scholars/add' }
    ]

    const toggleScholarshipMenu = (event) => {
      // Calculate position relative to viewport
      const rect = event.currentTarget.getBoundingClientRect()
      menuPosition.value = rect.top
      sidebarWidth.value = rect.width // Adjust this value based on the actual width of the sidebar
      isScholarshipMenuOpen.value = !isScholarshipMenuOpen.value
      isScholarsMenuOpen.value = false
    }

    const toggleScholarsMenu = (event) => {
      // Calculate position relative to viewport
      const rect = event.currentTarget.getBoundingClientRect()
      menuPosition.value = rect.top
      sidebarWidth.value = rect.width // Adjust this value based on the actual width of the sidebar
      isScholarsMenuOpen.value = !isScholarsMenuOpen.value
      isScholarshipMenuOpen.value = false
    }

    // Close menu when clicking outside
    onMounted(() => {
      const savedState = localStorage.getItem('sidebarState');
      if (savedState !== null) {
        dataOpenSideBar.value = JSON.parse(savedState); // Parse the saved state
      }

      document.addEventListener('click', (event) => {
      const target = event.target
      if (!target.closest('.relative')) {
        isScholarshipMenuOpen.value = false
        isScholarsMenuOpen.value = false
      }
      })
    })
</script>

<style scoped>
:root {
  --sidebar-width: 230px; /* Adjust this value to match your sidebar width */
  --p-tooltip-background: #003366 !important;
}

.side-bar-visible {
  width: var(--sidebar-width) !important;
}

/* .p-tooltip {
  margin-left: 10px;
} */

.p-tooltip-text {
  /* margin-left: 10px !important; */
  font-size: 12px !important;
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
  
  