<template>
  <div class="w-full bg-[F8F8FA] dark:bg-dprimary shadow-sm">
    <!-- desktop -->
    <div class="hidden lg:flex justify-between items-center h-[50px] place-content-center px-10">
      <div class="flex items-center space-x-4">
        <div class="pl-3">
          <div class="flex flex-row items-center justify-center gap-2">
            <img 
              v-if="branding?.logo_light" 
              :src="`/storage/branding/logos/${branding.logo_light}`" 
              alt="Light Mode Logo" 
              class="w-[40px] h-[40px] dark:hidden"
            >
            <img 
              v-if="branding?.logo_dark" 
              :src="`/storage/branding/logos/${branding.logo_dark}`" 
              alt="Dark Mode Logo"
              class="w-[40px] h-[40px] hidden dark:block"
            >
            <span class="font-poppins text-3xl font-bold text-navy tracking-tight dark:text-white">
              {{ branding?.branding_name || 'App' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Theme and Notification -->
      <div class="flex items-center space-x-4 pr-5">
        <!-- Theme Toggle -->
        <div class="border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-200 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg">
          <label class="swap swap-rotate h-[35px] w-[35px] cursor-pointer">
            <input 
              type="checkbox" 
              class="theme-controller hidden" 
              :checked="isDark" 
              @change="toggleTheme" 
            />
            
            <font-awesome-icon 
              v-if="!isDark" 
              :icon="['fas', 'lightbulb']" 
              class="text-blue-500 text-lg transition-all duration-300 ease-in-out" 
            />

            <font-awesome-icon 
              v-if="isDark" 
              :icon="['fas', 'moon']" 
              class="text-gray-400 text-lg transition-all duration-300 ease-in-out" 
            />
          </label>
        </div>

        <!-- Avatar with Dropdown -->
        <div class="relative">
          <div v-if="$page.props.auth?.user?.picture">
            <img 
              id="avatarButton" 
              type="button" 
              data-dropdown-toggle="userDropdown" 
              @click.stop="toggleUserDropdown"
              data-dropdown-placement="bottom-start" 
              class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
              :src="`/storage/user/profile/${$page.props.auth.user.picture}`" 
              alt="User picture"
            >
          </div>
          <div v-else>
            <img 
              id="avatarButton" 
              type="button" 
              data-dropdown-toggle="userDropdown" 
              @click.stop="toggleUserDropdown"
              data-dropdown-placement="bottom-start" 
              class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
              src="/storage/user/profile/no_userpic.png" 
              alt="Default picture"
            >
          </div>
        </div>

        <!-- Avatar Dropdown -->
        <div 
          id="userDropdown"
          :class="{ 'hidden': !isUserDropdownOpen }"
          class="absolute right-5 top-12 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600"
        >
          <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
            <div>{{ $page.props.auth?.user?.first_name || '' }} {{ $page.props.auth?.user?.last_name || '' }}</div>
            <div class="font-medium truncate">{{ $page.props.auth?.user?.email || '' }}</div>
          </div>
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
            <li>
              <Link 
                :href="route('sa.account')" 
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
              >
                Account Settings
              </Link>
            </li>
          </ul>
          <div class="py-1 text-left">
            <Link 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="w-full px-4 items-start justify-start py-2 text-sm text-gray-700 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
            >
              Sign out
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- mobile -->
    <div class="flex lg:hidden justify-between items-center h-[50px] place-content-center">
      <div class="flex items-center space-x-4">
        <div class="pl-1">
          <div class="flex flex-row items-center justify-center gap-2">
            <img 
              v-if="branding?.logo_light" 
              :src="`/storage/branding/logos/${branding.logo_light}`" 
              alt="Logo" 
              class="w-[30px] h-[30px] dark:hidden"
            >
            <img 
              v-if="branding?.logo_dark" 
              :src="`/storage/branding/logos/${branding.logo_dark}`" 
              alt="Logo"
              class="w-[30px] h-[30px] hidden dark:block"
            >
            <span class="font-poppins text-xl font-bold text-navy tracking-tight dark:text-white">
              {{ branding?.branding_name || 'App' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Mobile Theme and Avatar -->
      <div class="flex items-center space-x-2 pr-1">
        <!-- Theme Toggle -->
        <div
          class="border border-gray-300 inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
        >
          <label class="swap swap-rotate h-[20px] w-[20px] cursor-pointer">
            <input type="checkbox" class="theme-controller hidden" :checked="isDark" @change="toggleTheme" />
            <font-awesome-icon 
              v-if="!isDark" 
              :icon="['fas', 'lightbulb']" 
              class="text-blue-500 text-sm" 
            />
            <font-awesome-icon 
              v-if="isDark" 
              :icon="['fas', 'moon']" 
              class="text-gray-400 text-sm" 
            />
          </label>
        </div>

        <!-- Avatar -->
        <div class="rounded-full inline-flex items-center justify-center w-8 h-8">
          <img 
            v-if="$page.props.auth?.user?.picture"
            :src="`/storage/user/profile/${$page.props.auth.user.picture}`" 
            alt="Avatar"
            class="w-8 h-8 rounded-full border border-gray-300"
          />
          <img 
            v-else
            src="/storage/user/profile/no_userpic.png" 
            alt="Avatar"
            class="w-8 h-8 rounded-full border border-gray-300"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  branding: {
    type: Object,
    required: false,
    default: () => ({})
  },
})

const isDark = ref(false)
const isUserDropdownOpen = ref(false)

const toggleTheme = () => {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

const toggleUserDropdown = () => {
  isUserDropdownOpen.value = !isUserDropdownOpen.value
}

const closeUserDropdown = (event) => {
  if (!event.target.closest('#avatarButton') && !event.target.closest('#userDropdown')) {
    isUserDropdownOpen.value = false
  }
}

onMounted(() => {
  // Initialize Flowbite if available
  if (window.initFlowbite) {
    window.initFlowbite();
  }
  
  // Check theme preference
  const savedTheme = localStorage.getItem('theme')
  
  if (savedTheme) {
    isDark.value = savedTheme === 'dark'
  } else {
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
  }

  // Apply the theme
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  }

  // Listen for system theme changes
  const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
  const handleThemeChange = (e) => {
    if (!localStorage.getItem('theme')) {
      isDark.value = e.matches
      if (e.matches) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }
  }
  
  mediaQuery.addEventListener('change', handleThemeChange)

  // Add click listener for closing dropdown
  document.addEventListener('click', closeUserDropdown)

  // Cleanup
  onUnmounted(() => {
    mediaQuery.removeEventListener('change', handleThemeChange)
    document.removeEventListener('click', closeUserDropdown)
  })
})
</script>

<style scoped>
/* Optional: Add smooth transitions */
.swap-rotate {
  transition: all 0.3s ease;
}
</style>