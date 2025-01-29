<template>
    <nav class="space-y-2">
      <!-- Dashboard -->
      <router-link to="/" 
                   class="flex items-center space-x-2 py-3 px-2 rounded-sm cursor-pointer text-gray-300 hover:text-white">
        <span class="material-symbols-rounded" 
              style="color: white;" 
              v-tooltip.right="!isOpen ? 'Dashboard' : ''">
          dashboard
        </span>
        <span v-show="isOpen" class="font-poppins">Dashboard</span>
      </router-link>
  
      <!-- Scholarships Dropdown -->
      <div class="relative">
        <div class="flex items-center justify-between py-3 px-2 rounded-sm cursor-pointer text-gray-300 hover:text-white"
             @click="toggleScholarshipMenu">
          <div class="flex items-center space-x-2">
            <span class="material-symbols-rounded" 
                  style="color: white;"
                  v-tooltip.right="!isOpen ? 'Scholarships' : ''">
              checkbook
            </span>
            <span v-show="isOpen" class="font-poppins">Scholarships</span>
          </div>
          <font-awesome-icon v-show="isOpen"
                            icon="fa-solid fa-chevron-right"
                            :class="{'rotate-180': isScholarshipMenuOpen}"
                            class="mr-2 transition-transform duration-200"/>
        </div>
  
        <!-- Scholarships Submenu -->
        <Transition enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-x-2"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 -translate-x-2">
          <div v-if="isScholarshipMenuOpen">
            <div v-if="isOpen" class="pl-8 py-2 space-y-2">
              <router-link v-for="item in scholarshipItems"
                          :key="item.route"
                          :to="item.route"
                          class="block py-2 text-gray-300 hover:text-white font-poppins">
                {{ item.label }}
              </router-link>
            </div>
            
            <!-- Flyout menu when sidebar is collapsed -->
            <div v-else
                 class="absolute left-full top-0 ml-2 bg-blue-900 rounded-md py-2 w-48 z-50">
              <router-link v-for="item in scholarshipItems"
                          :key="item.route"
                          :to="item.route"
                          class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-blue-800 font-poppins">
                {{ item.label }}
              </router-link>
            </div>
          </div>
        </Transition>
      </div>
  
      <!-- Scholars Dropdown -->
      <div class="relative">
        <div class="flex items-center justify-between py-3 px-2 rounded-sm cursor-pointer text-gray-300 hover:text-white"
             @click="toggleScholarsMenu">
          <div class="flex items-center space-x-2">
            <span class="material-symbols-rounded" 
                  style="color: white;"
                  v-tooltip.right="!isOpen ? 'Scholars' : ''">
              school
            </span>
            <span v-show="isOpen" class="font-poppins">Scholars</span>
          </div>
          <font-awesome-icon v-show="isOpen"
                            icon="fa-solid fa-chevron-right"
                            :class="{'rotate-180': isScholarsMenuOpen}"
                            class="mr-2 transition-transform duration-200"/>
        </div>
  
        <!-- Scholars Submenu -->
        <Transition enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-x-2"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 -translate-x-2">
          <div v-if="isScholarsMenuOpen">
            <div v-if="isOpen" class="pl-8 py-2 space-y-2">
              <router-link v-for="item in scholarItems"
                          :key="item.route"
                          :to="item.route"
                          class="block py-2 text-gray-300 hover:text-white font-poppins">
                {{ item.label }}
              </router-link>
            </div>
            
            <!-- Flyout menu when sidebar is collapsed -->
            <div v-else
                 class="absolute left-full top-0 ml-2 bg-blue-900 rounded-md py-2 w-48 z-50">
              <router-link v-for="item in scholarItems"
                          :key="item.route"
                          :to="item.route"
                          class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-blue-800 font-poppins">
                {{ item.label }}
              </router-link>
            </div>
          </div>
        </Transition>
      </div>
  
      <!-- Other Navigation Items -->
      <router-link v-for="item in menuItems"
                   :key="item.route"
                   :to="item.route"
                   class="flex items-center space-x-2 py-3 px-2 rounded-sm cursor-pointer text-gray-300 hover:text-white">
        <span class="material-symbols-rounded"
              style="color: white;"
              v-tooltip.right="!isOpen ? item.label : ''">
          {{ item.icon }}
        </span>
        <span v-show="isOpen" class="font-poppins">{{ item.label }}</span>
      </router-link>
    </nav>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { Tooltip } from 'primevue'
//   import { useRoute } from 'vue-router'
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      required: true
    }
  })
  
  // State
  const isScholarshipMenuOpen = ref(false)
  const isScholarsMenuOpen = ref(false)
  const route = useRoute()
  
  // Menu Data
  const scholarshipItems = [
    { label: 'View Scholarships', route: '/scholarships/view' },
    { label: 'Add Scholarships', route: '/scholarships/add' }
  ]
  
  const scholarItems = [
    { label: 'View Scholars', route: '/scholars/view' },
    { label: 'Add Scholars', route: '/scholars/add' }
  ]
  
  const menuItems = [
    { 
      label: 'Messaging',
      route: '/contact',
      icon: 'message'
    },
    { 
      label: 'Feed',
      route: '/feed',
      icon: 'feed'
    },
    { 
      label: 'Payouts',
      route: '/customer',
      icon: 'price_check'
    },
    { 
      label: 'Reports',
      route: '/activation',
      icon: 'summarize'
    },
    { 
      label: 'Archives',
      route: '/archives',
      icon: 'archive'
    }
  ]
  
  // Methods
  const toggleScholarshipMenu = () => {
    isScholarshipMenuOpen.value = !isScholarshipMenuOpen.value
    if (isScholarshipMenuOpen.value) {
      isScholarsMenuOpen.value = false
    }
  }
  
  const toggleScholarsMenu = () => {
    isScholarsMenuOpen.value = !isScholarsMenuOpen.value
    if (isScholarsMenuOpen.value) {
      isScholarshipMenuOpen.value = false
    }
  }
  
  // Close dropdowns when clicking outside
  onMounted(() => {
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
  .router-link-active {
    background-color: rgba(255, 255, 255, 0.1);
  }
  </style>