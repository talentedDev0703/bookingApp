function initialState () {
    return {
        reports: null
    }
}

export const state = initialState;

export const mutations = {
    SET_REPORTS(state, reports) {
        state.reports = reports
    },
    RESET_STATE(state){
        const s = initialState()
        Object.keys(s).forEach(key => {
            state[key] = s[key]
        })
    }
}

export const actions = {
    async GET_REPORTS ({ commit }, { id }) {
        const reports = await this.$axios.$get(`b2b-report?company_id=${id}`)
        commit('SET_REPORTS', reports)
    },
}
