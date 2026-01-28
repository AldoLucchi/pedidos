<template>
    <div>
      <h1>Lista de Métodos de Pago</h1>
      <table class="table-auto w-full border-collapse">
        <thead>
          <tr>
            <th class="px-4 py-2">Método de Pago</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="paymentMethod in paymentMethods" :key="paymentMethod.id">
            <td class="px-4 py-2">{{ paymentMethod.method_name }}</td>
            <td class="px-4 py-2">{{ paymentMethod.contact_info }}</td>
            <td class="px-4 py-2">
              <inertia-link :href="`/payment-methods/edit/${paymentMethod.id}`" class="text-blue-500">Editar</inertia-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3';
  
  export default {
    props: {
      paymentMethods: Array,
    },
  }
  </script>
  