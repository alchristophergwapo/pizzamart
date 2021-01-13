<template>
  <v-form ref="form" lazy-validation>
    <v-card flat color="transparent" class="px-10">
      <v-card-text class="headline">Add New Product</v-card-text>
      <v-card-text>
        <v-text-field
          v-model="productName"
          name="productName"
          type="text"
          :rules="[value => !!value || 'Required.']"
          label="Product name"
        />

        <v-text-field
          v-model="description"
          name="description"
          type="text"
          :rules="[value => !!value || 'Required.']"
          label="Product Description"
        />

        <v-text-field
          label="Price"
          :rules="[value => !!value || 'Required.']"
          v-model="price"
          name="price"
          type="number"
        />

        <v-file-input
          label="Select Image"
          v-model="imageUrl"
          name="imageUrl"
          :rules="[value => !!value || 'Required.']"
          accept="image/*"
          prepend-icon="mdi-camera"
        ></v-file-input>
      </v-card-text>
      <v-card-actions>
        <v-spacer/>
        <v-btn
          :disabled="added"
          :loading="added"
          class="white--text"
          @click="addProduct"
          large 
          depressed 
          color="primary"
        >add product</v-btn>
        <v-dialog v-model="added" hide-overlay persistent width="300">
          <v-card color="primary" dark>
            <v-card-text>
              Adding! Please wait ......
              <v-progress-circular indeterminate color="white" class="mb-0"></v-progress-circular>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-card-actions>
      <p class="text-right body-2">
        <v-btn
          small
          color="primary"
          text
          class="mx-0 px-0"
          @click="$router.push('/pizzamart/home')"
        >Cancel</v-btn>
      </p>
    </v-card>
  </v-form>
</template>

<script>
import axios from "axios";
export default {
  props: ["app"],

  data() {
    return {
      productName: "",
      description: "",
      price: "",
      imageUrl: [],
      added: false
    };
  },
  watch: {
    added(val) {
      if (!val) return;

      setTimeout(() => (this.added = false), 4000);
    }
  },
  methods: {
    addProduct() {
      this.added = true; 
      if (this.$refs.form.validate()) { // validating the form
        let data = new FormData();
        data.append("productName", this.productName);
        data.append("description", this.description);
        data.append("price", this.price);
        data.append("storeId", this.$app.user.userInfoId);
        data.append(
          "imageUrl",
          this.imageUrl,
          Math.round(+new Date() / 1000) + "_" + this.imageUrl.name
        );
        let settings = { headers: { "content-type": "multipart/form-data" } };

        // axios request for adding a product
        this.$axios
          .post("/product/addProduct", data, settings)
          .then(response => {
            if (response.status === 200) {
              this.$router.push("/pizzamart/home");
              this.added = true;
            }
          })
          .catch(e => {
            this.$bus.$emit("notify", "error");
            this.added = false;
            console.log(e);
          });
      }
    }
  }
};
</script>