<template>
  <v-card flat color="transparent" class="px-10">
    <v-card-text class="headline">Log in to continue</v-card-text>
    <v-form ref="form" lazy-validation>
      <v-card-text>
        <v-text-field
          v-model="email"
          label="Email"
          type="email"
          :rules="[rules.required]"
          name="username"
        ></v-text-field>
        <br>
        <v-text-field
          v-model="password"
          @keypress.enter="validate"
          label="Password"
          name="password"
          :rules="[rules.required]"
          type="password"
        ></v-text-field>
        <v-checkbox
          v-model="userTypeId"
          dense
          label="I am a store owner"
          color="primary"
          hide-details
        ></v-checkbox>
      </v-card-text>
    </v-form>
    <v-card-actions>
      <v-spacer/>
      <v-btn
        width="100%"
        :disabled="loading"
        :loading="loading"
        large
        depressed
        color="primary"
        @click="validate"
      >login</v-btn>
    </v-card-actions>
    <br>

    <p class="text-right body-2">
      <span class="text-right">
        Create your new account
        <v-btn
          small
          color="primary"
          text
          class="mx-0 px-0"
          @click="$router.push('/pizzamart/register/user')"
        >here</v-btn>
      </span>
    </p>
  </v-card>
</template>
<script>
export default {
  name: "login",
  props: ["app"],
  data() {
    return {
      email: "",
      password: "",
      userTypeId: false,
      loading: false,
      rules: {
        required: value => !!value || "Required."
      }
    };
  },
  computed: {
    csrf_token() {
      let token = document.head.querySelector('meta[name="csrf-token"]');
      return token.content;
    }
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.loading = true;
        this.$axios
          .post("/auth/login", {
            email: this.email,
            password: this.password,
            userTypeId: this.userTypeId ? 1 : 2
          })
          .then(res => {
            this.loading = false;
            var user = res.data.user;
            this.$user = user;
            this.$bus.$emit("update", user);
            localStorage.setItem("userTypeId", user.userTypeId);

            if (user.userTypeId == 2) {
              window.location.href = "/pizzamart/home";
            } else {
              window.location.href = "/pizzamart/dashboard";
            }
          })
          .catch(err => {
            this.loading = false;
            this.password = "";
            if (err.response.status == 401) {
              this.$bus.$emit("notify", err.response.data.error);
            } else if (err.response.status == 422) {
              this.$bus.$emit("notify", err.response.data.errors.email[0]);
            } else {
              this.$bus.$emit("notify", "Something went wrong");
            }
          });
      }
    }
  }
};
</script>