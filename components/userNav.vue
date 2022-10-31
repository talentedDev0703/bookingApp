<template>

    <div
        class="User"
        v-if="user.username"
        data-inclusive-menu>
        <div
            class="User-inner">
            <button
                ref="showNav"
                data-inclusive-menu-opens="nav"
                class="Button Button--ghost"
            >
                <span class="u-isHiddenVisually">{{ $t('auth.userMenu') }}</span>

                <span
                    aria-hidden="true"
                >
                    <svg-inline src="~/assets/vector/inline/user.svg"/>
                </span>
                <span class="User-username">{{ user.username }}</span>
            </button>
            <nav
                class="User-nav"
                id="nav"
                data-inclusive-menu-from="bottom">
                <ul>
                    <li>
                        <button
                            type="button"
                            class="Button Button--link"
                            @click="logout">{{ $t('auth.logout') }}</button>
                    </li>
                    <li>
                        <nuxt-link
                            :to="{ name: 'lang-admin', params: { lang: $i18n.locale }}"
                            v-if="user.role === 'Administrator'">
                            <span>{{ $t('auth.links.admin') }}</span>
                        </nuxt-link>
                    </li>
                    <li>
                        <nuxt-link
                            :to="{ name: 'lang-orders', params: { lang: $i18n.locale }}"
                            v-if="user.role === 'Administrator' || user.role === 'Seller'">
                            <span>{{ $t('auth.links.orders') }}</span>
                        </nuxt-link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</template>
<script>
    import { mapActions } from "vuex";
    import { mapState } from "vuex";

    const MenuButton = require("inclusive-menu-button");

    export default {
        data(){
            return {
                menuButton: false
            }
        },
        watch: {
            '$route.path': function () {
                if (this.user.username) {
                    if (!this.menuButton) {
                        this.initMenuButton();
                    } else {
                        this.menuButton.close();
                    }
                }
            }
        },
        computed: {
            ...mapState({
                user: state => state.user
            })
        },
        mounted(){
            if(!this.menuButton) {
                this.$nextTick(() => {
                    this.initMenuButton();
                });
            }
        },
        methods: {
            ...mapActions({
                logout: "LOGOUT",
            }),
            initMenuButton() {
                if (this.user.username){
                    this.menuButton = new MenuButton(this.$refs.showNav, {
                        checkable: "one"
                    });
                }
            }
        }
    }
</script>

