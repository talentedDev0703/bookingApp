function initialState () {
    return {
        orders: null
    }
}

export const state = initialState;

export const mutations = {
    SET_ORDERS(state, orders) {
        state.orders = orders
    },
    RESET_STATE(state){
        const s = initialState()
        Object.keys(s).forEach(key => {
            state[key] = s[key]
        })
    }
}

export const actions = {
    async GET_ORDERS ({ commit }, id) {
        const orders = await this.$axios.$get(`b2b-orders?company_id=${id}`)
        commit('SET_ORDERS', orders)
    },
    async REMOVE_ORDER ({ commit }, { companyID, orderID }) {
        if (window.confirm('Are you sure')) {
            const orders = confirm && await this.$axios.$get(`b2b-orders?company_id=${companyID}&remove=${orderID}`);
            commit('SET_ORDERS', orders);
        }
    },
}
