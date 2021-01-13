<template>
  <div>
    <v-btn @click="dialog = true" color="primary" dark>Edit</v-btn>

    <v-dialog v-model="dialog" persistent max-width="500">
      <v-form ref="update" lazy-validation>
        <v-card>
          <v-card-title class="headline">Edit Product Details</v-card-title>
          <v-card-text>
            <v-text-field
              v-model="data.productName"
              name="productName"
              type="text"
              :rules="[value => !!value || 'Required.']"
              label="Product name"
            />

            <v-text-field
              v-model="data.description"
              name="description"
              type="text"
              :rules="[value => !!value || 'Required.']"
              label="Product Description"
            />

            <v-text-field
              label="Price"
              :rules="[value => !!value || 'Required.']"
              v-model="data.price"
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
            <v-spacer></v-spacer>
            <v-btn class="my-3" color="red" @click="dialog = false">Cancel</v-btn>
            <v-btn
              :disabled="updated && !$app.user"
              :loading="updated"
              class="mx-3 white--text"
              @click="updateProduct(data.id)"
              depressed
              color="orange white--text"
            >update</v-btn>
            <v-dialog v-model="updated" hide-overlay persistent width="300">
              <v-card color="primary" dark>
                <v-card-text>
                  Updating! Please wait ......
                  <v-progress-circular indeterminate color="white" class="mb-0"></v-progress-circular>
                </v-card-text>
              </v-card>
            </v-dialog>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "edit",
  props: ["item"],
  data() {
    return {
      dialog: false,
      imageUrl: [],
      data: {
        id: 0,
        productName: "",
        description: "",
        price: "",
        imageUrl: [],
        storeId: "",
        created_at: "",
        updated_at: ""
      },
      updated: false
    };
  },
  watch: {
    updated(val) {
      if (!val) return;
      setTimeout(() => (this.updated = false), 4000);
    }
  },
  mounted() {
      this.data = this.item;
  },
  methods: {
    updateProduct(id) {
      this.updated = true;
      if (this.$refs.update.validate()) {
        let data = new FormData();
        data.append("productName", this.data.productName);
        data.append("description", this.data.description);
        data.append("price", this.data.price);
        data.append(
          "imageUrl",
          this.imageUrl,
          Math.round(+new Date() / 1000) + "_" + this.imageUrl.name
        );
        data.append("id", this.data.id);
        let settings = { headers: { "content-type": "multipart/form-data" } };

        // axios update request
        this.$axios
          .post("/product/editProduct/" + id, data, settings)
          .then(response => {
            if (response.status === 200) {
              this.dialog = false;
              window.location.reload(true);
            }
          })
          .catch(error => {
            console.log(error);
            this.updated = false;
          });
      }
    }
  }
};
</script>

