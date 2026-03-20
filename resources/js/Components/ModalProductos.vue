<!-- resources/js/Components/ModalProductos.vue -->
<template>
    <div>
      <!-- Botón para abrir el modal -->
      <button @click="openModal" class="btn btn-primary">Ver Productos</button>
  
      <!-- Modal -->
      <div v-if="isModalOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <h2 class="modal-title">Productos</h2>
          <p class="modal-description">Lista de productos disponibles</p>
  
          <!-- Tabla de productos -->
          <table class="productos-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="producto in productos" :key="producto.id">
                <td>{{ producto.id }}</td>
                <td>{{ producto.nombre }}</td>
                <td>{{ producto.precio }}</td>
              </tr>
            </tbody>
          </table>
  
          <!-- Botón para cerrar el modal -->
          <button @click="closeModal" class="btn btn-secondary">Cerrar</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      productos: Array
    },
    data() {
      return {
        isModalOpen: false
      };
    },
    methods: {
      openModal() {
        this.isModalOpen = true;
      },
      closeModal() {
        this.isModalOpen = false;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos del modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  
  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 600px;
    z-index: 1000;
  }
  
  .modal-title {
    font-size: 1.5em;
    margin-bottom: 10px;
  }
  
  .modal-description {
    margin-bottom: 20px;
  }
  
  .productos-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .productos-table th, .productos-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  .productos-table th {
    background-color: #f4f4f4;
  }
  
  .btn {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
  }
  
  .btn-secondary {
    background-color: #6c757d;
  }
  </style>
  