<template>
    <v-content>
      <v-container
        style="padding:0"
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
          style="height:100%!important"
        >
          <v-col
            cols="12"
            md="7"
            class="account-form hidden-sm-and-down"
          >
          <Toolbar/>
           </v-col>
           <v-col
            cols="12"
            md="5"
          >
           <!-- form -->
           <v-btn depressed color="white" href="/" class="hidden-md-and-up">
              <v-icon>mdi-arrow-left</v-icon>
           </v-btn>
           <Login v-if="$route.name =='Login'" ></Login>
           <AddItem v-else-if="$route.name=='Add Item'" ></AddItem>
           <Register v-else ></Register>
           </v-col>
        </v-row>
      </v-container>
    </v-content>
</template>
<script>
import Login from "./Login";
import Toolbar from "./Toolbar";
import Register from "./Register";
import AddItem from "./AddItem";
export default {
    data(){
        return {}
    },
    components:{
        Login,
        AddItem,
        Register,
        Toolbar,
        Notification
    },
    methods:{
     
    },
    created(){
      setTimeout(() => {
        if (this.$app.user && this.$route.name!=="Add Item") {
          this.$router.push({name : "Home"});
        }
      }, 200);
    }
}
</script>

