import Vue from 'vue';
import axios from 'axios';
import { isNullOrUndefined } from "util";

var app = new Vue({
    data() {
        return {
            user: null,
            details: null
        }
    },
    methods: {
        init() {

            axios.get("/auth/init").then(res => {
                if (!isNullOrUndefined(res.data.user)) {
                    this.user = res.data.user;
                    localStorage.setItem("userTypeId", this.user.userTypeId)
                } else {
                    localStorage.setItem("userTypeId", null)
                }
            })
        }
    },
    created() {
        this.init();
    },
    updated() {
        this.init();
    }
});
export default app;