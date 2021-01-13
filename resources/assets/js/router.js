import VueRouter from 'vue-router'
import { isNull } from 'util';
const router = new VueRouter({
    mode: 'history',
    routes: [{
            path: '/pizzamart',
            redirect: {
                name: localStorage.getItem("userTypeId") == 2 ? "Home" : 'Dashboard'
            },

        },
        {
            path: '/pizzamart/login',
            name: 'Login',
            component: require(`./components/AccountForm.vue`),
            meta: {
                title: "Login"
            },

        },
        {
            path: '/pizzamart/stores',
            name: 'Stores',
            component: require(`./components/Stores.vue`),
            meta: {
                title: "Stores"
            }
        },
        {
            path: '/pizzamart/register/:user',
            name: 'Register',
            component: require(`./components/AccountForm.vue`),
            meta: {
                title: "Register"
            }
        }, {
            path: '/pizzamart/home',
            name: 'Home',
            component: require(`./components/Home.vue`),

            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == "null" || localStorage.getItem("userTypeId") == 2) {
                    next();
                } else {
                    next({ name: 'dashboard' });
                }
            },
            meta: {
                title: "Home"
            }
        },
        {
            path: '/pizzamart/addItem',
            name: 'Add Item',
            component: require('./components/AccountForm.vue'),
            meta: {
                title: "AddItem"
            },
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == 1) {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        },
        {
            path: "/",
            redirect: {
                name: "Home"
            },
            meta: {
                title: "Home"
            }
        },

        {
            path: "*",
            name: "404",
            component: require('./components/404.vue'),
            meta: {
                title: "Page not found"
            }
        },
        {
            path: "/pizzamart/store/profile/:id",
            name: "Store Profile",
            component: require('./components/StoreProfile.vue'),
            meta: {
                title: "Store Profile"
            },
            // beforeEnter: (to, from, next) => {
            //     if (localStorage.getItem("userTypeId") == 1) {
            //         next();
            //     } else {
            //         next({ name: '404' })
            //     }
            // }

        },
        {
            path: "/pizzamart/transaction/:type",
            name: "Pending",
            component: require('./components/Orders.vue'),
            meta: {
                title: "Transactions",
            },
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == 1) {
                    next();
                } else {
                    next({ name: 'home' })
                }
            }
        },
        {
            path: "/pizzamart/order_details/:item_id",
            component: require('./components/OrderDetails.vue'),
            meta: {
                title: "Order Detail"
            },
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == 1) {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        },
        {
            path: "/pizzamart/about",
            name: "About",
            component: require('./components/AboutUs.vue'),
            meta: {
                title: "About"
            }

        },

        {
            path: '/pizzamart/dashboard',
            name: 'Dashboard',
            component: require('./components/Dashboard.vue'),
            meta: {
                title: "Dashboard"
            },
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == 1) {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        },
        {
            path: '/pizzamart/products',
            name: 'Products',
            component: require('./components/StoreOwnerDashboard.vue'),
            meta: {
                title: "My Products"
            },
            beforeEnter: (to, from, next) => {
                if (localStorage.getItem("userTypeId") == 1) {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        },
        {
            path: '/pizzamart/profile',
            name: 'Profile',
            component: require('./components/Profile.vue'),
            meta: {
                title: "Profile"
            },
            beforeEnter: (to, from, next) => {

                if (localStorage.getItem("userTypeId") == '2') {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        },
        {
            path: '/pizzamart/rewards',
            name: 'Rewards',
            component: require('./components/PrizeWheel.vue'),
            meta: {
                title: "Rewards"
            },
            beforeEnter: (to, from, next) => {

                if (localStorage.getItem("userTypeId") == '2') {
                    next();
                } else {
                    next({ name: '404' })
                }
            }
        }
    ],
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
})

export default router;