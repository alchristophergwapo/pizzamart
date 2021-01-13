<template>
  <v-container>
    <br>
    <br>
    <br>
    <br>
    <h1 class="headline text-center">Available Stores</h1>
    <v-row>
      <v-container class="px-10">
        <v-col>
          <v-text-field
            color="danger"
            v-model="search"
            prepend-inner-icon="mdi-magnify"
            label="Type to Search (Name or Location)"
          ></v-text-field>
        </v-col>
      </v-container>
    </v-row>
    <v-row>
      <v-col v-for="store in filteredStore" :key="store.id" sm="4" md="4" cols="4">
        <v-card class="mx-auto" max-width="400">
          <v-img
            class="white--text align-end"
            height="200px"
            
            aspect-ratio="1"
            :src="`/images/uploads/${store.imageUrl?store.imageUrl:'banner.jpg'}`"
            :lazy-src="`\\images\\uploads\\banner.jpg`"
          ></v-img>

          <v-card-title class="pb-0">{{store.name}}</v-card-title>

          <v-card-text class="text--primary">
            <div>{{store.address}}</div>
            <div>{{store.contactNumber}}</div>
          </v-card-text>

          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color="primary"
              @click="$router.push(`/pizzamart/store/profile/${store.id}`)"
              outlined
            >Visit</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import StoreProfile from "./StoreProfile.vue";
export default {
  components: {
    StoreProfile
  },
  computed: {
    filteredStore() {
      return this.stores.filter(el => {
        return (
          el.name.toLowerCase().includes(this.search.toLowerCase()) ||
          el.address.toLowerCase().includes(this.search.toLowerCase())
        );
      });
    }
  },
  data() {
    return {
      search: "",
      stores: []
    };
  },
  mounted() {
    this.$axios
      .get("/auth/viewStores")
      .then(response => {
        this.stores = response.data.stores;
        console.log(this.stores);
        
      })
      .catch(error => {
        console.log(error);
      });
  }
};
</script>

