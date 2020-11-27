var state = {
    customer: false,
    isCustomerFetched: false,
};

setTimeout(() => {
    EventBus.$on('user-logged-in', user => {
        state.customer = user;
    });
    
    EventBus.$on('user-logged-out', () => {
        state.customer = null;
    });
}, 0);

export default state;