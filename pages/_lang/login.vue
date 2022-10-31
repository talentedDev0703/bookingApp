<template>
    <div class="BoxedLayout Login">

        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <div class="BoxedLayout-main">
            <h1 class="BoxedLayout-mainTitle">{{ $t('auth.login.title') }}</h1>


            <form
                @submit="doLogin"
                class="Login-form">

                <div
                    class="Field Booking-capacity Booking-capacity--warning"
                    v-if="user.message && user.success === false">
                    {{ user.message }}
                </div>

                <div
                    class="Field"
                    :class="{'has-error': errors.first('username')}">
                    <label for="username">{{ $t('auth.login.username') }}</label>

                    <div class="Field-holder">
                        <input
                            type="text"
                            name="username"
                            id="username"
                            v-model="username"
                            v-validate="'required'"
                        >
                    </div>

                    <span class="Field-error">{{ errors.first('username') }}</span>
                </div>

                <div
                    class="Field"
                    :class="{'has-error': errors.first('password')}">
                    <label for="password">{{ $t('auth.login.password') }}</label>

                    <div class="Field-holder">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            v-model="password"
                            v-validate="'required'"
                        >
                    </div>

                    <span class="Field-error">{{ errors.first('password') }}</span>
                </div>

                <button
                    class="Button Button--booking"
                >{{ $t('auth.login.action') }}</button>
            </form>
        </div>
    </div>
</template>

<script>

    import { mapActions } from "vuex";
    import { mapState } from "vuex";

    export default {
        middleware: 'notAuthenticated',
        data() {
            return {
                username: '',
                password: '',
                error: null
            }
        },
        head: {
            htmlAttrs: {
                class: "is-boxed-layout fontsLoaded"
            }
        },
        computed: {
            ...mapState({
                user: state => state.user
            })
        },
        methods: {
            ...mapActions({
                login: "LOGIN",
            }),
            doLogin(e) {
                e.preventDefault();

                this.error = null

                this.$validator
                    .validateAll()
                    .then(() => {
                        if (!this.errors.any()) {
                            this.login({username: this.username, password: this.password});
                        }
                    })
                    .catch(() => {
                        return false;
                });
            }
        }
    }
</script>
