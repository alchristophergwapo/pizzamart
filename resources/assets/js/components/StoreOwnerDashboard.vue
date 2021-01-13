<template>
  <div>
    <br>
    <br>
    <br>
    <v-row id="products">
      <v-col cols="12">
        <v-container fluid>
          <div>
            <h1 class="text-center">My Products</h1>
          </div>
          <v-row v-if="loading">
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
            <div v-if="!items.length">
              <h1 class="text-center">No Products Posted</h1>
            </div>
            <v-col v-else v-for="$item in items" :key="$item.id" cols="12" sm="4" md="4">
              <v-card class="mx-auto my-12" max-width="320">
                <v-img
                  height="250"
                  :src="`\\images\\uploads\\${$item.imageUrl}`"
                  :lazy-src="`\\images\\banner.jpg`"
                ></v-img>

                <v-card-title>{{$item.productName}}</v-card-title>
                <v-row align="center" class="mx-0">
                  <div class="grey--text ml-4"></div>
                </v-row>
                <v-card-text>
                  <div>{{$item.description }}</div>
                </v-card-text>
                <v-divider class="mx-4"></v-divider>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    :disabled="deleted && !$app.user"
                    :loading="deleted && item.id"
                    class="mx-3 white--text"
                    @click="deleteProduct($item.id)"
                    depressed
                    color="danger white--text"
                  >delete</v-btn>
                  <v-dialog v-model="deleted" hide-overlay persistent width="300">
                    <v-card color="primary" dark>
                      <v-card-text>
                        Deleting! Please wait ......
                        <v-progress-circular indeterminate color="white" class="mb-0"></v-progress-circular>
                      </v-card-text>
                    </v-card>
                  </v-dialog>
                  <edit :item="$item"></edit>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Loader from "./Loader";
import edit from "./Edit";
export default {
  name: "ownerDashboard",
  props: ["item"],
  components: { edit, Loader },
  data() {
    return {
      details: null,
      items: [],
      url: "",
      loading: false,
      dialog: false,
      deleted: false
    };
  },
  watch: {
    deleted(val) {
      if (!val) return;
      setTimeout(() => (this.deleted = false), 4000);
    }
  },
  created() {
    this.loading = true;
    this.$axios
      .get("/product/getMyProducts/" + this.$app.user.userInfoId)
      .then(response => {
        this.loading = false;
        this.items = response.data.products;
      })
      .catch(error => {
        this.$bus.$emit("notify", "Something went wrong.");
        this.loading = false;
      });

    this.$axios
      .get(`/images/uploads/${this.items.imageUrl}`)
      .then(res => {
        this.url = this.items.imageUrl;
      })
      .catch(err => {
        this.url = "/images/banner.jpg";
      });
  },
  methods: {
    deleteProduct(id) {
      this.deleted = true;
      this.$axios
        .post("/product/deleteProduct/" + id)
        .then(response => {
          window.location.reload(true);
          this.deleted = true;
        })
        .catch(error => {
          console.log(error);
          this.deleted = false;
        });
    }
  }
};
</script>
