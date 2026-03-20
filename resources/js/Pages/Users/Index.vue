<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-700 mb-6">Lista de Usuarios</h1>
    
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">ID</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nombre</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Email</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rol
              <div class="text-xs text-gray-500">1=Admin; 2=Delivery</div>
            </th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Foto de perfil</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2 text-sm text-gray-700">{{ user.id }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ user.name }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ user.email }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ user.role }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ user.profile_photo_path }}</td>
            <td class="px-4 py-2 text-sm">
              <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">
                Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  users: Array,
});

const deleteUser = (id) => {
  if (confirm("¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.")) {
    router.delete(`/users/${id}`).then(() => {
    });
  }
};
</script>

