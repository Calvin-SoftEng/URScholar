<template>
    <AuthenticatedLayout>
        <template #default>
            <div class="w-full h-full bg-dirtywhite">
                <div class="px-48 border-box w-full h-full flex flex-row bg-dirtywhite">
                    <div class="w-[95%] pt-3 overflow-auto scrollbar-thin scrollbar-thumb-blue-900 scrollbar-track-gray-100 scrollbar-thumb-rounded h-full">
                        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 ">
                            <div class="rounded-lg mb-10">
                                <div class="h-full space-y-3 flex flex-col items-center justify-start pt-2 pb-10">
                                    
                                    <div class="bg-white w-full h-full p-5 space-y-3 rounded-lg shadow-md dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
                                        <span class="font-poppins font-semibold text-xl dark:text-dtext">Announce to Everyone</span>
                                        
                                        <!-- Card -->
                                        <div class="flex flex-col flex-grow gap-3">
                                            <!-- Message Container (Expands to fill space) -->
                                            <div class="flex-grow w-full">
                                                <textarea class="w-full h-full resize-none p-2 bg-transparent border-none outline-none focus:ring-0" rows="5"
                                                    placeholder="Write your announcement here..."></textarea>
                                            </div>
                                            <!-- Buttons Container (Stays at the Bottom) -->
                                            <div class="w-full flex flex-row justify-between items-center">
                                                <div class="flex gap-2">
                                                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                                                        <span class="material-symbols-rounded text-[18px] me-2">school</span>
                                                        Share to University
                                                    </button>
                                                    
                                                    <button type="button" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:ring-4 focus:outline-none focus:ring-[#3b5998]/50 font-medium rounded-lg text-xs px-3 py-1 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55">
                                                        <span class="material-symbols-rounded text-[18px] me-2">groups</span>
                                                        Share to Group
                                                    </button>
                                                </div>

                                                <button @click="toggleSharePost" class="text-white font-sans bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Share a Post
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr D</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <!-- <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div> -->
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-white rounded-lg shadow-md">
                                        <div class="w-full p-4 flex items-center border-b border-gray-200">
                                            <img class="w-12 h-12 rounded-full" src="https://placehold.co/50x50" alt="user-avatar" />
                                            <div class="ml-4">
                                                <p class="text-primary font-semibold">URS - HEAD SCHOLARSHIP ADMINISTRATOR</p>
                                                <p class="text-sm text-gray-500">Posted NOV. 15, 2024 @ 10:30 PM Binangonan Campus</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <div class="bg-gradient-to-t from-blue-900 via-blue-800 to-blue-700 h-96 flex items-center justify-center text-white text-xl text-center font-onest text-bold break-words overflow-hidden">
                                                <p>fefrgr ghthtyjyj</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>

    <!-- Creating a post modal -->
    <div v-if="Share"
        class="fixed inset-0 flex items-center justify-center z-[999] bg-black bg-opacity-65 dark:bg-primary dark:bg-opacity-50 transition-opacity-ease-in duration-300">
        <div class="bg-white dark:bg-gray-900 dark:border-gray-200 rounded-lg shadow-xl w-4/12 overflow-visible relative">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <div class="flex items-center gap-3">
                    <!-- Icon -->
                    <font-awesome-icon :icon="['fas', 'graduation-cap']" class="text-blue-600 text-2xl flex-shrink-0" />

                    <!-- Title and Description -->
                    <div class="flex flex-col">
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white">
                            Share Announcement
                        </h2>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Send to everyone or you can filter out specific groups.
                        </span>
                    </div>
                </div>

                <!-- Close Button -->
                <button type="button" @click="closeModal"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitForm" class="p-6 flex flex-col gap-3">
                
                <!-- User Profile Section -->
                <div class="flex items-center gap-3">
                    <img src="../../../../assets/images/no_userpic.png" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">si ako</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">taga binondo</p>
                    </div>
                </div>

                <div
                    class="col-span-4 gap-2 relative w-full flex items-center mt-4 whitespace-nowrap">
                    <h3 class="font-semibold text-xs text-blue-900 dark:text-white">
                        Who can see your post?
                    </h3>
                    <div class="flex-1 h-0.5 bg-gray-200 rounded-lg"></div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <!-- Scholarship Filter -->
<div class="relative">
  <label class="block text-xs font-medium mb-1">Scholarship</label>
  <button type="button" class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
      @click="toggleDropdown('scholarship')">
      {{ selectedScholarships.length ? selectedScholarships.join(', ') : 'Select Scholarships' }}
  </button>
  <div v-if="openDropdown === 'scholarship'"
      class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
      <label v-for="item in props.scholarships" :key="item.id" class="block px-4 py-2 hover:bg-gray-100">
          <input 
          type="checkbox" 
          class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          :value="item.name"
          v-model="selectedScholarships"
          />
          {{ item.name }}
      </label>
  </div>
</div>

                    <!-- Batch Filter -->
<div class="relative">
  <label class="block text-xs font-medium mb-1">Batch</label>
  <button type="button" class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
      @click="toggleDropdown('batch')">
      {{ selectedBatches.length ? selectedBatches.join(', ') : 'Select Batches' }}
  </button>
  <div v-if="openDropdown === 'batch'"
      class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
      <label v-for="item in filteredBatches" :key="item.id" class="block px-4 py-2 hover:bg-gray-100">
          <input 
          type="checkbox" 
          class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          :value="`Batch ${item.batch_no}`"
          v-model="selectedBatches"
          />
          Batch {{ item.batch_no }} ({{ item.scholarship.name }})
      </label>
  </div>
</div>

<!-- Campus Filter -->
<div class="relative">
  <label class="block text-xs font-medium mb-1">Campus</label>
  <button type="button" class="w-full text-left border border-gray-200 text-sm rounded-lg p-2 bg-white"
      @click="toggleDropdown('campus')">
      {{ selectedCampuses.length ? selectedCampuses.join(', ') : 'Select Campuses' }}
  </button>
  <div v-if="openDropdown === 'campus'"
      class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md max-h-60 overflow-y-auto">
      <label v-for="item in filteredCampuses" :key="item.id" class="block px-4 py-2 hover:bg-gray-100">
          <input 
          type="checkbox" 
          class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          :value="item.name"
          v-model="selectedCampuses"
          />
          {{ item.name }}
      </label>
  </div>
</div>
                </div>

                <!-- Message Box (Pre-filled Message) -->
                <div class="">
                    <label for="message" class="block text-sm font-medium text-gray-900 dark:text-gray-300">Announcement Message</label>
                    <textarea class="w-full h-full resize-none p-2 bg-transparent border-gray-300 rounded-md bg-gray-200 outline-none focus:ring-0" rows="5"
                    placeholder="Write your announcement here..."></textarea>

                </div>


                <!-- Post Button -->
                <div class="mt-2">
                    <button type="submit"
                        class="text-white font-sans w-full bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-900/90 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 ">
                        Post Announcement</button>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount, watch, nextTick, computed } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { initFlowbite } from 'flowbite';

