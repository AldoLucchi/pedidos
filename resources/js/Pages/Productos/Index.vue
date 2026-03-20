<template>
  <Head title="Productos" />
  <AuthenticatedLayout>
    <div class="container mx-auto py-8">
      <div class="flex justify-between items-center mb-6">
        <span class="left">
          <h1 class="text-3xl font-semibold text-gray-800">Productos</h1>
        </span>
        <span class="right">
          <button @click="navigateToCreateProduct" class="p-2 bg-blue-500 text-white rounded-md">
            Crear Producto Nuevo
          </button>
        </span>
      </div>

      <div class="relative mb-8">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Buscar productos..."
          class="w-full p-2 pl-10 pr-4 border rounded-md"
        />
        <!-- Icono de lupa -->
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </span>
    </div>

      <!-- Mostrar los productos -->
      <div v-if="filteredProductos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="producto in filteredProductos" :key="producto.id" class="bg-white shadow-md rounded-lg overflow-hidden">
          <div class="pt-4" style="width: 100%; height: 170px; display:flex; justify-content: center;">
            <img style="width: 170px; height:auto; text-align: center;" :src="`/storage/app/public/imagenesproductos/${producto.imagen}`" alt="Imagen del producto">
          </div>
          <div class="p-6">
            <h3 class="text-xl font-medium text-gray-800 mb-2">{{ producto.titulo }}</h3>
            <p class="text-gray-600 mb-4">{{ producto.descripcion }}</p>
            <p class="text-lg font-semibold text-gray-800">Precio Dolares: ${{ producto.precio }}</p>
            <div class="flex justify-between mt-4">
              <button @click="openEditModal(producto)" class="p-2 bg-yellow-500 text-white rounded-md">Editar</button>
              <button @click="deleteProduct(producto.id)" class="p-2 bg-red-500 text-white rounded-md">Eliminar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Si no hay productos -->
      <div v-else class="text-center text-gray-500">
        <p>No hay productos disponibles.</p>
      </div>

      <!-- Modal para editar producto -->
      <div v-if="isEditModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-1/3">
          <h3 class="text-2xl font-semibold text-gray-800 mb-4">Editar Producto</h3>
          <form class="formulario-edit" @submit.prevent="updateProduct">
            <div class="mb-4">
              <label for="titulo" class="block text-gray-700"><b>Título</b></label>
              <input v-model="editForm.titulo" type="text" id="titulo" class="w-full p-2 border rounded-md" required />
            </div>
            <div class="mb-4">
              <label for="descripcion" class="block text-gray-700"><b>Descripción</b></label>
              <textarea v-model="editForm.descripcion" id="descripcion" class="w-full p-2 border rounded-md" required></textarea>
            </div>
            <div class="mb-4">
              <label for="precio" class="block text-gray-700"><b>Precio en Dolares</b></label>
              <input v-model="editForm.precio" type="number" id="precio" step="0.01" class="w-full p-2 border rounded-md" required />
            </div>
            <div class="mb-4">
              <div class="border rounded-md p-2">
              <label for="imagen" class="block text-gray-700"><b>Imagen</b></label>
                <div class="box-p-image">
                  <p class="text-red-500 text-sm">No subir imagenes con un peso mayor a 2mb <br>
                      No subir fotos directamente desde el celular, en vez de eso buscar la imagen referencial del producto en la Web y subir esta
                  </p>
                </div>
              </div>
              <input @change="handleImageChange" type="file" id="imagen" class="w-full p-2" accept="image/*" />
              <img 
                  v-if="editForm.imagen && typeof editForm.imagen === 'string'" 
                  :src="editForm.imagen" 
                  class="mt-2 w-32 h-32 object-cover" 
                  alt="Imagen actual" 
                  @error="handleImageError"
                />
            </div>
            <div class="flex justify-between">
              <button @click="closeEditModal" type="button" class="p-2 bg-gray-500 text-white rounded-md">Cancelar</button>
              <button type="submit" class="p-2 bg-blue-500 text-white rounded-md">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { defineProps,computed } from 'vue';
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const isEditModalOpen = ref(false);
const editForm = ref({
  id: null,
  titulo: '',
  descripcion: '',
  precio: '',
  imagen: null,
});
const searchQuery = ref(''); // Variable para la búsqueda

const props = defineProps({
  productos: Array,
  successMessage: String,
});

// Computed para filtrar los productos en tiempo real
const filteredProductos = computed(() => {
  return props.productos.filter(producto => 
    producto.titulo.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

function navigateToCreateProduct() {
  router.visit(route('productos.create'));
}

function openEditModal(producto) {
  editForm.value = { 
    id: producto.id,
    titulo: producto.titulo,
    descripcion: producto.descripcion,
    precio: producto.precio,
    imagen: producto.imagen ? `/storage/app/public/imagenesproductos/${producto.imagen}` : null
  };
  isEditModalOpen.value = true;
}

function closeEditModal() {
  isEditModalOpen.value = false;
  editForm.value = {
    id: null,
    titulo: '',
    descripcion: '',
    precio: '',
    imagen: null, // Restablece la imagen a null
  };
}

function handleImageChange(event) {
  editForm.value.imagen = event.target.files[0];
}

function updateProduct() {
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('titulo', editForm.value.titulo);
  formData.append('descripcion', editForm.value.descripcion);
  formData.append('precio', editForm.value.precio);
  
  if (editForm.value.imagen instanceof File) {
    formData.append('imagen', editForm.value.imagen);
  }

  router.post(`/productos/${editForm.value.id}`, formData, {
    forceFormData: true,
    onSuccess: () => {
      closeEditModal();
    },
    onError: (errors) => {
      console.error('Errores:', errors);
    },
  });
}

function deleteProduct(id) {
  if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
    router.delete(`/productos/${id}`);
  }
}
</script>

<style scoped>
/* Estilos generales */
.container {
  max-width: 1200px;
}

/* Estilos de los productos */
.bg-white {
  background-color: #fff;
}
.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.rounded-lg {
  border-radius: 10px;
}
.overflow-hidden {
  overflow: hidden;
}
.p-6 {
  padding: 1.5rem;
}

/* Estilos de los textos */
.text-gray-800 {
  color: #2d3748;
}
.text-gray-600 {
  color: #718096;
}
.text-gray-500 {
  color: #a0aec0;
}

/* Estilos de las imágenes */
.object-cover {
  object-fit: cover;
}

.text-xl {
  font-size: 1.25rem;
}
.font-medium {
  font-weight: 500;
}
.font-semibold {
  font-weight: 600;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.pagination {
    padding: 1rem;
    text-align: center;
}

/* Modal */
.fixed {
  position: fixed;
}
.bg-opacity-50 {
  background-color: rgba(0, 0, 0, 0.5);
}

@media (max-width: 640px) {
    .bg-white.p-6.rounded-lg.w-1\/3 {
      width: 90%;
  }
}

</style>