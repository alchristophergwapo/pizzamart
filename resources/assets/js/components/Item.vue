<template>
  <v-card class="mx-auto my-12" max-width="320">
    <v-img height="250" :src="`/images/uploads/${item.imageUrl}`"
        lazy-src="/images/banner.jpg"
      ></v-img>

    <v-card-title>{{item.productName}}</v-card-title>
    <v-card-subtitle>{{item.store?item.store.name:"Store Name"}}</v-card-subtitle>
    <v-row align="center" class="mx-0">
      <div class="grey--text ml-4"></div>
    </v-row>
    <v-card-text>
      <div>{{item.description }}</div>
    </v-card-text>
    <v-chip v-if="item.sold > 50" class="ma-2" color="orange" label>
      <v-icon small left>mdi-star</v-icon>Best Seller
    </v-chip>
    <v-divider class="mx-4"></v-divider>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn @click="handleClick" :disabled="!logged_in" class="my-3" color="danger white--text">{{logged_in?onCart?'Remove from cart':'Add to cart': 'Log in to order'}}</v-btn>
    </v-card-actions>
  </v-card>
</template>
<script>
import { isNullOrUndefined } from "util";

export default {
  name: "item",
  props: ["app","item", 'store'],
  data() {
    return {
      logged_in : false,
      onCart :false
    };
  },
  methods: {
    placeOrder() {
     this.$emit("addToCart" , this.item);
     this.onCart = true;
    },
    removeOrder(){
     this.onCart = false;
     this.$emit("removeFromCart" , this.item);
    },
    handleClick(){
      if (this.onCart) {
        this.removeOrder();
      } else {
        this.placeOrder();
      }
    }
  },

  created(){
    this.$bus.$on('update', user => {
        this.logged_in = isNullOrUndefined(this.app.user);    
    });
    
    this.$bus.$on("removedId" , (id)=>{
      if (this.item.id == id) {
        this.onCart = false;
      }
    })

    this.$bus.$on("clear_cart" , ()=>{
      this.onCart = false;
    })
    let cart = localStorage.getItem("cart");
    if (!isNullOrUndefined(cart)) {
      JSON.parse(cart).forEach(el => {
        if (el.productId == this.item.id) {
          this.onCart = true;
        }
      });
    }
  },
  mounted() {
    this.logged_in = !isNullOrUndefined(this.$user);
    
     
  },
};
</script>