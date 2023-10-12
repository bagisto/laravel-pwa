var state = {
    cart                : null,
    pagination          : null,
    customer            : false,
    token               : JSON.parse(localStorage.getItem('token')),
    isCustomerFetched   : false,
};

setTimeout(() => {
    EventBus.$on('user-logged-in', user => {
        state.customer = user;
        state.token = JSON.parse(localStorage.getItem('token'));
    });
    
    EventBus.$on('user-logged-out', () => {
        state.customer = null;
    });

    EventBus.$on('checkout.cart.changed', cart => {
        state.cart = cart;
    });
}, 0);

export default state;