// Props from controller
const props = defineProps({
    campuses: Array,
    batches: Array,
    scholarships: Array,
});

// Modal state
const Share = ref(false);

// Filter selections
const selectedScholarships = ref([]);
const selectedBatches = ref([]);
const selectedCampuses = ref([]);

// Dropdown control
const openDropdown = ref(null);

// Create computed properties for filtered options based on selections
const filteredBatches = computed(() => {
    if (selectedScholarships.value.length === 0) {
        return props.batches;
    } else {
        return props.batches.filter(batch => {
            return selectedScholarships.value.includes(batch.scholarship.name);
        });
    }
});

const filteredCampuses = computed(() => {
    if (selectedBatches.value.length === 0) {
        return props.campuses;
    } else {
        // Extract batch IDs from the selected batches (format: "Batch X")
        const selectedBatchNumbers = selectedBatches.value.map(batchName => {
            return parseInt(batchName.replace('Batch ', ''));
        });
        
        // Find campus IDs associated with these batches
        const campusIds = props.batches
            .filter(batch => selectedBatchNumbers.includes(batch.batch_no))
            .map(batch => batch.campus_id);
        
        // Return campuses with these IDs
        return props.campuses.filter(campus => campusIds.includes(campus.id));
    }
});

// Reset dependent selections when parent selection changes
watch(selectedScholarships, () => {
    // When scholarships change, reset batch and campus selections
    selectedBatches.value = [];
    selectedCampuses.value = [];
});

watch(selectedBatches, () => {
    // When batches change, reset campus selections
    selectedCampuses.value = [];
});

const toggleSharePost = async () => {
    Share.value = !Share.value;
    
    if (Share.value) {
        await nextTick();
        initFlowbite();
        document.addEventListener('click', handleClickOutside);
    } else {
        document.removeEventListener('click', handleClickOutside);
    }
};

const closeModal = () => {
    Share.value = false;
    // Reset selections when closing modal
    selectedScholarships.value = [];
    selectedBatches.value = [];
    selectedCampuses.value = [];
    openDropdown.value = null;
};

const toggleDropdown = (dropdown) => {
    openDropdown.value = openDropdown.value === dropdown ? null : dropdown;
};

const handleClickOutside = (event) => {
    const dropdownElements = document.querySelectorAll('.relative');
    let clickedOutside = true;
    
    dropdownElements.forEach(element => {
        if (element.contains(event.target)) {
            clickedOutside = false;
        }
    });
    
    if (clickedOutside && !event.target.closest('.your-modal-class')) {
        openDropdown.value = null;
    }
};

const submitForm = () => {
    // Handle form submission logic here with the filtered selections
    console.log({
        scholarships: selectedScholarships.value,
        batches: selectedBatches.value,
        campuses: selectedCampuses.value
    });
    
    closeModal();
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    initFlowbite();
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
