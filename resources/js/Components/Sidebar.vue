<template>
    <div class="lg:max-h-screen flex flex-col border-r border-gray-200 dark:border-r dark:border-gray-600 transition-all duration-300" id="side-bar"
    :class="{'side-bar-visible': dataOpenSideBar, 'side-bar-close': !dataOpenSideBar}">
      <!-- desktop -->
      <div class="hidden lg:flex flex-col justify-between h-full dark:bg-dprimary">
        <div class="space-y-1" :class="[ dataOpenSideBar ? 'px-1' : 'px-1']">
          <!-- Hamburger Icon -->
            <div class="p-1 cursor-pointer flex items-center" @click="clickHamburger"
              :class="[
                dataOpenSideBar ? 'justify-between' : 'justify-center',
                'min-h-[50px]'
              ]">
              <span v-if="dataOpenSideBar" class="text-blue-900 opacity-90 font-poppins text-sm font-semibold dark:text-dtext">
                Main Menu
              </span>

              <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" 
                class="w-6 h-6 pointer-events-none transition-transform duration-300 text-blue-900 dark:text-dtext"
                :class="{ 'rotate-180': dataOpenSideBar }">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 8a4 4 0 014-4h10a4 4 0 014 4v8a4 4 0 01-4 4H7a4 4 0 01-4-4V8zm13-2.5A2.5 2.5 0 0013.5 8v8a2.5 2.5 0 002.5 2.5h1a2.5 2.5 0 002.5-2.5V8A2.5 2.5 0 0017 5.5h-1zm-3.123 13A3.984 3.984 0 0112 16V8c0-.946.328-1.815.877-2.5H7A2.5 2.5 0 004.5 8v8A2.5 2.5 0 007 18.5h5.877z"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.25 12a.75.75 0 01-.22.53l-2 2a.75.75 0 01-1.06-1.06L8.44 12l-1.47-1.47a.75.75 0 111.06-1.06l2 2c.141.14.22.331.22.53z"></path>
              </svg>
            </div>
            
            <Link :href="(route('super_admin.dashboard'))" >
              <div v-tooltip.right="!dataOpenSideBar ? 'Dashboard' : ''" :class="[
                'py-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md',
                { 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url === '/admin/dashboard' }
              ]">
              <div :class="['flex items-center space-x-2 text-blue-900 dark:text-dtext font-quicksand font-semibold pl-2 text-[16px]']">
                <span :class="['material-symbols-rounded', { 'active text-dtext': $page.url === '/admin/dashboard' }]" :style="['text-dtext hover:text-white', { 'active text-dtext hover:text-white': $page.url === '/admin/dashboard' }]"
                >
                dashboard
                </span>
                <span v-show="dataOpenSideBar" :class="['pl-2', { 'active text-dtext': $page.url === '/admin/dashboard' }]">Dashboard</span>
              </div>
              </div>
            </Link>

          <!-- cascading scholarship menu -->
          <div :class="['relative py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md', { 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships') }]">
            <!-- Main menu item -->
            <div v-tooltip.right="!dataOpenSideBar ? 'Scholarships' : ''" @click="toggleScholarshipMenu" :class="['flex items-center w-full justify-between font-quicksand font-semibold cursor-pointer text-blue-900 dark:text-dtext pl-0', { 'active text-primary': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships') }]"
            >
              <div class="flex items-center space-x-2">
                <span v-tooltip.right="'Scholarships'"></span> 
                <span :class="['material-symbols-rounded', { 'active text-dtext': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships') }]" :style="['text-dtext hover:text-white', { 'active text-dtext hover:text-white': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships') }]"
                >
                checkbook
                </span>
              <span v-show="dataOpenSideBar" :class="['pl-2', { 'active text-dtext': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships') }]">Scholarship</span>
              </div>
              <font-awesome-icon 
              icon="fa-solid fa-chevron-right" 
              v-show="dataOpenSideBar" 
              :class="{
                'rotate-180': isScholarshipMenuOpen,
                'active text-white': $page.url.startsWith('/sponsors') || $page.url.startsWith('/scholarships')
              }"
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
                class="fixed top-0 bg-white dark:bg-dprimary w-48 z-50 cursor-pointer"
                :style="{ top: `${menuPosition - 9}px`, left: `${sidebarWidth + 8}px` }"
              >
                <ul class="space-y-0">
                  <li v-for="(item, index) in ScholarshipItems" :key="index">
                    <Link
                      :href="item.route.name"
                      class="block px-4 py-2 text-md font-quicksand font-bold border border-gray-200 dark:bg-dsecondary dark:border-gray-600 text-blue-900 hover:bg-gray-100 dark:text-white dark:hover:bg-dprimary transition-colors duration-200"
                    >
                      {{ item.label }}
                    </Link>
                  </li>
                </ul>
              </div>
            </Transition>
          </div>
  
          <!-- cascading scholars menu -->
          <div class="relative py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
            <!-- Main menu item -->
            <div v-tooltip.right="!dataOpenSideBar ? 'Scholars' : ''"
              class="flex items-center w-full justify-between font-quicksand font-semibold cursor-pointer text-blue-900 dark:text-dtext pl-0"
              @click="toggleScholarsMenu"
            >
              <div class="flex items-center space-x-2">
                <span v-tooltip.right="''"></span> 
                <span class="material-symbols-rounded text-blue-900 dark:text-dtext">
                school
                </span>
              <span v-show="dataOpenSideBar" class="pl-2">Scholars</span>
              </div>
              <font-awesome-icon 
                icon="fa-solid fa-chevron-right" 
                v-show="dataOpenSideBar" 
                :class="{
                  'rotate-180': isScholarsMenuOpen,
                  
                  } "
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
                class="fixed top-0 bg-white w-48 z-50 cursor-pointer"
                :style="{ top: `${menuPosition - 9}px`, left: `${sidebarWidth + 8}px` }">
                <ul class="space-y-0">
                  <li v-for="(item, index) in ScholarItems" :key="item.label">
                    <router-link 
                      :to="item.route" 
                      class="block px-4 py-2 text-md font-quicksand font-bold border border-gray-200 dark:border-gray-600 dark:bg-dsecondary text-blue-900 hover:bg-gray-100 dark:text-white dark:hover:bg-dprimary transition-colors duration-200"
                    >
                      {{ item.label }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </Transition>
          </div>
  
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-sm font-semibold py-2 px-1 w-full" :class="{ 'opacity-0': !dataOpenSideBar }">Communication</div>

            <div v-tooltip.right="!dataOpenSideBar ? 'Feed' : ''" class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
              <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                <span class="material-symbols-rounded text-blue-900 dark:text-dtext" >
                  rate_review
                  </span>
                <span v-show="dataOpenSideBar" class="pl-2">Feed</span>
              </router-link>
            </div>
            
            <Link :href="route('messaging.index')">
              <div v-tooltip.right="!dataOpenSideBar ? 'Messaging' : ''" class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <div class="flex items-center space-x-2 font-quicksand font-semibold pl-2 text-[16px]">
                    <span class="material-symbols-rounded text-blue-900 dark:text-dtext">
                    forum
                    </span>
                  <span v-show="dataOpenSideBar" class="pl-2">Messaging</span>
                  </div>
              </div>
            </Link>
          
            <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-sm font-semibold py-2 px-1 w-full" :class="{ 'opacity-0': !dataOpenSideBar }">Docs</div>

              <div v-tooltip.right="!dataOpenSideBar ? 'Payouts' : ''" class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                  <span class="material-symbols-rounded text-blue-900 dark:text-dtext">
                    price_check
                    </span>
                  <span v-show="dataOpenSideBar" class="pl-2">Payouts</span>
                </router-link>
              </div>
              <div v-tooltip.right="!dataOpenSideBar ? 'Reports' : ''" class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                  <span class="material-symbols-rounded text-blue-900 dark:text-dtext" >
                    docs
                    </span>
                  <span v-show="dataOpenSideBar" class="pl-2">Reports</span>
                </router-link>
              </div>
              <div v-tooltip.right="!dataOpenSideBar ? 'Archives' : ''" class="py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md">
                <router-link to="/customer" class="flex space-x-2 font-quicksand font-semibold pl-2">
                  <span class="material-symbols-rounded text-blue-900 dark:text-dtext" >
                    archive
                    </span>
                  <span v-show="dataOpenSideBar" class="pl-2">Archives</span>
                </router-link>
              </div>

          <div class="text-blue-900 dark:text-dtext opacity-90 font-poppins text-sm font-semibold py-2 px-1 w-full" :class="{ 'opacity-0': !dataOpenSideBar }">Settings</div>

          <Link :href="route('settings.index')">
            <div v-tooltip.right="!dataOpenSideBar ? 'Settings' : ''" :class="['py-2 rounded-md cursor-pointer text-blue-900 dark:text-dtext hover:bg-gray-100 dark:hover:bg-dcontainer hover:rounded-md', { 'active bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 dark:bg-primary': $page.url.startsWith('/settings') }]">
              <div class="flex items-center space-x-2 font-quicksand font-semibold pl-2 text-[16px]">
                  <span :class="['material-symbols-rounded', { 'active text-dtext': $page.url.startsWith('/settings') }]" :style="['text-dtext hover:text-white', { 'active text-dtext hover:text-white': $page.url.startsWith('/settings') }]">
                  settings
                  </span>
                <span v-show="dataOpenSideBar" :class="['pl-2', { 'active text-dtext': $page.url.startsWith('/settings') }]">Settings</span>
              </div>
            </div>
          </Link>
        </div>

        

        <!-- account -->
        <div class="menu-man pb-5 w-full text-left px-2 justify-self-end">
          <!-- Dropdown Toggle Button -->
          <button id="dropdownTopButton" data-dropdown-toggle="dropdownTop" data-dropdown-placement="top" type="button"
            v-show="dataOpenSideBar" class="w-full text-white bg-blue-100 hover:bg-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 inline-flex items-center dark:bg-primary dark:hover:bg-dcontainer dark:focus:ring-blue-800 justify-between">
            <div class="w-full flex px-1 items-center justify-between">
              <div class="justify-start flex items-center space-x-3">
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
                <div class="flex flex-col items-start text-[12px] text-blue-900 dark:text-dtext">
                  <span v-show="dataOpenSideBar">{{ $page.props.auth.user.email }}</span>
                  <span v-show="dataOpenSideBar">{{ $page.props.auth.user.usertype }}</span>
                </div>
              </div>
                <div>
                <svg class="w-2.5 h-2.5 ms-3 text-blue-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                </svg>
                </div>
            </div>
          </button>
  
          <!-- Dropdown Menu -->
          <div id="dropdownTop" class="z-10 hidden bg-gray-50 divide-y divide-gray-100 rounded-lg w-52 dark:bg-primary">
            <ul class="text-sm text-gray-700 dark:text-gray-200"
              aria-labelledby="dropdownTopButton">
              <li class="flex items-left space-x-2 px-4 py-1 hover:bg-gray-100 hover:rounded-t-lg dark:hover:bg-dcontainer dark:hover:text-white">
                <Link :href="route('profile.edit')" method="post" as="button" class="flex items-center justify-start space-x-2 py-2 ">
                  <span class="material-symbols-rounded text-blue-900 dark:text-dtext">
                    person
                  </span>
                  <span class="font-poppins">My Profile</span>
                </Link>
              </li>
              <Link :href="route('logout')" method="post" as="button" class="w-full">
                <li class="flex items-center space-x-2 px-4 py-1 hover:rounded-b-lg hover:bg-gray-100 dark:hover:bg-dcontainer dark:hover:text-white">
                  <div class="flex items-center space-x-2 py-2">
                    <span class="material-symbols-rounded text-blue-900 dark:text-dtext">
                      logout
                    </span>
                    <span class="font-poppins">Logout</span>
                  </div>
                </li>
              </Link>
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
            <div id="dropdownTopcollapsed" class="z-10 hidden bg-gray-100 divide-y divide-gray-100 rounded-lg w-9 dark:bg-primary">
              <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                aria-labelledby="dropdownTopButton">
                <li class="flex items-center justify-center space-x-1 px-2 py-1 hover:bg-gray-200 dark:hover:bg-dcontainer dark:hover:text-white">
                  <Link :href="route('profile.edit')" method="post" as="button">
                    <span class="material-symbols-rounded text-blue-900 dark:text-dtext"  v-tooltip.right="!dataOpenSideBar ? 'Account' : ''">
                      person
                    </span>
                  </Link>
                </li>
                <li class="flex items-center justify-center space-x-1 px-2 py-1 hover:bg-gray-200 dark:hover:bg-dcontainer dark:hover:text-white">
                  <Link :href="route('logout')" method="post" as="button">
                    <span class="material-symbols-rounded text-blue-900 dark:text-dtext" v-tooltip.right="!dataOpenSideBar ? 'Logout' : ''">
                      logout
                    </span>
                  </Link>
                </li>
              </ul>
            </div>
        </div>
      </div>
    </div>
    
    <!-- Mobile -->
    <div class="w-full lg:hidden flex items-center bg-white px-4 py-2 overflow-x-auto scrollbar-hidden">
        <!-- Sidebar and Navigation Section -->
        <div class="w-full flex flex-row items-center space-x-6">
          <!-- Dashboard Link -->
          <div class="flex items-center cursor-pointer">
            <Link :href="route('super_admin.dashboard')" class="flex items-center space-x-2 text-blue-900 font-quicksand font-semibold">
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

<style>
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
  
  