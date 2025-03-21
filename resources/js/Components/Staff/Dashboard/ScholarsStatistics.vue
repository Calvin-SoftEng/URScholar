<template>
    <!-- Content Container -->
    <div class="bg-white w-full h-full space-y-3 rounded-xl dark:bg-dcontainer dark:border dark:border-gray-600 flex flex-col">
        <div class="flex flex-row justify-between">
            <span class="font-poppins font-semibold text-xl dark:text-dtext px-5 pt-5">Total Scholars</span>
            <!-- Chart Selection Buttons -->
            <div class="flex space-x-2">
                <!-- Line Chart Button -->
                <button 
                    @click="setChartType('line')" 
                    class="p-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'bg-gray-300 dark:bg-gray-600': chartType === 'line' }">
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
                <button 
                    @click="setChartType('pie')" 
                    class="p-2 rounded-md transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{ 'bg-gray-300 dark:bg-gray-600': chartType === 'pie' }">
                    <font-awesome-icon :icon="['fas', 'chart-pie']" class="text-xl" />
                </button>
            </div>


        </div>
       
        
        <div class="px-2">
            <VueApexCharts
            :type="chartType"
            :series="lineSeries"
            :options="lineOptions"
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
import { ref } from 'vue';
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

const chartType = ref("line");

// Function to change the chart type
const setChartType = (type) => {
  chartType.value = type;
};

const lineSeries = ref([{
    name: "Scholars",
    data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
}]);

const lineOptions = ref({
    xaxis: {
    categories: [1991,1992,1993,1994,1995,1996,1997,1998,1999]
  },
});
</script>