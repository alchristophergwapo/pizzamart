<template>
    <div class="text-center">
    <v-snackbar
        class="text-capitalize"
        v-model="snackbar"
        :timeout="timeout"
    >
      {{ text }}
      <v-btn
        color="primary"
        text
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </v-snackbar>
  </div>
</template>
<script>
  export default {
    data: () => ({
        text:"",
        snackbar: false,
        timeout: 3000,
    }),
  }
</script>