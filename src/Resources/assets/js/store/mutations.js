import Vue from 'vue';
import router from '../router';

var setCustomer = (state, customer) => {
    EventBus.$emit('hide-ajax-loader');
    
    state.customer = customer;
    state.isCustomerFetched = true;

    if (router.app._route.name == 'login-register') {
        router.app._router.push({name: 'dashboard'})
    }
}

const GET_CUSTOMER = state => {
    EventBus.$emit('show-ajax-loader');

    if (! state.isCustomerFetched) {
        Vue.prototype.$http.get('/api/customer/get')
            .then(response => {
                setCustomer(state, response.data.data);
            })
            .catch(error => {
                state.isCustomerFetched = true;
            });
    }

    if (state.customer) {
        setCustomer(state, state.customer);
    }

    EventBus.$emit('hide-ajax-loader');
};

export default {
    GET_CUSTOMER
};