<template>
<ownerDashboard v-if="isAdmin"></ownerDashboard>
  <div v-else>
    <Banner></Banner>
    
    <v-row  v-if="loading">
      <v-col cols="12">
        <v-container fluid>
          <v-row>
            <v-col v-for="i in 3" :key="i" cols="12" sm="4" md="4">
             <Loader/>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-row>
    <v-row v-else>
      <p class=title v-if="!items.length"> No Products Available</p>
      <v-col cols="12" v-else>
      <v-col cols="12" class="pt-10 display-1">
        
        <div>
          <h1 class="text-center">Hot Deals</h1>
        </div>
        <v-container fluid>
          <v-row id="products">
            <v-col v-for="$item in items" :key="$item['productId']" cols="12" sm="4" md="4">
              <Item :item="$item"  @removeFromCart="removeOrder" @addToCart="addOrder" />
            </v-col>
          </v-row>
        </v-container>
      </v-col>
      </v-col>
    </v-row>
  </div>

  
  
</template>

<script>
import Banner from "./Banner";
import Item from "./Item";
import ownerDashboard from './StoreOwnerDashboard.vue';
import { isNull } from "util";
import Loader from "./Loader";

export default {
    name:'home',
    props:['app', "item", "store"],
    components:{
        Banner,Item,Loader,ownerDashboard
    },  
    data() {
      return {
          items : [],
          cart :[],
          loading :false,
      }
  },
  computed:{
    isAdmin(){
      return localStorage.getItem('userTypeId') == 1;
    }
  },
  methods: {
      addOrder(item){
          if (this.checkItem(item )) {
              this.cart.forEach(el => {
                  if (el.productId == item.id) {
                      el.quantity = Number(el.quantity+1); 
                  }
              });
          } else {
            var newItem = {
              productId : item.id,
              quantity:1,
              item:{
                productName: item.productName,
                description: item.description,
                price: item.price,
                imageUrl: item.imageUrl,
                storeId: item.storeId,
              },
              total: item.price * 1
            }
              this.cart.push(newItem);
          }
          localStorage.setItem("cart" ,  JSON.stringify(this.cart));
          this.$bus.$emit('newItem', this.cart)
          
      },
      checkItem(item) {
        var inCart = false;
         this.cart.forEach(el => {
            if (el.productId == item.id) {
                inCart =  true;
            }
        });
        return inCart;
    },
    removeOrder(item){
        this.cart = JSON.parse(localStorage.getItem("cart"));
        this.cart = this.cart.filter((ele)=>{
            return ele.productId != item.id;
        });
        localStorage.setItem("cart" ,  JSON.stringify(this.cart));
        this.$bus.$emit('removed', this.cart);
    }

  },
    mounted() {
        this.loading = true;
        this.$axios.get('/product/getAll')
        .then(response => {
            this.loading = false;
            this.items = response.data.products;
        })
        .catch(error => {
            this.loading = false;
        })

        if(!isNull(localStorage.getItem("cart"))){
            this.cart = JSON.parse(localStorage.getItem("cart"))
        }
        this.$bus.$emit('newItem', this.cart);
    },
    created(){
       this.$bus.$on('removed', orders => {
        localStorage.setItem("cart" ,  JSON.stringify(orders));
        this.cart = orders;   
      })
      this.$bus.$on('clear_cart', orders => {
        localStorage.removeItem("cart");
        this.cart=[];   
      })
    }
};
</script>
