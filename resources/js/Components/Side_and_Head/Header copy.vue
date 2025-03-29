<template>
  <div class="w-full bg-white border-b dark:bg-dprimary dark:border-b dark:border-gray-600">
    <!-- desktop -->
    <div class="hidden lg:flex justify-between items-center h-[50px] place-content-center">
      <div class="flex items-center space-x-4">
        <div class="pl-1">
          <img src="../../../assets/images/logo-hori.png" alt="Light Mode Logo" class="w-[180px] h-[40px] dark:hidden">

          <!-- Dark Mode Logo -->
          <img src="../../../assets/images/logo-hori-white.png" alt="Dark Mode Logo"
            class="w-[180px] h-[40px] hidden dark:block">
        </div>
      </div>

      <!-- Theme and Notification -->
      <div class="flex items-center space-x-4 pr-5">
        <!-- Theme Toggle -->
        <div
          class="border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-200 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg">
          <label class="swap swap-rotate h-[35px] w-[35px] cursor-pointer">
            <!-- Hidden Checkbox to Toggle Theme -->
            <input type="checkbox" class="theme-controller hidden" :checked="isDark" @change="toggleTheme" />

            <!-- Light Icon (Visible when in light mode) -->
            <font-awesome-icon v-if="!isDark" :icon="['fas', 'lightbulb']"
              class="text-blue-500 text-lg transition-all duration-300 ease-in-out" />

            <!-- Moon Icon (Visible when in dark mode) -->
            <font-awesome-icon v-if="isDark" :icon="['fas', 'moon']"
              class="text-gray-400 text-lg transition-all duration-300 ease-in-out" />
          </label>
        </div>


        <!-- notification -->
        <button id="dropdownNotificationButton" @click.stop="toggleDropdown"
          class="relative border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 20">
            <path
              d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
          </svg>
          <!-- Notification Badge -->
          <div
            class="absolute block w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full -top-0.5 start-7 dark:border-gray-900">
          </div>
        </button>

      </div>
    </div>
  </div>
  <!-- Dropdown menu -->
  <div v-show="isDropdownVisible" id="dropdownNotification"
    class="absolute right-2 mt-14 z-20 w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow-xl dark:bg-gray-800 dark:divide-gray-700">
    <div
      class="block px-4 py-2 text-left text-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-800 dark:text-white font-quicksand font-bold">
      Notifications
    </div>
    <div
      class="divide-y divide-gray-100 dark:divide-gray-700 max-h-90 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer">
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
          <!-- <div class="absolute flex items-center justify-center w-5 h-5 ms-6 -mt-5 bg-blue-600 border border-white rounded-full dark:border-gray-800">
              <svg class="w-2 h-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                <path d="M1 18h16a1 1 0 0 0 1-1v-6h-4.439a.99.99 0 0 0-.908.6 3.978 3.978 0 0 1-7.306 0 .99.99 0 0 0-.908-.6H0v6a1 1 0 0 0 1 1Z"/>
                <path d="M4.439 9a2.99 2.99 0 0 1 2.742 1.8 1.977 1.977 0 0 0 3.638 0A2.99 2.99 0 0 1 13.561 9H17.8L15.977.783A1 1 0 0 0 15 0H3a1 1 0 0 0-.977.783L.2 9h4.239Z"/>
              </svg>
            </div> -->
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
      <a href="#" class="flex px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700">
        <div class="shrink-0">
          <img class="rounded-full w-11 h-11 border border-gray-100" src="/docs/images/people/profile-picture-1.jpg"
            alt="Jese image">
        </div>
        <div class="w-full ps-3">
          <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">
            New message from <span class="font-semibold text-gray-900 dark:text-white">Jese Leos</span>: "Hey, what's
            up? All set for the presentation?"
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-500">a few moments ago</div>
        </div>
      </a>
    </div>
    <!-- <a href="#" class="block py-2 text-sm font-medium text-center text-gray-900 rounded-b-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white">
        <div class="inline-flex items-center ">
          <svg class="w-4 h-4 me-2 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
            <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
          </svg>
          View all
        </div>
      </a> -->
  </div>

</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref, onMounted, defineProps, onUnmounted } from 'vue';

const props = defineProps({
  dataOpenSideBar: Boolean,
  clickHamburger: Function
});

const items = ref([
  {
    label: 'Logout',
    icon: 'pi pi-refresh',
    command: () => {
      this.$toast.add({ severity: 'success', summary: 'Updated', detail: 'Data Updated', life: 3000 });
    }
  },
  {
    label: 'Change Password',
    icon: 'pi pi-times',
    command: () => {
      this.$toast.add({ severity: 'warn', summary: 'Delete', detail: 'Data Deleted', life: 3000 });
    }
  }
]);

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

const isDropdownVisible = ref(false);

const toggleDropdown = () => {
  isDropdownVisible.value = !isDropdownVisible.value;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest("#dropdownNotificationButton") && !event.target.closest("#dropdownNotification")) {
    isDropdownVisible.value = false;
  }
};

// Check theme preference on page load
onMounted(() => {
  window.initFlowbite();

  // notifs
  document.addEventListener("click", handleClickOutside);

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

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});

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