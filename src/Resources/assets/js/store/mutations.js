import Vue from 'vue';
import router from '../router';

var setCustomer = (state, customer) => {
    EventBus.$emit('hide-ajax-loader');

    state.customer = customer;
    state.isCustomerFetched = true;
    Vue.prototype.$http.defaults.headers.common['Authorization'] = `Bearer ${state.token}`;

    if (router.app._route.name == 'login-register') {
        router.app._router.push({name: 'dashboard'})
    }
}

const GET_CUSTOMER = state => {
    EventBus.$emit('show-ajax-loader');

    if (state.token) {        
        Vue.prototype.$http.get('/api/customer/get',{ params: {token:state.token } })
            .then(response => {
                setCustomer(state, response.data.data);
            })
            .catch(error => {
                state.isCustomerFetched = true;
            });
    }

    if (state.token) {
        setCustomer(state, state.customer);
    }

    EventBus.$emit('hide-ajax-loader');
};

const GET_CART = state => {
    EventBus.$emit('show-ajax-loader');
    Vue.prototype.$http.get('/api/pwa/checkout/cart', {
            params: {
                token: true,
            },
            headers: {
                Authorization : `Bearer ${state.token}`
            }
        })
        .then(response => {
            EventBus.$emit('hide-ajax-loader');
            state.cart = response.data.data;
            state.pagination = response.data.meta;
        })
        .catch(error => {});
};

export default {
    GET_CART,
    GET_CUSTOMER
};