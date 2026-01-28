<template>
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-xl font-bold mb-4">Información de Pago</h2>
            <br>

            <p v-if="selectedMethod === 'Pago Movil'">
                <span><b>Pago Móvil Telf.:</b> 0412-1589032</span>&nbsp;
                <button @click="copiarTexto('0412-1589032')" class="ml-2 text-blue-600"><span class="bg-gray-200 p-2 rounded-md">🔗</span>
                </button> <br> <br> 
                
                <span><b>Banco de Venezuela:</b> CI.: 14657371 </span>&nbsp;
                <button @click="copiarTexto('14657371')" class="ml-2 text-blue-600"><span class="bg-gray-200 p-2 rounded-md">🔗</span>
                </button> <br> <br> 
            </p>

            <p v-else-if="selectedMethod === 'Transferencia Bancaria'">
                <span><b>Transferencia Bancaria:</b> Banco de Venezuela</span><br> <br>
                
                <span><b>Cuenta Corriente:</b> 0102 0216 79000</span>&nbsp;
                <button @click="copiarTexto('0102021679000')" class="ml-2 text-blue-600"><span class="bg-gray-200 p-2 rounded-md">🔗</span>
                </button> <br> <br> 

                <span><b>CI.:</b> 14657371</span>&nbsp;
                <button @click="copiarTexto('14657371')" class="ml-2 text-blue-600"><span class="bg-gray-200 p-2 rounded-md">🔗</span>
                </button> <br> <br> 
            </p>

            <p v-else-if="selectedMethod === 'Zelle'">
                <span><b>Zelle: </b> Proximamente</span> <br><br>

                <span><b>Email:</b> </span>&nbsp;
                <button @click="copiarTexto('')" class="ml-2 text-blue-600"><span class="bg-gray-200 p-2 rounded-md">🔗</span>
                </button> <br> <br> 
            </p>

            <button @click="closeModal" class="mt-4 px-4 py-2 bg-[#449a41] text-white rounded-md">¡Listo!</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        showModal: Boolean,
        selectedMethod: String
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                alert("Copiado al portapapeles: " + texto);
            }).catch(err => {
                console.error("Error al copiar", err);
            });
        }
    }
};
</script>
