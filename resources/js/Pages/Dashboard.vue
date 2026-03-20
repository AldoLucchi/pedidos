<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="bloqueDashboard">
        <div class="bloqueDashboard01">
          <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Panel de Control: 
            <span v-if="user.role === 1">Administrador</span>
            <span v-else-if="user.role === 2">Delivery</span>
          </h2>
        </div>
        <div class="bloqueDashboard02">
          <button v-if="user && user.role === 1"
            @click="navigateToAllProduct" 
            class="p-2 bg-blue-500 text-white rounded-md mr-2"
          >
            Ver Productos
          </button>
          <button v-if="user && user.role === 1"
            @click="navigateToCreateProduct" 
            class="p-2 bg-blue-500 text-white rounded-md mr-2"
          >
            Crear Producto Nuevo
          </button>
          <button v-if="user && user.role === 1"
            @click="createNewUser" 
            class="p-2 bg-blue-500 text-white rounded-md"
          >
            Crear nuevo gestor
          </button>
        </div>

        <!-- Sección de la campanita -->
        <div class="relative inline-block mr-4" @click="toggleModal">
          <button class="p-2 text-gray-600 hover:text-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span v-if="newPedidosCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ newPedidosCount }}
            </span>
          </button>
        </div>

  <!-- Modal de pedidos nuevos -->
  <div v-show="isModalVisible" class="modal-overlay fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50" @click.self="closeModal">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-xs">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold">Pedidos Nuevos</h3>
        <button @click="closeModal" class="text-gray-500">&times;</button>
      </div>
      <p class="mt-2">Tienes {{ newPedidosCount }} nuevo(s) pedido(s).</p>
      <button @click="reloadPedidos" class="mt-4 p-2 bg-blue-500 text-white rounded-md w-full flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>  
        Actualizar Pedidos
      </button>
    </div>
  </div>
      </div>
    </template>

    <div class="py-12 flex bloque-dashboard">
      <div class="w-full md:w-3/4 sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 area-pedidos-tickets">
            <h3 class="text-lg font-semibold">Tickets Pedidos</h3>
            
            <!-- Mostrar el mensaje de éxito o error -->
            <div v-if="$page.props.successMessage" class="mb-4 text-green-500">
              {{ $page.props.successMessage }}
            </div>
            <div v-if="$page.props.errorMessage" class="mb-4 text-red-500">
              {{ $page.props.errorMessage }}
            </div>

            <div class="mt-4">
              <div v-for="pedido in pedidosOrdenados" :key="pedido.id" class="mb-4">
                <button
                  class="w-full text-left p-2 bg-gray-100 rounded-md focus:outline-none"
                  @click="pedido.open = !pedido.open"
                >
                <span class="text-[115%]"><b>Pedido #{{ pedido.id }}</b></span> 
                <br>
                <b>Nombre Cliente:</b> {{ pedido.cliente_nombre }} {{ pedido.cliente_apellido }}
                  <br />
                  <b>Fecha y hora:</b> {{ pedido.fecha_pedido }} - Estado de pedido:
                  <b>
                    <span 
                      :class="{
                        'text-red-600': pedido.estado === 'Por entregar',
                        'text-yellow-600': pedido.estado === 'En camino',
                        'text-green-600': pedido.estado === 'Entregado'
                      }"
                    >
                      {{ pedido.estado }}
                    </span>
                  </b>
                </button>

                <div v-show="pedido.open" class="mt-2 p-2 bg-gray-50 rounded-md">
                  <p><strong>Producto pedidos:</strong></p>
                      <ul class="list-disc pl-6 mt-2">
                      <li v-for="producto in pedido.productos" :key="producto.id" class="mb-2">
                          <b>{{ producto.titulo }}</b>
                          <br>
                          <span class="text-sm text-gray-600">
                              <b>Cantidad:</b> {{ producto.pivot?.cantidad || 0 }} <br>
                              <b>Precio unitario:</b> ${{ Number(producto.pivot?.precio_unitario || 0).toFixed(2) }}
                              a <b>Bs. {{ producto.pivot?.precio_unitario
                              ? (Number(producto.pivot.precio_unitario) * Number(props.exchangeRate)).toFixed(2)
                              : '0.00' }}</b>
                              <br>
                              <b>Total ${{ (Number(producto.pivot?.cantidad || 0) * Number(producto.pivot?.precio_unitario || 0)).toFixed(2) }}</b>
                              a
                              <b class=""> 
                              Bs. {{ (producto.pivot?.cantidad && producto.pivot?.precio_unitario) ? 
                              (Number(producto.pivot.cantidad) * Number(producto.pivot.precio_unitario) * Number(props.exchangeRate)).toFixed(2) 
                              : '0.00' }}
                            </b>
                          </span>
                      </li>
                  </ul>
                  <p class="font-bold text-[105%] mt-4 border-t pt-4">
                      Subtotal a pagar de productos (sin IVA ni envío): ${{ calcularSubtotalSinImpuestos(pedido).usd.toFixed(2) }}
                      <span class="text-yellow-600">(Bs. {{ calcularSubtotalSinImpuestos(pedido).bs.toFixed(2) }})</span>
                  </p>


                  <p class="font-bold text-[105%]">
                     + IVA ({{ props.ivaRate }}%): ${{ calcularMontoIva(pedido).usd.toFixed(2) }}
                    <span class="text-yellow-600">(Bs. {{ calcularMontoIva(pedido).bs.toFixed(2) }})</span>
                  </p>

                  <p class="font-bold text-[105%]">
                  + Costo de envío ${{ calcularCostoEnvio(pedido).usd.toFixed(2) }}
                  <span class="text-yellow-600">(Bs. {{ calcularCostoEnvio(pedido).bs.toFixed(2) }})</span>
                  </p>

                <p class="font-bold text-[115%] pb-2">
                  Total a pagar el cliente (con envío e IVA): ${{ calcularTotalConEnvioIva(pedido).usd.toFixed(2) }}
                  <span class="text-red-600">(Bs. {{ calcularTotalConEnvioIva(pedido).bs.toFixed(2) }})</span>
                </p>
                  <p><strong>Teléfono del cliente:</strong> {{ pedido.cliente_telefono }}</p>
                  <p><strong>Correo del cliente:</strong> {{ pedido.random_value_1 }}</p>
                  <p><strong>Dirección del cliente:</strong> {{ pedido.cliente_direccion }}</p>
                  <p><strong>Ubicación:</strong> 
                    <a v-if="pedido.random_value_2" :href="pedido.random_value_2" target="_blank" style="color:blue">
                      <b> Ver ubicación en Google Maps</b>
                    </a>
                  </p>
                  <p><strong>Método de pago:</strong> {{ pedido.metodo_pago }}</p>
                  <p><strong>Notas Adicionales:</strong> {{ pedido.notas }}</p>

                  <!-- Cambiar estado -->
                  <div class="mt-4">
                    <label for="estado" class="block font-semibold">Actualizar Estado:</label>
                    <select
                      id="estado"
                      class="p-2 border rounded-md w-full"
                      v-model="pedido.estado"
                    >
                      <option value="Por entregar">Por entregar</option>
                      <option value="En camino">En camino</option>
                      <option value="Entregado">Entregado</option>
                    </select>
                  </div>

                  <!-- Botón para actualizar el estado -->
                  <div class="mt-4">
                    <button
                      class="p-2 bg-blue-500 text-white rounded-md"
                      @click="updatePedidoEstado(pedido.id, pedido.estado)"
                    >
                      Actualizar estado
                    </button>
                  </div>

                  <!-- Botón para eliminar el pedido -->
                  <div class="mt-4">
                    <button v-if="user && user.role === 1"
                      class="p-2 bg-red-500 text-white rounded-md"
                      @click="deletePedido(pedido.id)"
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="pedidos.links.length > 0" class="pagination">
            <button
              v-for="link in pedidos.links"
              :key="link.label"
              :disabled="!link.url || link.active"
              @click="loadPage(link.url)"
              :class="{ 'bg-blue-500 text-white': link.active, 'bg-gray-300': !link.url || link.active }"
              class="p-2 mx-1 rounded-md"
            >
              <span v-html="link.label"></span>
            </button>
          </div>
        </div>
      </div>
      <div class="w-full md:w-1/4">
      <div 
      v-if="user && user.role === 1"
      class="w-full px-4">
        <ExchangeRate />
      </div>
      <div 
      v-if="user && user.role === 1"
      class="w-full px-4">
        <DeliveryRate />
      </div>
      <div 
      v-if="user && user.role === 1"
      class="w-full px-4">
        <IvaRate />
      </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';
