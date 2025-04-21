<template>
  <div class="w-full bg-white border-b dark:bg-dprimary dark:border-b dark:border-gray-600">
    <!-- desktop -->
    <div class="hidden lg:flex justify-between items-center h-[50px] place-content-center">
      <div class="flex items-center space-x-4">
        <div class="pl-3">
          <!-- <img src="../../../assets/images/logo-hori.png" alt="Light Mode Logo" class="w-[180px] h-[40px] dark:hidden"> -->

          <div class="flex flex-row  items-center justify-center gap-2">
            <img src="../../../assets/images/main_logo.png" alt="Light Mode Logo" class="w-[40px] h-[40px] dark:hidden">
            <img src="../../../assets/images/main_logo_white.png" alt="Light Mode Logo"
              class="w-[40px] h-[40px] hidden dark:block">

            <span class="font-poppins text-3xl font-bold text-navy tracking-tight dark:text-white">URScholar</span>
          </div>
          <!-- Dark Mode Logo -->
          <!-- <img src="../../../assets/images/logo-hori-white.png" alt="Dark Mode Logo"
            class="w-[180px] h-[40px] hidden dark:block"> -->
        </div>
        <ul class="flex pl-10 space-x-10 font-inter font-semibold text-navy">
          <li class="relative">
            <Link :href="route('sponsor.dashboard')" class="flex items-center space-x-2">
            <p class="text-primary-foreground hover:text-primary transition">Dashboard</p>
            <!-- Notification Badge (only shows if unreadMessages > 0) -->
            <!-- <span
              class="absolute -top-1 -right-3 flex items-center justify-center w-4 h-4 bg-red-600 text-white text-xs font-normal rounded-full shadow-md">
              1
            </span> -->
            </Link>
          </li>
          <li class="relative">
            <Link :href="route('messaging.index')" class="flex items-center space-x-2">
            <p class="text-primary-foreground hover:text-primary transition">Messaging</p>
            <!-- Notification Badge (only shows if unreadMessages > 0) -->
            <!-- <span
              class="absolute -top-1 -right-3 flex items-center justify-center w-4 h-4 bg-red-600 text-white text-xs font-normal rounded-full shadow-md">
              1
            </span> -->
            </Link>
          </li>
        </ul>
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
        <button id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotificationWeb"
          class="relative border border-gray-300 inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-center text-gray-500 hover:text-gray-900 focus:outline-none dark:hover:text-white dark:text-gray-400 rounded-lg"
          type="button">
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 14 20">
            <path
              d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z" />
          </svg>

          <!-- Notification Badge -->
          <div v-if="filteredUnreadCount > 0"
            class="absolute block w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full -top-0.5 start-7 dark:border-gray-900">
          </div>
        </button>

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
              :src="`/storage/user/profile/${$page.props.auth.user.picture}`" alt="picture">
            <!-- Red Notification Dot -->
            <!-- <div class="absolute top-0 right-1 w-4 h-4 bg-red-600 rounded-full border-2 border-white"></div> -->
          </div>
        </div>
      </div>
    </div>

    <div id="dropdownNotificationWeb"
      class="z-20 hidden w-full max-w-sm bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-800 dark:divide-gray-700"
      aria-labelledby="dropdownNotificationButton">
      <div
        class="flex justify-between items-center px-4 py-2 text-left text-gray-700 rounded-t-lg bg-gray-50 dark:bg-gray-800 dark:text-white font-quicksand font-bold">
        Notifications
        <button v-if="filteredNotifications.length > 0 && filteredUnreadCount > 0" @click="markAllAsRead"
          class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
          Mark all as read
        </button>
      </div>
      <div v-if="filteredNotifications.length === 0"
        class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
        No notifications
      </div>
      <div v-else
        class="divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-dprimary dark:scrollbar-track-dcontainer">
        <div v-for="notification in filteredNotifications" :key="notification.id"
          class="flex px-4 py-3 bg-white hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="{ 'bg-blue-50 dark:bg-blue-900/10': !notification.read }">
          <div class="w-full">
            <div>{{ notification.title }}</div>
            <div class="flex justify-between items-start mb-1.5">
              <div class="text-gray-500 text-sm dark:text-gray-400" v-html="notification.message"></div>
              <button @click.stop="deleteNotification(notification.id)"
                class="ml-2 text-gray-400 hover:text-red-500 dark:hover:text-red-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="flex justify-between items-center">
              <div class="text-xs text-blue-600 dark:text-blue-500">{{ formatTime(notification.created_at) }}</div>
              <button v-if="!notification.read" @click="markAsRead(notification.id)"
                class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400">
                Mark as read
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- avatar dropdown -->
    <!-- Dropdown menu -->
    <div id="userDropdown" v-show="isDropdownOpen"
      class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
      <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
        <div>{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</div>
        <div class="font-medium truncate">{{ $page.props.auth.user.email }}</div>
      </div>
      <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
        <li class="relative">
          <Link :href="route('sponsor.account')"
            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
          Account Settings
          </Link>
          <!-- Notification Dot with FontAwesome Icon -->
          <div class="absolute top-0 right-1 flex items-center space-x-2">
            <!-- Red Notification Icon with FontAwesome -->
            <!-- <div class="bg-red-600 text-white text-xs font-semibold py-1 px-2 rounded-full flex items-center">
              <font-awesome-icon :icon="['fas', 'exclamation']" />
            </div> -->
          </div>
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
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref, onMounted, defineProps, onUnmounted, computed } from 'vue';
import axios from 'axios';
import { initFlowbite } from 'flowbite';
import { Link } from '@inertiajs/vue3';

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

