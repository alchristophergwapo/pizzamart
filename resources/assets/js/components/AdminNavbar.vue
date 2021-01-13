<template>
  <div>
    <v-app-bar app clipped-right light>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
      <v-toolbar-title>
        <!-- <v-btn tile depressed class="mx-0 underlined" text x-large href="/pizzamart/home"> -->
          <div class="span underlined">
            <span>PizzaMart</span>
          </div>
        <!-- </v-btn> -->
      </v-toolbar-title>
      <v-spacer/>
      <v-btn icon class="mx-3" small @click="show = false">
        <v-badge
          small
          :content="push?notification:''"
          :value="notification"
          :color="push?'danger':'transparent'"
          overlap
        >
          <v-icon medium>mdi-bell</v-icon>
        </v-badge>
      </v-btn>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list>
        <v-list-item class="px-2">
          <v-list-item-avatar size="150">
            <v-img
              src="https://img.favpng.com/1/23/17/account-icon-avatar-icon-human-icon-png-favpng-iVx59N6usuf5BvihHqjR7zUWv.jpg"
            ></v-img>
          </v-list-item-avatar>
        </v-list-item>

        <v-list-item link>
          <v-list-item-content>
            <v-list-item-title class="title">{{details?details.store.name:''}}</v-list-item-title>
            <v-list-item-subtitle>{{this.$app.user.email}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-divider></v-divider>
      <v-list nav dense>
        <v-list-item @click="$route.name=='Dashboard'?'':$router.push('/pizzamart/dashboard')">
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$route.name=='Products'?'':$router.push('/pizzamart/products')">
          <v-list-item-icon>
            <v-icon>mdi-silverware-fork-knife</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>My Products</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/pizzamart/transaction/pending">
          <v-list-item-icon>
            <v-icon>mdi-cart-arrow-down</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Pending Orders</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item href="/pizzamart/transaction/resolve">
          <v-list-item-icon>
            <v-icon>mdi-cart-arrow-right</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Resolved Orders</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link href="/pizzamart/addItem">
          <v-list-item-icon>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Add Product</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list>
        <v-list-item link @click="logout">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import { isNull } from "util";
export default {
  name: "admin",
  data() {
    return {
      orders: 0,
      drawer: false,
      group: null,
      notification: '*',
      show: false,
      temp: null
    };
  },
  computed: {
    details(){
      return this.temp;
    },
    push(){
      return this.show;
    }
  },
  watch: {
    group() {
      this.drawer = false;
    }
  },
  methods: {
    addPush(_show){
      this.show = _show.storeId == localStorage.getItem("id"); 
      if (this.show) {
        this.$bus.$emit("new_order");
      } 
    },
    logout() {
      this.$axios
        .post("/auth/logout")
        .then(res => {
         window.location.href = "/pizzamart";    
          localStorage.removeItem("userTypeId");
          localStorage.removeItem("id");
        })
        .catch(err => {
          console.log(err);
        });
    },
  },
  created() {
    this.$axios
      .get("/auth/init")
      .then(response => {
        this.temp = response.data.user;   
      });
      
     this.$pusher_channel.bind("new order", this.addPush);
  }
};
</script>
