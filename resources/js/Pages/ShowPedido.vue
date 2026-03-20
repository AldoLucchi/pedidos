<script setup>
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Define las propiedades que vienen del controlador (por ejemplo, el pedido)
const props = defineProps({
    pedido: Object, // El objeto pedido que será pasado desde la ruta
});

// Maneja la acción de ingresar otro pedido
const handleNewOrder = () => {
    router.push({ name: 'pedidos.create' }); // Redirige a la página de creación de un nuevo pedido
};

// Maneja la acción de hablar por WhatsApp
const handleWhatsapp = () => {
    window.open('https://wa.me/+1234567890', '_blank'); // Cambia el número con el correcto
};
</script>

<template>
    <div>
        <h2>Detalle del Pedido</h2>
        <div v-if="pedido">
            <p><strong>Cliente:</strong> {{ pedido.cliente_nombre }} {{ pedido.cliente_apellido }}</p>
            <p><strong>Fecha del Pedido:</strong> {{ pedido.fecha_pedido }}</p>
            <p><strong>Dirección:</strong> {{ pedido.cliente_direccion }}</p>
            <!-- Agrega más campos según tu modelo de Pedido -->
        </div>

        <!-- Opciones después de realizar un pedido -->
        <div>
            <button @click="handleNewOrder">Ingresar otro pedido</button>
            <button @click="handleWhatsapp">Hablar vía WhatsApp</button>
        </div>
    </div>
</template>
