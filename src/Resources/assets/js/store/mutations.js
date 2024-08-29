import Vue from "vue";
import router from "../router";

var setCustomer = (state, customer) => {
    EventBus.$emit("hide-ajax-loader");

    state.customer = customer;
    state.isCustomerFetched = true;

    Vue.prototype.$http.defaults.headers.common[
        "Authorization"
    ] = `Bearer ${state.token}`;

    if (router.app._route.name == "login-register") {
        router.app._router.push({ name: "dashboard" });
    }
};

const GET_CUSTOMER = (state) => {
    EventBus.$emit("show-ajax-loader");
    const token = JSON.parse(localStorage.getItem("token"));

    if (!state.token && token) {
        state.token = token;
    }

    if (token) {
        Vue.prototype.$http.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${token}`;

        if (!state.isCustomerFetched) {
            Vue.prototype.$http
                .get("/api/v1/customer/get")
                .then((response) => {
                    setCustomer(state, response.data.data);
                })
                .catch((error) => {
                    state.isCustomerFetched = true;
                });
        }

        if (state.customer) {
            setCustomer(state, state.customer);
        }
    }

    EventBus.$emit("hide-ajax-loader");
};

const GET_CART = (state) => {
    EventBus.$emit("show-ajax-loader");

    const token = JSON.parse(localStorage.getItem("token"));

    if (!state.token) {
        state.token = token;
    }
    console.log("token", token);

    if (token) {
        Vue.prototype.$http.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${token}`;

        Vue.prototype.$http
            .get("/api/v1/customer/cart")
            .then((response) => {
                console.log("cart", response);

                state.cart = response.data.data;
                state.pagination = response.data.meta;
            })
            .catch((error) => {});
    }

    EventBus.$emit("hide-ajax-loader");
};

export default {
    GET_CUSTOMER,
    GET_CART,
};
