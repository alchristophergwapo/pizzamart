<template>
  <v-dialog v-model="dialog" max-width="400">
    <v-card class="px-10 py-10">
      <span class="headline">Edit Profile</span>
      <v-card-text>
        <v-form ref="form" lazy-validation>
          <v-row v-if="$app.user.userTypeId  == 2">
            <v-col cols="12" md="6">
              <v-text-field
                v-model="firstname"
                name="firstname"
                type="text"
                :rules="[value => !!value || 'Required.']"
                label="Firstname"
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="lastname"
                name="lastname"
                :rules="[value => !!value || 'Required.']"
                type="text"
                label="Lastname"
              />
            </v-col>
          </v-row>
          <v-text-field
            v-else-if="$route.params.user  === 'store'"
            v-model="name"
            name="name"
            :rules="[value => !!value || 'Required.']"
            type="text"
            label="Bussiness name/Store name"
          />
          <v-text-field
            v-if="$route.params.user  === 'store'"
            v-model="description"
            name="description"
            type="text"
            label="Store Description(Optional)"
          />
          <v-text-field
            v-model="address"
            name="address"
            type="text"
            :rules="[value => !!value || 'Required.']"
            label="Address"
          />
          <v-text-field
            label="Contact number"
            v-model="contactNumber"
            name="contactNumber"
            :rules="[value => !!value || 'Required.']"
            type="number"
          />
          <v-text-field
            v-model="email"
            name="email"
            type="Email"
            :rules="[value => !!value || 'Required.']"
            label="email"
          />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn width="100%" large depressed color="primary" @click="updateProfile(userId)">update</v-btn>
      </v-card-actions>
      <v-card-actions>
        <v-btn width="100%" large depressed color="error" @click="cancel">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
import { isNull } from "util";
export default {
  name: "userProfile",
  props: ["app"],
  components: {},
  data() {
    return {
      userId: this.$app.user.id,
      loading: false,
      userTypeId: this.$app.user.userTypeId,
      firstname: this.$app.user.user_info.firstname,
      lastname: this.$app.user.user_info.lastname,
      business: "",
      address: this.$app.user.user_info.address,
      contactNumber: this.$app.user.user_info.contactNumber,
      email: this.$app.user.email,
      password: "",
      name: "",
      description: "",
      userInfoId: this.$app.user.userInfoId,
      dialog: false
    };
  },
  methods: {
    updateProfile(userId) {
      if (this.$refs.form.validate()) {
        this.$axios
          .post("/auth/updateProfile", {
            firstname: this.firstname,
            lastname: this.lastname,
            address: this.address,
            contactNumber: this.contactNumber,
            email: this.email,
            userInfoId: this.userInfoId,
            id: this.userId
          })
          .then(res => {            
            this.loading = false;
            this.dialog = false;
            this.$app.init();

            if (res.data[0] && res.data[0].includes('Error')) {
              this.$bus.$emit("notify", "Error");
            } else {
              this.$bus.$emit("notify", "success");
            }
          })
          .catch(err => {            
            this.loading = false;
            this.dialog = false;
            this.$bus.$emit("notify", "Something went wrong");
          });
      }
    },
    cancel() {
      this.$router.push("home");
    }
  }
};
</script>

          


