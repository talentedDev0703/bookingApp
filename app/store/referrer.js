export const state = () => ({
    referrer: false
})

export const mutations = {
    SET_REFERRER(state, referrer) {
        this.$cookies.set('referrer', referrer, {expires: new Date(new Date().getTime()+(10*24*60*60*1000))});
        state.referrer = referrer
    }
}
