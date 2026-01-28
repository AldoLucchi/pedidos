<template>
    <div class="max-w-lg mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
        <form @submit.prevent="updateIvaRate" class="space-y-4">
            <div>
                <label for="rate" class="block text-sm font-medium text-gray-700">Porcentaje de IVA (%):</label>
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
                Actualizar Porcentaje IVA
            </button>
        </form>

        <div v-if="currentRate" class="mt-6 text-center">
            <p class="text-lg font-semibold text-gray-800">Porcentaje actual de IVA: <span class="font-bold text-indigo-600">{{ currentRate }}%</span></p>
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

        const getIvaRate = async () => {
            try {
                const response = await axios.get('/iva-rate/get');
                currentRate.value = response.data.rate;
            } catch (error) {
                console.error('Error obteniendo el porcentaje de IVA', error);
            }
        };

        const updateIvaRate = async () => {
            try {
                await axios.post('/iva-rate/update', { iva_rate: rate.value });
                alert('Porcentaje de IVA actualizado correctamente');
                getIvaRate();
                window.location.reload();
            } catch (error) {
                console.error('Error actualizando el porcentaje de IVA', error);
            }
        };

        onMounted(getIvaRate);

        return { rate, currentRate, updateIvaRate };
    },
};
</script>