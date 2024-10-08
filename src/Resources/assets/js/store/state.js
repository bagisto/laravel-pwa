var state = {
    cart: null,
    pagination: null,
    customer: false,
    isCustomerFetched: false,
    token: null,
};

setTimeout(() => {
    EventBus.$on("user-logged-in", (user) => {
        state.customer = user.data;
        state.token = user.token;
    });

    EventBus.$on("user-logged-out", () => {
        state.customer = null;
    });

    EventBus.$on("checkout.cart.changed", (cart) => {
        state.cart = cart;
    });
}, 0);

export default state;
