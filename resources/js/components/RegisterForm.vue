<template>
  <form @submit.prevent="submit">
    <input v-model="name" type="text" placeholder="Nombre">
    <input v-model="email" type="email" placeholder="Correo">
    <input v-model="password" type="password" placeholder="Contraseña">
    <input v-model="password_confirmation" type="password" placeholder="Confirmar contraseña">
    <button type="submit">Registrarse</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
  },
  methods: {
    async submit() {
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch('/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        }),
      });

      if (response.redirected) {
        window.location.href = response.url;
      } else {
        const data = await response.json();
        alert('Error: ' + (data.message || 'verifica los campos'));
      }
    }
  }
}
</script>
