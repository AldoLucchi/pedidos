<template>
    <div class="max-w-lg mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
        <form @submit.prevent="updateDeliveryRate" class="space-y-4">
            <div>
                <label for="rate" class="block text-sm font-medium text-gray-700">Porcentaje de Delivery (%):</label>
                <input
                    type="number"
                    v-model="rate"
                    step="0.01"
                    min="0"
                    max="100"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                Actualizar Porcentaje
            </button>
        </form>

        <div v-if="currentRate" class="mt-6 text-center">
            <p class="text-lg font-semibold text-gray-800">Porcentaje actual: <span class="font-bold text-indigo-600">{{ currentRate }}%</span></p>
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

        const getDeliveryRate = async () => {
            try {
                const response = await axios.get('/delivery-rate/get');
                currentRate.value = response.data.rate; // Recibir 'rate' (contiene delivery_rate)
            } catch (error) {
                console.error('Error obteniendo el porcentaje de delivery', error);
            }
        };

        const updateDeliveryRate = async () => {
            try {
                await axios.post('/delivery-rate/update', { delivery_rate: rate.value }); // Enviar 'delivery_rate'
                alert('Porcentaje actualizado correctamente');
                getDeliveryRate();
                window.location.reload();
            } catch (error) {
                console.error('Error actualizando el porcentaje', error);
            }
        };

        onMounted(getDeliveryRate);

        return { rate, currentRate, updateDeliveryRate };
    },
};
</script>