import ExchangeRate from './ExchangeRate.vue';
import DeliveryRate from './DeliveryRate.vue';
import IvaRate from '@/Components/IvaRate.vue';

const props = defineProps({
  pedidos: {
    type: Object,
    default: () => ({ data: [], links: [] })
  },
  user: Object,
  exchangeRate: {
    type: Number,
    required: true
  },
  deliveryRate: {
    type: Number,
    required: true
  },
  ivaRate: {
    type: Number,
    required: true
  }
});

// Estado
const newPedidosCount = ref(0);
const isModalVisible = ref(false);
const totalPedidosCount = ref(0);
const pollingInterval = ref(null);

// Computed
const pedidosOrdenados = computed(() => {
  if (Array.isArray(props.pedidos.data)) {
    return [...props.pedidos.data].sort((a, b) => b.id - a.id);
  } else {
    return [];  // Retorna un arreglo vacío si no es un arreglo
  }
});

const calcularCostoEnvio = (pedido) => {
    const subtotalUSD = pedido.productos.reduce((total, producto) => {
        const cantidad = producto.pivot?.cantidad || 0;
        const precioUnitario = producto.pivot?.precio_unitario || 0;
        return total + (cantidad * precioUnitario);
    }, 0);

    const totalConIvaUSD = subtotalUSD * (1 + props.ivaRate / 100); // Calculate total with IVA

    const costoEnvioUSD = totalConIvaUSD * (props.deliveryRate / 100); // Calculate delivery on total with IVA

    const costoEnvioBS = costoEnvioUSD * props.exchangeRate;

    return {
        usd: costoEnvioUSD,
        bs: costoEnvioBS
    };
};