const toggleTheme = () => {
  isDark.value = !isDark.value
  if (isDark.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
};

const isDark = ref(false);


const notifications = ref([]);
const isDropdownVisible = ref(false);
const isDropdownVisibleNotif = ref(false);
const currentUserId = ref(null);

const filteredNotifications = computed(() => {
  return notifications.value.filter(n => n.user_id !== currentUserId.value);
});

const filteredUnreadCount = computed(() => {
  return filteredNotifications.value.filter(n => !n.read).length;
});

// Get current user ID
const getCurrentUserId = () => {
  const userIdMeta = document.querySelector('meta[name="user-id"]');
  if (userIdMeta) {
    currentUserId.value = userIdMeta.getAttribute('content');
  }
};


// Notification API calls
const fetchNotifications = async () => {
  try {
    const response = await axios.get('/notifications');
    notifications.value = response.data;
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};

const markAsRead = async (id) => {
  try {
    await axios.patch(`/notifications/${id}/read`);
    const index = notifications.value.findIndex(n => n.id === id);
    if (index !== -1) {
      notifications.value[index].read = true;
    }
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
};

const markAllAsRead = async () => {
  try {
    // Only mark notifications from others as read
    const notificationIds = filteredNotifications.value
      .filter(n => !n.read)
      .map(n => n.id);

    if (notificationIds.length > 0) {
      await axios.patch('/notifications/read-all', { notification_ids: notificationIds });
      notifications.value = notifications.value.map(n => {
        if (notificationIds.includes(n.id)) {
          return { ...n, read: true };
        }
        return n;
      });
    }
  } catch (error) {
    console.error('Error marking all notifications as read:', error);
  }
};

const deleteNotification = async (id) => {
  try {
    await axios.delete(`/notifications/${id}`);
    notifications.value = notifications.value.filter(n => n.id !== id);
  } catch (error) {
    console.error('Error deleting notification:', error);
  }
};

const formatTime = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diff = Math.floor((now - date) / 1000);

  if (diff < 60) {
    return 'Just now';
  } else if (diff < 3600) {
    const minutes = Math.floor(diff / 60);
    return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
  } else if (diff < 86400) {
    const hours = Math.floor(diff / 3600);
    return `${hours} hour${hours > 1 ? 's' : ''} ago`;
  } else {
    return date.toLocaleDateString();
  }
};

// Set up Pusher to listen for real-time notifications
const setupPusher = () => {
  const userId = currentUserId.value;

  if (userId && window.Echo) {
    window.Echo.private(`notifications.${userId}`)
      .listen('.new.notification', (data) => {
        // Only add notifications from other users
        if (data.user_id !== userId) {
          const newNotification = {
            id: data.id,
            title: data.title,
            message: data.message,
            type: data.type,
            read: false,
            created_at: data.created_at,
            user_id: data.user_id
          };

          notifications.value = [newNotification, ...notifications.value];
        }
      });
  }
};


// State to track dropdown visibility
const isDropdownOpen = ref(false);

// Toggle dropdown visibility
const toggleUserDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

// Close dropdown when clicking outside
const closeDropdown = (event) => {
  const dropdown = document.getElementById('userDropdown');
  const avatar = document.getElementById('avatarButton');

  if (isDropdownOpen.value &&
    dropdown &&
    avatar &&
    !dropdown.contains(event.target) &&
    !avatar.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

// Add click listener to document to close dropdown when clicking outside
onMounted(() => {
  initFlowbite();

  import('flowbite').then(({ initDropdowns }) => {
    initDropdowns()
  })

  // Get user ID
  getCurrentUserId();

  fetchNotifications();

  setupPusher();

  document.addEventListener('click', closeDropdown);

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
});

// Clean up event listener when component is unmounted
onUnmounted(() => {
  initFlowbite();

  // Clean up Pusher connection
  const userId = currentUserId.value;
  if (userId && window.Echo) {
    window.Echo.leave(`notifications.${userId}`);
  }

  document.removeEventListener('click', closeDropdown);
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