<template>
    <div class="w-full flex flex-row font-sans text-gray-900 relative gap-3">
        <div class="w-full flex flex-col relative space-y-1" ref="startInputRef">
        <label class="block font-normal font-hanken">Check-in Date</label>
        <div class="flex w-full max-w-sm">
            <input
            type="text"
            readonly
            :value="start ? formatDate(start) : ''"
            @click.stop="openStart"
            placeholder="Select start date"
            class="flex-grow px-4 py-2 border border-gray-200 rounded-l-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div
            class="flex items-center px-3 bg-blue rounded-r-md cursor-pointer"
            @click.stop="openStart"
            >
            <font-awesome-icon :icon="['far', 'calendar']" class="text-white" />
            </div>
        </div>

        <!-- Start calendar popup inside this wrapper -->
        <div v-if="showStart" class="absolute z-50 mt-2" ref="startPopupRef" @click.stop>
            <DateRangeCalendarPopup
            v-model="start"
            :maxDate="end"
            :minDate="null"
            :startDate="start"
            :endDate="end"
            selecting="start"
            @update:modelValue="onStartDateSelected"
            />
        </div>
        </div>

        <!-- Same for end date -->

        <div class="w-full flex flex-col relative space-y-1" ref="endInputRef">
        <label class="block font-normal font-hanken">Check-out Date</label>
        <div class="flex w-full max-w-sm">
            <input
            type="text"
            readonly
            :value="end ? formatDate(end) : ''"
            @click.stop="openEnd"
            placeholder="Select end date"
            class="flex-grow px-4 py-2 border border-gray-200 rounded-l-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div
            class="flex items-center px-3 bg-blue rounded-r-md cursor-pointer"
            @click.stop="openEnd"
            >
            <font-awesome-icon :icon="['far', 'calendar']" class="text-white" />
            </div>
        </div>

        <!-- End calendar popup inside this wrapper -->
        <div v-if="showEnd" class="absolute z-50 mt-2" ref="endPopupRef" @click.stop>
            <DateRangeCalendarPopup
            v-model="end"
            :minDate="start"
            :maxDate="null"
            :startDate="start"
            :endDate="end"
            selecting="end"
            @update:modelValue="onEndDateSelected"
            />
        </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import DateRangeCalendarPopup from './DateRangeCalendarPopup.vue'

const props = defineProps({
  modelValueStart: Date,
  modelValueEnd: Date,
});
const emit = defineEmits(["update:modelValueStart", "update:modelValueEnd"]);

const start = ref(props.modelValueStart || null);
const end = ref(props.modelValueEnd || null);

watch(start, (val) => {
  emit("update:modelValueStart", val);
});
watch(end, (val) => {
  emit("update:modelValueEnd", val);
});


const showStart = ref(false);
const showEnd = ref(false);

const startInputRef = ref(null);
const endInputRef = ref(null);
const startPopupRef = ref(null);
const endPopupRef = ref(null);

function formatDate(date) {
    if (!date) return '';
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
  // Example output: "Jul 13, 2022"
}

function formatModelDate(date) {
  if (!date) return '';
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}/${month}/${day}`;
  // Example output: "2022/07/13"
}

function openStart() {
  showStart.value = true;
  showEnd.value = false;
}

function openEnd() {
  showEnd.value = true;
  showStart.value = false;
}

function onStartDateSelected(newDate) {
  if (end.value && newDate > end.value) {
    end.value = newDate;
  }
  start.value = newDate;
  showStart.value = false;
}

function onEndDateSelected(newDate) {
  if (start.value && newDate < start.value) {
    start.value = newDate;
  }
  end.value = newDate;
  showEnd.value = false;
}

// Click outside handler
function handleClickOutside(event) {
  const path = event.composedPath ? event.composedPath() : event.path;
  if (
    !path.includes(startInputRef.value) &&
    !path.includes(endInputRef.value) &&
    !path.includes(startPopupRef.value) &&
    !path.includes(endPopupRef.value)
  ) {
    showStart.value = false;
    showEnd.value = false;
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
/* Position the calendar popups nicely */
.absolute {
  position: absolute;
  background: transparent;
  top: 100%;
  left: 0;
}
</style>
