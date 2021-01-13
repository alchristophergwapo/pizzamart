import Vue from 'vue'
import Vuetify from 'vuetify'
import VueRouter from 'vue-router'
import router from "./router";
import App from './App.vue'
import Axios from "axios";
import bus from "./components/com/bus";
import controller from "./controller";
import VueGraph from 'vue-graph'

Vue.use(VueGraph)
Vue.use(VueRouter)
Vue.use(Vuetify)
window.Vue = Vue
window.Pusher = require('pusher-js');
Pusher.logToConsole = true;

var user = null;

Axios.get("/auth/init").then(res => {
    init();
    user = res.data.user;
    Vue.prototype.$user = user;
}).catch(err => {
    init();
});

var pusher = new Pusher("0ac434ea6dc913c87c04", {
    cluster: "ap1",
    encrypted: true
});

var channel = pusher.subscribe("pizzamart-channel");

Vue.prototype.$pusher_channel = channel;
Vue.prototype.$axios = Axios;
Vue.prototype.$bus = bus;
Vue.prototype.$app = controller;

function init() {
    new Vue({
        render: h => h(App),
        router,
        vuetify: new Vuetify({
            theme: {
                themes: {
                    light: {
                        danger: '#F4511E',
                        primary: '#ff6f00', // #E53935
                        secondary: '#F4511E', // #FFCDD2
                        accent: '#FF6F00', // #3F51B5
                    },
                },
            },
        })
    }).$mount("#app");
}