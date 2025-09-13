<script setup>
import { ref } from "vue";
import axios from "axios";

const age = ref(25);
const salary = ref(50000);
const result = ref(null);

const predict = async () => {
  try {
    const response = await axios.post("/predict", {
      age: age.value,
      salary: salary.value
    });
    result.value = response.data.prediction;
  } catch (error) {
    console.error(error);
  }
};
</script>

<template>
  <div>
    <input v-model="age" type="number" placeholder="Age" />
    <input v-model="salary" type="number" placeholder="Salary" />
    <button @click="predict">Predict</button>
    <p v-if="result">Prediction: {{ result }}</p>
  </div>
</template>
