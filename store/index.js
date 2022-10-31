import cookieparser from 'cookieparser';

export const state = () => ({
    locales: ['en', 'hr'],
    locale: 'en',
    bookUrl: 'https://www.adriaticsunsets.com/booking/',
    user: {
        success: null
    }
})

export const mutations = {
    SET_LANG(state, locale) {
        if (state.locales.indexOf(locale) !== -1) {
            state.locale = locale
        }
    },
    SET_USER(state, user) {
        state.user = user;
    },
    SET_TOKEN(state, token) {
        state.user.token = token;
    }
}

export const actions = {
    async nuxtServerInit ({ commit }, { req, app }) {
        const tours = await app.$axios.$get('next-tour')
        commit('nextTours/SET_TOURS', tours)

        if (this.$cookies.get('user')){
            commit('SET_USER', this.$cookies.get('user'));
        }

        if (req && req.headers.cookie) {
            const parsed = cookieparser.parse(req.headers.cookie);
            if (parsed.auth) {
                const accessToken = parsed.auth;
                commit('SET_TOKEN', accessToken);
                app.$axios.setToken(accessToken, 'Bearer');
            } else {
                app.$axios.setToken(false);
            }
        }

    },
    async LOGIN ({ commit }, { username, password }) {
        try {
            const user = await this.$axios.$get(`login?username=${username}&password=${password}`);
            commit('SET_USER', user)
            if (user.success) {
                this.$cookies.set('user', JSON.stringify(user));
                this.$cookies.set('auth', user.b2b_cookie);
                this.$router.push(`/${this.state.locale}/#tours`)
                this.$axios.setToken(user.b2b_cookie, 'Bearer');
            }
        } catch (error) {

        }
    },
    LOGOUT ({ commit }) {
        this.$cookies.remove('user');
        this.$cookies.remove('auth');
        this.$cookies.remove('b2b');
        this.$axios.setToken(false);
        commit('SET_USER', {success: null})
        this.$router.go('/');
    }
}
