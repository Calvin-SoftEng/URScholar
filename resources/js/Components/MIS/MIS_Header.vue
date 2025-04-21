<template>
  <div class="w-full bg-[F8F8FA] dark:bg-dprimary shadow-sm">
    <!-- desktop -->
    <div class="hidden lg:flex justify-between items-center h-[50px] place-content-center px-10">
      <div class="flex items-center space-x-4">
        <div class="pl-1">
          <img src="../../../assets/images/logo-hori.png" alt="" class="w-[180px] h-[40px]">
        </div>
      </div>

      <!-- Theme and Notification -->
      <div class="flex items-center space-x-4 pr-5">
        <!-- Theme Toggle -->
        <div class="border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-200 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg">
          <label class="swap swap-rotate h-[35px] w-[35px] cursor-pointer">
            <!-- Hidden Checkbox to Toggle Theme -->
            <input 
              type="checkbox" 
              class="theme-controller hidden" 
              :checked="isDark" 
              @change="toggleTheme" 
            />
            
            <!-- Light Icon (Visible when in light mode) -->
            <font-awesome-icon 
              v-if="!isDark" 
              :icon="['fas', 'lightbulb']" 
              class="text-blue-500 text-lg transition-all duration-300 ease-in-out" 
            />

            <!-- Moon Icon (Visible when in dark mode) -->
            <font-awesome-icon 
              v-if="isDark" 
              :icon="['fas', 'moon']" 
              class="text-gray-400 text-lg transition-all duration-300 ease-in-out" 
            />
          </label>
        </div>

        <!-- notification -->
        <!-- <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification"
          class="relative border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 20">
            <path
              d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
          </svg>

          <div
            class="absolute block w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full -top-0.5 start-7 dark:border-gray-900">
          </div>
        </button> -->

        <!-- avatar -->
       <!-- Avatar with Red Notification Dot -->
       <div v-if="$page.props.auth.user.picture">
            <div class="relative">
                <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" @click.stop="toggleUserDropdown"
                    data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
                    :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="picture">
                <!-- Red Notification Dot -->
                <!-- <div class="absolute top-[-5px] right-[-5px] w-4 h-4 bg-red-600 rounded-full border-2 border-white"></div> -->
            </div>
        </div>
        <div v-else>
          <div class="relative">
              <img id="avatarButton" type="button" data-dropdown-toggle="userDropdown" @click.stop="toggleUserDropdown"
                  data-dropdown-placement="bottom-start" class="w-10 h-10 rounded-lg border border-gray-300 cursor-pointer"
                  :src="`/storage/user/profile/no_userpic.png`" alt="picture">
              <!-- Red Notification Dot -->
              <!-- <div class="absolute top-0 right-1 w-4 h-4 bg-red-600 rounded-full border-2 border-white"></div> -->
          </div>
        </div>

        <!-- Notifs Dropdown menu -->
        <div id="dropdownNotification"
          class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-800 dark:divide-gray-700"
          aria-labelledby="dropdownNotificationButton">
          <div
            class="block px-4 py-2 font-medium text-center text-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-800 dark:text-white">
            Notifications
          </div>
          <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
              <div class="flex-shrink-0">
                <img class="rounded-full w-11 h-11" src="" alt="Jese image">
                <div
                  class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full dark:border-gray-800">
                  <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 18 18">
                    <path
                      d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z" />
                    <path
                      d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z" />
                  </svg>
                </div>
              </div>
              <div class="w-full ps-3">
                <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">New message from <span
                    class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's up? All set for
                  the presentation?"</div>
                <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
              </div>
            </a>
          </div>
          <a href="#"
            class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white">
            <div class="inline-flex items-center ">
              <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                <path
                  d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
              </svg>
              View all
            </div>
          </a>
        </div>

        <!-- avatar dropdown -->
        <!-- Dropdown menu -->
        <div id="userDropdown"
          class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
          <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
            <div>{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</div>
        <div class="font-medium truncate">{{ $page.props.auth.user.email }}</div>
          </div>
          <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
            <li>
              <Link :href="(route('sa.account'))" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Account Settings</Link>
            </li>
          </ul>
          <div class="py-1 text-left">
            <Link :href="route('logout')" method="post" as="button"
              class="w-full px-4 items-start justify-start py-2 text-sm text-gray-700 text-left hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
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
          <img src="../../../assets/images/logo-hori.png" alt="" class="w-[145px] h-[35px]">
        </div>
      </div>

      <!-- Theme and Notification -->
      <div class="flex items-center space-x-2 pr-1">
        <!-- Theme Toggle -->
        <div
          class="border border-gray-300 inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <label class="swap swap-rotate h-[20px] w-[20px]">
            <!-- Hidden Checkbox -->
            <input type="checkbox" class="theme-controller hidden" value="synthwave" @change="toggleTheme" />
            <!-- Sun Icon -->
            <svg class="swap-off h-full w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
            </svg>
            <!-- Moon Icon -->
            <svg class="swap-on h-full w-full fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
            </svg>
          </label>
        </div>

        <!-- notification -->
        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification"
          class="relative border border-gray-300 inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 20">
            <path
              d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
          </svg>

          <div
            class="absolute block w-3 h-3 bg-red-500 border-2 border-white rounded-full -top-0.5 start-7 dark:border-gray-900">
          </div>
        </button>

        <!-- Avatar -->
        <div
          class="rounded-full inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400"
          type="button">
          <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="Avatar" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const isDark = ref(false)

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

// Check theme preference on page load
onMounted(() => {
  window.initFlowbite();
  
  // First check localStorage
  const savedTheme = localStorage.getItem('theme')
  
  if (savedTheme) {
    // If user has a saved preference, use that
    isDark.value = savedTheme === 'dark'
  } else {
    // If no saved preference, check system preference
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
  }

  // Apply the theme
  if (isDark.value) {
    document.documentElement.classList.add('dark')
  }

  // Listen for system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    // Only update if user hasn't set a preference
    if (!localStorage.getItem('theme')) {
      isDark.value = e.matches
      if (e.matches) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }
  })
})



// export default {
//   components: {
//     Link,
//   },
//   props: {
//     dataOpenSideBar: Boolean,
//     clickHamburger: Function
//   },
//   name: 'headerTop',
//   mounted() {
//     window.initFlowbite();
//   },
//   data() {
//     return {
//       items: [
//         {
//           label: 'Logout',
//           icon: 'pi pi-refresh',
//           command: () => {
//             this.$toast.add({ severity: 'success', summary: 'Updated', detail: 'Data Updated', life: 3000 });
//           }
//         },
//         {
//           label: 'Change Password',
//           icon: 'pi pi-times',
//           command: () => {
//             this.$toast.add({ severity: 'warn', summary: 'Delete', detail: 'Data Deleted', life: 3000 });
//           }
//         },
//       ]
//     }
//   },
//   methods: {
//     toggle(event) {
//       this.$refs.menu.toggle(event);
//     },
//   }


  
// }
</script>

<style scoped>
.swap-rotate .swap-on {
  display: none;
}

.swap-rotate .swap-off {
  display: block;
}

.swap-rotate:hover .swap-on {
  display: block;
}

.swap-rotate:hover .swap-off {
  display: none;
}
</style>