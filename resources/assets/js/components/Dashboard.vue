<template>
  <v-container>
    <br>
    <br>
    <br>
    <h1 class="grey--text">Dashboard</h1>
    <v-row>
      <v-col class="cards" v-for="(card ,i) in cards" :key="i" cols="12" md="4" sm="4">
        <v-card
          :class="`mx-auto blue-grey lighten-5 `"
          hover
          :href="card.href?card.href:'#'"
          :loading="loading"
          max-width="344"
          ripple
          outlined
          elevation="2"
        >
          <v-card-text class="py-2 title font-weight-black text-uppercase dark-grey--text">
            <v-icon>{{card.icon}}</v-icon>
            {{card.title}}
          </v-card-text>
          <v-divider/>
          <v-card-text class="py-2">
            <p class="dark-grey--text display-2">
              {{card.total}}
              <span class="subtitle-2 text-uppercase">orders</span>
            </p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-divider class="mt-5"></v-divider>
    <v-card-text v-if="show">
      <center>
        <graph-bar
          :width="700"
          :height="400"
          :axis-min="0"
          :axis-max="max"
          :labels="labels"
          :values="values"
        >
          <p
            class="title dark-grey--text text-uppercase my-0 font-weight-black"
          >{{$app.user.store.name}}</p>
          <tooltip :names="names" :position="'center'"></tooltip>
          <legends :names="names" :filter="true"></legends>
        </graph-bar>
      </center>
    </v-card-text>
  </v-container>
</template>

<script>
import axios from 'axios';
export default {
  props: ["app"],
  data() {
    return {
      loading: true,
      labels: ["Orders"],
      names: [],
      values: [],
      show: false,
      max: 0,
      cards: [
        { icon: "mdi-pizza", title: "Total Orders", total: 0 },
        {
          icon: "mdi-clock",
          title: "Pending Orders",
          href: "/pizzamart/transaction/pending",
          total: 0
        },
        {
          icon: "mdi-check-circle",
          title: "Resolved Orders",
          href: "/pizzamart/transaction/resolve",
          total: 0
        }
      ]
    };
  },
  methods: {
    initialize() {
      this.$axios
        .get("/transaction/summary")
        .then(res => {
          this.loading = false;
          var counter = res.data.counter;
          this.cards[0].total = counter.total;
          this.cards[1].total = counter.pending;
          this.cards[2].total = counter.resolve;
          res.data.products.forEach(product => {
            if (product.sold > 0) {
              this.names.push(product.productName);
              this.values.push([product.sold]);
              this.show = product.sold > 0;
              this.max = product.sold > this.max ? product.sold : this.max;
            }
          });
          console.log(this.values);
          
        })
        .catch(err => {
          this.loading = false;
          this.$bus.$emit("notify", "Something went wrong.");
        });
    }
  },
  created() {
    this.initialize();
    this.$bus.$on('new_order',this.initialize);
  }
};
</script>

<style>
</style>
