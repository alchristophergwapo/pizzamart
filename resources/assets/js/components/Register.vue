<template>
  <v-card flat color="transparent" class="px-8">
    <span class="headline">Create {{user_typeId == 2 ?'user':'store'}} account</span>
    <v-card-text>
      <div v-show="show_errors">
        <v-card>
          <v-list expand color="red lighten-4">
            <v-list-group color="error darken-3" prepend-icon="mdi-alert" value="true">
              <template v-slot:activator>
                <v-list-item-title>Invalid Inputs!</v-list-item-title>
              </template>
              <v-subheader class="font-weight-black">Message :</v-subheader>
                <v-list-item dense v-for="(err, i) in errors" :key="i" link>
                  <v-list-item-title class="text-wrap text-left">*{{err}}</v-list-item-title>
                </v-list-item>
            </v-list-group>
          </v-list>
        </v-card>
      </div>
      <v-form ref="form" lazy-validation>
        <v-row v-if="$route.params.user  === 'user'">
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
          :rules="[value => !!value || 'Required.', v => !v || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid']"
          label="email"
        />
        <v-text-field
          label="password"
          v-model="password"
          name="password"
          :rules="[value => !!value || 'Required.']"
          type="password"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn
        width="100%"
        large
        depressed
        :loading="loading"
        :disabled="loading"
        color="primary"
        @click="onSumbmit"
      >register{{$route.params.user == 'store' ? ' STORE' :''}}</v-btn>
    </v-card-actions>
    <p class="text-right body-2">
      <span class="text-right">
        Already have and account? Log in
        <v-btn
          small
          color="primary"
          text
          class="mx-0 px-0"
          @click="$router.push('/pizzamart/login')"
        >here</v-btn>
      </span>
    </p>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn class="ma-2" tile large color="primary" text @click="toggleform">
        <v-icon>{{user_typeId == 2 ?'mdi-store':'mdi-account-circle-outline'}}</v-icon>
        {{`register as ${user_typeId == 2 ?'store':'user'}`}}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { isNull } from "util";
export default {
  name: "register",
  props: ["app"],
  data() {
    return {
      errors: [],
      show_errors: false,
      loading: false,
      user_typeId: 0,
      firstname: "",
      lastname: "",
      business: "",
      address: "",
      contactNumber: "",
      email: "",
      password: "",
      name: "",
      description: ""
    };
  },
  methods: {
    onSumbmit() {
      if (this.$refs.form.validate()) {
        if (this.user_typeId === 1) {
          this.loading = true;
          this.$axios
            .post("/auth/registerStore", {
              name: this.name,
              description: this.description,
              address: this.address,
              contactNumber: this.contactNumber.toString(),
              email: this.email,
              password: this.password,
              user_typeId: this.user_typeId
            })
            .then(this.successHandler)
            .catch(this.errHandler);
        } else {
          this.loading = true;
          this.$axios
            .post("/auth/register", {
              firstname: this.firstname,
              lastname: this.lastname,
              address: this.address,
              contactNumber: this.contactNumber.toString(),
              email: this.email,
              password: this.password,
              user_typeId: this.user_typeId
            })
            .then(this.successHandler)
            .catch(this.errHandler);
        }
      }
    },
    successHandler(res) {
      this.loading = false;
      this.$router.push({ name: "Login" });
    },
    errHandler(err) {
      this.loading = false;
      this.errors = [];
      this.password = "";
      if (err.response.status == 422) {
        let errors = err.response.data.errors;
        this.show_errors = true;
        for (var i in errors) {
          if (errors[i].length >= 1) {
            for (var j in errors[i]) {
              for (var n in errors[i][j].split("|")) {
                this.errors.push(errors[i][j].split("|")[n]);
              }
            }
          } else {
            this.errors.push(errors[i]);
          }
        }
      } else {
        this.$emit("notify", "Something went wrong.");
      }
    },
    toggleform() {
      this.$router.push(
        `/pizzamart/register/${this.user_typeId == 2 ? "store" : "user"}`
      );
      this.user_typeId = this.user_typeId == 2 ? 1 : 2;
    }
  },
  mounted() {
    if (!["store", "user"].includes(this.$route.params.user)) {
      window.location.href = "/notfound";
    } else {
      this.user_typeId = this.$route.params.user == "user" ? 2 : 1;
    }
  }
};
</script>
