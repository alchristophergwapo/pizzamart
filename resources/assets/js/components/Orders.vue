<template>
  <v-card>
    <br>
    <br>
    <br>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :loading="loading"
        no-data-text="No Orders so far."
        loading-text="Loading... Please wait"
        :items="orders"
        sort-by="calories"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white text-uppercase">
            <v-toolbar-title>Orders |{{$route.params.type}}</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
          </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
          <v-row no-gutters>
            <v-col cols="12" md="6" sm="6">
              <OrderDetails :item="item" :key="item.id"/>
            </v-col>
            <v-col cols="12" md="6" sm="6">
              <v-btn
                v-if="$route.params.type =='pending'"
                :key="item.id+100"
                outlined
                color="success"
                @click="resolveOrder(item.id)"
                small
              >resolve</v-btn>
            </v-col>
          </v-row>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>
<script>
import OrderDetails from "./OrderDetails";
export default {
  components: {
    OrderDetails
  },
  data: () => ({
    loading: false,
    headers: [
      {
        text: "Product Name",
        align: "left",
        value: "productname"
      },
      { text: "Quantity", value: "quantity", sortable: false },
      { text: "Firstname", value: "firstname", sortable: false },
      { text: "Lastname", value: "lastname", sortable: false },
      { text: "Contact Number", value: "contactNumber", sortable: false },
      { text: "Payable", value: "amount" },
      { text: "Created At", value: "created_at" },
      { text: "Actions", value: "action", align: "center", sortable: false }
    ],
    orders: []
  }),

  methods: {
    initialize() {
      this.orders = [];
      this.loading = true;
      var _url = `/transaction/viewOrders/${
        this.$route.params.type
      }/${localStorage.getItem("id")}`;
      this.$axios
        .get(_url)
        .then(res => {
          this.loading = false;
          this.orders = res.data.orders;
        })
        .catch(err => {
          this.loading = false;
        });
    },

    resolveOrder(id) {
      this.$axios
        .post("/transaction/resolveOrder", { id: id })
        .then(res => {
          this.initialize();
          this.$bus.$emit("notify", "Success");
        })
        .catch(err => {
          this.$bus.$emit("notify", "Something went wrong");
          this.initialize();
        });
    },
    showDetails(item) {
      this.$router.push({
        path: "/pizzamart/order_details/item_id=" + item.id
      });
    }
  },
  created() {
    this.$bus.$on("new_order", this.initialize);
  },
  mounted() {
    localStorage.setItem("id", this.$app.user.userInfoId);
    this.initialize();
    if (
      localStorage.getItem("userTypeId" != 1) ||
      !["pending", "resolve"].includes(this.$route.params.type)
    ) {
      this.$router.push({ name: "404" });
    }
    //
  }
};
</script>