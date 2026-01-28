<template>
    <!-- Desktop Header -->
    <div class="hidden lg:block header-formulario fixed top-0 left-0 w-full bg-white shadow-md z-40">
        <div class="container mx-auto px-4 py-2 flex items-center justify-between">
            <!-- Logo -->
            <figure class="flex-shrink-0">
                <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-gray-200 shadow-md hover:shadow-lg transition-shadow">
                    <a class="logo-box" href="/">
                        <img 
                            src="/logo.jpg"
                            alt="Banner" 
                            class="w-full h-full object-cover transform hover:scale-110 transition-transform"
                        />
                    </a>
                </div>
            </figure>

            <!-- Search Bar -->
            <div v-show="currentStep !== 2" class="flex-grow mx-8 relative">
                <input 
                    type="text" 
                    v-model="searchQuery"
                    placeholder="Buscar productos..."
                    class="w-full px-4 py-2 pl-10 border-2 border-gray-200 rounded-full focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
            </div>

            <div class="flex items-center space-x-4">
                <a href="/dashboard" class="px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors text-sm flex items-center gap-3">
                    <div class="relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="w-8 h-8 rounded-full cursor-pointer hover:opacity-80 transition-opacity">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </div>

    <!-- HEADER MOBILE -->
    <div class="lg:hidden header-formulario-mobile fixed top-0 left-0 w-full bg-white shadow-md z-50">
        <div class="p-2">
            <div class="flex items-center justify-center gap-3">
                <!-- Logo -->
                <figure class="flex-shrink-0">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 shadow-md hover:shadow-lg transition-shadow">
                        <a href="/">
                            <img 
                                src="/logo.jpg" 
                                alt="Banner" 
                                class="w-full h-full object-cover transform hover:scale-110 transition-transform"
                            />
                        </a>
                    </div>
                </figure>

                <!-- Search Bar -->
                <div v-show="currentStep !== 2" class="flex-grow relative">
                    <input 
                        type="text" 
                        v-model="searchQuery"
                        placeholder="Buscar productos..."
                        class="w-full px-4 py-2 pl-10 border-2 border-gray-200 rounded-full focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                    />
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 mt-32 lg:mt-32">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden px-0 md:px-8 py-0 md:py-8">
            <!-- Form -->
            <form @submit.prevent="submitForm" class="space-y-8 p-4">
                <!-- STEP 1: Product List -->
                <div v-if="currentStep === 1">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white rounded-xl px-0 md:px-6 py-0 md:py-6">
                            <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Product List</h2>
                            
                            <!-- Product List -->
                            <div v-if="filteredProducts.length === 0" class="text-gray-500 text-center py-6">
                                No hay productos disponibles.
                            </div>
                            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                <div v-for="producto in paginatedProducts" :key="producto.id" 
                                    class="border-2 border-gray-100 p-2 md:p-4 rounded-xl hover:shadow-lg hover:border-green-200 transition-all flex flex-col justify-end">
                                    <div class="flex justify-center items-center h-32 md:h-48">
                                        <img class="max-h-full max-w-full rounded-lg" :src="`/storage/app/public/imagenesproductos/${producto.imagen}`" alt="Imagen del producto">
                                    </div>
                                    <h3 class="text-sm md:text-xl font-semibold mt-2 md:mt-4 text-gray-800 leading-tight mb-1">{{ producto.titulo }}</h3>
                                    <p class="text-gray-600 text-xs md:text-sm mt-0">{{ producto.descripcion }}</p>
                                    <div class="mt-2 md:mt-4">
                                        <p class="font-bold text-green-600 text-base md:text-lg">
                                            Bs. {{ calcularPrecioConvertido(producto).toFixed(2) }}
                                        </p>
                                        <div class="mt-2 md:mt-4 flex items-center justify-center space-x-2 md:space-x-3">
                                            <button 
                                                type="button"
                                                @click="decrementarCantidad(producto)"
                                                class="px-3 py-1 md:px-4 md:py-2 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors font-bold"
                                                :disabled="!productosSeleccionados[producto.id]"
                                            >
                                                X
                                            </button>
                                            <span class="mx-1 md:mx-2 text-base md:text-lg font-bold">
                                                {{ productosSeleccionados[producto.id] || 0 }}
                                            </span>
                                            <button 
                                                type="button"
                                                @click="incrementarCantidad(producto)"
                                                class="px-3 py-1 md:px-4 md:py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-bold"
                                            >
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-8 flex justify-center items-center">
                                <button type="button" @click="prevPage" :disabled="currentPage === 1" 
                                    class="px-5 py-2 mx-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 transition-colors font-bold">
                                    Anterior
                                </button>
                                <span class="mx-4 text-gray-700 font-bold">Página {{ currentPage }} de {{ totalPages }}</span>
                                <button type="button" @click="nextPage" :disabled="currentPage === totalPages" 
                                    class="px-5 py-2 mx-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 transition-colors font-bold">
                                    Siguiente
                                </button>
                            </div>

                            <!-- Selected Products Summary -->
                            <div class="mt-8 border-t pt-6">
                                <h3 class="text-2xl font-bold mb-4 text-gray-800">Selected Products</h3>
                                <div v-if="Object.keys(productosSeleccionados).length === 0" class="text-gray-500 py-4">
                                    No has seleccionado ningún producto
                                </div>
                                <div v-else class="space-y-3">
                                    <div v-for="(cantidad, productoId) in productosSeleccionados" 
                                        :key="productoId"
                                        class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                                        <span class="text-gray-700">{{ obtenerNombreProducto(productoId) }} x {{ cantidad }}</span>
                                        <span class="font-bold text-green-600">
                                            Bs. {{ calcularSubtotal(productoId) }}
                                        </span>
                                        <!-- Delete Button -->
                                        <button type="button" @click="eliminarProducto(productoId)" class="text-red-600 font-bold text-2xl">
                                            X
                                        </button>
                                    </div>

                                    <!-- Total Breakdown -->
                                    <div class="border-t pt-4 mt-4">
                                        <p class="font-bold text-gray-700">
                                            Subtotal (sin IVA ni envío): Bs. {{ calcularSubtotalSinImpuestos.toFixed(2) }}
                                        </p>
                                        <p class="font-bold text-gray-700">
                                            + IVA ({{ ivaRate }}%): Bs. {{ calcularMontoIva.toFixed(2) }}
                                        </p>
                                        <p class="font-bold text-gray-700">
                                            + Costo de envío Bs. {{ calcularCostoEnvio.toFixed(2) }}
                                        </p>
                                        <p class="font-bold text-2xl text-green-700 mt-2">
                                            Total a pagar: Bs. {{ calcularTotalConEnvioIva.toFixed(2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Continue Button -->
                            <div class="mt-8">
                                <button 
                                    @click="nextStep"
                                    :disabled="!hasSelectedProducts"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all disabled:opacity-50"
                                >
                                    Continuar con los datos de entrega
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Delivery Information -->
                <div v-else-if="currentStep === 2">
                    <div class="mb-4">
                        <h2 class="text-3xl font-bold mb-3 text-center text-gray-800">Enter your information</h2>
                        <label for="cliente_nombre" class="block text-lg font-medium text-gray-800 mb-2">Nombre y Apellido:</label>
                        <input type="text" id="cliente_nombre" v-model="form.cliente_nombre" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            required
                        />
                    </div>     
                    <div class="mb-4">
                        <label for="cliente_telefono" class="block text-lg font-medium text-gray-800 mb-2">Teléfono:</label>
                        <div class="flex gap-3 flex-wrap">
                            <select v-model="form.cliente_codigo_area" 
                                class="w-full md:w-2/5 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                                <option v-for="codigo in codigosArea" :key="codigo.value" :value="codigo.value">
                                    {{ codigo.label }}
                                </option>
                            </select>
                            <input type="text" id="cliente_telefono" v-model="form.cliente_telefono"
                                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"/>
                        </div>
                    </div>    
                    <div class="mb-4">
                        <label for="random_value_1" class="block text-lg font-medium text-gray-800 mb-2">Correo:</label>
                        <input type="text" id="random_value_1" v-model="form.random_value_1" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"/>
                    </div>
                    <div class="mb-4">
                        <label for="metodo_pago" class="block text-lg font-medium text-gray-800 mb-2">Método de Pago:</label>
                        <select id="metodo_pago" v-model="form.metodo_pago" @change="onPaymentMethodChange"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            required>
                            <option value="" disabled selected>Seleccione un método</option>
                            <option value="Dolares Efectivo">Dólares Efectivo</option>
                            <option value="Bs. Efectivo">Bolívares Efectivo</option>
                            <option value="Pago Movil">Pago Móvil</option>
                            <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                            <option value="Zelle">Zelle</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="cliente_direccion" class="block text-lg font-medium text-gray-800 mb-2">Dirección: (Asegurate de enviarla)</label>
                        <div class="flex gap-3 flex-wrap">
                            <input type="text" id="cliente_direccion" v-model="form.cliente_direccion" placeholder=""
                                class="flex-[3] px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                                required
                            />
                            <button type="button" @click="obtenerUbicacion" 
                                class="flex-[1] px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                                📍 Enviar
                            </button>
                        </div>
                        <p v-if="mensajeEnviado" class="text-green-600 font-bold mt-2">
                            ¡Enviado!
                        </p>
                    </div>
                    <div class="mb-4">
                        <label for="notas" class="block text-lg font-medium text-gray-800 mb-2">Nota Adicional:</label>
                        <textarea id="notas" v-model="form.notas" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                            placeholder="Intrucciones de entrega: Tocar el timbre, llamar antes, dejar en la puerta, no dejar con vecinos, Entregar a nombre de [Nombre], Etc..."
                        ></textarea>
                    </div>
                    <div class="flex justify-between gap-4 flex-wrap">
                        <button 
                            @click="previousStep"
                            class="w-full md:w-1/2 px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all"
                        >
                            Volver a productos
                        </button>
                        <button 
                            type="submit"
                            class="w-full md:w-1/2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                        >
                            Crear Pedido
                        </button>
                    </div>
                </div>
            </form>   
            
            <WhatsAppButton />
            
            <PaymentMethodModal 
                :showModal="showModal" 
                :selectedMethod="selectedMethod" 
                @close="closeModal" 
            />   
            
            <!-- Success Modal -->
            <div v-if="showSuccessModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50">
                <div class="bg-white p-8 rounded-2xl shadow-2xl text-gray-800 max-w-md w-full relative">
                    <span @click="closeModal" class="text-2xl absolute top-4 right-4 cursor-pointer hover:text-gray-600">&times;</span>
                    <h2 class="text-2xl font-bold text-center mb-6">Pedido Creado Exitosamente!</h2>
                    <p class="text-center mb-6">El pedido ha sido creado correctamente.</p>
                    <button @click="redirectToOrders" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                        Hacer otro pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import WhatsAppButton from '@/Components/WhatsAppButton.vue';
import PaymentMethodModal from '@/Components/PaymentMethodModal.vue';
import axios from 'axios';

export default {
    components: {
        WhatsAppButton,
        PaymentMethodModal,
    },
    props: {
        productos: {
            type: Array,
            required: true,
        },
        exchangeRate: {
            type: Number,
            default: 1,
        },
        deliveryRate: {
            type: Number,
            required: true,
            default: 1,
        },
        ivaRate: {
            type: Number,
            required: true,
            default: 1,
        },
    },
    data() {
        return {
            currentStep: 1,
            searchQuery: '',
            mensajeEnviado: false,
            currentPage: 1,
            perPage: 12,
            form: {
                cliente_nombre: '',
                cliente_telefono: '',
                cliente_codigo_area: '0412',
                cliente_direccion: '',
                fecha_pedido: new Date().toISOString().slice(0, 19),
                metodo_pago: '',
                notas: '',
                latitud: null,
                longitud: null,
                producto_id: '',
                random_value_1: '',
                random_value_2: '',
            },
            codigosArea: [
                { value: '0412', label: '0412' },
                { value: '0414', label: '0414' },
                { value: '0424', label: '0424' },
                { value: '0416', label: '0416' },
                { value: '0426', label: '0426' },
            ],
            showModal: false,
            showSuccessModal: false,
            selectedMethod: '',
            ubicacionError: '',
            productosSeleccionados: {},
        };
    },
    computed: {
        calcularSubtotalSinImpuestos() {
            return Object.entries(this.productosSeleccionados).reduce((total, [productoId, cantidad]) => {
                const producto = this.productos.find(p => p.id === parseInt(productoId));
                if (!producto) return total;
                return total + (this.calcularPrecioConDelivery(producto) * cantidad);
            }, 0);
        },
        calcularMontoIva() {
            return this.calcularSubtotalSinImpuestos * (this.ivaRate / 100);
        },
        calcularCostoEnvio() {
            const subtotalConIva = this.calcularSubtotalSinImpuestos + this.calcularMontoIva;
            return subtotalConIva * (this.deliveryRate / 100);
        },
        calcularTotalConEnvioIva() {
            return this.calcularSubtotalSinImpuestos + this.calcularMontoIva + this.calcularCostoEnvio;
        },
        paginatedProducts() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            return this.filteredProducts.slice(start, end);
        },
        totalPages() {
            return Math.ceil(this.filteredProducts.length / this.perPage);
        },
        hasSelectedProducts() {
            return Object.keys(this.productosSeleccionados).length > 0;
        },
        filteredProducts() {
            const query = this.searchQuery.toLowerCase();
            return this.productos.filter(producto => 
                producto.titulo.toLowerCase().includes(query) ||
                producto.descripcion.toLowerCase().includes(query)
            );
        },
    },
    methods: {
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        calcularPrecioConvertido(producto) {
            return producto.precio * this.exchangeRate;
        },
        async getExchangeRate() {
            try {
                const response = await axios.get('/exchange-rate/get');
                this.exchangeRate = response.data.rate;
            } catch (error) {
                console.error('Error getting exchange rate:', error);
            }
        },
        nextStep() {
            if (this.hasSelectedProducts) {
                this.currentStep = 2;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        previousStep() {
            this.currentStep = 1;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        obtenerNombreProducto(productoId) {
            const producto = this.productos.find(p => p.id === parseInt(productoId));
            return producto ? producto.titulo : 'Product not found';
        },
        incrementarCantidad(producto) {
            if (!this.productosSeleccionados[producto.id]) {
                this.productosSeleccionados[producto.id] = 0;
            }
            this.productosSeleccionados[producto.id]++;
        },
        decrementarCantidad(producto) {
            if (this.productosSeleccionados[producto.id] > 0) {
                this.productosSeleccionados[producto.id]--;
            }
        },
        onPaymentMethodChange(event) {
            const selectedMethod = event.target.value;
            const metodosConModal = ['Pago Movil', 'Transferencia Bancaria', 'Zelle'];
            
            this.selectedMethod = selectedMethod;
            this.showModal = metodosConModal.includes(selectedMethod);
        },
        closeModal() {
            this.showModal = false;
            this.showSuccessModal = false;
        },
        enviarUbicacionPorWhatsApp() {
            const numeroWhatsApp = '584121589032';
            const productosConNombres = Object.entries(this.productosSeleccionados)
                .map(([productoId, cantidad]) => {
                    const producto = this.productos.find(p => p.id === parseInt(productoId));
                    return `${producto ? producto.titulo : 'Product not found'} - Quantity: ${cantidad}`;
                })
                .join("\n");

            const mensajePedido = `
New Order Confirmed
---------------------
Customer: ${this.form.cliente_nombre}
Phone: ${this.form.cliente_codigo_area}${this.form.cliente_telefono}
Products:
${productosConNombres}
Total: ${this.calcularTotalConEnvioIva.toFixed(2)} BS.
Payment type: ${this.form.metodo_pago}
Date: ${new Date().toLocaleString()}
---------------------`;

            const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensajePedido)}`;
            window.open(urlWhatsApp, '_blank');
        },
        obtenerUbicacion() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.form.latitud = position.coords.latitude;
                        this.form.longitud = position.coords.longitude;
                        this.form.random_value_2 = `https://www.google.com/maps?q=${position.coords.latitude},${position.coords.longitude}`;
                        this.ubicacionError = '';
                    },
                    (error) => {
                        this.manejarErrorUbicacion(error);
                    }
                );
            } else {
                this.ubicacionError = 'Geolocation is not supported by this browser.';
            }
            
            this.mensajeEnviado = true;
            setTimeout(() => {
                this.mensajeEnviado = false;
            }, 3000);
        },
        manejarErrorUbicacion(error) {
            const mensajes = {
                [error.PERMISSION_DENIED]: 'Location access was not allowed. You can enter it manually.',
                [error.POSITION_UNAVAILABLE]: 'Location information is not available.',
                [error.TIMEOUT]: 'The request to get location timed out.',
            };
            this.ubicacionError = mensajes[error.code] || 'An unknown error occurred while getting location.';
        },
        async submitForm() {
            try {
                const productosFormateados = Object.entries(this.productosSeleccionados)
                    .filter(([_, cantidad]) => cantidad > 0)
                    .map(([productoId, cantidad]) => ({
                        producto_id: productoId,
                        cantidad: cantidad
                    }));

                const formData = { 
                    ...this.form,
                    cliente_telefono: `${this.form.cliente_codigo_area}${this.form.cliente_telefono}`,
                    productos: productosFormateados,
                    random_value_2: this.form.random_value_2, 
                };

                await this.$inertia.post('/pedidos', formData);
                this.showSuccessModal = true;
                this.enviarUbicacionPorWhatsApp();
            } catch (error) {
                console.error('Error creating order:', error);
                alert('There was an error creating the order. Please try again.');
            }
        },
        redirectToOrders() {
            this.$inertia.visit('/');
        },
        calcularPrecioConDelivery(producto) {
            const precioEnBolivares = producto.precio * this.exchangeRate;
            const deliveryRateDecimal = this.deliveryRate / 100;
            return precioEnBolivares * (1 + deliveryRateDecimal);
        },
        calcularSubtotal(productoId) {
            const producto = this.productos.find(p => p.id === parseInt(productoId));
            if (!producto) return 0;
            const cantidad = this.productosSeleccionados[productoId] || 0;
            return (this.calcularPrecioConDelivery(producto) * cantidad).toFixed(2);
        },
        eliminarProducto(productoId) {
            delete this.productosSeleccionados[productoId];
        }
    },
    mounted() {
        this.getExchangeRate();
    },
    watch: {
        searchQuery() {
            this.currentPage = 1;
        }
    }
};
</script>

<style scoped>
/* Only minimal necessary styles that Tailwind cannot do */
</style>
