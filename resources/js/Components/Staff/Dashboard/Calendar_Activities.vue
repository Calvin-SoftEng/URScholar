<template>
    <div class="bg-dirtywhite w-full h-full p-5 space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex">
    <!-- Calendar Component -->
    <RadixCalendar v-model="selectedDate" @click-date="openModal">
    <!-- Customize calendar styling here -->
    </RadixCalendar>

    <!-- Modal for Adding Notes -->
    <RadixDialog v-if="isModalOpen" @close="closeModal">
    <div class="modal-content">
        <h2>Add Note for {{ selectedDate }}</h2>
        <textarea v-model="noteText" placeholder="Enter your note here..."></textarea>
        <button @click="saveNote">Save</button>
        <button @click="closeModal">Close</button>
    </div>
    </RadixDialog>

    <!-- Display Notes on Calendar -->
    <div v-if="notes[selectedDate]">
    <p>{{ notes[selectedDate] }}</p>
    </div>
</div>
</template>

<script setup>
import { ref } from 'vue';
// import { RadixCalendar, RadixDialog } from '@radix-ui/vue';

// const components = {
//     RadixCalendar,
//     RadixDialog,
// }

const selectedDate = ref(null);
const isModalOpen = ref(false);
const noteText = ref('');
const notes = ref({});

const openModal = (date) => {
    selectedDate.value = date;
    isModalOpen.value = true;
    };

    // Close modal
    const closeModal = () => {
    isModalOpen.value = false;
    noteText.value = ''; // Clear text
    };

    // Save note to the selected date
    const saveNote = () => {
    if (selectedDate.value) {
        notes.value[selectedDate.value] = noteText.value;
    }
    closeModal();
};
</script>

<style scoped>
/* Add your styles for the calendar and modal */
.modal-content {
padding: 20px;
background: white;
border-radius: 8px;
width: 300px;
margin: auto;
}
</style>
  