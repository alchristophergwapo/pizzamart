<template>
  <div class="text-center">
    <v-dialog v-model="dialog" width="500">
      <template v-slot:activator="{ on }">
        <v-btn outlined color="danger" v-on="on" small>details</v-btn>
      </template>

      <v-card>
        <v-card-title class="title primary" primary-title>Order Details</v-card-title>

        <v-card-text class="py-2 my-2">
          <v-card  outlined class="py-2 px-2">
            <v-card-text class="black--text">
              <p class="text-uppercase title">Product Name: <b>{{item.productname}}</b></p>
              <p class="text-uppercase subtitle-1">Quantity : <b>{{item.quantity}}</b></p>
              <p
                class="text-uppercase subtitle-1"
              >Ordered By : <b>{{`${item.firstname} ${item.lastname}` }}</b></p>
              <p class="text-uppercase subtitle-1">Contact Number : <b>{{item.contactNumber}}</b></p>
              <p class="text-uppercase subtitle-1">Total :	<b>&#8369; {{item.amount}}</b></p>
              <p class="font-italic font-weight-light-">Ordered {{new Date(item.created_at).toLocaleString()}}</p>
            </v-card-text>
          </v-card>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" outlined @click="dialog = false">close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  props: ["item"],
  data() {
    return {
      dialog: false
    };
  }
};
</script>