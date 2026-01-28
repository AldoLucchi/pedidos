<template>
    <div>
      <h1>Editar Método de Pago</h1>
      <form @submit.prevent="updatePaymentMethod">
        <div>
          <label for="method_name">Método de Pago</label>
          <input v-model="paymentMethod.method_name" type="text" id="method_name" required />
        </div>
        <div>
          <label for="contact_info">Información de Contacto</label>
          <textarea v-model="paymentMethod.contact_info" id="contact_info" required></textarea>
        </div>
        <button type="submit">Actualizar</button>
      </form>
    </div>
  </template>
  
  <script>
  import { useForm } from '@inertiajs/vue3';
  
  export default {
    props: {
      paymentMethod: Object,
    },
    setup(props) {
      const form = useForm({
        method_name: props.paymentMethod.method_name,
        contact_info: props.paymentMethod.contact_info,
      });
  
      function updatePaymentMethod() {
        form.put(`/payment-methods/${props.paymentMethod.id}`);
      }
  
      return {
        form,
        updatePaymentMethod,
      };
    },
  }
  </script>
  