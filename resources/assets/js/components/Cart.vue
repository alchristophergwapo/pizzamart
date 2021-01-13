<template>
    <v-dialog v-model="dialog" scrollable max-width="600">
    <v-card>
    <v-card-title>
            <span class="text-start cart-header">Cart</span>
            <span class="text-end text-capitalize cart-header">total:  &#8369;{{payable}}</span>
        </v-card-title>
    <v-divider></v-divider>
   
    <v-card-text style="height: 500px;">
        <br>
       <p class="text-center text-bold primary--text text-underline">Pizza Points Reward  :  <v-icon color="primary" small>mdi-pizza</v-icon>{{(payable * 0.002).toFixed(2)}}</p>
       <p class="text-center text-bold primary--text text-underline">Current Pizza Points  :  <v-icon color="primary" small>mdi-pizza</v-icon>{{points +'= P'+ (points*0.05).toFixed(2) }}</p>
       <v-divider></v-divider>
        <div class="title text-center" v-if="!items.length"></div>
        <v-list two-line dense v-for="(prod, i) in products" :key="i" v-else>
            <v-list-item  class="px-5 py-5">
                    <v-img   height="120"  contain :src="`/images/uploads/${prod.item.imageUrl}`"></v-img>
                <v-list-item-content class="mx-5">
                    <v-list-item-title>{{prod.item.productName}}</v-list-item-title>
                    <v-list-item-subtitle>store</v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action>
                    <v-btn-toggle >
                        <v-btn icon @click="removeOrder(prod.productId)">
                            <v-icon color="primary">mdi-delete</v-icon>
                        </v-btn>
                        <v-btn @click="subtractQuantity(i)" text>
                            <v-icon color="primary">mdi-minus</v-icon>
                        </v-btn>
                       <v-btn outlined color="black">{{prod.quantity}}</v-btn>
                        <v-btn text @click="addQuantity(i)">
                            <v-icon color="primary">mdi-plus</v-icon>
                        </v-btn>
                    </v-btn-toggle>
                    <br>
                    <br>
                    <p class="caption">Sub Total : &#8369;{{prod.item.price * prod.quantity}}</p>
              </v-list-item-action>
             
            </v-list-item>
            <v-divider></v-divider>
           
    </v-list>
        
    </v-card-text>
    <v-divider></v-divider>
    <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
         <v-btn @click="placeOrder" :disabled="!this.items.length" depressed  class="mx-5 my-3" color="danger white--text">Order</v-btn>
    </v-card-actions>
    </v-card>
</v-dialog>
</template>
<script>
import { isNull } from "util";
export default {
    name:"Cart",
    data(){
        return{
            dialog:false,
            items: [],
            total:0,
            points: this.$app.user?this.$app.user.user_info.points:0
        }
    },
    computed:{
        products(){
            return this.items;
        },
        payable(){
            return this.total;
        }
    },
    methods: {
        addQuantity(index){
            var $updated = this.items[index];
            ++$updated.quantity;
            $updated.total = $updated.item.price * $updated.quantity;
            this.computeTotal();
            localStorage.setItem("cart" , JSON.stringify(this.items));

        },
        subtractQuantity(index){
             var $updated = this.items[index];
             --$updated.quantity;
             if ($updated.quantity<1) {
                 $updated.quantity = 1;
             }
            $updated.total = $updated.item.price * $updated.quantity;
            this.computeTotal();
            localStorage.setItem("cart" , JSON.stringify(this.items));
        },
        computeTotal(){
            this.total = 0;
            this.items.forEach(item => {
                this.total+=item.quantity * item.item.price;
            });
        },
        removeOrder(id){
            this.items = this.items.filter((ele)=>{
                return ele.productId != id;
            });
            this.$bus.$emit('removedId', id);
            this.$bus.$emit('removed', this.items);
            localStorage.setItem("cart" ,  JSON.stringify(this.items));
            this.computeTotal();
        },
        placeOrder(){
            this.$axios.post("/transaction/order",{
                    orders:JSON.parse(localStorage.getItem("cart")),
                    userId: this.$user.userInfoId
            }).then(res=>{
                this.items =[];
                localStorage.removeItem("cart");
                this.$bus.$emit('clear_cart');
                this.$bus.$emit("notify" , `you have gained ${(res.data.points).toFixed(2)} pizza points!`);
                this.dialog = false;

            }).catch(err=>{
                console.log(err)
                this.$bus.$emit("notify" , "error");
            })
        }    
    },
    mounted() {
        if (!isNull(localStorage.getItem("cart"))) {
            this.items = JSON.parse(localStorage.getItem("cart"));
            this.computeTotal();
        }        
    },
     created () {
      this.$bus.$on('newItem', orders => {          
        this.items = orders;
        this.computeTotal();
      });
      this.$bus.$on('removed', orders => {
        this.items = orders;
        this.computeTotal();
      })    
  },
}
</script>
