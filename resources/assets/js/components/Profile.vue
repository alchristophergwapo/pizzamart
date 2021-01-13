<template>
  <header class="hero pt-10" v-if="$app.user">
    <br>
    <br>
    <v-row no-gutters class="fill-height">
      <v-col class="my-10">
        <UpdateProfile ref="modal"/>
        <v-card height="480" max-width="375" elevation="2" class="mx-auto my-10">
          <v-card-title class="display-1 font-weight-black">
            Profile
            <v-btn icon color="indigo" @click="$refs.modal.dialog = true">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </v-card-title>
          <v-divider></v-divider>
          <v-list two-line>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="indigo">mdi-account-circle</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{$app.user.user_info.firstname}} {{$app.user.user_info.lastname}}</v-list-item-title>
                <v-list-item-subtitle>Name</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-divider inset></v-divider>
            <v-list-item>
              <v-list-item-icon>
                <v-icon color="indigo">mdi-phone</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{$app.user.user_info.contactNumber}}</v-list-item-title>
                <v-list-item-subtitle>Mobile</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-divider inset></v-divider>

            <v-list-item>
              <v-list-item-icon>
                <v-icon color="indigo">mdi-email</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{$app.user.email}}</v-list-item-title>
                <v-list-item-subtitle>Email</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <v-divider inset></v-divider>

            <v-list-item>
              <v-list-item-icon>
                <v-icon color="indigo">mdi-map-marker</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{$app.user.user_info.address}}</v-list-item-title>
                <v-list-item-subtitle>Address</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-divider inset></v-divider>

            <v-list-item>
              <v-list-item-icon>
                <v-icon color="indigo">mdi-calendar</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title>{{new Date($app.user.user_info.created_at).toDateString()}}</v-list-item-title>
                <v-list-item-subtitle>joined</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-divider></v-divider>
        </v-card>
      </v-col>
      <v-col class="my-10 mx-2">
        <v-card height="480" width="450" class="my-10 overflow-hidden">
          <v-card-title class="display-1 font-weight-black">My Orders</v-card-title>
          <v-divider></v-divider>
          <div v-if="loading">
            <v-skeleton-loader
              v-for="i in 4"
              :key="i"
              :loading="loading"
              transition="fade-transition"
              height="94"
              type="list-item-two-line"
            >
              <v-card>
                <v-card-title>Title</v-card-title>
                <v-card-text>Card Text</v-card-text>
              </v-card>
            </v-skeleton-loader>
          </div>
          <div v-else>
            <v-sheet id="scrolling-techniques-7" v-if="my_orders.length" class="overflow-y-auto">
              <v-container style="height: 364px;">
                <v-list two-line>
                  <v-list-item-group>
                    <template v-for="order in my_orders">
                      <v-list-item :key="order.id" disabled>
                        <template>
                          <v-list-item-content>
                            <v-row>
                              <v-col>
                                <v-img
                                  width="200"
                                  :src="`/images/uploads/${order.imageUrl}`"
                                  lazy-src="/images/uploads/banner.jpg"
                                ></v-img>
                              </v-col>
                              <v-col>
                                <v-list-item-title
                                  class="font-weight-bold black--text text-uppercase"
                                  v-text="order.productname"
                                ></v-list-item-title>
                                <v-list-item-title
                                  class="font-italic caption black--text text-uppercase"
                                  v-text="order.name"
                                ></v-list-item-title>
                                <v-list-item-subtitle
                                  class="caption text--primary"
                                  v-text="order.created_at"
                                ></v-list-item-subtitle>
                                <v-list-item-subtitle
                                  class="text--primary"
                                  v-text="'Qty : '+order.quantity"
                                ></v-list-item-subtitle>
                                <v-list-item-subtitle
                                  class="font-weight-bold text--primary"
                                  v-text="'Total : ' +order.amount"
                                ></v-list-item-subtitle>
                              </v-col>
                            </v-row>
                          </v-list-item-content>
                        </template>
                      </v-list-item>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-container>
            </v-sheet>
            <v-card-text v-else>
              <v-card-title class="headine text-center font-weight-black">No orders made</v-card-title>
            </v-card-text>
          </div>
        </v-card>
      </v-col>

    </v-row>
  </header>
</template>

<script>
import UpdateProfile from "./UpdateProfile";
export default {
  name: "profile",
  components: {
    UpdateProfile
  },
  data() {
    return {
      my_orders: [],
      loading: false
    };
  },
  created() {
    if (!this.$app.user) {
      this.$router.push("/pizzamart/home");
    }
    this.loading = true;
    this.$axios
      .get(`/auth/myOrders/${this.$app.user.userInfoId}`)
      .then(res => {
        this.loading = false;
        this.my_orders = res.data;
      })
      .catch(err => {
        this.loading = false;
        this.$bus.$emit("notify", "Something went wrong");
      });
  },
  methods: {}
};
</script>


<style lang="sass" scoped>
.hero
  // background:linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url('/images/banner.jpg')
  background-size: cover
  background-position: center
  background-attachment: fixed
  width: 100wh
  display: flex

.center-content
  display: flex
  flex-direction: column
  align-items: center
  margin: auto

.button
  text-transform: uppercase
  color: white
  padding: 20px
      text-decoration: none  
  
  &:hover
    background-color: rgba(255, 255, 255, 0.1)
    border-color: rgba(255, 255, 255, 0.7)
    color: white

.card
  margin-top: 0
  width: 100wh
  height: 100%
</style>
