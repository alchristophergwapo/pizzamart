<template>
  <div>
    <v-app id="top">
      <AdminNavbar v-if="isAdmin && !['Register' , 'AddItem','Login' , '404'].includes($route.name)"  />
      <myNavbar v-if="!isAdmin && !['Register' ,'Login' , '404'].includes($route.name)"  />
      <router-view ></router-view>
      <Notification ref="notif"/>
      <Footer v-if="['Home','About','Stores'].includes($route.name)"/>
    </v-app>
  </div>
</template>


<script>
import Notification from "./components/Notification";
import myNavbar from "./components/MyNavbar";
import AdminNavbar from "./components/AdminNavbar";
import Footer from "./components/Footer";

export default {
  components: {
    myNavbar,
    Notification,
    Footer,
    AdminNavbar
  },
  computed:{
    isAdmin (){
      return localStorage.getItem("userTypeId") == 1
    }
  },

  created() {
    this.$bus.$on("notify", message => {
      this.$refs.notif.snackbar = true;
      this.$refs.notif.text = message;
    });
  },
  mounted() {
    this.$app.init();
    
  }
};
</script>
