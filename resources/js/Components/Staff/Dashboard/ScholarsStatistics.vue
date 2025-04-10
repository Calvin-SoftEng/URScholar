<template>
    <!-- Content Container -->
    <div class="bg-white p-2 w-full h-full rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col min-h-0 flex-shrink-0">
        <div class="flex flex-row justify-between">
            <span class="font-poppins font-semibold text-xl dark:text-dtext px-5 pt-5">Total Scholars</span>
            <!-- Chart Selection Buttons -->
            <div class="flex space-x-2 p-1">
                <!-- Line Chart Button -->
                <button 
                    @click="setChartType('line')" 
                    class="p-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'bg-gray-300 dark:bg-gray-600 text-white': chartType === 'line' }">
                    <span class="material-symbols-rounded text-xl">show_chart</span>
                </button>

                <!-- Bar Chart Button -->
                <button 
                    @click="setChartType('bar')" 
                    class="p-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'bg-gray-300 dark:bg-gray-600': chartType === 'bar' }">
                    <font-awesome-icon :icon="['fas', 'chart-simple']" class="text-xl" />
                </button>

                <!-- Pie Chart Button -->
                <!-- <button 
                    @click="setChartType('pie')" 
                    class="p-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'bg-gray-300 dark:bg-gray-600': chartType === 'pie' }">
                    <font-awesome-icon :icon="['fas', 'chart-pie']" class="text-xl" />
                </button> -->
            </div>


        </div>
       
        
        <div class="min-h-0 flex-shrink-0 h-[300px] max-h-[260px] overflow-hidden mt-2">
            <VueApexCharts
                :type="chartType"
                :series="lineSeries"
                :options="lineOptions"
                class="w-full h-full"
            />
        </div>

<!-- 
        <div>
            <VueApexCharts
            type="bar"
            :series="lineSeries"
            :options="lineOptions"
            />
        </div> -->

    </div>

</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watchEffect } from 'vue';
import { Link } from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    sponsors: {
        type: Array,
        required: true
    },
    scholarships: {
        type: Array,
        required: true
    }
});

// Detect Dark Mode Reactively
const isDarkMode = ref(false);

const updateDarkMode = () => {
  isDarkMode.value = document.documentElement.classList.contains("dark");
};

onMounted(() => {
  updateDarkMode(); // Initial Check


  const observer = new MutationObserver(updateDarkMode);
  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["class"],
  });


  onUnmounted(() => observer.disconnect());
});

const chartType = ref("line");

// Function to change the chart type
const setChartType = (type) => {
  chartType.value = type;
};

const lineSeries = ref([{
    name: "Scholars",
    data: [60, 100, 35, 200, 176, 300]
}]);

const lineOptions = computed(() => ({
    chart: {
        background: "transparent",
    },
    theme: {
        mode: isDarkMode.value ? "dark" : "light",
    },
    xaxis: {
        categories: [2020, 2021, 2022, 2023, 2024, 2025],
        labels: {
        style: {
            colors: isDarkMode.value ? "#ffffff" : "#333333",
        },
        },
    },
    yaxis: {
        labels: {
        style: {
            colors: isDarkMode.value ? "#ffffff" : "#333333",
        },
        },
    },
    tooltip: {
        theme: isDarkMode.value ? "dark" : "light",
    },
    legend: {
        labels: {
        colors: isDarkMode.value ? "#ffffff" : "#333333",
        },
    },
    }));
</script>