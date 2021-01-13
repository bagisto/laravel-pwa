const getCustomer = ({ commit }) => {
    commit('GET_CUSTOMER');
};

const getCart = ({ commit }) => {
    commit('GET_CART');
};

const log_out = ({ commit }) => {
    commit('LOGOUT');
};

export default {
    getCart,
    log_out,
    getCustomer
};