$table->timestamps();<template>
  <v-app-bar hide-on-scroll app light>
    <v-toolbar-title>
      <router-link to="/" class="underlined black--text">PizzaMart</router-link>
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <div class="links" v-if="!$app.user">
      <div class="hidden-sm-and-down">
        <v-btn
          v-if="$route.name!=='Stores'"
          @click="$router.push({name: 'Stores'})"
          text
          color="danger"
        >
          <v-icon>mdi-store</v-icon>Visit Stores
        </v-btn>
        <router-link class="underlined" to="/pizzamart/login">Login</router-link>
        <router-link class="underlined" to="/pizzamart/register/user">Sign up</router-link>
      </div>
      <div class="hidden-md-and-up">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn fab small v-bind="attrs" v-on="on" depressed>
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>

          <v-list>
            <div>
              <v-list-item>
                <router-link class="underlined" to="/pizzamart/stores">Stores</router-link>
              </v-list-item>
              <v-list-item>
                <router-link class="underlined" to="/pizzamart/login">Login</router-link>
              </v-list-item>
              <v-list-item>
                <router-link class="underlined" to="/pizzamart/register/user">Sign up</router-link>
              </v-list-item>
            </div>
          </v-list>
        </v-menu>
      </div>
    </div>
    <div v-else>
      <v-btn outlined rounded disabled text color="danger">
        <v-icon>mdi-pizza</v-icon>100
      </v-btn>
      <v-btn
        v-if="$route.name!=='Stores'"
        @click="$router.push({name: 'Stores'})"
        text
        color="danger"
      >
        <v-icon>mdi-store</v-icon>Visit Stores
      </v-btn>
      <v-chip class="ma-2" @click="viewCart" color="danger" text-color="white">
        <v-avatar>
          <svg
            viewBox="0 0 24 24"
            fill="#ffffff"
            aria-hidden="true"
            height="24"
            width="24"
            style="flex-shrink: 0;"
          >
            <title></title>
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M4.278 4.006A7.023 7.023 0 0 0 3.865 4H3a1 1 0 0 1 0-2h.951c.313-.002.706-.003 1.063.136a2 2 0 0 1 .797.565c.25.292.377.663.48.958l.028.082.09.259h12.185c.373 0 .718 0 1 .026.293.026.661.09.998.318a2 2 0 0 1 .846 1.293c.074.4-.016.762-.109 1.042-.09.268-.228.584-.377.926l-2.116 4.837-.032.073c-.116.267-.26.602-.508.863a1.998 1.998 0 0 1-.769.502c-.338.123-.703.121-.993.12h-7.48l-.676 1.082a12.721 12.721 0 0 0-.53.894l.037.003c.212.02.51.021 1.002.021H18a1 1 0 1 1 0 2H8.844c-.434 0-.827 0-1.142-.029-.317-.029-.73-.098-1.09-.365a2 2 0 0 1-.801-1.446c-.036-.447.124-.834.268-1.118.142-.283.35-.615.58-.984l.739-1.18L4.43 4.401a7.03 7.03 0 0 0-.142-.388l-.003-.007h-.008z"
              fill="#ffffff"
            ></path>
            <path
              d="M7.5 22a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM16.5 22a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"
              fill="#ffffff"
            ></path>
          </svg>
        </v-avatar>
        <span v-if="orders > 0" style="color:white">
          <h3>{{orders}}</h3>
        </span>
      </v-chip>
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn fab small v-bind="attrs" v-on="on" depressed>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <div>
            <v-list-item link to="/pizzamart/profile">
              <v-list-item-content>
                <v-list-item-title @click="editProfile">
                  <v-icon>mdi-account-circle</v-icon>Account
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click="logout">
              <v-list-item-title>
                <v-icon>mdi-logout</v-icon>Logout
              </v-list-item-title>
            </v-list-item>
          </div>
        </v-list>
      </v-menu>
    </div>
    <Cart ref="cart"/>
  </v-app-bar>
</template>

<script>
import { isNull } from "util";
import Cart from "./Cart";
export default {
  name: "myNavbar",
  data() {
    return {
      orders: 0
    };
  },
  components: {
    Cart
  },
  methods: {
    logout() {
      this.$axios
        .post("/auth/logout")
        .then(res => {
          window.location.href="/";
          localStorage.removeItem("userTypeId");
        })
        .catch(err => {
          this.$bus.$emit("notify" , "Something went wrong.");
          console.log(err);
        });
    },
    editProfile() {
      this.$router.push("editProfile");
    },
    viewCart() {
      this.$refs.cart.dialog = true;
    }
  },
  created() {
    this.$bus.$on("clear_cart", () => {
      this.orders = 0;
    });

    this.$bus.$on("newItem", orders => {
      this.orders = orders.length;
    });

    this.$bus.$on("removed", orders => {
      this.orders = orders.length;
    });
  }
};
</script>
