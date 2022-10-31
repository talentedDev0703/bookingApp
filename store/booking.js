function initialState () {
    return {
        id: false,
        name: '',
        pickupPoints: false,
        dates: false,
        times: false,
        currentStep: 1,
        availableSteps: 1,
        tourID: false,
        tourInfo: false
    }
}

export const state = initialState;

export const mutations = {
    SET_TOUR_ID(state, id) {
        state.id = id
    },
    SET_TOUR_NAME(state, name) {
        state.name = name
    },
    SET_PICKUP_POINTS(state, points) {
        state.pickupPoints = points
    },
    SET_DATES(state, dates) {
        state.dates = dates
    },
    SET_TIMES(state, times) {
        state.times = times
    },
    STEP_CHANGE(state, step) {
        state.currentStep = step
    },
    AVAILABLE_STEP_CHANGE(state, step) {
        state.availableSteps = step
    },
    SET_TOUR_ID(state, id) {
        state.tourID = id
    },
    SET_TOUR(state, tour) {
        state.tourInfo = tour
    },
    RESET_STATE(state){
        const s = initialState()
        Object.keys(s).forEach(key => {
            state[key] = s[key]
        })
    }
}

export const actions = {
    async GET_PICKUP_POINTS ({ commit }, name) {
        const points = await this.$axios.$get(`tour-selector?tour=${name}`)
        commit('SET_PICKUP_POINTS', points)
        commit('SET_TOUR_NAME', name)
    },
    async GET_DATES ({ commit }, { pickup, tour }) {
        const dates = await this.$axios.$get(`tour-selector?tour=${tour}&pickup=${pickup}`)
        commit('SET_DATES', dates)
    },
    async GET_TIMES ({ commit }, { pickup, tour, date }) {
        const times = await this.$axios.$get(`tour-selector?tour=${tour}&pickup=${pickup}&date=${date}`)
        commit('SET_TIMES', times)
    },
    async GET_TOUR_ID ({ commit }, { pickup, tour, date, time }) {
        const id = await this.$axios.$get(`tour-selector?tour=${tour}&pickup=${pickup}&date=${date}&time=${time}`)
        commit('SET_TOUR_ID', parseInt(id))
    },
    async GET_TOUR ({ commit }, { id }) {
        const tour = await this.$axios.$get(`tour-selector?id=${id}`)
        commit('SET_TOUR', tour)
    },
}
