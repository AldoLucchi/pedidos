<template>
  <div class="max-w-lg mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
    <form @submit.prevent="updateExchangeRate" class="space-y-4">
      <div>
        <label for="rate" class="block text-sm font-medium text-gray-700">Tasa de Cambio (USD a Bs):</label>
        <input 
          type="number" 
          v-model="rate" 
          step="0.01" 
          required 
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        >
      </div>

      <button 
        type="submit" 
        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        Actualizar Tasa
      </button>
    </form>

    <div v-if="currentRate" class="mt-6 text-center">
      <p class="text-lg font-semibold text-gray-800">Tasa actual: <span class="font-bold text-indigo-600">{{ currentRate }} Bs/USD</span></p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const rate = ref('');
    const currentRate = ref(null);

    const getExchangeRate = async () => {
      try {
        const response = await axios.get('/exchange-rate/get');
        currentRate.value = response.data.rate;
      } catch (error) {
        console.error('Error obteniendo la tasa de cambio', error);
      }
    };

    const updateExchangeRate = async () => {
      try {
        await axios.post('/exchange-rate/update', { rate: rate.value });
        alert('Tasa actualizada correctamente');
        getExchangeRate();
        window.location.reload(); // Recarga la página después del alert
      } catch (error) {
        console.error('Error actualizando la tasa', error);
      }
    };

    onMounted(getExchangeRate);

    return { rate, currentRate, updateExchangeRate };
  },
};
</script>
