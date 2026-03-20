<template>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <a class="whatsapp" @click="redirectToWhatsApp">
    <i class="fa fa-whatsapp whatsapp-icon"></i>
  </a>
</template>

<script>
export default {
  methods: {
    redirectToWhatsApp() {
      const phoneNumber = '584121589032'; // Reemplaza con el número de WhatsApp (sin el '+' y el código de país)
      const message = '¡Hola! Tengo una consulta.'; // Mensaje predeterminado
      const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
      
      window.open(url, '_blank'); // Abre WhatsApp en una nueva pestaña
    }
  }
}
</script>

<style scoped>
.whatsapp {
  position: fixed;
  width: 60px;
  height: 60px;
  bottom: 40px;
  right: 40px;
  background-color: #25d366;
  color: #FFF;
  border-radius: 50px;
  text-align: center;
  font-size: 30px;
  z-index: 100;
  cursor: pointer;
}

.whatsapp-icon {
  margin-top: 13px;
}
</style>

  