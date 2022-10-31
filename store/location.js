export const state = () => ({
    userCoords: false
})

export const mutations = {
    SET_USER_COORDS(state, userCoords) {
        state.userCoords = userCoords
    }
}