const loadPage = (url) => {
  console.log("Cargando página:", url);
  if (!url) {
    console.warn("URL no válida");
    return;
  }
  Inertia.visit(url, {
    preserveState: true,
    onSuccess: () => {
      console.log("Página cargada correctamente");
    },
    onError: (errors) => {
      console.error("Error al cargar la página:", errors);
    },
  });
};

const calcularSubtotalSinImpuestos = (pedido) => {
    return {
        usd: pedido.productos.reduce((total, producto) => {
            const cantidad = producto.pivot?.cantidad || 0;
            const precioUnitario = producto.pivot?.precio_unitario || 0;
            return total + (cantidad * precioUnitario);
        }, 0),
        get bs() {
            return this.usd * props.exchangeRate;
        }
    };
};

const calcularMontoIva = (pedido) => {
    const subtotalUSD = pedido.productos.reduce((total, producto) => {
        const cantidad = producto.pivot?.cantidad || 0;
        const precioUnitario = producto.pivot?.precio_unitario || 0;
        return total + (cantidad * precioUnitario);
    }, 0);

    const montoIvaUSD = subtotalUSD * (props.ivaRate / 100);
    const montoIvaBS = montoIvaUSD * props.exchangeRate;

    return {
        usd: montoIvaUSD,
        bs: montoIvaBS
    };
};

const checkNewPedidos = async () => {
    try {
        // Obtén el último timestamp almacenado
        const lastCheck = localStorage.getItem('lastPedidoCheck') || Date.now();
        
        // Realiza la solicitud al backend
        const response = await axios.get('/check-new-pedidos', {
            params: {
                since: lastCheck, // Envía el timestamp al backend
            },
        });

        // Actualiza el último timestamp almacenado
        localStorage.setItem('lastPedidoCheck', response.data.timestamp);

        // Solo suma nuevos pedidos al contador existente
        if (response.data.count > 0) {
            newPedidosCount.value += response.data.count; // Acumula los nuevos pedidos
            showNotification(`Tienes ${response.data.count} pedido(s) nuevo(s)`);
        }
    } catch (error) {
        console.error('Error checking new pedidos:', error);
    }
};

let soundInterval = null;
const audio = new Audio('/sounds/alarm-05.mp3');
audio.volume = 0.3; // Volumen moderado

const playNotificationSound = () => {
  // Si ya hay un intervalo activo, no hacer nada
  if (soundInterval) return;
  
  // Reproducir inmediatamente
  audio.play().catch(e => console.log('Error inicial:', e));
  
  // Configurar repetición cada 3 segundos
  soundInterval = setInterval(() => {
    audio.currentTime = 0; // Reiniciar el audio
    audio.play().catch(e => console.log('Error en repetición:', e));
  }, 3000); // 3 segundos entre repeticiones
};

// Funciones de notificación y UI
const showNotification = (message) => {
  // Reproducir sonido primero
  playNotificationSound();
  
  // Luego la notificación visual (código que ya tenías)
  if ("Notification" in window) {
    Notification.requestPermission().then(permission => {
      if (permission === "granted") {
        new Notification("Nuevo Pedido", {
          body: message,
          icon: '/favicon.ico'
        });
      } else {
        console.warn("Permiso para notificaciones no concedido");
      }
    });
  } else {
    console.warn("Este navegador no soporta notificaciones");
  }
};

const toggleModal = () => {
  isModalVisible.value = !isModalVisible.value;
};

const closeModal = () => {
  isModalVisible.value = false;
};

const reloadPedidos = () => {
    localStorage.setItem('lastPedidoCheck', Date.now()); // Actualizar timestamp
    newPedidosCount.value = 0; // Reiniciar el contador aquí
    Inertia.reload({
        onSuccess: () => {
            isModalVisible.value = false;
        }
    });
};

