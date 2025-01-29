<template>
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
              <div 
                v-show="isScholarsMenuOpen" 
                class="fixed top-0 bg-[#003366] w-48 z-50"
                :style="{ top: `${menuPosition}px`, left: `${sidebarWidth + 15}px` }"
              >
                <ul class="space-y-1">
            <li v-for="(item, index) in ScholarItems" :key="index">
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
                <a href="#" class="font-poppins">
                  Logout
                </a>
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
                <li class="flex items-center space-x-1 px-2 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                  <span class="material-symbols-rounded" style="color: black;" v-tooltip.right="!dataOpenSideBar ? 'Logout' : ''">
                    logout
                  </span>
                </li>
              </ul>
            </div>
  
        </div>
      </div>
    </div>
  </template>

<script>
import { ref, onMounted } from 'vue'
import { Tooltip } from 'primevue';

export default {
  props: {
    dataOpenSideBar: Boolean
  },
  directives: {
    tooltip: Tooltip
  },
  // setup() {
  //   const isOpen = ref(false)
  //   const menuItems = [
  //     { label: 'View Scholarships', route: '/scholarships/view' },
  //     { label: 'Add Scholarships', route: '/scholarships/add' }
  //   ]

  //   return {
  //     isOpen,
  //     menuItems
  //   }
  // }

  // for tiered menu
//   data() {
//   return {
//     isOpen: false,
//     dataOpenSideBar: true, // Controls visibility of sidebar elements
//     menuItems: [
//       {
//         label: "Scholarship A",
//         route: "/scholarship-a",
//         children: [
//           { label: "Details", route: "/scholarship-a/details" },
//           { label: "Apply", route: "/scholarship-a/apply" }
//         ]
//       },
//       {
//         label: "Scholarship B",
//         route: "/scholarship-b",
//         children: [
//           { label: "Details", route: "/scholarship-b/details" },
//           { label: "Apply", route: "/scholarship-b/apply" }
//         ]
//       }
//     ]
//   };
// }

// for cascading menu
setup() {
  const isScholarshipMenuOpen = ref(false)
  const isScholarsMenuOpen = ref(false)
  const menuPosition = ref(0)
  const sidebarWidth = ref(0)

  const ScholarshipItems = [
    { label: 'View Scholarships', route: '/scholarships/view' },
    { label: 'Add Scholarships', route: '/scholarships/add' }
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
  }

  const toggleScholarsMenu = (event) => {
    // Calculate position relative to viewport
    const rect = event.currentTarget.getBoundingClientRect()
    menuPosition.value = rect.top
    sidebarWidth.value = rect.width // Adjust this value based on the actual width of the sidebar
    isScholarsMenuOpen.value = !isScholarsMenuOpen.value
  }

  // Close menu when clicking outside
  onMounted(() => {
    document.addEventListener('click', (event) => {
    const target = event.target
    if (!target.closest('.relative')) {
      isScholarshipMenuOpen.value = false
      isScholarsMenuOpen.value = false
    }
    })
  })

  return {
    isScholarshipMenuOpen,
    isScholarsMenuOpen,
    menuPosition,
    ScholarshipItems,
    toggleScholarshipMenu,
    sidebarWidth,
    toggleScholarsMenu,
    ScholarItems
  }
  }
}


</script>

<style>
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
</style>
  
  