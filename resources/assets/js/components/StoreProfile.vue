<template>
  <v-row style="margin-top: 50px">
    <br>
    <v-row v-if="loading" class="pt-10">
      <v-col v-for="i in 3" :key="i" cols="12" sm="4" md="4">
        <Loader/>
      </v-col>
    </v-row>
    <v-col v-else-if="!items.length">
      <div class="mt-10 text-center">
        <br>
        <br>
        <br>
        <p class="mt-10 display-3 font-weight-black">
          No
          <v-icon color="black" x-large>mdi-pizza</v-icon>Pizza Available
        </p>
      </div>
    </v-col>
    <v-col cols="12" v-else>
      <v-col cols="12" class="pt-10 display-1">
        <div>
          <h2 class="text-center">{{storeName}}</h2>
        </div>
        <v-container fluid>
          <v-row id="products">
            <v-col v-for="$item in items" :key="$item['productId']" cols="12" sm="4" md="4">
              <Item :item="$item" @removeFromCart="removeOrder" @addToCart="addOrder"/>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-col>
  </v-row>
</template>

<script>
import Item from "./Item";
import Loader from "./Loader";
export default {
  name: "storeInfo",
  props: ["item", "store"],
  components: {
    Item,
    Loader
  },
  data() {
    return {
      items: [],
      cart: [],
      loading: false,
      storeName: ""
    };
  },
  methods: {
    addOrder(item) {
      if (this.checkItem(item)) {
        this.cart.forEach(el => {
          if (el.productId == item.id) {
            el.quantity = Number(el.quantity + 1);
          }
        });
      } else {
        var newItem = {
          productId: item.id,
          quantity: 1,
          item: {
            productName: item.productName,
            description: item.description,
            price: item.price,
            imageUrl: item.imageUrl,
            storeId: item.storeId
          },
          total: item.price * 1
        };
        this.cart.push(newItem);
      }
      localStorage.setItem("cart", JSON.stringify(this.cart));
      this.$bus.$emit("newItem", this.cart);
    },
    checkItem(item) {
      var inCart = false;
      this.cart.forEach(el => {
        if (el.productId == item.id) {
          inCart = true;
        }
      });
      return inCart;
    },
    removeOrder(item) {
      this.cart = JSON.parse(localStorage.getItem("cart"));
      this.cart = this.cart.filter(ele => {
        return ele.productId != item.id;
      });
      localStorage.setItem("cart", JSON.stringify(this.cart));
      this.$bus.$emit("removed", this.cart);
    }
  },
  mounted() {
    this.loading = true;
    this.$axios
      .get("/product/getStoreProducts/" + this.$route.params.id)
      .then(response => {
        this.loading = false;
        this.items = response.data.products;
        this.storeName = this.items[0].store.name;
      })
      .catch(err => {
        this.loading = false;
        this.$bus.$emit("notify", "Something went wrong!");
      });
  }
};
</script>
