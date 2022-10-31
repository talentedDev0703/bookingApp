export const state = () => ({
    tour: false
})

export const mutations = {
    SET_TOURS(state, tours) {
        state.tour = tours[0];
    }
}