// Funciones de navegación
const navigateToCreateProduct = () => {
  Inertia.visit(route('productos.create'));
};

const navigateToAllProduct = () => {
  Inertia.visit(route('productos.index'));
};

const createNewUser = async () => {
  try {
    // Llamamos al endpoint para cerrar sesión
    await axios.post(route('logout'));

    // Luego redirigimos al registro
    Inertia.visit(route('register'));
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
    alert('Hubo un error al cerrar sesión');
  }
};

// Funciones de gestión de pedidos
const updatePedidoEstado = (pedidoId, estado) => {
  Inertia.put(`/pedidos/${pedidoId}/estado`, { estado }, {
    onSuccess: () => {
      alert('Estado actualizado correctamente');
    },
    onError: (errors) => {
      console.error(errors);
      alert('Error al actualizar el estado');
    }
  });
};

const deletePedido = (pedidoId) => {
  if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
    Inertia.delete(`/pedidos/${pedidoId}`, {
      onSuccess: () => {
        alert('Pedido eliminado correctamente');
      },
      onError: (errors) => {
        console.error(errors);
        alert('Error al eliminar el pedido');
      }
    });
  }
};

onMounted(() => {
  // Reiniciar el contador al cargar la página
  newPedidosCount.value = 0;

  // Establecer el conteo inicial de pedidos
  totalPedidosCount.value = props.pedidos.data.length;
  
  // Iniciar el polling con un intervalo de 10 segundos
  pollingInterval.value = setInterval(checkNewPedidos, 5000);
  
  // Verificar inmediatamente
  checkNewPedidos();
});

onUnmounted(() => {
  if (pollingInterval.value) {
    clearInterval(pollingInterval.value);
  }
});

// Watch para cambios en pedidos
watch(() => props.pedidos.data, (newPedidos, oldPedidos) => {
  if (oldPedidos && newPedidos.length !== oldPedidos.length) {
    checkNewPedidos();
  }
}, { deep: true });

const calcularTotalPedido = (pedido) => {
  const subtotalUSD = pedido.productos.reduce((total, producto) => {
    const cantidad = producto.pivot?.cantidad || 0;
    const precioUnitario = producto.pivot?.precio_unitario || 0;
    return total + (cantidad * precioUnitario);
  }, 0);

  const costoEnvioUSD = calcularCostoEnvio(pedido).usd; // Obtén el costo de envío calculado

  const totalUSD = subtotalUSD + costoEnvioUSD; // Suma el costo de envío al subtotal

  const totalBS = totalUSD * props.exchangeRate;

  return {
    usd: totalUSD,
    bs: totalBS
  };
};

const calcularTotalConEnvioIva = (pedido) => {
    const subtotalUSD = pedido.productos.reduce((total, producto) => {
        const cantidad = producto.pivot?.cantidad || 0;
        const precioUnitario = producto.pivot?.precio_unitario || 0;
        return total + (cantidad * precioUnitario);
    }, 0);

    const totalConIvaUSD = subtotalUSD * (1 + props.ivaRate / 100); // Calculate total with IVA

    const costoEnvioUSD = calcularCostoEnvio(pedido).usd; // Use the new calculation

    const totalConEnvioIvaUSD = totalConIvaUSD + costoEnvioUSD; // Add delivery cost

    const totalConEnvioIvaBS = totalConEnvioIvaUSD * props.exchangeRate;

    return {
        usd: totalConEnvioIvaUSD,
        bs: totalConEnvioIvaBS,
    };
};

</script>

<style scoped>
.modal-overlay {
  transition: opacity 0.3s ease;
}

.bloqueDashboard {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.bloqueDashboard02 {
  text-align: right;
}

.pagination {display: flex;justify-content: center;padding-bottom: 1rem;}

.p-6.text-gray-900.area-pedidos-tickets {
    padding-bottom: 0;
}

button.p-2.mx-1.rounded-md {
    background: rgb(209 213 219 / var(--tw-bg-opacity, 1));
}

@media (max-width: 991px) {
    .mx-auto.max-w-7xl.px-4.py-6.sm\:px-6.lg\:px-8 {
        display: flex;
        flex-direction: column;
    }

    .bloqueDashboard {
        display: flex;
        flex-direction: column-reverse;
    }

    .bloqueDashboard02 {
        display: flex;
        padding: 1rem;
        margin-bottom: 1rem;
    }

  .py-12.flex.bloque-dashboard {
    display: flex;
    flex-direction: column;
    }
}

.py-12.flex.bloque-dashboard {
    padding-top: 1rem;
}

</style>