<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="container mx-auto py-8">
      <h1 class="text-3xl font-semibold text-gray-800 mb-6">Agregar Nuevo Producto</h1>

      <!-- Formulario para agregar un nuevo producto -->
      <form @submit.prevent="submitForm" class="bg-white shadow-md rounded-lg p-6 space-y-6">
        <div>
          <label for="titulo" class="block text-gray-700 font-medium mb-2">Título</label>
          <input
            type="text"
            v-model="form.titulo"
            id="titulo"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            required
          />
        </div>

        <div>
          <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción</label>
          <textarea
            v-model="form.descripcion"
            id="descripcion"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            required
          ></textarea>
        </div>

        <div>
          <label for="precio" class="block text-gray-700 font-medium mb-2">Precio</label>
          <input
            type="number"
            v-model="form.precio"
            step="0.01"
            id="precio"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            required
          />
        </div>

        <div>
          <label for="imagen" class="block text-gray-700 font-medium mb-2">Imagen</label>
          <div class="box-p-image mb-3">
                  <p class="text-red-500 text-sm">No subir imagenes con un peso mayor a 2mb <br>
                      No subir fotos directamente desde el celular, en vez de eso buscar la imagen referencial del producto en la Web y subir esta
                  </p>
                </div>
          <input
            type="file"
            @change="handleFileChange"
            id="imagen"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
          />
        </div>

        <button
          type="submit"
          class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          Crear Producto
        </button>

        <!-- Mensaje de éxito -->
        <div v-if="successMessage" class="text-green-600 text-center mt-4">
          {{ successMessage }}
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';  // Importar Inertia desde @inertiajs/inertia

// Define el estado del formulario
const form = ref({
  titulo: '',
  descripcion: '',
  precio: '',
  imagen: null,
});

// Mensaje de éxito
const successMessage = ref('');

// Manejar el cambio de archivo
const handleFileChange = (event) => {
  form.value.imagen = event.target.files[0];
};

// Método para enviar el formulario
const submitForm = async () => {
    try {
        // Crear un FormData para enviar archivos
        const formData = new FormData();
        formData.append('titulo', form.value.titulo);
        formData.append('descripcion', form.value.descripcion);
        formData.append('precio', form.value.precio);
        if (form.value.imagen) {
            formData.append('imagen', form.value.imagen);
        }

        // Usar el método post de Inertia con FormData
        Inertia.post('/productos', formData, {
            forceFormData: true
        });

        // ... resto del código ...
    } catch (error) {
        console.error('Error al crear el producto: ', error);
    }
};
</script>


<style scoped>
/* Estilos para el formulario */
.container {
  max-width: 600px;
}

.bg-white {
  background-color: #fff;
}

.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.rounded-lg {
  border-radius: 10px;
}

.p-6 {
  padding: 1.5rem;
}

.space-y-6 > * + * {
  margin-top: 1.5rem;
}

.text-gray-700 {
  color: #4a5568;
}

.text-green-600 {
  color: #48bb78;
}

.text-green-700 {
  color: #2f855a;
}

.w-full {
  width: 100%;
}

.p-3 {
  padding: 0.75rem;
}

.border {
  border-width: 1px;
}

.border-gray-300 {
  border-color: #e2e8f0;
}

.focus\:outline-none {
  outline: none;
}

.focus\:ring-green-500 {
  --ring-opacity: 1;;
}

.font-semibold {
  font-weight: 600;
}

.font-medium {
  font-weight: 500;
}

.rounded-lg {
  border-radius: 5px;
}

button {
  cursor: pointer;
}

h1.text-3xl.font-semibold.text-gray-800.mb-6 {
    text-align: center;
}

</style>

