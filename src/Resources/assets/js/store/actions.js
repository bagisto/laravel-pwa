const getCustomer = ({ commit }) => {
    commit('GET_CUSTOMER');
};

const getCart = ({ commit }) => {
    commit('GET_CART');
};

export default {
    getCart,
    getCustomer
};