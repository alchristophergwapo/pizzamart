<template>
  <v-footer dark padless>
    <v-card flat tile width="100%" class="primary lighten-1 white--text text-center">
      <v-card-text>
        <v-btn href="#top" v-for="icon in icons" :key="icon" class="mx-4 white--text" icon>
          <v-icon color="black" size="24px">{{ icon }}</v-icon>
        </v-btn>
      </v-card-text>

      <v-card-text class="white--text">
        <strong>We connect your all time pizza house products from pan to doorstep through the internet.</strong>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-text class="white--text">
        {{ new Date().getFullYear() }} —
        <strong>
          <a class="white--text px-2" href="/">PIZZAMART CORPORATION</a>
        </strong>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-text class="white--text">
        <strong>
          <a class="white--text px-2" href="/pizzamart/about">About Us</a>|
          <a class="white--text px-2" href="#top">Back to Top</a>
        </strong>
      </v-card-text>
    </v-card>
  </v-footer>
</template>
<script>
export default {
  data: () => ({
    icons: [
      "mdi-facebook",
      "mdi-twitter",
      "mdi-google-plus",
      "mdi-linkedin",
      "mdi-instagram"
    ]
  })
};
</script>